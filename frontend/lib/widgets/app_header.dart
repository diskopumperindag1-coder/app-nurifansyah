import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../config/app_theme.dart';
import '../core/localization/app_localizations.dart';
import '../providers/portal_provider.dart';
import 'language_switcher.dart';

class AppHeader extends StatelessWidget implements PreferredSizeWidget {
  const AppHeader({super.key});

  @override
  Size get preferredSize => const Size.fromHeight(72);

  @override
  Widget build(BuildContext context) {
    final loc = AppLocalizations.of(context);
    final portalProvider = Provider.of<PortalProvider>(context);
    final siteName = portalProvider.settings?.siteName ?? loc.appTitle;

    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        border: Border(bottom: BorderSide(color: Colors.grey.shade200)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.02),
            blurRadius: 10,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: SafeArea(
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
          child: LayoutBuilder(
            builder: (context, constraints) {
              final isDesktop = constraints.maxWidth > 850;

              return Row(
                children: [
                  if (!isDesktop)
                    IconButton(
                      icon: const Icon(Icons.menu, color: AppTheme.textDark),
                      onPressed: () => Scaffold.of(context).openDrawer(),
                    ),
                  InkWell(
                    onTap: () => context.go('/'),
                    borderRadius: BorderRadius.circular(8),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Container(
                          width: 40,
                          height: 40,
                          decoration: BoxDecoration(
                            color: AppTheme.primaryBlue,
                            borderRadius: BorderRadius.circular(10),
                          ),
                          child: const Icon(
                            Icons.account_balance,
                            color: Colors.white,
                            size: 24,
                          ),
                        ),
                        const SizedBox(width: 12),
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Text(
                              siteName,
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                                fontSize: 16,
                                color: AppTheme.textDark,
                              ),
                            ),
                            Text(
                              loc.tagline,
                              style: const TextStyle(
                                fontSize: 11,
                                color: AppTheme.textMuted,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                  const Spacer(),
                  if (isDesktop) ...[
                    _navItem(context, label: loc.navHome, route: '/'),
                    _navItem(context, label: loc.navPosts, route: '/posts'),
                    _navItem(context, label: loc.navFaqs, route: '/faqs'),
                    _navItem(context, label: loc.navQuestions, route: '/questions'),
                    _navItem(context, label: loc.navContact, route: '/contact'),
                    const SizedBox(width: 16),
                  ],
                  const LanguageSwitcher(),
                ],
              );
            },
          ),
        ),
      ),
    );
  }

  Widget _navItem(BuildContext context, {required String label, required String route}) {
    final currentRoute = GoRouterState.of(context).uri.toString();
    final isActive = (route == '/' && currentRoute == '/') ||
        (route != '/' && currentRoute.startsWith(route));

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 6),
      child: TextButton(
        onPressed: () => context.go(route),
        style: TextButton.styleFrom(
          foregroundColor: isActive ? AppTheme.primaryBlue : AppTheme.textDark,
          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
          backgroundColor: isActive ? AppTheme.primaryBlue.withOpacity(0.08) : Colors.transparent,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
        ),
        child: Text(
          label,
          style: TextStyle(
            fontWeight: isActive ? FontWeight.bold : FontWeight.w500,
            fontSize: 14,
          ),
        ),
      ),
    );
  }
}

