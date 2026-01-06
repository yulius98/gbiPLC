import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:flutter/foundation.dart';
import '../models/pastor_note.dart';
import '../helpers/url_helper.dart';


Future<PastorNote?> fetchPastorNote() async {
  try {
    // Ganti endpoint ke helper so it adapts for Android emulator or LAN host
    final raw = '/api/pastornote';
    final uri = buildApiUri(raw);
    final response = await http.get(uri).timeout(Duration(seconds: 10));
    
    if (response.statusCode == 200) {
      final jsonMap = json.decode(response.body);
      
      if (jsonMap is Map<String, dynamic> && 
          jsonMap['status'] == true && 
          jsonMap['data'] != null) {
        return PastorNote.fromJson(jsonMap['data']);
      } else {
        throw Exception('Unexpected JSON structure');
      }
    } else {
      throw Exception('Failed to load pastor note: ${response.statusCode}');
    }
  } catch (e) {
    debugPrint('Error fetching pastor note: $e');
    throw Exception('Failed to load pastor note: $e');
  }
}
