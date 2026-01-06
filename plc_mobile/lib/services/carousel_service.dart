import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:flutter/foundation.dart';
import '../models/caraousel.dart';
import '../helpers/url_helper.dart';


Future<List<CarouselItem>> fetchCarousel() async {
  try {
    // Use helper so this can adapt for Android emulator if needed
    final raw = '/api/carousel';
    final uri = buildApiUri(raw);
    final response = await http.get(uri).timeout(Duration(seconds: 10));
    
    if (response.statusCode == 200) {
      final jsonMap = json.decode(response.body);
      if (jsonMap is Map<String, dynamic> && jsonMap['data'] is List) {
        List<dynamic> data = jsonMap['data'];
        return data.map((item) => CarouselItem.fromJson(item)).toList();
      } else {
        throw Exception('Unexpected JSON structure');
      }
    } else {
      throw Exception('Failed to load carousel: ${response.statusCode}');
    }
  } catch (e) {
    debugPrint('Error fetching carousel: $e');
    throw Exception('Failed to load carousel: $e');
  }
}