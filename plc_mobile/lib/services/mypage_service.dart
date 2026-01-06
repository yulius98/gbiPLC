import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../models/mypage.dart';
import '../helpers/url_helper.dart';

Future<List<Mypage>> fetchMypage() async {
  try {
    // Ambil token dari secure storage
    const storage = FlutterSecureStorage();
    final token = await storage.read(key: 'access_token');
    
    if (token == null || token.isEmpty) {
      throw Exception('Token tidak ditemukan. Silakan login terlebih dahulu.');
    }

    debugPrint('Token: ${token.substring(0, 20)}...');

    // Gunakan endpoint untuk mendapatkan profil user yang sedang login
    final uri = buildApiUri('/api/user');
    debugPrint('Fetching profile from: $uri');
    
    final response = await http.get(
      uri,
      headers: {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
      },
    ).timeout(const Duration(seconds: 10));

    debugPrint('Response status: ${response.statusCode}');
    debugPrint('Response body: ${response.body}');

    if (response.statusCode == 200) {
      final jsonData = json.decode(response.body);

      // Cek jika response berupa single object (user profile)
      if (jsonData is Map<String, dynamic>) {
        // Jika memiliki key 'user', gunakan itu (dari backend Laravel)
        if (jsonData.containsKey('user')) {
          final userData = jsonData['user'];
          if (userData is Map<String, dynamic>) {
            return [Mypage.fromJson(userData)];
          }
        }
        // Jika memiliki key 'data', gunakan itu
        else if (jsonData.containsKey('data')) {
          final data = jsonData['data'];
          if (data is Map<String, dynamic>) {
            return [Mypage.fromJson(data)];
          } else if (data is List) {
            return data.map((item) => Mypage.fromJson(item)).toList();
          }
        }
        // Jika tidak ada key 'data' atau 'user', anggap object langsung adalah user profile
        else {
          return [Mypage.fromJson(jsonData)];
        }
      } 
      // Jika response adalah array langsung
      else if (jsonData is List) {
        return jsonData.map((item) => Mypage.fromJson(item)).toList();
      } 
      
      throw Exception('Unexpected JSON structure');
    } else if (response.statusCode == 401) {
      throw Exception('Token tidak valid. Silakan login kembali.');
    } else {
      throw Exception('Failed to load profile: HTTP ${response.statusCode} - ${response.body}');
    }
  } catch (e) {
    debugPrint('Error fetching my profile: $e');
    rethrow;
  }
}
