import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../config/app_theme.dart';
import '../../core/localization/app_localizations.dart';
import '../../models/question.dart';
import '../../providers/portal_provider.dart';
import '../../providers/question_provider.dart';
import '../../widgets/ask_question_dialog.dart';
import '../../widgets/responsive_scaffold.dart';

class QuestionScreen extends StatefulWidget {
  final int? initialTopicId;

  const QuestionScreen({super.key, this.initialTopicId});

  @override
  State<QuestionScreen> createState() => _QuestionScreenState();
}

class _QuestionScreenState extends State<QuestionScreen> {
  final TextEditingController _searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final qProvider = Provider.of<QuestionProvider>(context, listen: false);
      qProvider.fetchQuestions(topicId: widget.initialTopicId);
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final loc = AppLocalizations.of(context);
    final portal = Provider.of<PortalProvider>(context);
    final qProvider = Provider.of<QuestionProvider>(context);

    return ResponsiveScaffold(
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => _openAskDialog(portal),
        icon: const Icon(Icons.add_comment_outlined),
        label: Text(loc.askQuestionBtn),
        backgroundColor: AppTheme.primaryBlue,
        foregroundColor: Colors.white,
      ),
      child: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 1000),
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 24),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            loc.questionsTitle,
                            style: const TextStyle(
                              fontSize: 26,
                              fontWeight: FontWeight.bold,
                              color: AppTheme.textDark,
                            ),
                          ),
                          const SizedBox(height: 6),
                          Text(
                            loc.questionsSubtitle,
                            style: const TextStyle(fontSize: 14, color: AppTheme.textMuted),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(width: 16),
                    ElevatedButton.icon(
                      onPressed: () => _openAskDialog(portal),
                      icon: const Icon(Icons.edit_note, size: 18),
                      label: Text(loc.askQuestionBtn),
                    ),
                  ],
                ),
                const SizedBox(height: 24),

                // Search Bar
                TextField(
                  controller: _searchController,
                  decoration: InputDecoration(
                    hintText: 'Cari pertanyaan masyarakat...',
                    prefixIcon: const Icon(Icons.search),
                    suffixIcon: _searchController.text.isNotEmpty
                        ? IconButton(
                            icon: const Icon(Icons.clear),
                            onPressed: () {
                              _searchController.clear();
                              qProvider.fetchQuestions(search: '');
                            },
                          )
                        : null,
                  ),
                  onSubmitted: (val) => qProvider.fetchQuestions(search: val),
                ),
                const SizedBox(height: 16),

                // Topics Chips
                if (portal.topics.isNotEmpty)
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      children: [
                        Padding(
                          padding: const EdgeInsets.only(right: 8),
                          child: FilterChip(
                            label: const Text('Semua Topik'),
                            selected: qProvider.selectedTopicId == null,
                            onSelected: (_) => qProvider.selectTopic(null),
                          ),
                        ),
                        ...portal.topics.map((t) {
                          final isSelected = qProvider.selectedTopicId == t.id;
                          return Padding(
                            padding: const EdgeInsets.only(right: 8),
                            child: FilterChip(
                              label: Text('${t.name} (${t.questionsCount})'),
                              selected: isSelected,
                              onSelected: (_) => qProvider.selectTopic(t.id),
                            ),
                          );
                        }),
                      ],
                    ),
                  ),
                const SizedBox(height: 24),

                // Questions List
                if (qProvider.isLoadingQuestions && qProvider.questions.isEmpty)
                  const Center(
                    child: Padding(
                      padding: EdgeInsets.all(48),
                      child: CircularProgressIndicator(),
                    ),
                  )
                else if (qProvider.questions.isEmpty)
                  Center(
                    child: Padding(
                      padding: const EdgeInsets.all(48),
                      child: Column(
                        children: [
                          Icon(Icons.mark_chat_read_outlined, size: 56, color: Colors.grey.shade400),
                          const SizedBox(height: 12),
                          Text(
                            loc.noQuestionsFound,
                            style: const TextStyle(fontSize: 16, color: AppTheme.textMuted),
                          ),
                        ],
                      ),
                    ),
                  )
                else
                  ListView.separated(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    itemCount: qProvider.questions.length,
                    separatorBuilder: (_, __) => const SizedBox(height: 16),
                    itemBuilder: (context, index) {
                      final item = qProvider.questions[index];
                      return _buildQuestionCard(item, loc);
                    },
                  ),
                const SizedBox(height: 48),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildQuestionCard(QuestionModel item, AppLocalizations loc) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                if (item.topicName != null)
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: AppTheme.primaryBlue.withValues(alpha: 0.08),
                      borderRadius: BorderRadius.circular(6),
                    ),
                    child: Text(
                      item.topicName!,
                      style: const TextStyle(
                        color: AppTheme.primaryBlue,
                        fontSize: 11,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                const Spacer(),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(
                    color: item.isAnswered ? Colors.green.shade50 : Colors.amber.shade50,
                    borderRadius: BorderRadius.circular(6),
                    border: Border.all(
                      color: item.isAnswered ? Colors.green.shade200 : Colors.amber.shade200,
                    ),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Icon(
                        item.isAnswered ? Icons.check_circle : Icons.hourglass_top,
                        size: 12,
                        color: item.isAnswered ? Colors.green.shade700 : Colors.amber.shade800,
                      ),
                      const SizedBox(width: 4),
                      Text(
                        item.isAnswered ? loc.statusAnswered : loc.statusPending,
                        style: TextStyle(
                          color: item.isAnswered ? Colors.green.shade800 : Colors.amber.shade900,
                          fontSize: 11,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Text(
              item.subject,
              style: const TextStyle(
                fontSize: 17,
                fontWeight: FontWeight.bold,
                color: AppTheme.textDark,
              ),
            ),
            const SizedBox(height: 6),
            Text(
              'Oleh: ${item.name} • ${_formatDate(item.createdAt)}',
              style: const TextStyle(fontSize: 12, color: AppTheme.textMuted),
            ),
            const SizedBox(height: 10),
            Text(
              item.body,
              style: const TextStyle(fontSize: 14, color: AppTheme.textDark, height: 1.5),
            ),
            if (item.answer != null) ...[
              const SizedBox(height: 16),
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.grey.shade50,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: Colors.grey.shade200),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        const Icon(Icons.admin_panel_settings_outlined, size: 16, color: AppTheme.primaryBlue),
                        const SizedBox(width: 6),
                        Text(
                          '${loc.answeredBy} ${item.answer!.answeredBy ?? 'Petugas Pelayanan'}',
                          style: const TextStyle(
                            fontWeight: FontWeight.bold,
                            fontSize: 13,
                            color: AppTheme.primaryBlue,
                          ),
                        ),
                        if (item.answer!.answeredAt != null) ...[
                          const SizedBox(width: 6),
                          Text(
                            '• ${_formatDate(item.answer!.answeredAt)}',
                            style: const TextStyle(fontSize: 11, color: AppTheme.textMuted),
                          ),
                        ],
                      ],
                    ),
                    const SizedBox(height: 8),
                    Text(
                      item.answer!.body,
                      style: const TextStyle(fontSize: 13, color: AppTheme.textDark, height: 1.5),
                    ),
                  ],
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }

  void _openAskDialog(PortalProvider portal) {
    showDialog<bool>(
      context: context,
      builder: (context) => AskQuestionDialog(
        topics: portal.topics,
        preselectedTopicId: Provider.of<QuestionProvider>(context, listen: false).selectedTopicId,
      ),
    ).then((submitted) {
      if (submitted == true && mounted) {
        Provider.of<QuestionProvider>(context, listen: false).fetchQuestions();
      }
    });
  }

  String _formatDate(DateTime? dt) {
    if (dt == null) return '-';
    return '${dt.day.toString().padLeft(2, '0')}/${dt.month.toString().padLeft(2, '0')}/${dt.year}';
  }
}
