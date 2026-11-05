import 'package:flutter/material.dart';
import '../core/network/api_client.dart';
import '../core/network/api_endpoints.dart';
import '../models/post.dart';

class PostProvider extends ChangeNotifier {
  final ApiClient _apiClient;

  List<PostModel> _featuredPosts = [];
  List<PostModel> _posts = [];
  PostModel? _currentPost;

  bool _isLoading = false;
  bool _isLoadingDetail = false;
  bool _isLoadingMore = false;
  String? _errorMessage;

  int _currentPage = 1;
  int _lastPage = 1;
  String? _selectedCategory;
  String _searchQuery = '';

  List<PostModel> get featuredPosts => _featuredPosts;
  List<PostModel> get posts => _posts;
  PostModel? get currentPost => _currentPost;
  bool get isLoading => _isLoading;
  bool get isLoadingDetail => _isLoadingDetail;
  bool get isLoadingMore => _isLoadingMore;
  String? get errorMessage => _errorMessage;
  int get currentPage => _currentPage;
  int get lastPage => _lastPage;
  bool get hasMore => _currentPage < _lastPage;
  String? get selectedCategory => _selectedCategory;
  String get searchQuery => _searchQuery;

  PostProvider(this._apiClient);

  Future<void> fetchFeaturedPosts() async {
    try {
      final res = await _apiClient.get(
        ApiEndpoints.posts,
        queryParameters: {'featured': '1', 'per_page': '5'},
      );
      if (res is Map<String, dynamic> && res['data'] is List) {
        final list = res['data'] as List<dynamic>;
        _featuredPosts = list
            .map((item) => PostModel.fromJson(item as Map<String, dynamic>))
            .toList();
        notifyListeners();
      }
    } catch (_) {}
  }

  Future<void> fetchPosts({
    bool refresh = false,
    String? category,
    String? search,
  }) async {
    if (refresh) {
      _currentPage = 1;
      _posts = [];
      _isLoading = true;
      _errorMessage = null;
      notifyListeners();
    }

    if (category != null) _selectedCategory = category.isEmpty ? null : category;
    if (search != null) _searchQuery = search;

    final queryParams = <String, dynamic>{
      'page': _currentPage.toString(),
      'per_page': '9',
    };

    if (_selectedCategory != null && _selectedCategory!.isNotEmpty) {
      queryParams['category'] = _selectedCategory;
    }

    if (_searchQuery.trim().isNotEmpty) {
      queryParams['search'] = _searchQuery.trim();
    }

    try {
      final res = await _apiClient.get(
        ApiEndpoints.posts,
        queryParameters: queryParams,
      );

      if (res is Map<String, dynamic> && res['data'] is List) {
        final list = res['data'] as List<dynamic>;
        final newPosts = list
            .map((item) => PostModel.fromJson(item as Map<String, dynamic>))
            .toList();

        if (refresh || _currentPage == 1) {
          _posts = newPosts;
        } else {
          _posts.addAll(newPosts);
        }

        final meta = res['meta'] as Map<String, dynamic>?;
        if (meta != null) {
          _currentPage = meta['current_page'] as int? ?? 1;
          _lastPage = meta['last_page'] as int? ?? 1;
        }
      }
    } catch (e) {
      _errorMessage = e.toString();
    } finally {
      _isLoading = false;
      _isLoadingMore = false;
      notifyListeners();
    }
  }

  Future<void> loadMore() async {
    if (_isLoadingMore || !hasMore) return;
    _isLoadingMore = true;
    _currentPage++;
    notifyListeners();
    await fetchPosts(refresh: false);
  }

  Future<PostModel?> fetchPostDetail(String slug) async {
    _isLoadingDetail = true;
    _errorMessage = null;
    _currentPost = null;
    notifyListeners();

    try {
      final res = await _apiClient.get('${ApiEndpoints.posts}/$slug');
      if (res is Map<String, dynamic> && res['data'] != null) {
        _currentPost = PostModel.fromJson(res['data'] as Map<String, dynamic>);
        return _currentPost;
      }
    } catch (e) {
      _errorMessage = e.toString();
    } finally {
      _isLoadingDetail = false;
      notifyListeners();
    }
    return null;
  }

  void selectCategory(String? categorySlug) {
    if (_selectedCategory == categorySlug) {
      _selectedCategory = null;
    } else {
      _selectedCategory = categorySlug;
    }
    fetchPosts(refresh: true);
  }

  void search(String query) {
    _searchQuery = query;
    fetchPosts(refresh: true);
  }
}

