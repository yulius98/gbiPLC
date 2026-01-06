import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:flutter/foundation.dart';
import '../models/reading_today.dart';
import '../helpers/url_helper.dart';

Future<ReadingTodayResponse> fetchReadingToday({
  required String token,
}) async {
  try {
    final raw = '/api/reading/today';
    final uri = buildApiUri(raw);
    
    debugPrint('Calling API: $uri');
    debugPrint('Token: ${token.substring(0, 20)}...');
    
    final response = await http.get(
      uri,
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    ).timeout(Duration(seconds: 15));
    
    debugPrint('Response status: ${response.statusCode}');
    debugPrint('Response body: ${response.body.substring(0, response.body.length > 200 ? 200 : response.body.length)}...');
    
    if (response.statusCode == 200) {
      final jsonMap = json.decode(response.body);
      
      if (jsonMap is Map<String, dynamic>) {
        // Log audio URLs untuk debugging
        if (jsonMap['morning'] != null && jsonMap['morning']['data'] != null) {
          debugPrint('Morning audio URLs: ${jsonMap['morning']['data']['audioUrl']}');
        }
        if (jsonMap['evening'] != null && jsonMap['evening']['data'] != null) {
          debugPrint('Evening audio URLs: ${jsonMap['evening']['data']['audioUrl']}');
        }
        
        final result = ReadingTodayResponse.fromJson(jsonMap);
        debugPrint('Parsed morning audio count: ${result.morning.data.audioUrl.length}');
        debugPrint('Parsed evening audio count: ${result.evening.data.audioUrl.length}');
        return result;
      }
      throw Exception('Response is not a JSON object');
    } else if (response.statusCode == 401) {
      throw Exception('Token tidak valid atau expired. Silakan login kembali.');
    } else if (response.statusCode == 404) {
      throw Exception('Data bacaan untuk hari ini tidak ditemukan.');
    } else if (response.statusCode == 500) {
      debugPrint('Server error 500: ${response.body}');
      throw Exception('Terjadi kesalahan di server. Silakan hubungi administrator atau coba lagi nanti.');
    } else {
      throw Exception('Gagal mengambil data bacaan: ${response.statusCode}');
    }
  } catch (e) {
    debugPrint('Error fetching reading today: $e');
    if (e.toString().contains('SocketException') || e.toString().contains('TimeoutException')) {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    }
    rethrow;
  }
}
