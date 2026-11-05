import 'package:flutter/material.dart';
import 'app_drawer.dart';
import 'app_footer.dart';
import 'app_header.dart';

class ResponsiveScaffold extends StatelessWidget {
  final Widget child;
  final Widget? floatingActionButton;
  final bool showFooter;

  const ResponsiveScaffold({
    super.key,
    required this.child,
    this.floatingActionButton,
    this.showFooter = true,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const AppHeader(),
      drawer: const AppDrawer(),
      floatingActionButton: floatingActionButton,
      body: SingleChildScrollView(
        child: Column(
          children: [
            child,
            if (showFooter) const AppFooter(),
          ],
        ),
      ),
    );
  }
}

