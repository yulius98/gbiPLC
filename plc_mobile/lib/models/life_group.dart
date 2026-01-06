class LifeGroup {
  final int id;
  final String namakomsel;
  final String ketuakomsel;
  final String notelp;
  final String? alamat;
  final String? deletedAt;
  final String createdAt;
  final String updatedAt;

  LifeGroup({
    required this.id,
    required this.namakomsel,
    required this.ketuakomsel,
    required this.notelp,
    required this.alamat,
    this.deletedAt,
    required this.createdAt,
    required this.updatedAt,
  });

  factory LifeGroup.fromJson(Map<String, dynamic> json) {
    return LifeGroup(
      id: json['id'] ?? 0,
      namakomsel: json['nama_komsel'] ?? '',
      ketuakomsel: json['ketua_komsel'] ?? '',
      notelp: json['no_telp'] ?? '',
      alamat: json['alamat'] ?? '',
      deletedAt: json['deleted_at'],
      createdAt: json['created_at'] ?? '',
      updatedAt: json['updated_at'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nama_komsel': namakomsel,
      'ketua_komsel': ketuakomsel,
      'no_telp': notelp,
      'alamat' : alamat,
      'deleted_at': deletedAt,
      'created_at': createdAt,
      'updated_at': updatedAt,
    };
  }

  LifeGroup copyWith({
    int? id,
    String? namakomsel,
    String? ketuakomsel,
    String? notelp,
    String? alamat,
    String? deletedAt,
    String? createdAt,
    String? updatedAt,
  }) {
    return LifeGroup(
      id: id ?? this.id,
      namakomsel: namakomsel ?? this.namakomsel,
      ketuakomsel: ketuakomsel ?? this.ketuakomsel,
      notelp: notelp ?? this.notelp,
      alamat: alamat ?? this.alamat,
      deletedAt: deletedAt ?? this.deletedAt,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }

  @override
  String toString() => 'LifeGroup(id: $id, namakomsel: $namakomsel, ketuakomsel: $ketuakomsel, notelp: $notelp, alamat: $alamat)';

  @override
  bool operator ==(Object other) =>
      identical(this, other) || (other is LifeGroup && other.id == id);

  @override
  int get hashCode => id.hashCode;
}
