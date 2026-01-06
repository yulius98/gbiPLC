import 'package:http/http.dart' as http;
import 'dart:io';
import '../helpers/url_helper.dart';
import 'package:mime/mime.dart';
import 'package:http_parser/http_parser.dart';

// Fungsi untuk melakukan register dengan MultipartRequest
Future<http.StreamedResponse> registerUser({
  required String name,
  required String email,
  required String alamat,
  required String tglLahir,
  required String noHP,
  required String golDarah,
  String? instagram,
  String? facebook,
  File? imageFile,
}) async {
  final raw = '/api/register';
  final uri = buildApiUri(raw);
  var request = http.MultipartRequest('POST', uri);
  request.fields['name'] = name;
  request.fields['email'] = email;
  request.fields['alamat'] = alamat;
  request.fields['tgl_lahir'] = tglLahir;
  request.fields['no_HP'] = noHP;
  request.fields['gol_darah'] = golDarah;
  if (instagram != null) request.fields['instagram'] = instagram;
  if (facebook != null) request.fields['facebook'] = facebook;
  if (imageFile != null) {
    final allowedExtensions = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
    final fileExtension = imageFile.path.split('.').last.toLowerCase();

    if (!allowedExtensions.contains(fileExtension)) {
      throw Exception('Format file tidak valid. Harus berupa: ${allowedExtensions.join(', ')}');
    }

    String fileName = '${name.replaceAll(' ', '_')}.$fileExtension';

    // Deteksi MIME type dari file
    final mimeType = lookupMimeType(imageFile.path) ?? 'image/jpeg';
    final mimeTypeParts = mimeType.split('/');
    
    request.files.add(await http.MultipartFile.fromPath(
      'filename', // Gunakan 'filename' sebagai key field
      imageFile.path,
      filename: fileName,
      contentType: MediaType(mimeTypeParts[0], mimeTypeParts[1]),
    ));
  }

  final response = await request.send();
  return response;
}
