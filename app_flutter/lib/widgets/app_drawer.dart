import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../config/app_theme.dart';
import '../core/localization/app_localizations.dart';
import '../providers/portal_provider.dart';
import 'language_switcher.dart';

class AppDrawer extends StatelessWidget {
  const AppDrawer({super.key});

  @override
  Widget build(BuildContext context) {
    final loc = AppLocalizations.of(context);
    final portalProvider = Provider.of<PortalProvider>(context);
    final siteName = portalProvider.settings?.siteName ?? loc.appTitle;

    return Drawer(
      child: SafeArea(
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.all(20),
              decoration: const BoxDecoration(
                color: AppTheme.primaryBlue,
              ),
              child: Row(
                children: [
                  Container(
                    width: 44,
                    height: 44,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: const Icon(Icons.account_balance, color: AppTheme.primaryBlue, size: 28),
                  ),
                  const SizedBox(width: 14),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          siteName,
                          style: const TextStyle(
                            color: Colors.white,
                            fontWeight: FontWeight.bold,
                            fontSize: 16,
                          ),
                        ),
                        Text(
                          loc.tagline,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: TextStyle(
                            color: Colors.white.withValues(alpha: 0.8),
                            fontSize: 11,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 12),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    loc.languageSwitcher,
                    style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13),
                  ),
                  const LanguageSwitcher(),
                ],
              ),
            ),
            const Divider(),
            Expanded(
              child: ListView(
                padding: const EdgeInsets.symmetric(vertical: 8),
                children: [
                  _drawerItem(
                    context,
                    icon: Icons.home_outlined,
                    label: loc.navHome,
                    route: '/',
                  ),
                  _drawerItem(
                    context,
                    icon: Icons.newspaper_outlined,
                    label: loc.navPosts,
                    route: '/posts',
                  ),
                  _drawerItem(
                    context,
                    icon: Icons.quiz_outlined,
                    label: loc.navFaqs,
                    route: '/faqs',
                  ),
                  _drawerItem(
                    context,
                    icon: Icons.question_answer_outlined,
                    label: loc.navQuestions,
                    route: '/questions',
                  ),
                  _drawerItem(
                    context,
                    icon: Icons.contact_phone_outlined,
                    label: loc.navContact,
                    route: '/contact',
                  ),
                  if (portalProvider.headerMenu != null) ...[
                    const Divider(),
                    ...portalProvider.headerMenu!.items
                        .where((item) => item.type == 'page')
                        .map((item) {
                      return ListTile(
                        leading: const Icon(Icons.description_outlined, size: 20),
                        title: Text(item.label, style: const TextStyle(fontSize: 14)),
                        onTap: () {
                          Navigator.of(context).pop();
                          context.go(item.url);
                        },
                      );
                    }),
                  ],
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _drawerItem(
    BuildContext context, {
    required IconData icon,
    required String label,
    required String route,
  }) {
    final currentRoute = GoRouterState.of(context).uri.toString();
    final isActive = (route == '/' && currentRoute == '/') ||
        (route != '/' && currentRoute.startsWith(route));

    return ListTile(
      leading: Icon(
        icon,
        color: isActive ? AppTheme.primaryBlue : AppTheme.textMuted,
      ),
      title: Text(
        label,
        style: TextStyle(
          color: isActive ? AppTheme.primaryBlue : AppTheme.textDark,
          fontWeight: isActive ? FontWeight.bold : FontWeight.w500,
        ),
      ),
      selected: isActive,
      onTap: () {
        Navigator.of(context).pop();
        context.go(route);
      },
    );
  }
}

