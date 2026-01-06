import 'package:http/http.dart' as http;
import 'dart:convert';
import '../models/ibadah_raya.dart';
import '../helpers/url_helper.dart';

// Fungsi untuk mendapatkan materi kotbah berdasarkan tanggal
Future<IbadahRayaResponse> getIbadahRayaByData(String tglibadah, String ibadahke) async {
  try {
    final raw = '/api/ibadahraya';
    final uri = buildApiUri(raw).replace(queryParameters: {
      'tgl_ibadah': tglibadah,
      'ibadah_ke': ibadahke,
    });

    final response = await http.get(uri).timeout(Duration(seconds: 10));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);

      if (data != null && data is Map<String, dynamic>) {
        // Check apakah response sukses dari backend
        if (data['status'] == true && data['data'] != null) {
          final responseData = data['data'];
          if (responseData is Map<String, dynamic>) {
            return IbadahRayaResponse.fromJson(data);
          } else {
            return IbadahRayaResponse.error('Data tidak valid');
          }
        } else {
          return IbadahRayaResponse.error(
            data['message'] ?? 'Data tidak ditemukan',
          );
        }
      } else {
        return IbadahRayaResponse.error('Data tidak valid');
      }
    } else {
      return IbadahRayaResponse.error(
        'Ibadah Raya tidak ditemukan (Status: ${response.statusCode})',
      );
    }
  } catch (e) {
    return IbadahRayaResponse.error('Error: $e');
  }
}
