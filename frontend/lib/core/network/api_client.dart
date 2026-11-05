import 'dart:async';
import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import '../../config/app_constants.dart';

class ApiException implements Exception {
  final String message;
  final int? statusCode;
  final Map<String, dynamic>? errors;

  ApiException(this.message, {this.statusCode, this.errors});

  @override
  String toString() => 'ApiException: $message (Status: $statusCode)';
}

class ApiClient {
  final String baseUrl;
  final http.Client _httpClient;
  String currentLocale;

  ApiClient({
    String? baseUrl,
    http.Client? httpClient,
    this.currentLocale = AppConstants.defaultLocale,
  })  : baseUrl = baseUrl ?? AppConstants.baseUrl,
        _httpClient = httpClient ?? http.Client();

  void updateLocale(String locale) {
    currentLocale = locale;
  }

  Map<String, String> get _headers => {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Accept-Language': currentLocale,
        'X-Locale': currentLocale,
      };

  Uri _buildUri(String path, [Map<String, dynamic>? queryParameters]) {
    final cleanPath = path.startsWith('/') ? path : '/$path';
    final fullUrl = '$baseUrl$cleanPath';

    final uri = Uri.parse(fullUrl);
    if (queryParameters == null || queryParameters.isEmpty) {
      return uri;
    }

    final sanitizedParams = <String, String>{};
    queryParameters.forEach((key, value) {
      if (value != null) {
        sanitizedParams[key] = value.toString();
      }
    });

    return uri.replace(queryParameters: sanitizedParams);
  }

  Future<dynamic> get(
    String path, {
    Map<String, dynamic>? queryParameters,
  }) async {
    try {
      final uri = _buildUri(path, queryParameters);
      final response = await _httpClient
          .get(uri, headers: _headers)
          .timeout(AppConstants.requestTimeout);

      return _handleResponse(response);
    } on SocketException {
      throw ApiException('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on TimeoutException {
      throw ApiException('Koneksi timeout. Silakan coba kembali.');
    } catch (e) {
      if (e is ApiException) rethrow;
      throw ApiException('Terjadi kesalahan tidak terduga: $e');
    }
  }

  Future<dynamic> post(
    String path, {
    Map<String, dynamic>? body,
  }) async {
    try {
      final uri = _buildUri(path);
      final response = await _httpClient
          .post(
            uri,
            headers: _headers,
            body: body != null ? jsonEncode(body) : null,
          )
          .timeout(AppConstants.requestTimeout);

      return _handleResponse(response);
    } on SocketException {
      throw ApiException('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on TimeoutException {
      throw ApiException('Koneksi timeout. Silakan coba kembali.');
    } catch (e) {
      if (e is ApiException) rethrow;
      throw ApiException('Terjadi kesalahan tidak terduga: $e');
    }
  }

  dynamic _handleResponse(http.Response response) {
    final bodyString = utf8.decode(response.bodyBytes);
    dynamic decoded;
    if (bodyString.isNotEmpty) {
      try {
        decoded = jsonDecode(bodyString);
      } catch (_) {
        decoded = bodyString;
      }
    }

    if (response.statusCode >= 200 && response.statusCode < 300) {
      return decoded;
    }

    if (response.statusCode == 422 && decoded is Map<String, dynamic>) {
      final message = decoded['message'] as String? ?? 'Validasi gagal';
      final errors = decoded['errors'] as Map<String, dynamic>?;
      throw ApiException(message, statusCode: 422, errors: errors);
    }

    if (decoded is Map<String, dynamic> && decoded['message'] != null) {
      throw ApiException(
        decoded['message'] as String,
        statusCode: response.statusCode,
      );
    }

    throw ApiException(
      'Server error: HTTP ${response.statusCode}',
      statusCode: response.statusCode,
    );
  }
}

