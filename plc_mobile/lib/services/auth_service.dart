import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter/foundation.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../models/login_response.dart';
import '../helpers/url_helper.dart';

class AuthService {
    /// Kirim permintaan reset password ke endpoint /api/forgot-password
    /// Returns true jika berhasil, throws Exception jika gagal
    Future<bool> sendPasswordResetLink(String email) async {
      try {
        debugPrint('🔗 Password reset request to: [1m${buildApiUrl('/api/forgot-password')}[0m');
        debugPrint('📧 Email: $email');
        final Map<String, dynamic> data = {
          'email': email,
        };
        debugPrint('📦 Request Body: ${jsonEncode(data)}');
        final response = await http.post(
          buildApiUri('/api/forgot-password'),
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: jsonEncode(data),
        );
        debugPrint('📡 Response Status: ${response.statusCode}');
        debugPrint('📄 Response Body: ${response.body}');
        if (response.statusCode == 200) {
          return true;
        } else {
          try {
            final Map<String, dynamic> errorData = jsonDecode(response.body);
            final errorMessage = errorData['message'] ?? 'Gagal mengirim link reset password';
            debugPrint('❌ Backend Error: $errorMessage');
            throw Exception(errorMessage);
          } catch (parseError) {
            debugPrint('❌ Parse Error: $parseError');
            debugPrint('❌ Raw Response: ${response.body}');
            throw Exception('Gagal mengirim link reset password. Status: ${response.statusCode}');
          }
        }
      } catch (e) {
        String errorMessage = e.toString();
        if (errorMessage.startsWith('Exception: ')) {
          throw Exception(errorMessage.replaceFirst('Exception: ', ''));
        }
        throw Exception('Gagal mengirim link reset password: $errorMessage');
      }
    }
  final FlutterSecureStorage _secureStorage = const FlutterSecureStorage();

  // Key untuk menyimpan token di secure storage
  static const String _accessTokenKey = 'access_token';
  static const String _tokenTypeKey = 'token_type';
  static const String _expiresInKey = 'expires_in';
  static const String _refreshTtlKey = 'refresh_ttl';

  /// Login user dengan username dan password
  /// Returns LoginResponse jika berhasil, throws Exception jika gagal
  Future<LoginResponse> login(String username, String password) async {
    try {
      debugPrint('🔐 Login attempt to: ${buildApiUrl('/api/login')}');
      debugPrint('📧 Username/Email: $username');
      
      // Tentukan apakah input adalah email atau username
      final bool isEmail = username.contains('@');
      
      final Map<String, dynamic> loginData = {
        'password': password,
      };
      
      // Tambahkan field email atau username sesuai format input
      if (isEmail) {
        loginData['email'] = username;
      } else {
        // Coba dengan username terlebih dahulu
        loginData['username'] = username;
        // Backend mungkin butuh email juga, tambahkan keduanya
        loginData['email'] = username;
      }
      
      debugPrint('📦 Request Body: ${jsonEncode(loginData)}');
      
      final response = await http.post(
        buildApiUri('/api/login'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode(loginData),
      );

      debugPrint('📡 Response Status: ${response.statusCode}');
      debugPrint('📄 Response Body: ${response.body}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        final loginResponse = LoginResponse.fromJson(data);
        
        // Simpan token ke secure storage
        await _saveTokenToStorage(loginResponse);
        
        return loginResponse;
      } else if (response.statusCode == 401) {
        // Parse error message khusus untuk 401 Unauthorized
        try {
          final Map<String, dynamic> errorData = jsonDecode(response.body);
          final errorMessage = errorData['message'] ?? 
                              errorData['error'] ?? 
                              'Username atau password salah';
          debugPrint('❌ Auth Error (401): $errorMessage');
          throw Exception(errorMessage);
        } catch (parseError) {
          debugPrint('❌ Parse Error: $parseError');
          debugPrint('❌ Raw Response: ${response.body}');
          throw Exception('Username atau password salah');
        }
      } else {
        // Parse error message dari backend jika ada
        try {
          final Map<String, dynamic> errorData = jsonDecode(response.body);
          final errorMessage = errorData['message'] ?? 'Login gagal';
          debugPrint('❌ Backend Error: $errorMessage');
          throw Exception(errorMessage);
        } catch (parseError) {
          // Jika gagal parse JSON, gunakan response body langsung
          debugPrint('❌ Parse Error: $parseError');
          debugPrint('❌ Raw Response: ${response.body}');
          throw Exception('Login gagal. Status: ${response.statusCode}');
        }
      }
    } catch (e) {
      // Tangani error khusus dari backend
      String errorMessage = e.toString();
      
      // Jika error terkait JWT Guard
      if (errorMessage.contains('JWTCookiesguard') || 
          errorMessage.contains('once()')) {
        throw Exception('Terjadi kesalahan konfigurasi server. Silakan hubungi administrator.');
      }
      
      // Error umum lainnya
      if (errorMessage.startsWith('Exception: ')) {
        throw Exception(errorMessage.replaceFirst('Exception: ', ''));
      }
      
      throw Exception('Gagal melakukan login: $errorMessage');
    }
  }

  /// Simpan token ke secure storage
  Future<void> _saveTokenToStorage(LoginResponse loginResponse) async {
    await _secureStorage.write(
      key: _accessTokenKey,
      value: loginResponse.accessToken,
    );
    await _secureStorage.write(
      key: _tokenTypeKey,
      value: loginResponse.tokenType,
    );
    await _secureStorage.write(
      key: _expiresInKey,
      value: loginResponse.expiresIn.toString(),
    );
    await _secureStorage.write(
      key: _refreshTtlKey,
      value: loginResponse.refreshTtl.toString(),
    );
  }

  /// Ambil access token dari secure storage
  Future<String?> getAccessToken() async {
    return await _secureStorage.read(key: _accessTokenKey);
  }

  /// Ambil token type dari secure storage
  Future<String?> getTokenType() async {
    return await _secureStorage.read(key: _tokenTypeKey);
  }

  /// Check apakah user sudah login
  Future<bool> isLoggedIn() async {
    final token = await getAccessToken();
    return token != null && token.isNotEmpty;
  }

  /// Logout - panggil API logout dan hapus token dari secure storage
  Future<void> logout() async {
    try {
      final token = await getAccessToken();
      
      if (token != null && token.isNotEmpty) {
        // Panggil API logout
        await http.post(
          buildApiUri('/api/logout'),
          headers: {
            'Authorization': 'Bearer $token',
            'Accept': 'application/json',
          },
        ).timeout(const Duration(seconds: 10));
      }
    } catch (e) {
      // Tetap lanjutkan logout meskipun API gagal
      debugPrint('Error calling logout API: $e');
    } finally {
      // Hapus token dari secure storage
      await _secureStorage.delete(key: _accessTokenKey);
      await _secureStorage.delete(key: _tokenTypeKey);
      await _secureStorage.delete(key: _expiresInKey);
      await _secureStorage.delete(key: _refreshTtlKey);
    }
  }

  /// Clear all storage (untuk debugging)
  Future<void> clearAllStorage() async {
    await _secureStorage.deleteAll();
  }
}
