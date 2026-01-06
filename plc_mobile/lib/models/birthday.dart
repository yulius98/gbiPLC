import '../helpers/url_helper.dart';

class Birthday {
  final int id;
  final String name;
  final String photourl;
  final String tglLahir;
  

  Birthday({
    required this.id,
    required this.name,
    required this.photourl,
    required this.tglLahir,
  });

  factory Birthday.fromJson(Map<String, dynamic> json) {
    final raw = json['photo_url'] ?? '';
    // Convert relative URL to full URL using buildApiUrl
    final url = raw.startsWith('http') ? raw : buildApiUrl(raw);
    return Birthday(
      id: json['id'] ?? 0,
      name: json['name'],
      photourl: url,
      tglLahir: json['tgl_lahir'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'photourl': photourl,
      'name': name,
      'tgl_lahir': tglLahir,
    };
  }
}

extension BirthdayHelpers on Birthday {
  Birthday copyWith({int? id, String? name, String? photourl, String? tglLahir}) {
    return Birthday(
      id: id ?? this.id,
      name: name ?? this.name,
      photourl: photourl ?? this.photourl,
      tglLahir: tglLahir ?? this.tglLahir,
    );
  }

  String asDebugString() => 'Birthday(id: $id, name: $name)';
}
