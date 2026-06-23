import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import '../config/api_config.dart';

class ApiException implements Exception {
  final String message;
  final int? statusCode;

  ApiException(this.message, {this.statusCode});

  @override
  String toString() => message;
}

class ApiService {
  String? _token;
  static const _timeout = Duration(seconds: 25);

  void setToken(String? token) => _token = token;

  Map<String, String> get _headers => {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        if (_token != null) 'Authorization': 'Bearer $_token',
      };

  Future<Map<String, dynamic>> get(String path, {Map<String, String>? query}) async {
    final uri = Uri.parse('${ApiConfig.apiUrl}$path').replace(queryParameters: query);
    try {
      final response = await http.get(uri, headers: _headers).timeout(_timeout);
      return _handleResponse(response);
    } on SocketException {
      throw ApiException('No internet connection. Check your network and try again.');
    } on http.ClientException {
      throw ApiException('Cannot reach server. Please try again later.');
    }
  }

  Future<Map<String, dynamic>> post(String path, {Map<String, dynamic>? body}) async {
    final uri = Uri.parse('${ApiConfig.apiUrl}$path');
    try {
      final response = await http
          .post(uri, headers: _headers, body: body != null ? jsonEncode(body) : null)
          .timeout(_timeout);
      return _handleResponse(response);
    } on SocketException {
      throw ApiException('No internet connection. Check your network and try again.');
    } on http.ClientException {
      throw ApiException('Cannot reach server. Please try again later.');
    }
  }

  Future<Map<String, dynamic>> put(String path, {Map<String, dynamic>? body}) async {
    final uri = Uri.parse('${ApiConfig.apiUrl}$path');
    try {
      final response = await http
          .put(uri, headers: _headers, body: body != null ? jsonEncode(body) : null)
          .timeout(_timeout);
      return _handleResponse(response);
    } on SocketException {
      throw ApiException('No internet connection. Check your network and try again.');
    } on http.ClientException {
      throw ApiException('Cannot reach server. Please try again later.');
    }
  }

  Future<Map<String, dynamic>> delete(String path) async {
    final uri = Uri.parse('${ApiConfig.apiUrl}$path');
    try {
      final response = await http.delete(uri, headers: _headers).timeout(_timeout);
      return _handleResponse(response);
    } on SocketException {
      throw ApiException('No internet connection. Check your network and try again.');
    } on http.ClientException {
      throw ApiException('Cannot reach server. Please try again later.');
    }
  }

  Future<Map<String, dynamic>> postMultipart(
    String path, {
    required String fileField,
    required String filePath,
    Map<String, String>? fields,
  }) async {
    final uri = Uri.parse('${ApiConfig.apiUrl}$path');
    final request = http.MultipartRequest('POST', uri);
    request.headers['Accept'] = 'application/json';
    if (_token != null) request.headers['Authorization'] = 'Bearer $_token';
    if (fields != null) request.fields.addAll(fields);
    request.files.add(await http.MultipartFile.fromPath(fileField, filePath));

    try {
      final streamed = await request.send().timeout(_timeout);
      final response = await http.Response.fromStream(streamed);
      return _handleResponse(response);
    } on SocketException {
      throw ApiException('No internet connection. Check your network and try again.');
    } on http.ClientException {
      throw ApiException('Cannot reach server. Please try again later.');
    }
  }

  Future<Map<String, dynamic>> postForm(String path, Map<String, String> fields) async {
    final uri = Uri.parse('${ApiConfig.apiUrl}$path');
    try {
      final response = await http.post(
        uri,
        headers: {
          'Accept': 'application/json',
          if (_token != null) 'Authorization': 'Bearer $_token',
        },
        body: fields,
      ).timeout(_timeout);
      return _handleResponse(response);
    } on SocketException {
      throw ApiException('No internet connection. Check your network and try again.');
    } on http.ClientException {
      throw ApiException('Cannot reach server. Please try again later.');
    }
  }

  Map<String, dynamic> _handleResponse(http.Response response) {
    Map<String, dynamic> data = {};
    if (response.body.isNotEmpty) {
      try {
        final decoded = jsonDecode(response.body);
        if (decoded is Map<String, dynamic>) data = decoded;
      } catch (_) {
        throw ApiException('Invalid server response.');
      }
    }

    if (response.statusCode >= 200 && response.statusCode < 300) {
      return data;
    }

    if (response.statusCode == 404) {
      throw ApiException('API route not found. Server may need an update.', statusCode: 404);
    }

    final message = data['message'] as String? ??
        (data['errors'] is Map ? (data['errors'] as Map).values.first?.first?.toString() : null) ??
        'Request failed (${response.statusCode})';
    throw ApiException(message, statusCode: response.statusCode);
  }
}
