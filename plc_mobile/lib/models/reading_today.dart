import 'package:flutter/foundation.dart';

/// Model untuk response API membaca alkitab harian
///
/// Expected API Response Format:
/// ```json
/// {
///   "date": "13 Desember 2025",
///   "morning": {
///     "data": {
///       "reference": "Kejadian:9-10 (TB)",
///       "audioUrl": [
///         "https://alkitab.mobi/audio/tb/Kej/9.mp3",
///         "https://alkitab.mobi/audio/tb/Kej/10.mp3"
///       ],
///       "content": [
///         {
///           "verse": 1,
///           "text": "Lalu Allah memberkati Nuh dan anak-anaknya..."
///         }
///       ]
///     }
///   },
///   "evening": {
///     "data": {
///       "reference": "Kejadian:11-12 (TB)",
///       "audioUrl": [ ... ],
///       "content": [ ... ]
///     }
///   },
///   "progress": {
///     "current_day": 3,
///     "total_days": 365
///   }
/// }
/// ```
class ReadingTodayResponse {
  final String date;
  final ReadingSection morning;
  final ReadingSection evening;
  final ProgressData progress;

  ReadingTodayResponse({
    required this.date,
    required this.morning,
    required this.evening,
    required this.progress,
  });

  factory ReadingTodayResponse.fromJson(Map<String, dynamic> json) {
    return ReadingTodayResponse(
      date: json['date'] ?? '',
      morning: ReadingSection.fromJson(json['morning'] ?? {}),
      evening: ReadingSection.fromJson(json['evening'] ?? {}),
      progress: ProgressData.fromJson(json['progress'] ?? {}),
    );
  }
}

class ReadingSection {
  final ReadingData data;

  ReadingSection({required this.data});

  factory ReadingSection.fromJson(Map<String, dynamic> json) {
    return ReadingSection(data: ReadingData.fromJson(json['data'] ?? {}));
  }
}

class ReadingData {
  final String reference;
  final List<String> audioUrl;
  final List<VerseContent> content;

  ReadingData({
    required this.reference,
    required this.audioUrl,
    required this.content,
  });

  factory ReadingData.fromJson(Map<String, dynamic> json) {
    List<VerseContent> parseContent(dynamic content) {
      if (content == null) return [];

      if (content is List) {
        return content
            .map((item) => VerseContent.fromJson(item as Map<String, dynamic>))
            .toList();
      }

      return [];
    }

    List<String> parseAudioUrl(dynamic audioUrl) {
      if (audioUrl == null) {
        debugPrint('DEBUG: audioUrl is null');
        return [];
      }

      if (audioUrl is List) {
        final urls = audioUrl.map((item) => item.toString()).toList();
        debugPrint('DEBUG: Parsed audio URLs from list: $urls');
        return urls;
      }

      if (audioUrl is String) {
        debugPrint('DEBUG: Parsed audio URL from string: [$audioUrl]');
        return [audioUrl];
      }

      debugPrint('DEBUG: audioUrl is unknown type: ${audioUrl.runtimeType}');
      return [];
    }

    final parsedAudioUrl = parseAudioUrl(json['audioUrl']);
    
    return ReadingData(
      reference: json['reference'] ?? '',
      audioUrl: parsedAudioUrl,
      content: parseContent(json['content']),
    );
  }
}

class VerseContent {
  final int verse;
  final String text;

  VerseContent({required this.verse, required this.text});

  factory VerseContent.fromJson(Map<String, dynamic> json) {
    return VerseContent(verse: json['verse'] ?? 0, text: json['text'] ?? '');
  }
}

class ProgressData {
  final int currentDay;
  final int totalDays;

  ProgressData({required this.currentDay, required this.totalDays});

  factory ProgressData.fromJson(Map<String, dynamic> json) {
    return ProgressData(
      currentDay: json['current_day'] ?? 0,
      totalDays: json['total_days'] ?? 0,
    );
  }
}
