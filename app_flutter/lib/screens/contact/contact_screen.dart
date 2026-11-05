import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../config/app_theme.dart';
import '../../core/localization/app_localizations.dart';
import '../../providers/portal_provider.dart';
import '../../widgets/responsive_scaffold.dart';

class ContactScreen extends StatelessWidget {
  const ContactScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final loc = AppLocalizations.of(context);
    final portal = Provider.of<PortalProvider>(context);
    final settings = portal.settings;

    return ResponsiveScaffold(
      child: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 1000),
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 24),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  loc.navContact,
                  style: const TextStyle(
                    fontSize: 26,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.textDark,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  'Hubungi kami melalui kontak resmi di bawah ini atau kunjungi kantor pelayanan kami.',
                  style: const TextStyle(fontSize: 14, color: AppTheme.textMuted),
                ),
                const SizedBox(height: 24),

                LayoutBuilder(
                  builder: (context, constraints) {
                    final isDesktop = constraints.maxWidth > 700;

                    final cards = [
                      _contactCard(
                        icon: Icons.location_on,
                        title: loc.officeAddress,
                        value: settings?.officeAddress ?? 'Jl. Merdeka No. 123, Indonesia',
                      ),
                      _contactCard(
                        icon: Icons.phone,
                        title: loc.officePhone,
                        value: settings?.officePhone ?? '(021) 1234567',
                      ),
                      _contactCard(
                        icon: Icons.email,
                        title: loc.officeEmail,
                        value: settings?.officeEmail ?? 'kontak@instansi.go.id',
                      ),
                    ];

                    if (isDesktop) {
                      return Row(
                        children: cards.map((c) => Expanded(child: Padding(padding: const EdgeInsets.symmetric(horizontal: 6), child: c))).toList(),
                      );
                    }

                    return Column(
                      children: cards.map((c) => Padding(padding: const EdgeInsets.only(bottom: 12), child: c)).toList(),
                    );
                  },
                ),
                const SizedBox(height: 32),

                // Office Hours / Service Notice Card
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(24),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: const [
                            Icon(Icons.schedule, color: AppTheme.primaryBlue),
                            SizedBox(width: 8),
                            Text(
                              'Jam Pelayanan Publik',
                              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                            ),
                          ],
                        ),
                        const SizedBox(height: 12),
                        const Text(
                          'Senin – Kamis : 08.00 – 16.00 WIB\n'
                          'Jumat : 08.00 – 16.30 WIB\n'
                          'Sabtu, Minggu & Hari Libur Nasional : Tutup',
                          style: TextStyle(fontSize: 14, height: 1.6, color: AppTheme.textDark),
                        ),
                      ],
                    ),
                  ),
                ),
                const SizedBox(height: 48),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _contactCard({
    required IconData icon,
    required String title,
    required String value,
  }) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              padding: const EdgeInsets.all(10),
              decoration: BoxDecoration(
                color: AppTheme.primaryBlue.withValues(alpha: 0.08),
                borderRadius: BorderRadius.circular(10),
              ),
              child: Icon(icon, color: AppTheme.primaryBlue, size: 24),
            ),
            const SizedBox(height: 14),
            Text(
              title,
              style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15),
            ),
            const SizedBox(height: 6),
            Text(
              value,
              style: const TextStyle(color: AppTheme.textMuted, fontSize: 13, height: 1.4),
            ),
          ],
        ),
      ),
    );
  }
}

