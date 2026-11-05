import 'package:flutter/foundation.dart';
import 'dart:io' show Platform;

class AppConstants {
  static const String appName = 'Portal Layanan Publik';
  static const String appVersion = '1.0.0';

  // Base API URL with platform fallback (Android emulator uses 10.0.2.2)
  static String get baseUrl {
    const fromEnv = String.fromEnvironment('API_BASE_URL');
    if (fromEnv.isNotEmpty) return fromEnv;

    if (kIsWeb) {
      return 'http://localhost:8000/api/v1';
    }

    try {
      if (Platform.isAndroid) {
        return 'http://10.0.2.2:8000/api/v1';
      }
    } catch (_) {}

    return 'http://localhost:8000/api/v1';
  }

  static const Duration requestTimeout = Duration(seconds: 15);

  // Storage keys
  static const String keyLanguage = 'selected_locale';
  static const String defaultLocale = 'id';
  static const List<String> supportedLocales = ['id', 'en'];
}

