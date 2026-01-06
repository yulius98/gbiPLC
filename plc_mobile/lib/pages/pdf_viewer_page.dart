import 'package:flutter/material.dart';
import 'package:flutter_pdfview/flutter_pdfview.dart';
import 'dart:io';
import 'package:path_provider/path_provider.dart';
import 'package:open_file/open_file.dart';

class PDFViewerPage extends StatefulWidget {
  final String url;
  final String title;

  const PDFViewerPage({
    super.key,
    required this.url,
    required this.title,
  });

  @override
  State<PDFViewerPage> createState() => _PDFViewerPageState();
}

class _PDFViewerPageState extends State<PDFViewerPage> {
  String? localPath;
  bool isLoading = true;
  String? errorMessage;
  int? pages = 0;
  int currentPage = 0;
  bool isReady = false;
  double downloadProgress = 0.0;
  String downloadStatus = 'Mengunduh PDF...';

  @override
  void initState() {
    super.initState();
    _downloadAndSavePDF();
  }

  Future<void> _downloadAndSavePDF({int retryCount = 0}) async {
    const int maxRetries = 3;
    
    try {
      setState(() {
        isLoading = true;
        errorMessage = null;
        downloadProgress = 0.0;
        downloadStatus = retryCount > 0 ? 'Mencoba lagi... (${retryCount + 1}/${maxRetries + 1})' : 'Menghubungkan...';
      });

      // Try chunked download for better handling of large files
      await _downloadPDFChunked();
      
    } catch (e) {
      if (retryCount < maxRetries) {
        // Retry download with exponential backoff
        await Future.delayed(Duration(seconds: 2 << retryCount));
        await _downloadAndSavePDF(retryCount: retryCount + 1);
      } else {
        setState(() {
          if (e.toString().contains('TimeoutException')) {
            errorMessage = 'Koneksi timeout. File PDF terlalu besar atau koneksi internet lambat.';
          } else if (e.toString().contains('Connection closed') || e.toString().contains('Connection close')) {
            errorMessage = 'Koneksi terputus saat mengunduh. Coba gunakan koneksi WiFi yang stabil.';
          } else if (e.toString().contains('SocketException')) {
            errorMessage = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
          } else {
            errorMessage = 'Gagal mengunduh PDF: ${e.toString().replaceAll('Exception: ', '')}';
          }
          isLoading = false;
        });
      }
    }
  }

