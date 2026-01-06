import '../helpers/url_helper.dart';

class Mypage {
  final int id;
  final String name;
  final String role;
  final String tglLahir;
  final String noHP;
  final String goldarah;
  final String alamat;
  final String email;
  final String? facebook;
  final String? instagram;
  final String? photourl;

  Mypage({
    required this.id,
    required this.name,
    required this.role,
    required this.tglLahir,
    required this.noHP,
    required this.goldarah,
    required this.alamat,
    required this.email,
    this.facebook,
    this.instagram,
    this.photourl,
  });

  factory Mypage.fromJson(Map<String, dynamic> json) {
    // Backend bisa mengirim 'photo_url', 'filename', atau 'path'
    final raw = json['photo_url'] ?? json['filename'] ?? json['path'] ?? '';
    // Convert relative URL to full URL using buildApiUrl
    final url = (raw != null && raw.toString().isNotEmpty)
        ? (raw.toString().startsWith('http')
              ? raw.toString()
              : buildApiUrl('/storage/$raw'))
        : '';

    return Mypage(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      role: json['role'] ?? '',
      email: json['email'] ?? '',
      alamat: json['alamat'] ?? '',
      tglLahir: json['tgl_lahir'] ?? '',
      noHP: json['no_HP'] ?? json['no_hp'] ?? '',
      goldarah: json['gol_darah'] ?? json['goldarah'] ?? '',
      instagram: json['instagram'] ?? '',
      facebook: json['facebook'] ?? '',
      photourl: url,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'role': role,
      'email': email,
      'alamat': alamat,
      'tgl_lahir': tglLahir,
      'no_HP': noHP,
      'gol_darah': goldarah,
      'instagram': instagram,
      'facebook': facebook,
      'photourl': photourl,
    };
  }
}

extension MyPageHelpers on Mypage {
  Mypage copyWith({
    int? id,
    String? name,
    String? role,
    String? tglLahir,
    String? noHP,
    String? goldarah,
    String? alamat,
    String? email,
    String? facebook,
    String? instagram,
    String? photourl,
  }) {
    return Mypage(
      id: id ?? this.id,
      name: name ?? this.name,
      role: role ?? this.role,
      tglLahir: tglLahir ?? this.tglLahir,
      noHP: noHP ?? this.noHP,
      goldarah: goldarah ?? this.goldarah,
      alamat: alamat ?? this.alamat,
      email: email ?? this.email,
      facebook: facebook ?? this.facebook,
      instagram: instagram ?? this.instagram,
      photourl: photourl ?? this.photourl,
    );
  }

  String asDebugString() => 'MyPage(id: $id, name: $name)';
}
