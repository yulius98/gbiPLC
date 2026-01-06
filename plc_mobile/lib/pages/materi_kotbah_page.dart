import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'dart:ui';
import 'dart:io';
import 'package:plc_mobile/services/materi_kotbah_service.dart';
import 'package:plc_mobile/models/materi_kotbah.dart';
import 'package:plc_mobile/pages/pdf_viewer_page.dart';
import '../helpers/url_helper.dart';
import 'package:path_provider/path_provider.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:flutter/services.dart';

class MateriKotbahPage extends StatefulWidget {
  const MateriKotbahPage({super.key});

  @override
  State<MateriKotbahPage> createState() => _MateriKotbahPageState();
}

class _MateriKotbahPageState extends State<MateriKotbahPage> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _tglKotbahController = TextEditingController();
  bool _isLoading = false;
  MateriKotbahResponse? _searchResult;
  bool _hasSearched = false;
  bool _isDownloading = false;
  bool _isCheckingFile = false;
  bool? _fileExists;

  Future<void> _pickDate() async {
    DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(1900),
      lastDate: DateTime.now(),
    );
    if (picked != null) {
      _tglKotbahController.text = DateFormat('yyyy-MM-dd').format(picked);
    }
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() {
      _isLoading = true;
      _searchResult = null;
      _hasSearched = false;
    });

    try {
      final result = await getMateriKotbahByDate(_tglKotbahController.text);

      if (mounted) {
        setState(() {
          _hasSearched = true;
          _searchResult = result;
          _fileExists = null; // Reset file check
        });

        // Check file availability if result found
        if (result.success && result.link != null && result.link!.isNotEmpty) {
          _checkFileAvailability(result.link!);
        }

        if (!result.success && result.message != null) {
          ScaffoldMessenger.of(
            context,
          ).showSnackBar(SnackBar(content: Text(result.message!)));
        }
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _hasSearched = true;
          _searchResult = MateriKotbahResponse.error('Error: $e');
        });
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(SnackBar(content: Text('Error: $e')));
      }
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      extendBodyBehindAppBar: true,
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        centerTitle: true,
        foregroundColor: Colors.white,
      ),
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [
              Color(0xFF0f2027), // deep blue
              Color(0xFF2c5364), // blue-grey
              Color(0xFF6a3093), // royal purple
              Color(0xFFa044ff), // purple-gold
              Color(0xFFf7971e), // gold
            ],
          ),
        ),
        child: Center(
          child: SingleChildScrollView(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 32),
              child: ClipRRect(
                borderRadius: BorderRadius.circular(24),
                child: Stack(
                  alignment: Alignment.center,
                  children: [
                    // Glassmorphism effect
                    BackdropFilter(
                      filter: ImageFilter.blur(sigmaX: 18, sigmaY: 18),
                      child: Container(
                        width: double.infinity,
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(24),
                          gradient: LinearGradient(
                            begin: Alignment.topLeft,
                            end: Alignment.bottomRight,
                            colors: [
                              Colors.white.withValues(alpha: 0.55),
                              Color(0xFFf8fafc).withValues(alpha: 0.35),
                              Color(0xFFe0c3fc).withValues(alpha: 0.25),
                            ],
                          ),
                        ),
                      ),
                    ),
                    // Formulir di atas efek glass
                    Padding(
                      padding: const EdgeInsets.all(24),
                      child: Form(
                        key: _formKey,
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.stretch,
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            // Menambahkan gambar di atas tulisan "Materi Kotbah"
                            Image.asset(
                              'assets/tema/materi_kotbah.jpg',
                              height: 400,
                              fit: BoxFit.fill,
                            ),
                            const SizedBox(height: 10),
                            Text(
                              'Materi Kotbah',
                              style: TextStyle(
                                fontSize: 22,
                                fontWeight: FontWeight.bold,
                                color: Color.fromARGB(255, 237, 239, 240),
                                letterSpacing: 1.2,
                              ),
                              textAlign: TextAlign.center,
                            ),
                            const SizedBox(height: 24),
                            const SizedBox(height: 14),
                            GestureDetector(
                              onTap: _pickDate,
                              child: AbsorbPointer(
                                child: _buildTextField(
                                  _tglKotbahController,
                                  'Tanggal Kotbah',
                                  Icons.date_range,
                                  suffixIcon: Icon(
                                    Icons.calendar_today,
                                    color: Colors.black,
                                  ),
                                  required: true,
                                ),
                              ),
                            ),

                            const SizedBox(height: 28),
                            Container(
                              width: double.infinity,
                              height: 48,
                              decoration: BoxDecoration(
                                gradient: const LinearGradient(
                                  colors: [
                                    Color(0xFF232526),
                                    Color(0xFF485563),
                                  ],
                                  begin: Alignment.topLeft,
                                  end: Alignment.bottomRight,
                                ),
                                borderRadius: BorderRadius.circular(16),
                                boxShadow: [
                                  BoxShadow(
                                    color: Colors.black.withValues(alpha: 0.15),
                                    blurRadius: 8,
                                    offset: Offset(0, 4),
                                  ),
                                ],
                              ),
                              child: ElevatedButton(
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: Colors.transparent,
                                  shadowColor: Colors.transparent,
                                  foregroundColor: Colors.white,
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(16),
                                  ),
                                  elevation: 0,
                                ),
                                onPressed: _isLoading ? null : _submit,
                                child: _isLoading
                                    ? const CircularProgressIndicator(
                                        color: Colors.white,
                                      )
                                    : const Text(
                                        'Cari',
                                        style: TextStyle(
                                          fontSize: 18,
                                          fontWeight: FontWeight.bold,
                                          letterSpacing: 1.1,
                                        ),
                                      ),
                              ),
                            ),
                            // Card hasil pencarian
                            if (_hasSearched) ...[_buildResultCard()],
                          ],
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildTextField(
    TextEditingController controller,
    String label,
    IconData icon, {
    Widget? suffixIcon,
    bool required = true,
  }) {
    return TextFormField(
      controller: controller,
      decoration: InputDecoration(
        labelText: label,
        prefixIcon: Icon(icon, color: Color(0xFF485563)),
        suffixIcon: suffixIcon,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide.none, // Menghilangkan garis border
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide.none, // Menghilangkan garis saat tidak fokus
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide.none, // Menghilangkan garis saat fokus
        ),
        filled: true,
        fillColor: Colors.white,
      ),
      validator: required ? (v) => v == null || v.isEmpty ? 'Wajib diisi' : null : null,
    );
  }

  Widget _buildResultCard() {
    return Padding(
      padding: const EdgeInsets.only(top: 20),
      child: Container(
        width: double.infinity,
        decoration: BoxDecoration(
          color: Colors.white.withValues(alpha: 0.9),
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.1),
              blurRadius: 8,
              offset: Offset(0, 4),
            ),
          ],
        ),
        child: Padding(
          padding: const EdgeInsets.all(20),
          child: _searchResult != null && _searchResult!.success
              ? _buildFoundCard()
              : _buildNotFoundCard(),
        ),
      ),
    );
  }

  Widget _buildFoundCard() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            Icon(Icons.check_circle, color: Colors.green, size: 24),
            SizedBox(width: 8),
            Text(
              'Materi Kotbah Ditemukan',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: Colors.green[700],
              ),
            ),
          ],
        ),
        SizedBox(height: 16),
        _buildInfoRow(
          'Tanggal Kotbah',
          _searchResult!.tglKotbah ?? _tglKotbahController.text,
        ),
        SizedBox(height: 12),
        _buildInfoRow('Judul Kotbah', _searchResult!.judul ?? 'Tidak tersedia'),
        SizedBox(height: 12),
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Aksi:',
              style: TextStyle(
                fontWeight: FontWeight.w600,
                color: Color(0xFF485563),
                fontSize: 14,
              ),
            ),
            SizedBox(height: 8),
            // File availability indicator
            if (_isCheckingFile)
              Container(
                padding: EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                decoration: BoxDecoration(
                  color: Colors.orange[100],
                  borderRadius: BorderRadius.circular(6),
                ),
                child: Row(
                  children: [
                    SizedBox(
                      width: 16,
                      height: 16,
                      child: CircularProgressIndicator(
                        strokeWidth: 2,
                        color: Colors.orange,
                      ),
                    ),
                    SizedBox(width: 8),
                    Text(
                      'Memeriksa ketersediaan file...',
                      style: TextStyle(
                        color: Colors.orange[800],
                        fontSize: 12,
                      ),
                    ),
                  ],
                ),
              )
            else if (_fileExists != null)
              Container(
                padding: EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                decoration: BoxDecoration(
                  color: _fileExists! ? Colors.green[100] : Colors.red[100],
                  borderRadius: BorderRadius.circular(6),
                ),
                child: Row(
                  children: [
                    Icon(
                      _fileExists! ? Icons.check_circle : Icons.error,
                      color: _fileExists! ? Colors.green : Colors.red,
                      size: 16,
                    ),
                    SizedBox(width: 8),
                    Text(
                      _fileExists! ? 'File PDF tersedia' : 'File PDF tidak tersedia',
                      style: TextStyle(
                        color: _fileExists! ? Colors.green[800] : Colors.red[800],
                        fontSize: 12,
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                  ],
                ),
              ),
            SizedBox(height: 8),
            Row(
              children: [
                Expanded(
                  child: GestureDetector(
                    onTap: (_fileExists == false || _isCheckingFile) ? null : () => _launchURL(_searchResult!.link ?? ''),
                    child: Container(
                      padding: EdgeInsets.symmetric(horizontal: 10, vertical: 10),
                      //decoration: BoxDecoration(
                      //  color: (_fileExists == false || _isCheckingFile) ? Colors.grey : Color(0xFF485563),
                      //  borderRadius: BorderRadius.circular(8),
                      //),
                      //child: Row(
                      //  mainAxisAlignment: MainAxisAlignment.center,
                      //  children: [
                      //    Icon(Icons.open_in_new, color: Colors.white, size: 16),
                      //    SizedBox(width: 6),
                      //    Flexible(
                      //      child: Text(
                      //        'Buka PDF',
                      //        overflow: TextOverflow.ellipsis,
                      //        maxLines: 1,
                      //        textAlign: TextAlign.center,
                      //        style: TextStyle(
                      //          color: Colors.white,
                      //          fontWeight: FontWeight.w500,
                      //          fontSize: 14,
                      //        ),
                      //      ),
                      //    ),
                      //  ],
                      //),
                    ),
                  ),
                ),
                SizedBox(width: 12),
                Expanded(
                  child: GestureDetector(
                    onTap: (_isDownloading || _fileExists == false || _isCheckingFile) ? null : () => _downloadPDF(_searchResult!.link ?? ''),
                    child: Container(
                      padding: EdgeInsets.symmetric(horizontal: 10, vertical: 10),
                      decoration: BoxDecoration(
                        color: (_isDownloading || _fileExists == false || _isCheckingFile) ? Colors.grey : Color(0xFF2E7D32),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          if (_isDownloading)
                            SizedBox(
                              width: 16,
                              height: 16,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                color: Colors.white,
                              ),
                            )
                          else
                            Icon(Icons.download, color: Colors.white, size: 16),
                          SizedBox(width: 6),
                          Flexible(
                            child: Text(
                              _isDownloading ? 'Downloading...' : 'Download',
                              overflow: TextOverflow.ellipsis,
                              maxLines: 1,
                              textAlign: TextAlign.center,
                              style: TextStyle(
                                color: Colors.white,
                                fontWeight: FontWeight.w500,
                                fontSize: 14,
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildNotFoundCard() {
    return Column(
      children: [
        Row(
          children: [
            Icon(Icons.info_outline, color: Colors.orange, size: 24),
            SizedBox(width: 8),
            Text(
              'Materi Tidak Ditemukan',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: Colors.orange[700],
              ),
            ),
          ],
        ),
        SizedBox(height: 16),
        Text(
          'Materi Kotbah pada Tanggal ${_tglKotbahController.text} belum tersedia',
          style: TextStyle(fontSize: 16, color: Color(0xFF485563), height: 1.4),
          textAlign: TextAlign.center,
        ),
      ],
    );
  }

  Widget _buildInfoRow(String label, String value) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SizedBox(
          width: 120,
          child: Text(
            '$label:',
            style: TextStyle(
              fontWeight: FontWeight.w600,
              color: Color(0xFF485563),
              fontSize: 14,
            ),
          ),
        ),
        SizedBox(width: 8),
        Expanded(
          child: Text(
            value,
            style: TextStyle(
              color: Color(0xFF485563),
              fontSize: 14,
              height: 1.3,
            ),
          ),
        ),
      ],
    );
  }

  // Use shared URL helper from `lib/helpers/url_helper.dart`

  Future<void> _checkFileAvailability(String url) async {
    setState(() {
      _isCheckingFile = true;
    });

    try {
      final response = await headWithConversion(
        url,
        timeout: const Duration(seconds: 10),
      );

      setState(() {
        _fileExists = response.statusCode == 200;
        _isCheckingFile = false;
      });
    } catch (e) {
      setState(() {
        _fileExists = false;
        _isCheckingFile = false;
      });
    }
  }

  void _launchURL(String url) async {
    if (url.isNotEmpty) {
      // Check file availability first if not already checked
      if (_fileExists == null) {
        await _checkFileAvailability(url);
      }
      
      if (_fileExists == false) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('File PDF tidak tersedia di server'),
              backgroundColor: Colors.red,
            ),
          );
        }
        return;
      }
      
      try {
        // Use URL directly (already converted via buildApiUrl if needed)
        final convertedUrl = url.startsWith('http') ? url : buildApiUrl(url);
        if (mounted) {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => PDFViewerPage(
                url: convertedUrl,
                title: _searchResult?.judul ?? 'Materi Kotbah',
              ),
            ),
          );
        }
      } catch (e) {
        if (mounted) {
          ScaffoldMessenger.of(
            context,
          ).showSnackBar(SnackBar(content: Text('Tidak dapat membuka PDF: ${e.toString()}')));
        }
      }
    }
  }

  Future<void> _downloadPDF(String url) async {
    if (url.isEmpty) return;

    // Check file availability first if not already checked
    if (_fileExists == null) {
      await _checkFileAvailability(url);
    }
    
    if (_fileExists == false) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('File PDF tidak tersedia di server'),
            backgroundColor: Colors.red,
          ),
        );
      }
      return;
    }

    setState(() {
      _isDownloading = true;
    });

    try {
      final convertedUrl = url.startsWith('http') ? url : buildApiUrl(url);
      
      // Try to save to public Downloads directory on Android first
      Directory? externalDownloads;
      if (Platform.isAndroid) {
        try {
          final dirs = await getExternalStorageDirectories(type: StorageDirectory.downloads);
          if (dirs != null && dirs.isNotEmpty) externalDownloads = dirs.first;
        } catch (e) {
          externalDownloads = null;
        }
      }

      // Fallback to app documents directory when public Downloads isn't available
      final Directory downloadsDir = externalDownloads ?? await getApplicationDocumentsDirectory();

      // Ensure directory exists
      if (!await downloadsDir.exists()) {
        await downloadsDir.create(recursive: true);
      }

      // Create filename with date and title
      //final dateStr = DateFormat('yyyy-MM-dd').format(DateTime.now());
      // Sanitize title without using the deprecated RegExp API.
      String sanitizeTitle(String input) {
        if (input.isEmpty) return '';
        final sb = StringBuffer();
        for (final cp in input.runes) {
          // allow 0-9, A-Z, a-z, underscore(_), hyphen(-) and space
          if (cp == 32 || cp == 45 || cp == 95 || (cp >= 48 && cp <= 57) || (cp >= 65 && cp <= 90) || (cp >= 97 && cp <= 122)) {
            sb.writeCharCode(cp);
          }
        }
        return sb.toString();
      }

      final rawTitle = _searchResult?.judul ?? 'MateriKotbah';
      var titleSafe = sanitizeTitle(rawTitle);
      if (titleSafe.isEmpty) titleSafe = 'MateriKotbah';
      titleSafe = titleSafe.replaceAll(' ', '_');

      // Limit title length
      final shortTitle = titleSafe.length > 60 ? titleSafe.substring(0, 60) : titleSafe;
      final fileName = '${shortTitle}_.pdf';
      final file = File('${downloadsDir.path}/$fileName');

      // Download with streaming and retry mechanism
      bool downloadSuccess = false;
      int maxRetries = 3;
      
      for (int attempt = 1; attempt <= maxRetries; attempt++) {
        try {
          final client = HttpClient();
          client.connectionTimeout = Duration(seconds: 30);
          client.idleTimeout = Duration(seconds: 120);
          
          final request = await client.getUrl(Uri.parse(convertedUrl));
          request.headers.set('User-Agent', 'Flutter App');
          request.headers.set('Accept', 'application/pdf');
          request.headers.set('Connection', 'keep-alive');
          
          final response = await request.close();
          
          if (response.statusCode == 200) {
            final sink = file.openWrite();
            
            try {
              await for (var data in response) {
                sink.add(data);
              }
              await sink.flush();
              downloadSuccess = true;
            } finally {
              await sink.close();
            }
          } else {
            throw HttpException('HTTP ${response.statusCode}');
          }
          
          client.close();
          
          if (downloadSuccess) break;
          
        } catch (e) {
          if (attempt == maxRetries) {
            rethrow;
          }
          // Wait before retry
          await Future.delayed(Duration(seconds: 2 * attempt));
        }
      }
      
      if (downloadSuccess) {
        // On Android, request storage permission and try to save to public Downloads via platform channel
        if (Platform.isAndroid) {
          // Request storage permission (for Android < 11 this allows writing to external storage)
          final status = await Permission.storage.request();
          if (!status.isGranted) {
            if (mounted) {
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: Text('Izin penyimpanan ditolak. Tidak dapat menyimpan file.'),
                  backgroundColor: Colors.red,
                ),
              );
            }
          } else {
            try {
              final platform = MethodChannel('plc_mobile/download');
              final bytes = await file.readAsBytes();
              final uri = await platform.invokeMethod<String>('saveFile', {
                'fileName': fileName,
                'bytes': bytes,
              });

              if (mounted) {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(
                    content: Column(
                      mainAxisSize: MainAxisSize.min,
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('PDF berhasil diunduh!', style: TextStyle(fontWeight: FontWeight.bold)),
                        SizedBox(height: 4),
                        Text('File: $fileName', style: TextStyle(fontSize: 12)),
                        if (uri != null) Text('Lokasi (URI): $uri', style: TextStyle(fontSize: 11, color: Colors.white70)),
                      ],
                    ),
                    backgroundColor: Colors.green,
                    duration: Duration(seconds: 6),
                    action: SnackBarAction(label: 'OK', textColor: Colors.white, onPressed: () {}),
                  ),
                );
              }
            } catch (e) {
              // If platform save fails, show the exception message and fallback path so user can find it
              final err = e.toString();
              if (mounted) {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(
                    content: Column(
                      mainAxisSize: MainAxisSize.min,
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('File disimpan di: ${file.path} (gagal menyimpan via MediaStore)', style: TextStyle(fontWeight: FontWeight.w600)),
                        SizedBox(height: 4),
                        Text('Detail error: $err', style: TextStyle(fontSize: 12)),
                      ],
                    ),
                    backgroundColor: Colors.orange,
                    duration: Duration(seconds: 8),
                  ),
                );
              }
            }
          }
        } else {
          // Non-Android: show app path
          if (mounted) {
            final savedPath = file.path;
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Column(
                  mainAxisSize: MainAxisSize.min,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text('PDF berhasil diunduh!', style: TextStyle(fontWeight: FontWeight.bold)),
                    SizedBox(height: 4),
                    Text('File: $fileName', style: TextStyle(fontSize: 12)),
                    Text('Path: $savedPath', style: TextStyle(fontSize: 11, color: Colors.white70)),
                  ],
                ),
                backgroundColor: Colors.green,
                duration: Duration(seconds: 6),
                action: SnackBarAction(label: 'OK', textColor: Colors.white, onPressed: () {}),
              ),
            );
          }
        }
      } else {
        throw Exception('Gagal mengunduh file setelah $maxRetries percobaan');
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Gagal mengunduh PDF: ${e.toString()}'),
            backgroundColor: Colors.red,
            duration: Duration(seconds: 4),
          ),
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isDownloading = false;
        });
      }
    }
  }
}
