import 'package:flutter/material.dart';
import '../core/network/api_client.dart';
import '../core/network/api_endpoints.dart';
import '../models/page.dart';

class PageProvider extends ChangeNotifier {
  final ApiClient _apiClient;
  final Map<String, PageModel> _pagesCache = {};

  bool _isLoading = false;
  String? _errorMessage;

  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;

  PageProvider(this._apiClient);

  PageModel? getCachedPage(String slug) => _pagesCache[slug];

  Future<PageModel?> fetchPage(String slug, {bool forceReload = false}) async {
    if (!forceReload && _pagesCache.containsKey(slug)) {
      return _pagesCache[slug];
    }

    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final res = await _apiClient.get('${ApiEndpoints.pages}/$slug');
      if (res is Map<String, dynamic> && res['data'] != null) {
        final page = PageModel.fromJson(res['data'] as Map<String, dynamic>);
        _pagesCache[slug] = page;
        return page;
      }
    } catch (e) {
      _errorMessage = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
    return null;
  }

  void clearCache() {
    _pagesCache.clear();
  }
}

