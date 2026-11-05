import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../config/app_constants.dart';

class AppLocaleProvider extends ChangeNotifier {
  Locale _locale = const Locale(AppConstants.defaultLocale);

  Locale get locale => _locale;
  String get languageCode => _locale.languageCode;
  bool get isIndonesian => _locale.languageCode == 'id';
  bool get isEnglish => _locale.languageCode == 'en';

  AppLocaleProvider() {
    _loadSavedLocale();
  }

  Future<void> _loadSavedLocale() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final savedCode = prefs.getString(AppConstants.keyLanguage);
      if (savedCode != null && AppConstants.supportedLocales.contains(savedCode)) {
        _locale = Locale(savedCode);
        notifyListeners();
      }
    } catch (_) {}
  }

  Future<void> setLocale(String languageCode) async {
    if (!AppConstants.supportedLocales.contains(languageCode)) return;
    if (_locale.languageCode == languageCode) return;

    _locale = Locale(languageCode);
    notifyListeners();

    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString(AppConstants.keyLanguage, languageCode);
    } catch (_) {}
  }

  Future<void> toggleLocale() async {
    final next = isIndonesian ? 'en' : 'id';
    await setLocale(next);
  }
}

