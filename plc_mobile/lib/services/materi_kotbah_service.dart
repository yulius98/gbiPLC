import 'package:http/http.dart' as http;
import 'dart:convert';
import '../models/materi_kotbah.dart';
import '../helpers/url_helper.dart';

// Fungsi untuk mendapatkan materi kotbah berdasarkan tanggal
Future<MateriKotbahResponse> getMateriKotbahByDate(String tglKotbah) async {
  try {
    final raw = '/api/materi-kotbah/getlink/';
    final uri = buildApiUri(raw).replace(queryParameters: {'tgl_kotbah': tglKotbah});

    final response = await http.get(uri).timeout(Duration(seconds: 10));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);

      if (data != null && data is Map<String, dynamic>) {
        // Check apakah response sukses dari backend
        if (data['status'] == true && data['data'] != null) {
          final dataList = data['data'] as List;
          if (dataList.isNotEmpty) {
            return MateriKotbahResponse.fromJson(data);
          } else {
            return MateriKotbahResponse.error('Data tidak ditemukan');
          }
        } else {
          return MateriKotbahResponse.error(
            data['message'] ?? 'Data tidak ditemukan',
          );
        }
      } else {
        return MateriKotbahResponse.error('Data tidak valid');
      }
    } else {
      return MateriKotbahResponse.error(
        'Materi kotbah tidak ditemukan (Status: ${response.statusCode})',
      );
    }
  } catch (e) {
    return MateriKotbahResponse.error('Error: $e');
  }
}
