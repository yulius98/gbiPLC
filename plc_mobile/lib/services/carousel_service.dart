import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:flutter/foundation.dart';
import '../models/caraousel.dart';
import '../helpers/url_helper.dart';
import 'cache_service.dart';

const String _cacheKey = 'carousel_data';
const _cacheDuration = Duration(minutes: 10);

Future<List<CarouselItem>> fetchCarousel() async {
  try {
    // Try to get from persistent cache first
    final cachedData = CacheService.get(_cacheKey);
    if (cachedData != null && cachedData is List) {
      debugPrint('Using cached carousel data from Hive');
      return cachedData
          .map((item) => CarouselItem.fromJson(item as Map<String, dynamic>))
          .toList();
    }

    // Fetch from API if no cache
    final raw = '/api/carousel';
    final uri = buildApiUri(raw);
    final response = await http.get(uri).timeout(Duration(seconds: 10));

    if (response.statusCode == 200) {
      final jsonMap = json.decode(response.body);
      if (jsonMap is Map<String, dynamic> && jsonMap['data'] is List) {
        List<dynamic> data = jsonMap['data'];
        final items = data.map((item) => CarouselItem.fromJson(item)).toList();

        // Save to persistent cache
        await CacheService.set(
          _cacheKey,
          data, // Save raw JSON for easy serialization
          duration: _cacheDuration,
        );
        debugPrint('Carousel data cached to Hive');

        return items;
      } else {
        throw Exception('Unexpected JSON structure');
      }
    } else {
      throw Exception('Failed to load carousel: ${response.statusCode}');
    }
  } catch (e) {
    debugPrint('Error fetching carousel: $e');
    // Try cache again even if expired, as fallback
    final cachedData = CacheService.get(_cacheKey);
    if (cachedData != null && cachedData is List) {
      debugPrint('Using expired cached carousel data due to error');
      return cachedData
          .map((item) => CarouselItem.fromJson(item as Map<String, dynamic>))
          .toList();
    }
    throw Exception('Failed to load carousel: $e');
  }
}
