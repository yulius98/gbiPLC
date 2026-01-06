import '../helpers/url_helper.dart';

class Event {
  final int id;
  final String tglevent;
  final String keterangan;
  final String photourl;
  

  Event({
    required this.id,
    required this.tglevent,
    required this.keterangan,
    required this.photourl,
  });

  factory Event.fromJson(Map<String, dynamic> json) {
    final raw = json['photo_url'] ?? '';
    // Convert relative URL to full URL using buildApiUrl
    final url = raw.startsWith('http') ? raw : buildApiUrl(raw);
    return Event(
      id: json['id'] ?? 0,
      tglevent: json['tgl_event'],
      keterangan: json['keterangan'],
      photourl: url,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'tgl_event': tglevent,
      'keterangan': keterangan,
      'photourl': photourl,
    };
  }
}

extension EventHelpers on Event {
  Event copyWith({int? id, String? tglevent, String? keterangan, String? photourl}) {
    return Event(
      id: id ?? this.id,
      tglevent: tglevent ?? this.tglevent,
      keterangan: keterangan ?? this.keterangan,
      photourl: photourl ?? this.photourl,
    );
  }

  String asDebugString() => 'Event(id: $id, tglevent: $tglevent)';
}
