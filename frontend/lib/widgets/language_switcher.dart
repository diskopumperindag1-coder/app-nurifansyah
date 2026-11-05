import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../core/localization/app_locale_provider.dart';
import '../core/network/api_client.dart';
import '../providers/page_provider.dart';
import '../providers/portal_provider.dart';
import '../providers/post_provider.dart';
import '../providers/question_provider.dart';

class LanguageSwitcher extends StatelessWidget {
  final bool isCompact;

  const LanguageSwitcher({super.key, this.isCompact = false});

  void _onLocaleChanged(BuildContext context, String langCode) {
    final localeProvider = Provider.of<AppLocaleProvider>(context, listen: false);
    if (localeProvider.languageCode == langCode) return;

    localeProvider.setLocale(langCode);

    // Update ApiClient locale and re-fetch active data
    final apiClient = Provider.of<ApiClient>(context, listen: false);
    apiClient.updateLocale(langCode);

    Provider.of<PortalProvider>(context, listen: false).fetchPortalData();
    Provider.of<PostProvider>(context, listen: false).fetchFeaturedPosts();
    Provider.of<PostProvider>(context, listen: false).fetchPosts(refresh: true);
    Provider.of<QuestionProvider>(context, listen: false).fetchFaqs();
    Provider.of<QuestionProvider>(context, listen: false).fetchQuestions();
    Provider.of<PageProvider>(context, listen: false).clearCache();
  }

  @override
  Widget build(BuildContext context) {
    final localeProvider = Provider.of<AppLocaleProvider>(context);
    final isId = localeProvider.isIndonesian;

    if (isCompact) {
      return TextButton.icon(
        onPressed: () {
          _onLocaleChanged(context, isId ? 'en' : 'id');
        },
        icon: const Icon(Icons.language, size: 18),
        label: Text(
          isId ? 'ID' : 'EN',
          style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
        ),
        style: TextButton.styleFrom(
          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
        ),
      );
    }

    return Container(
      decoration: BoxDecoration(
        color: Colors.grey.shade100,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: Colors.grey.shade300),
      ),
      padding: const EdgeInsets.all(3),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          _buildButton(
            context: context,
            label: 'ID',
            isActive: isId,
            onTap: () => _onLocaleChanged(context, 'id'),
          ),
          _buildButton(
            context: context,
            label: 'EN',
            isActive: !isId,
            onTap: () => _onLocaleChanged(context, 'en'),
          ),
        ],
      ),
    );
  }

  Widget _buildButton({
    required BuildContext context,
    required String label,
    required bool isActive,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(16),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
        decoration: BoxDecoration(
          color: isActive ? Theme.of(context).primaryColor : Colors.transparent,
          borderRadius: BorderRadius.circular(16),
        ),
        child: Text(
          label,
          style: TextStyle(
            fontSize: 12,
            fontWeight: FontWeight.bold,
            color: isActive ? Colors.white : Colors.grey.shade700,
          ),
        ),
      ),
    );
  }
}
