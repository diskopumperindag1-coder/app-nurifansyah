import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/app_theme.dart';
import '../core/localization/app_localizations.dart';
import '../models/question_topic.dart';
import '../providers/question_provider.dart';

class AskQuestionDialog extends StatefulWidget {
  final List<QuestionTopicModel> topics;
  final int? preselectedTopicId;

  const AskQuestionDialog({
    super.key,
    required this.topics,
    this.preselectedTopicId,
  });

  @override
  State<AskQuestionDialog> createState() => _AskQuestionDialogState();
}

class _AskQuestionDialogState extends State<AskQuestionDialog> {
  final _formKey = GlobalKey<FormState>();

  int? _selectedTopicId;
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _phoneController = TextEditingController();
  final _subjectController = TextEditingController();
  final _bodyController = TextEditingController();

  bool _isSuccess = false;

  @override
  void initState() {
    super.initState();
    if (widget.preselectedTopicId != null) {
      _selectedTopicId = widget.preselectedTopicId;
    } else if (widget.topics.isNotEmpty) {
      _selectedTopicId = widget.topics.first.id;
    }
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _phoneController.dispose();
    _subjectController.dispose();
    _bodyController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    if (_selectedTopicId == null) return;

    final qProvider = Provider.of<QuestionProvider>(context, listen: false);
    final success = await qProvider.submitQuestion(
      topicId: _selectedTopicId!,
      name: _nameController.text.trim(),
      email: _emailController.text.trim(),
      phone: _phoneController.text.trim(),
      subject: _subjectController.text.trim(),
      body: _bodyController.text.trim(),
    );

    if (success && mounted) {
      setState(() => _isSuccess = true);
    }
  }

  @override
  Widget build(BuildContext context) {
    final loc = AppLocalizations.of(context);
    final qProvider = Provider.of<QuestionProvider>(context);

    return Dialog(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      child: ConstrainedBox(
        constraints: const BoxConstraints(maxWidth: 540),
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: _isSuccess ? _buildSuccessView(loc) : _buildFormView(loc, qProvider),
        ),
      ),
    );
  }

  Widget _buildSuccessView(AppLocalizations loc) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        Container(
          width: 72,
          height: 72,
          decoration: BoxDecoration(
            color: Colors.green.shade50,
            shape: BoxShape.circle,
          ),
          child: Icon(Icons.check_circle_outline, size: 48, color: Colors.green.shade600),
        ),
        const SizedBox(height: 20),
        Text(
          loc.formSuccessTitle,
          textAlign: TextAlign.center,
          style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
        ),
        const SizedBox(height: 12),
        Text(
          loc.formSuccessDesc,
          textAlign: TextAlign.center,
          style: const TextStyle(color: AppTheme.textMuted, fontSize: 14, height: 1.5),
        ),
        const SizedBox(height: 24),
        SizedBox(
          width: double.infinity,
          child: ElevatedButton(
            onPressed: () => Navigator.of(context).pop(true),
            child: Text(loc.close),
          ),
        ),
      ],
    );
  }

  Widget _buildFormView(AppLocalizations loc, QuestionProvider qProvider) {
    return Form(
      key: _formKey,
      child: SingleChildScrollView(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Expanded(
                  child: Text(
                    loc.askDialogTitle,
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: AppTheme.textDark,
                    ),
                  ),
                ),
                IconButton(
                  onPressed: () => Navigator.of(context).pop(),
                  icon: const Icon(Icons.close),
                  tooltip: loc.close,
                ),
              ],
            ),
            const Divider(),
            const SizedBox(height: 12),
            if (qProvider.questionError != null)
              Container(
                margin: const EdgeInsets.only(bottom: 16),
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: Colors.red.shade50,
                  borderRadius: BorderRadius.circular(8),
                  border: Border.all(color: Colors.red.shade200),
                ),
                child: Row(
                  children: [
                    Icon(Icons.error_outline, color: Colors.red.shade700, size: 20),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        qProvider.questionError!,
                        style: TextStyle(color: Colors.red.shade800, fontSize: 13),
                      ),
                    ),
                  ],
                ),
              ),
            Text(loc.formTopic, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
            const SizedBox(height: 6),
            DropdownButtonFormField<int>(
              initialValue: _selectedTopicId,
              decoration: const InputDecoration(contentPadding: EdgeInsets.symmetric(horizontal: 14, vertical: 12)),
              items: widget.topics.map((t) {
                return DropdownMenuItem<int>(
                  value: t.id,
                  child: Text(t.name),
                );
              }).toList(),
              onChanged: (val) => setState(() => _selectedTopicId = val),
              validator: (v) => v == null ? loc.formRequiredField : null,
            ),
            const SizedBox(height: 14),
            Text(loc.formName, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
            const SizedBox(height: 6),
            TextFormField(
              controller: _nameController,
              decoration: InputDecoration(
                hintText: 'Contoh: Ahmad Fauzi',
                errorText: qProvider.validationErrors?['name']?[0],
              ),
              validator: (v) => v == null || v.trim().isEmpty ? loc.formRequiredField : null,
            ),
            const SizedBox(height: 14),
            Row(
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(loc.formEmail, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                      const SizedBox(height: 6),
                      TextFormField(
                        controller: _emailController,
                        keyboardType: TextInputType.emailAddress,
                        decoration: InputDecoration(
                          hintText: 'nama@email.com',
                          errorText: qProvider.validationErrors?['email']?[0],
                        ),
                        validator: (v) {
                          if (v == null || v.trim().isEmpty) return loc.formRequiredField;
                          if (!v.contains('@') || !v.contains('.')) return loc.formInvalidEmail;
                          return null;
                        },
                      ),
                    ],
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(loc.formPhone, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                      const SizedBox(height: 6),
                      TextFormField(
                        controller: _phoneController,
                        keyboardType: TextInputType.phone,
                        decoration: InputDecoration(
                          hintText: '081234567890',
                          errorText: qProvider.validationErrors?['phone']?[0],
                        ),
                        validator: (v) => v == null || v.trim().isEmpty ? loc.formRequiredField : null,
                      ),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 14),
            Text(loc.formSubject, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
            const SizedBox(height: 6),
            TextFormField(
              controller: _subjectController,
              decoration: InputDecoration(
                hintText: 'Ringkasan maksud pertanyaan Anda',
                errorText: qProvider.validationErrors?['subject']?[0],
              ),
              validator: (v) => v == null || v.trim().isEmpty ? loc.formRequiredField : null,
            ),
            const SizedBox(height: 14),
            Text(loc.formBody, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
            const SizedBox(height: 6),
            TextFormField(
              controller: _bodyController,
              maxLines: 4,
              decoration: InputDecoration(
                hintText: 'Tuliskan detail pertanyaan atau keluhan layanan Anda secara lengkap...',
                errorText: qProvider.validationErrors?['body']?[0],
              ),
              validator: (v) {
                if (v == null || v.trim().isEmpty) return loc.formRequiredField;
                if (v.trim().length < 10) return loc.formMinLength;
                return null;
              },
            ),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              height: 48,
              child: ElevatedButton(
                onPressed: qProvider.isSubmitting ? null : _submit,
                child: qProvider.isSubmitting
                    ? const SizedBox(
                        width: 20,
                        height: 20,
                        child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                      )
                    : Text(loc.formSubmit),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

