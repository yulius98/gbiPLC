class MateriKotbah {
  final int id;
  final String tglkotbah;
  final String judul;
  final String filename;
  final String? path;
  final String? deletedAt;
  final String createdAt;
  final String updatedAt;

  MateriKotbah({
    required this.id,
    required this.tglkotbah,
    required this.judul,
    required this.filename,
    this.path,
    this.deletedAt,
    required this.createdAt,
    required this.updatedAt,
  });

  factory MateriKotbah.fromJson(Map<String, dynamic> json) {
    return MateriKotbah(
      id: json['id'] ?? 0,
      tglkotbah: json['tgl_kotbah'] ?? '',
      judul: json['judul'] ?? '',
      filename: json['filename'] ?? '',
      path: json['path'],
      deletedAt: json['deleted_at'],
      createdAt: json['created_at'] ?? '',
      updatedAt: json['updated_at'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'tgl_kotbah': tglkotbah,
      'judul': judul,
      'filename': filename,
      'path': path,
      'deleted_at': deletedAt,
      'created_at': createdAt,
      'updated_at': updatedAt,
    };
  }

  MateriKotbah copyWith({
    int? id,
    String? tglkotbah,
    String? judul,
    String? filename,
    String? path,
    String? deletedAt,
    String? createdAt,
    String? updatedAt,
  }) {
    return MateriKotbah(
      id: id ?? this.id,
      tglkotbah: tglkotbah ?? this.tglkotbah,
      judul: judul ?? this.judul,
      filename: filename ?? this.filename,
      path: path ?? this.path,
      deletedAt: deletedAt ?? this.deletedAt,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }

  @override
  String toString() => 'MateriKotbah(id: $id, judul: $judul)';

  @override
  bool operator ==(Object other) =>
      identical(this, other) || (other is MateriKotbah && other.id == id);

  @override
  int get hashCode => id.hashCode;
}

// Model untuk response get link materi kotbah
class MateriKotbahResponse {
  final String? tglKotbah;
  final String? judul;
  final String? link;
  final bool success;
  final String? message;

  MateriKotbahResponse({
    this.tglKotbah,
    this.judul,
    this.link,
    required this.success,
    this.message,
  });

  factory MateriKotbahResponse.fromJson(Map<String, dynamic> json) {
    // Handle struktur JSON dari backend: {"status": true, "message": "...", "data": [...]}
    if (json['status'] == true &&
        json['data'] != null &&
        json['data'] is List) {
      final dataList = json['data'] as List;
      if (dataList.isNotEmpty) {
        final firstItem = dataList[0] as Map<String, dynamic>;
        // Convert any localhost URL when used on Android emulator
        final rawLink = firstItem['materi_kotbah_url'];
        return MateriKotbahResponse(
          tglKotbah: firstItem['tgl_kotbah'],
          judul: firstItem['judul'],
          link: rawLink,
          success: true,
        );
      }
    }

    // Fallback untuk format langsung (jika ada)
    return MateriKotbahResponse(
      tglKotbah: json['tgl_kotbah'],
      judul: json['judul'],
      link: json['link'],
      success: true,
    );
  }

  factory MateriKotbahResponse.error(String message) {
    return MateriKotbahResponse(success: false, message: message);
  }
}
