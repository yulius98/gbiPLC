class Register {
  final int id;
  final String name;
  final String tglLahir;
  final String noHP;
  final String goldarah;
  final String alamat;
  final String email;
  final String? facebook;
  final String? instagram;
  final String? filename;

  Register({
    required this.id,
    required this.name,
    required this.tglLahir,
    required this.noHP,
    required this.goldarah,
    required this.alamat,
    required this.email,
    this.facebook,
    this.instagram,
    this.filename
  });

  factory Register.fromJson(Map<String, dynamic> json) {
    return Register(
      id: json['id'] ?? 0,
      name: json['name'],
      email: json['email'],
      alamat: json['alamat'],
      tglLahir: json['tgl_lahir'],
      noHP: json['no_HP'],
      goldarah: json['gol_darah'],
      instagram: json['instagram'],
      facebook: json['facebook'],
      filename: json['filename'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email' : email,
      'alamat' : alamat,
      'tgl_lahir': tglLahir,
      'no_HP' : noHP,
      'gol_darah' : goldarah,
      'instagram' : instagram,
      'facebook' : facebook,
      'filename' : filename,
    };
  }
}

extension RegisterHelpers on Register {
  Register copyWith({
    int? id,
    String? name,
    String? tglLahir,
    String? noHP,
    String? goldarah,
    String? alamat,
    String? email,
    String? facebook,
    String? instagram,
    String? filename,
  }) {
    return Register(
      id: id ?? this.id,
      name: name ?? this.name,
      tglLahir: tglLahir ?? this.tglLahir,
      noHP: noHP ?? this.noHP,
      goldarah: goldarah ?? this.goldarah,
      alamat: alamat ?? this.alamat,
      email: email ?? this.email,
      facebook: facebook ?? this.facebook,
      instagram: instagram ?? this.instagram,
      filename: filename ?? this.filename,
    );
  }

  String asDebugString() => 'Register(id: $id, name: $name)';
}
