import 'package:hive_flutter/hive_flutter.dart';
import 'package:flutter/foundation.dart';

/// Service untuk mengelola persistent cache menggunakan Hive
/// Cache otomatis expired setelah durasi tertentu
class CacheService {
  static const String _cacheBoxName = 'api_cache';
  static Box? _cacheBox;

  /// Initialize Hive dan buka cache box
  static Future<void> init() async {
    try {
      await Hive.initFlutter();
      _cacheBox = await Hive.openBox(_cacheBoxName);
      debugPrint('Cache service initialized');
    } catch (e) {
      debugPrint('Error initializing cache service: $e');
    }
  }

  /// Simpan data ke cache dengan key tertentu
  /// [key] - unique identifier untuk cache
  /// [data] - data yang akan disimpan (harus serializable)
  /// [duration] - durasi cache valid (default 5 menit)
  static Future<void> set(
    String key,
    dynamic data, {
    Duration duration = const Duration(minutes: 5),
  }) async {
    try {
      if (_cacheBox == null) await init();

      final cacheData = {
        'data': data,
        'timestamp': DateTime.now().millisecondsSinceEpoch,
        'expiresIn': duration.inMilliseconds,
      };

      await _cacheBox!.put(key, cacheData);
      debugPrint('Cache set for key: $key');
    } catch (e) {
      debugPrint('Error setting cache: $e');
    }
  }

  /// Ambil data dari cache
  /// Returns null jika cache tidak ada atau sudah expired
  static dynamic get(String key) {
    try {
      if (_cacheBox == null) return null;

      final cacheData = _cacheBox!.get(key);
      if (cacheData == null) return null;

      final timestamp = cacheData['timestamp'] as int;
      final expiresIn = cacheData['expiresIn'] as int;
      final now = DateTime.now().millisecondsSinceEpoch;

      // Check if cache expired
      if (now - timestamp > expiresIn) {
        debugPrint('Cache expired for key: $key');
        delete(key);
        return null;
      }

      debugPrint('Cache hit for key: $key');
      return cacheData['data'];
    } catch (e) {
      debugPrint('Error getting cache: $e');
      return null;
    }
  }

  /// Hapus cache dengan key tertentu
  static Future<void> delete(String key) async {
    try {
      if (_cacheBox == null) return;
      await _cacheBox!.delete(key);
      debugPrint('Cache deleted for key: $key');
    } catch (e) {
      debugPrint('Error deleting cache: $e');
    }
  }

  /// Hapus semua cache
  static Future<void> clearAll() async {
    try {
      if (_cacheBox == null) return;
      await _cacheBox!.clear();
      debugPrint('All cache cleared');
    } catch (e) {
      debugPrint('Error clearing cache: $e');
    }
  }

  /// Check apakah cache dengan key tertentu masih valid
  static bool isValid(String key) {
    return get(key) != null;
  }

  /// Get size of cache box
  static int get cacheSize {
    return _cacheBox?.length ?? 0;
  }
}
