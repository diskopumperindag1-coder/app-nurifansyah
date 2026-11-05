import 'package:flutter/material.dart';
import '../core/network/api_client.dart';
import '../core/network/api_endpoints.dart';
import '../models/category.dart';
import '../models/menu.dart';
import '../models/question_topic.dart';
import '../models/setting.dart';
import '../models/slide.dart';

class PortalProvider extends ChangeNotifier {
  final ApiClient _apiClient;

  SettingModel? _settings;
  List<SlideModel> _slides = [];
  MenuModel? _headerMenu;
  MenuModel? _footerMenu;
  List<CategoryModel> _categories = [];
  List<QuestionTopicModel> _topics = [];

  bool _isLoading = false;
  String? _errorMessage;

  SettingModel? get settings => _settings;
  List<SlideModel> get slides => _slides;
  MenuModel? get headerMenu => _headerMenu;
  MenuModel? get footerMenu => _footerMenu;
  List<CategoryModel> get categories => _categories;
  List<QuestionTopicModel> get topics => _topics;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;

  PortalProvider(this._apiClient);

  Future<void> fetchPortalData() async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      await Future.wait([
        _fetchSettings(),
        _fetchSlides(),
        _fetchHeaderMenu(),
        _fetchFooterMenu(),
        _fetchCategories(),
        _fetchTopics(),
      ]);
    } catch (e) {
      _errorMessage = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> _fetchSettings() async {
    try {
      final res = await _apiClient.get(ApiEndpoints.settings);
      if (res is Map<String, dynamic> && res['data'] != null) {
        _settings = SettingModel.fromJson(res['data'] as Map<String, dynamic>);
      }
    } catch (_) {}
  }

  Future<void> _fetchSlides() async {
    try {
      final res = await _apiClient.get(ApiEndpoints.slides);
      if (res is Map<String, dynamic> && res['data'] is List) {
        final list = res['data'] as List<dynamic>;
        _slides = list
            .map((item) => SlideModel.fromJson(item as Map<String, dynamic>))
            .toList();
      }
    } catch (_) {}
  }

  Future<void> _fetchHeaderMenu() async {
    try {
      final res = await _apiClient.get('${ApiEndpoints.menus}/header');
      if (res is Map<String, dynamic> && res['data'] != null) {
        _headerMenu = MenuModel.fromJson(res['data'] as Map<String, dynamic>);
      }
    } catch (_) {}
  }

  Future<void> _fetchFooterMenu() async {
    try {
      final res = await _apiClient.get('${ApiEndpoints.menus}/footer');
      if (res is Map<String, dynamic> && res['data'] != null) {
        _footerMenu = MenuModel.fromJson(res['data'] as Map<String, dynamic>);
      }
    } catch (_) {}
  }

  Future<void> _fetchCategories() async {
    try {
      final res = await _apiClient.get(ApiEndpoints.categories);
      if (res is Map<String, dynamic> && res['data'] is List) {
        final list = res['data'] as List<dynamic>;
        _categories = list
            .map((item) => CategoryModel.fromJson(item as Map<String, dynamic>))
            .toList();
      }
    } catch (_) {}
  }

  Future<void> _fetchTopics() async {
    try {
      final res = await _apiClient.get(ApiEndpoints.questionTopics);
      if (res is Map<String, dynamic> && res['data'] is List) {
        final list = res['data'] as List<dynamic>;
        _topics = list
            .map((item) => QuestionTopicModel.fromJson(item as Map<String, dynamic>))
            .toList();
      }
    } catch (_) {}
  }
}

