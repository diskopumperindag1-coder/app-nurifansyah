import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../config/app_theme.dart';
import '../core/localization/app_localizations.dart';
import '../providers/portal_provider.dart';

class AppFooter extends StatelessWidget {
  const AppFooter({super.key});

  @override
  Widget build(BuildContext context) {
    final loc = AppLocalizations.of(context);
    final portalProvider = Provider.of<PortalProvider>(context);
    final settings = portalProvider.settings;

    return Container(
      color: AppTheme.primaryDark,
      padding: const EdgeInsets.only(top: 48, bottom: 24, left: 24, right: 24),
      child: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 1200),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              LayoutBuilder(
                builder: (context, constraints) {
                  final isDesktop = constraints.maxWidth > 800;

                  final leftCol = Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Container(
                            width: 36,
                            height: 36,
                            decoration: BoxDecoration(
                              color: AppTheme.accentTeal,
                              borderRadius: BorderRadius.circular(8),
                            ),
                            child: const Icon(Icons.account_balance, color: Colors.white, size: 20),
                          ),
                          const SizedBox(width: 12),
                          Text(
                            settings?.siteName ?? loc.appTitle,
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 12),
                      Text(
                        settings?.siteDescription ?? loc.tagline,
                        style: TextStyle(
                          color: Colors.white.withOpacity(0.7),
                          fontSize: 13,
                          height: 1.5,
                        ),
                      ),
                    ],
                  );

                  final centerCol = Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        loc.quickLinks,
                        style: const TextStyle(
                          color: Colors.white,
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 12),
                      _footerLink(context, label: loc.navHome, route: '/'),
                      _footerLink(context, label: loc.navPosts, route: '/posts'),
                      _footerLink(context, label: loc.navFaqs, route: '/faqs'),
                      _footerLink(context, label: loc.navQuestions, route: '/questions'),
                      _footerLink(context, label: loc.navContact, route: '/contact'),
                    ],
                  );

                  final rightCol = Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        loc.officeAddress,
                        style: const TextStyle(
                          color: Colors.white,
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 12),
                      if (settings?.officeAddress != null) ...[
                        Row(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Icon(Icons.location_on_outlined, size: 16, color: AppTheme.accentTeal),
                            const SizedBox(width: 8),
                            Expanded(
                              child: Text(
                                settings!.officeAddress!,
                                style: TextStyle(color: Colors.white.withOpacity(0.8), fontSize: 13),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                      ],
                      if (settings?.officePhone != null) ...[
                        Row(
                          children: [
                            const Icon(Icons.phone_outlined, size: 16, color: AppTheme.accentTeal),
                            const SizedBox(width: 8),
                            Text(
                              settings!.officePhone!,
                              style: TextStyle(color: Colors.white.withOpacity(0.8), fontSize: 13),
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                      ],
                      if (settings?.officeEmail != null) ...[
                        Row(
                          children: [
                            const Icon(Icons.email_outlined, size: 16, color: AppTheme.accentTeal),
                            const SizedBox(width: 8),
                            Text(
                              settings!.officeEmail!,
                              style: TextStyle(color: Colors.white.withOpacity(0.8), fontSize: 13),
                            ),
                          ],
                        ),
                      ],
                    ],
                  );

                  if (isDesktop) {
                    return Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Expanded(flex: 4, child: leftCol),
                        const SizedBox(width: 48),
                        Expanded(flex: 3, child: centerCol),
                        const SizedBox(width: 48),
                        Expanded(flex: 4, child: rightCol),
                      ],
                    );
                  }

                  return Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      leftCol,
                      const SizedBox(height: 32),
                      centerCol,
                      const SizedBox(height: 32),
                      rightCol,
                    ],
                  );
                },
              ),
              const SizedBox(height: 48),
              Divider(color: Colors.white.withOpacity(0.1)),
              const SizedBox(height: 16),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Expanded(
                    child: Text(
                      '© ${DateTime.now().year} ${settings?.siteName ?? loc.appTitle}. ${loc.copyright}',
                      style: TextStyle(
                        color: Colors.white.withOpacity(0.5),
                        fontSize: 12,
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _footerLink(BuildContext context, {required String label, required String route}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: InkWell(
        onTap: () => context.go(route),
        child: Text(
          label,
          style: TextStyle(
            color: Colors.white.withOpacity(0.75),
            fontSize: 13,
          ),
        ),
      ),
    );
  }
}

