import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'dart:ui';
import 'package:plc_mobile/services/ibadah_raya_service.dart';
import 'package:plc_mobile/models/ibadah_raya.dart';
import 'package:plc_mobile/pages/youtube_player_page.dart';

class IbadahRayaPage extends StatefulWidget {
  const IbadahRayaPage({super.key});

  @override
  State<IbadahRayaPage> createState() => _IbadahRayaPageState();
}

class _IbadahRayaPageState extends State<IbadahRayaPage> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _tglIbadahController = TextEditingController();
  bool _isLoading = false;
  IbadahRayaResponse? _searchResult;
  bool _hasSearched = false;

  // Declare the _ibadahkeController variable to store the selected value from the dropdown
  String? _ibadahkeController;

  Future<void> _pickDate() async {
    DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(1900),
      lastDate: DateTime.now(),
    );
    if (picked != null) {
      _tglIbadahController.text = DateFormat('yyyy-MM-dd').format(picked);
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
      final result = await getIbadahRayaByData(
        _tglIbadahController.text,
        _ibadahkeController ?? '',
      );

      if (mounted) {
        setState(() {
          _hasSearched = true;
          _searchResult = result;
        });

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
          _searchResult = IbadahRayaResponse.error('Error: $e');
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
                              'assets/tema/ibadah_raya.jpg',
                              height: 400,
                              fit: BoxFit.fill,
                            ),
                            const SizedBox(height: 10),
                            Text(
                              'Ibadah Raya',
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
                                  _tglIbadahController,
                                  'Tanggal Ibadah',
                                  Icons.date_range,
                                  suffixIcon: Icon(
                                    Icons.calendar_today,
                                    color: Colors.black,
                                  ),
                                  required: true,
                                ),
                              ),
                            ),

                            const SizedBox(height: 14),
                            DropdownButtonFormField<String>(
                              initialValue: _ibadahkeController,
                              items: ['Ibadah 1', 'Ibadah 2']
                                  .map((g) => DropdownMenuItem(value: g, child: Text(g)))
                                  .toList(),
                              onChanged: (v) => setState(() => _ibadahkeController = v),
                              decoration: InputDecoration(
                                labelText: 'Ibadah Ke ',
                                prefixIcon: Icon(Icons.bloodtype, color: Colors.red[700]),
                                border: OutlineInputBorder(
                                  borderRadius: BorderRadius.circular(12),
                                ),
                                filled: true,
                                fillColor: Colors.grey[100],
                              ),
                              validator: (v) => v == null || v.isEmpty ? 'Wajib dipilih' : null,
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
              'Ibadah Raya Ditemukan',
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
          'Tanggal Ibadah',
          _searchResult!.tglibadah ?? _tglIbadahController.text,
        ),
        SizedBox(height: 12),
        _buildInfoRow('Ibadah', _searchResult!.ibadahke ?? 'Tidak tersedia'),
        SizedBox(height: 12),
        _buildInfoRow('Link Ibadah', _searchResult?.linkibadah ?? 'Tidak tersedia'),
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
            const SizedBox(height: 8),
            ElevatedButton(
              onPressed: () {
                if (_searchResult?.linkibadah != null && _searchResult!.linkibadah!.isNotEmpty) {
                  // Log the URL to the console
                  //print('Launching URL: ${_searchResult!.linkibadah!}');

                  // Use URL helper to launch the link
                  _launchURL(_searchResult!.linkibadah!);
                } else {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(
                      content: Text('Link tidak tersedia'),
                      backgroundColor: Colors.red,
                    ),
                  );
                }
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.blue,
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
              child: Text('View'),
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
              'Ibadah Tidak Ditemukan',
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
          'Ibadah Raya pada Tanggal ${_tglIbadahController.text} belum tersedia',
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
  void _launchURL(String url) async {
    if (url.isNotEmpty) {
      // Check if the URL is a YouTube link
      if (url.contains('youtube.com') || url.contains('youtu.be')) {
        final videoId = _extractYouTubeVideoId(url);
        if (videoId != null && mounted) {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => YouTubePlayerPage(videoId: videoId),
            ),
          );
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('Invalid YouTube link'),
              backgroundColor: Colors.red,
            ),
          );
        }
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Unsupported link format'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  String? _extractYouTubeVideoId(String url) {
    final uri = Uri.tryParse(url);
    if (uri == null) return null;

    if (uri.host.contains('youtube.com')) {
      // Handle YouTube Live links (e.g., youtube.com/live/VIDEO_ID)
      if (uri.path.contains('/live/')) {
        final segments = uri.pathSegments;
        final liveIndex = segments.indexOf('live');
        if (liveIndex >= 0 && liveIndex + 1 < segments.length) {
          return segments[liveIndex + 1];
        }
      }
      // Handle standard YouTube links (e.g., youtube.com/watch?v=VIDEO_ID)
      return uri.queryParameters['v'];
    } else if (uri.host.contains('youtu.be')) {
      // Handle shortened YouTube links
      return uri.pathSegments.isNotEmpty ? uri.pathSegments.first : null;
    } else if (uri.host.contains('youtube') && uri.path.contains('embed')) {
      // Handle embedded YouTube links
      return uri.pathSegments.isNotEmpty ? uri.pathSegments.last : null;
    }

    return null; // Not a valid YouTube link
  }

  
}
