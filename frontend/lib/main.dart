import 'package:flutter/material.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:provider/provider.dart';
import 'config/app_constants.dart';
import 'config/app_theme.dart';
import 'core/localization/app_locale_provider.dart';
import 'core/localization/app_localizations.dart';
import 'core/network/api_client.dart';
import 'core/router/app_router.dart';
import 'providers/page_provider.dart';
import 'providers/portal_provider.dart';
import 'providers/post_provider.dart';
import 'providers/question_provider.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AppLocaleProvider()),
        ProxyProvider<AppLocaleProvider, ApiClient>(
          update: (_, localeProvider, previous) {
            final client = previous ?? ApiClient();
            client.updateLocale(localeProvider.languageCode);
            return client;
          },
        ),
        ChangeNotifierProxyProvider<ApiClient, PortalProvider>(
          create: (ctx) => PortalProvider(ctx.read<ApiClient>()),
          update: (_, apiClient, previous) => previous ?? PortalProvider(apiClient),
        ),
        ChangeNotifierProxyProvider<ApiClient, PostProvider>(
          create: (ctx) => PostProvider(ctx.read<ApiClient>()),
          update: (_, apiClient, previous) => previous ?? PostProvider(apiClient),
        ),
        ChangeNotifierProxyProvider<ApiClient, QuestionProvider>(
          create: (ctx) => QuestionProvider(ctx.read<ApiClient>()),
          update: (_, apiClient, previous) => previous ?? QuestionProvider(apiClient),
        ),
        ChangeNotifierProxyProvider<ApiClient, PageProvider>(
          create: (ctx) => PageProvider(ctx.read<ApiClient>()),
          update: (_, apiClient, previous) => previous ?? PageProvider(apiClient),
        ),
      ],
      child: Consumer<AppLocaleProvider>(
        builder: (context, localeProvider, _) {
          return MaterialApp.router(
            title: AppConstants.appName,
            debugShowCheckedModeBanner: false,
            theme: AppTheme.lightTheme(),
            locale: localeProvider.locale,
            supportedLocales: const [
              Locale('id'),
              Locale('en'),
            ],
            localizationsDelegates: const [
              AppLocalizations.delegate,
              GlobalMaterialLocalizations.delegate,
              GlobalWidgetsLocalizations.delegate,
              GlobalCupertinoLocalizations.delegate,
            ],
            routerConfig: AppRouter.router,
          );
        },
      ),
    );
  }
}
