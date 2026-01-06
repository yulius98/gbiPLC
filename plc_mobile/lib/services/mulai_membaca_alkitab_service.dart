import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:flutter/foundation.dart';
import '../models/mulai_membaca_alkitab.dart';
import '../helpers/url_helper.dart';


Future<MembacaAlkitabResponse?> fetchMulaiMembacaAlkitab({
  required String token,
  required String tanggal,
}) async {
  try {
    // Ganti endpoint ke helper so it adapts for Android emulator or LAN host
    final raw = '/api/reading/start-date';
    final uri = buildApiUri(raw);
    
    debugPrint('Calling API: $uri');
    debugPrint('Token: ${token.substring(0, 20)}...');
    debugPrint('Tanggal: $tanggal');
    
    final response = await http.post(
      uri,
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: json.encode({
        'start_date': tanggal,
      }),
    ).timeout(Duration(seconds: 10));
    
    debugPrint('Response status: ${response.statusCode}');
    debugPrint('Response body: ${response.body}');
    
    if (response.statusCode == 200) {
      final jsonMap = json.decode(response.body);
      
      debugPrint('Parsed JSON: $jsonMap');
      debugPrint('Has date field: ${jsonMap.containsKey('date')}');
      debugPrint('Has tanggal_mulai field: ${jsonMap.containsKey('tanggal_mulai')}');
      
      // Handle the actual response structure from API
      if (jsonMap is Map<String, dynamic>) {
        // Response structure: {"status": false, "tanggal_mulai": "2025-12-07", "message": "..."}
        // or {"message": "..."} or any other structure
        final response = MembacaAlkitabResponse.fromJson(jsonMap);
        debugPrint('Parsed response: ${response.toString()}');
        return response;
      }
      throw Exception('Response is not a JSON object');
    } else if (response.statusCode == 401) {
      throw Exception('Token tidak valid atau expired. Silakan login kembali.');
    } else {
      throw Exception('Server error: ${response.statusCode} - ${response.body}');
    }
  } catch (e) {
    debugPrint('Error fetching membaca Alkitab: $e');
    rethrow;
  }
}
