class IbadahRaya {
  final int id;
  final String tglibadah;
  final String ibadahke;
  final String linkibadah;
  final String? deletedAt;
  final String createdAt;
  final String updatedAt;

  IbadahRaya({
    required this.id,
    required this.tglibadah,
    required this.ibadahke,
    required this.linkibadah,
    this.deletedAt,
    required this.createdAt,
    required this.updatedAt,
  });

  factory IbadahRaya.fromJson(Map<String, dynamic> json) {
    return IbadahRaya(
      id: json['id'] ?? 0,
      tglibadah: json['tgl_ibadah'] ?? '',
      ibadahke: json['ibadah_ke'] ?? '',
      linkibadah: json['link_ibadah'] ?? '',
      deletedAt: json['deleted_at'],
      createdAt: json['created_at'] ?? '',
      updatedAt: json['updated_at'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'tgl_kotbah': tglibadah,
      'ibadah_ke': ibadahke,
      'link_ibadah': linkibadah,
      'deleted_at': deletedAt,
      'created_at': createdAt,
      'updated_at': updatedAt,
    };
  }

  IbadahRaya copyWith({
    int? id,
    String? tglibadah,
    String? ibadahke,
    String? linkibadah,
    String? deletedAt,
    String? createdAt,
    String? updatedAt,
  }) {
    return IbadahRaya(
      id: id ?? this.id,
      tglibadah: tglibadah ?? this.tglibadah,
      ibadahke: ibadahke ?? this.ibadahke,
      linkibadah: linkibadah ?? this.linkibadah,
      deletedAt: deletedAt ?? this.deletedAt,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }

  @override
  String toString() => 'IbadahRaya(id: $id, ibadahke: $ibadahke)';

  @override
  bool operator ==(Object other) =>
      identical(this, other) || (other is IbadahRaya && other.id == id);

  @override
  int get hashCode => id.hashCode;
}

// Model untuk response get link ibadah raya
class IbadahRayaResponse {
  final String? tglibadah;
  final String? ibadahke;
  final String? linkibadah;
  final bool success;
  final String? message;

  IbadahRayaResponse({
    this.tglibadah,
    this.ibadahke,
    this.linkibadah,
    required this.success,
    this.message,
  });

  factory IbadahRayaResponse.fromJson(Map<String, dynamic> json) {
    // Handle struktur JSON dari backend: {"status": true, "message": "...", "data": {...}}
    if (json['status'] == true && json['data'] != null) {
      final data = json['data'];
      if (data is Map<String, dynamic>) {
        return IbadahRayaResponse(
          tglibadah: data['tgl_ibadah'],
          ibadahke: data['ibadah_ke'],
          linkibadah: data['link_ibadah'],
          success: true,
        );
      }
    }

    // Fallback untuk format tidak valid atau data kosong
    return IbadahRayaResponse(
      success: false,
      message: json['message'] ?? 'Data tidak valid',
    );
  }

  factory IbadahRayaResponse.error(String message) {
    return IbadahRayaResponse(success: false, message: message);
  }
}
