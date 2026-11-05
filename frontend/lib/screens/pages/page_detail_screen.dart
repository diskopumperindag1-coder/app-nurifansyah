import 'package:flutter/material.dart';
import 'package:flutter_widget_from_html/flutter_widget_from_html.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../config/app_theme.dart';
import '../../core/localization/app_localizations.dart';
import '../../models/page.dart';
import '../../providers/page_provider.dart';
import '../../widgets/responsive_scaffold.dart';

class PageDetailScreen extends StatefulWidget {
  final String slug;

  const PageDetailScreen({super.key, required this.slug});

  @override
  State<PageDetailScreen> createState() => _PageDetailScreenState();
}

class _PageDetailScreenState extends State<PageDetailScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<PageProvider>(context, listen: false).fetchPage(widget.slug);
    });
  }

  @override
  void didUpdateWidget(covariant PageDetailScreen oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.slug != widget.slug) {
      Provider.of<PageProvider>(context, listen: false).fetchPage(widget.slug);
    }
  }

  @override
  Widget build(BuildContext context) {
    final loc = AppLocalizations.of(context);
    final pageProvider = Provider.of<PageProvider>(context);
    final page = pageProvider.getCachedPage(widget.slug);

    return ResponsiveScaffold(
      child: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 900),
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 24),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                TextButton.icon(
                  onPressed: () {
                    if (context.canPop()) {
                      context.pop();
                    } else {
                      context.go('/');
                    }
                  },
                  icon: const Icon(Icons.arrow_back, size: 16),
                  label: Text(loc.navHome),
                ),
                const SizedBox(height: 16),
                if (pageProvider.isLoading && page == null)
                  const Center(
                    child: Padding(
                      padding: EdgeInsets.all(64),
                      child: CircularProgressIndicator(),
                    ),
                  )
                else if (page == null)
                  Center(
                    child: Padding(
                      padding: const EdgeInsets.all(64),
                      child: Column(
                        children: [
                          const Icon(Icons.description_outlined, size: 48, color: Colors.grey),
                          const SizedBox(height: 12),
                          Text(
                            pageProvider.errorMessage ?? 'Halaman tidak ditemukan.',
                            style: const TextStyle(fontSize: 16, color: AppTheme.textMuted),
                          ),
                        ],
                      ),
                    ),
                  )
                else
                  _buildContent(page),
                const SizedBox(height: 48),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildContent(PageModel page) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          page.title,
          style: const TextStyle(
            fontSize: 28,
            fontWeight: FontWeight.bold,
            color: AppTheme.textDark,
          ),
        ),
        const SizedBox(height: 24),
        if (page.imageUrl != null && page.imageUrl!.isNotEmpty) ...[
          ClipRRect(
            borderRadius: BorderRadius.circular(16),
            child: Image.network(
              page.imageUrl!,
              width: double.infinity,
              height: 350,
              fit: BoxFit.cover,
              errorBuilder: (_, __, ___) => const SizedBox.shrink(),
            ),
          ),
          const SizedBox(height: 24),
        ],
        if (page.content != null && page.content!.isNotEmpty)
          HtmlWidget(
            page.content!,
            textStyle: const TextStyle(
              fontSize: 16,
              color: AppTheme.textDark,
              height: 1.7,
            ),
          ),
      ],
    );
  }
}

