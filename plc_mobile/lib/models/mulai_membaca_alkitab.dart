// Response model for /api/reading/start-date endpoint
class MembacaAlkitabResponse {
  final bool status;
  final String? tanggalMulai;
  final String message;

  MembacaAlkitabResponse({
    required this.status,
    this.tanggalMulai,
    required this.message,
  });

  factory MembacaAlkitabResponse.fromJson(Map<String, dynamic> json) {
    // Try to get date from multiple possible fields
    final dateValue = json['date'] ?? json['tanggal_mulai'] ?? json['reading_start_date'] ?? '';
    
    return MembacaAlkitabResponse(
      status: json['status'] ?? false,
      tanggalMulai: dateValue,
      message: json['message'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'status': status,
      'tanggal_mulai': tanggalMulai,
      'message': message,
    };
  }

  @override
  String toString() => 'MembacaAlkitabResponse(status: $status, tanggal_mulai: $tanggalMulai, message: $message)';
}

// Original model for detailed reading data
class MulaiMembacaAlkitab {
  final int id ;
  final String email;
  final String readingStartDate;
  final String? tanggalMulai;
  final String? deletedAt;
  final String createdAt;
  final String updatedAt;


  MulaiMembacaAlkitab({
    required this.id,
    required this.email,
    required this.readingStartDate,
    this.tanggalMulai,
    this.deletedAt,
    required this.createdAt,
    required this.updatedAt,
  });

  factory MulaiMembacaAlkitab.fromJson(Map<String, dynamic> json) {
    return MulaiMembacaAlkitab(
      id: json['id'] ?? 0,
      email: json['email'] ?? '',
      readingStartDate: json['reading_start_date'] ?? '',
      tanggalMulai: json['tanggal_mulai'],
      deletedAt: json['deleted_at'],
      createdAt: json['created_at'] ?? '',
      updatedAt: json['updated_at'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'email': email,
      'reading_start_date': readingStartDate,
      'tanggal_mulai': tanggalMulai,
      'deleted_at': deletedAt,
      'created_at': createdAt,
      'updated_at': updatedAt,
    };
  }

  MulaiMembacaAlkitab copyWith({
    int? id,
    String? email,
    String? readingStartDate,
    String? tanggalMulai,
    String? deletedAt,
    String? createdAt,
    String? updatedAt,
  }) {
    return MulaiMembacaAlkitab(
      id: id ?? this.id,
      email: email ?? this.email,
      readingStartDate: readingStartDate ?? this.readingStartDate,
      tanggalMulai: tanggalMulai ?? this.tanggalMulai,
      deletedAt: deletedAt ?? this.deletedAt,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }

  @override
  String toString() => 'MulaiMembacaAlkitab(id: $id, email: $email, reading_start_date: $readingStartDate, tanggal_mulai: $tanggalMulai)';

  @override
  bool operator ==(Object other) =>
      identical(this, other) || (other is MulaiMembacaAlkitab && other.id == id);

  @override
  int get hashCode => id.hashCode;
}
