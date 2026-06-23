import 'package:flutter/foundation.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/models.dart';
import '../services/api_service.dart';

class AuthProvider extends ChangeNotifier {
  AuthProvider(this._api);

  final ApiService _api;
  static const _tokenKey = 'jetfly_auth_token';

  UserModel? _user;
  String? _token;
  bool _loading = true;

  UserModel? get user => _user;
  String? get token => _token;
  bool get isLoggedIn => _token != null && _user != null;
  bool get loading => _loading;

  Future<void> init() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString(_tokenKey);
    if (_token != null) {
      _api.setToken(_token);
      try {
        final data = await _api.get('/auth/user');
        _user = UserModel.fromJson(data['user'] as Map<String, dynamic>);
      } catch (_) {
        await logout();
      }
    }
    _loading = false;
    notifyListeners();
  }

  Future<void> login(String email, String password) async {
    final data = await _api.post('/auth/login', body: {
      'email': email,
      'password': password,
    });
    await _saveSession(data['token'] as String, data['user'] as Map<String, dynamic>);
  }

  Future<void> register({
    required String name,
    required String email,
    required String password,
    String? phone,
    String? referral,
  }) async {
    final data = await _api.post('/auth/register', body: {
      'name': name,
      'email': email,
      'password': password,
      'password_confirmation': password,
      if (phone != null && phone.isNotEmpty) 'phone': phone,
      if (referral != null && referral.isNotEmpty) 'referral': referral,
    });
    await _saveSession(data['token'] as String, data['user'] as Map<String, dynamic>);
  }

  Future<void> sendOtp(String phone) async {
    await _api.post('/auth/otp/send', body: {'phone': phone});
  }

  Future<void> verifyOtp(String phone, String code) async {
    final data = await _api.post('/auth/otp/verify', body: {
      'phone': phone,
      'code': code,
    });
    await _saveSession(data['token'] as String, data['user'] as Map<String, dynamic>);
  }

  Future<void> logout() async {
    try {
      if (_token != null) await _api.post('/auth/logout');
    } catch (_) {}
    _token = null;
    _user = null;
    _api.setToken(null);
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_tokenKey);
    notifyListeners();
  }

  Future<void> refreshUser() async {
    if (!isLoggedIn) return;
    final data = await _api.get('/auth/user');
    _user = UserModel.fromJson(data['user'] as Map<String, dynamic>);
    notifyListeners();
  }

  Future<void> _saveSession(String token, Map<String, dynamic> userJson) async {
    _token = token;
    _user = UserModel.fromJson(userJson);
    _api.setToken(token);
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_tokenKey, token);
    notifyListeners();
  }
}
