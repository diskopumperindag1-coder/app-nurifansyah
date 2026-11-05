import 'package:flutter/material.dart';
import '../core/network/api_client.dart';
import '../core/network/api_endpoints.dart';
import '../models/faq.dart';
import '../models/question.dart';

class QuestionProvider extends ChangeNotifier {
  final ApiClient _apiClient;

  List<FaqModel> _faqs = [];
  List<QuestionModel> _questions = [];

  bool _isLoadingFaqs = false;
  bool _isLoadingQuestions = false;
  bool _isSubmitting = false;

  String? _faqError;
  String? _questionError;
  String? _submitSuccessMessage;
  Map<String, dynamic>? _validationErrors;

  int? _selectedTopicId;
  String _searchQuery = '';

  List<FaqModel> get faqs => _faqs;
  List<QuestionModel> get questions => _questions;
  bool get isLoadingFaqs => _isLoadingFaqs;
  bool get isLoadingQuestions => _isLoadingQuestions;
  bool get isSubmitting => _isSubmitting;
  String? get faqError => _faqError;
  String? get questionError => _questionError;
  String? get submitSuccessMessage => _submitSuccessMessage;
  Map<String, dynamic>? get validationErrors => _validationErrors;
  int? get selectedTopicId => _selectedTopicId;

  QuestionProvider(this._apiClient);

  Future<void> fetchFaqs({int? topicId}) async {
    _isLoadingFaqs = true;
    _faqError = null;
    if (topicId != null) _selectedTopicId = topicId;
    notifyListeners();

    try {
      final queryParams = <String, dynamic>{};
      if (_selectedTopicId != null) {
        queryParams['topic'] = _selectedTopicId.toString();
      }

      final res = await _apiClient.get(
        ApiEndpoints.faqs,
        queryParameters: queryParams,
      );

      if (res is Map<String, dynamic> && res['data'] is List) {
        final list = res['data'] as List<dynamic>;
        _faqs = list
            .map((item) => FaqModel.fromJson(item as Map<String, dynamic>))
            .toList();
      }
    } catch (e) {
      _faqError = e.toString();
    } finally {
      _isLoadingFaqs = false;
      notifyListeners();
    }
  }

  Future<void> fetchQuestions({int? topicId, String? search}) async {
    _isLoadingQuestions = true;
    _questionError = null;
    if (topicId != null) _selectedTopicId = topicId;
    if (search != null) _searchQuery = search;
    notifyListeners();

    try {
      final queryParams = <String, dynamic>{};
      if (_selectedTopicId != null) {
        queryParams['topic'] = _selectedTopicId.toString();
      }
      if (_searchQuery.trim().isNotEmpty) {
        queryParams['search'] = _searchQuery.trim();
      }

      final res = await _apiClient.get(
        ApiEndpoints.questions,
        queryParameters: queryParams,
      );

      if (res is Map<String, dynamic> && res['data'] is List) {
        final list = res['data'] as List<dynamic>;
        _questions = list
            .map((item) => QuestionModel.fromJson(item as Map<String, dynamic>))
            .toList();
      }
    } catch (e) {
      _questionError = e.toString();
    } finally {
      _isLoadingQuestions = false;
      notifyListeners();
    }
  }

  Future<bool> submitQuestion({
    required int topicId,
    required String name,
    required String email,
    required String phone,
    required String subject,
    required String body,
  }) async {
    _isSubmitting = true;
    _submitSuccessMessage = null;
    _validationErrors = null;
    notifyListeners();

    try {
      final res = await _apiClient.post(
        ApiEndpoints.questions,
        body: {
          'topic_id': topicId,
          'name': name,
          'email': email,
          'phone': phone,
          'subject': subject,
          'body': body,
        },
      );

      if (res is Map<String, dynamic>) {
        _submitSuccessMessage = res['message'] as String? ??
            'Pertanyaan berhasil dikirim dan akan segera diproses.';
        _isSubmitting = false;
        notifyListeners();
        return true;
      }
    } on ApiException catch (e) {
      if (e.statusCode == 422) {
        _validationErrors = e.errors;
      } else {
        _questionError = e.message;
      }
    } catch (e) {
      _questionError = e.toString();
    } finally {
      _isSubmitting = false;
      notifyListeners();
    }
    return false;
  }

  void clearErrors() {
    _validationErrors = null;
    _submitSuccessMessage = null;
    _faqError = null;
    _questionError = null;
    notifyListeners();
  }

  void selectTopic(int? topicId) {
    _selectedTopicId = topicId;
    fetchFaqs();
    fetchQuestions();
  }
}

