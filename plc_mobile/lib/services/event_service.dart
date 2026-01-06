import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:flutter/foundation.dart';
import '../models/event.dart';
import '../helpers/url_helper.dart';

Future<List<Event>> fetchEvent() async {
  try {
    final raw = '/api/event';
    final uri = buildApiUri(raw);
    final response = await http.get(uri).timeout(Duration(seconds: 10));

    if (response.statusCode == 200) {
      final jsonMap = json.decode(response.body);

      if (jsonMap is Map<String, dynamic> &&
          jsonMap['data'] is List) {
        List<dynamic> data = jsonMap['data'];
        return data.map((item) => Event.fromJson(item)).toList();
      } else {
        throw Exception('Unexpected JSON structure');
      }
    } else {
      throw Exception('Failed to load birthday: ${response.statusCode}');
    }
  } catch (e) {
    debugPrint('Error fetching birthday: $e');
    throw Exception('Failed to load birthday: $e');
  }
}
