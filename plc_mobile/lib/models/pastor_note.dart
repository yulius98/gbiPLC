class PastorNote {
  final int id;
  final String tglNote;
  final String note;
  final String filename;
  final String? path;
  final String? deletedAt;
  final String createdAt;
  final String updatedAt;

  PastorNote({
    required this.id,
    required this.tglNote,
    required this.note,
    required this.filename,
    this.path,
    this.deletedAt,
    required this.createdAt,
    required this.updatedAt,
  });

  factory PastorNote.fromJson(Map<String, dynamic> json) {
    return PastorNote(
      id: json['id'] ?? 0,
      tglNote: json['tgl_note'] ?? '',
      note: json['note'] ?? '',
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
      'tgl_note': tglNote,
      'note': note,
      'filename': filename,
      'path': path,
      'deleted_at': deletedAt,
      'created_at': createdAt,
      'updated_at': updatedAt,
    };
  }

  PastorNote copyWith({
    int? id,
    String? tglNote,
    String? note,
    String? filename,
    String? path,
    String? deletedAt,
    String? createdAt,
    String? updatedAt,
  }) {
    return PastorNote(
      id: id ?? this.id,
      tglNote: tglNote ?? this.tglNote,
      note: note ?? this.note,
      filename: filename ?? this.filename,
      path: path ?? this.path,
      deletedAt: deletedAt ?? this.deletedAt,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }

  @override
  String toString() => 'PastorNote(id: $id, tglNote: $tglNote, filename: $filename)';

  @override
  bool operator ==(Object other) =>
      identical(this, other) || (other is PastorNote && other.id == id);

  @override
  int get hashCode => id.hashCode;
}