  Future<void> _downloadPDFChunked() async {
    final client = HttpClient();
    client.connectionTimeout = Duration(seconds: 30);
    
    try {
      setState(() {
        downloadStatus = 'Mengunduh PDF...';
      });

      final request = await client.getUrl(Uri.parse(widget.url));
      final response = await request.close();
      
      if (response.statusCode == 200) {
        final contentLength = response.contentLength;
        final dir = await getTemporaryDirectory();
        final file = File('${dir.path}/materi_kotbah_${DateTime.now().millisecondsSinceEpoch}.pdf');
        
        final sink = file.openWrite();
        int downloaded = 0;
        
        await response.listen(
          (List<int> chunk) {
            sink.add(chunk);
            downloaded += chunk.length;
            
            if (contentLength > 0) {
              final progress = downloaded / contentLength;
              setState(() {
                downloadProgress = progress;
                downloadStatus = 'Mengunduh... ${(progress * 100).toStringAsFixed(1)}%';
              });
            }
          },
          onDone: () async {
            await sink.close();
            // Verify file exists and has non-zero length
            try {
              final fileExists = await file.exists();
              final fileSize = fileExists ? await file.length() : 0;
              if (!fileExists || fileSize == 0) {
                throw Exception('File download kosong atau tidak ditemukan (size=$fileSize)');
              }

              setState(() {
                localPath = file.path;
                isLoading = false;
                downloadProgress = 1.0;
                downloadStatus = 'Selesai (size: $fileSize bytes)';
              });
                // If the embedded PDF view doesn't render within a short time,
                // offer to open with an external app as a fallback.
                Future.delayed(Duration(seconds: 2), () {
                  if (!mounted) return;
                  if (!isReady && localPath != null) {
                    // Try opening externally so user can still view the PDF
                    OpenFile.open(localPath!);
                  }
                });
            } catch (verifyError) {
              try {
                await file.delete();
              } catch (_) {}
              rethrow;
            }
          },
          onError: (error) {
            sink.close();
            throw error;
          },
          cancelOnError: true,
        ).asFuture();
      } else {
        throw Exception('HTTP ${response.statusCode}: Server error');
      }
    } finally {
      client.close();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(
          widget.title,
          style: TextStyle(fontSize: 16),
          overflow: TextOverflow.ellipsis,
        ),
        backgroundColor: Color(0xFF485563),
        foregroundColor: Colors.white,
        actions: [
          if (isReady && pages != null)
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16.0),
              child: Center(
                child: Text(
                  '${currentPage + 1} / $pages',
                  style: TextStyle(fontSize: 14),
                ),
              ),
            ),
          if (localPath != null)
            IconButton(
              tooltip: 'Buka di aplikasi lain',
              icon: Icon(Icons.open_in_new),
              onPressed: () => OpenFile.open(localPath!),
            ),
        ],
      ),
      body: _buildBody(),
    );
  }

  Widget _buildBody() {
    if (isLoading) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            SizedBox(
              width: 80,
              height: 80,
              child: Stack(
                alignment: Alignment.center,
                children: [
                  SizedBox(
                    width: 80,
                    height: 80,
                    child: CircularProgressIndicator(
                      value: downloadProgress > 0 ? downloadProgress : null,
                      backgroundColor: Colors.grey[300],
                      color: Color(0xFF485563),
                      strokeWidth: 6,
                    ),
                  ),
                  if (downloadProgress > 0)
                    Text(
                      '${(downloadProgress * 100).toInt()}%',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        color: Color(0xFF485563),
                      ),
                    ),
                ],
              ),
            ),
            SizedBox(height: 24),
            Text(
              downloadStatus,
              style: TextStyle(
                fontSize: 16,
                color: Color(0xFF485563),
              ),
            ),
            if (downloadProgress > 0)
              Padding(
                padding: EdgeInsets.symmetric(horizontal: 40, vertical: 8),
                child: LinearProgressIndicator(
                  value: downloadProgress,
                  backgroundColor: Colors.grey[300],
                  color: Color(0xFF485563),
                ),
              ),
          ],
        ),
      );
    }

    if (errorMessage != null) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              Icons.error_outline,
              size: 64,
              color: Colors.red,
            ),
            SizedBox(height: 16),
            Text(
              'Gagal Memuat PDF',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: Colors.red,
              ),
            ),
            SizedBox(height: 8),
            Padding(
              padding: EdgeInsets.symmetric(horizontal: 32),
              child: Text(
                errorMessage!,
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 14),
              ),
            ),
            SizedBox(height: 16),
            ElevatedButton(
              onPressed: () => _downloadAndSavePDF(retryCount: 0),
              style: ElevatedButton.styleFrom(
                backgroundColor: Color(0xFF485563),
                foregroundColor: Colors.white,
              ),
              child: Text('Coba Lagi'),
            ),
          ],
        ),
      );
    }

    if (localPath != null) {
      return PDFView(
        filePath: localPath!,
        enableSwipe: true,
        swipeHorizontal: false,
        autoSpacing: false,
        pageFling: true,
        pageSnap: true,
        onRender: (pages) {
          setState(() {
            this.pages = pages;
            isReady = true;
          });
        },
        onError: (error) {
          setState(() {
            errorMessage = 'Error membuka PDF: $error';
          });
        },
        onPageError: (page, error) {
          setState(() {
            errorMessage = 'Error pada halaman $page: $error';
          });
        },
        onViewCreated: (PDFViewController controller) {
          // PDF view telah dibuat
        },
        onPageChanged: (page, total) {
          setState(() {
            currentPage = page ?? 0;
          });
        },
      );
    }

    return Center(
      child: Text('Tidak dapat memuat PDF'),
    );
  }
}