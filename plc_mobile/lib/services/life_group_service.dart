import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:flutter/foundation.dart';
import '../models/life_group.dart';
import '../helpers/url_helper.dart';

Future<List<LifeGroup>> fetchLifeGroup() async {
  try {
    final raw = '/api/lifegroup';
    final uri = buildApiUri(raw);
    final response = await http.get(uri).timeout(Duration(seconds: 10));

    if (response.statusCode == 200) {
      final jsonMap = json.decode(response.body);

      if (jsonMap is Map<String, dynamic> &&
          jsonMap['data'] is List) {
        List<dynamic> data = jsonMap['data'];
        return data.map((item) => LifeGroup.fromJson(item)).toList();
      } else {
        throw Exception('Unexpected JSON structure');
      }
    } else {
      throw Exception('Failed to load life group: ${response.statusCode}');
    }
  } catch (e) {
    debugPrint('Error fetching life group: $e');
    throw Exception('Failed to load life group: $e');
  }
}
