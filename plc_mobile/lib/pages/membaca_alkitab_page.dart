import 'package:flutter/material.dart';
import 'dart:ui';
import 'package:plc_mobile/services/mulai_membaca_alkitab_service.dart';
import 'package:plc_mobile/models/mulai_membaca_alkitab.dart';
import 'package:plc_mobile/services/reading_today_service.dart';
import 'package:plc_mobile/models/reading_today.dart';
import 'package:just_audio/just_audio.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:plc_mobile/helpers/url_helper.dart';

class MembacaAlkitabPage extends StatefulWidget {
  const MembacaAlkitabPage({super.key});

  @override
  State<MembacaAlkitabPage> createState() => _MembacaAlkitabPageState();
}

class _MembacaAlkitabPageState extends State<MembacaAlkitabPage> {
  bool _isLoading = true;
  MembacaAlkitabResponse? _data;
  String? _errorMessage;
  String? _token;
  String? _tanggal;
  ReadingTodayResponse? _readingData;
  String? _selectedReading; // 'pagi' or 'sore'
  bool _isLoadingReading = false;

  // Audio player
  final AudioPlayer _audioPlayer = AudioPlayer();
  List<String> _currentPlaylist = [];
  int _currentAudioIndex = 0;
  bool _isPlaying = false;
  bool _isLoadingAudio = false;
  Duration _currentPosition = Duration.zero;
  Duration _totalDuration = Duration.zero;

  @override
  void initState() {
    super.initState();
    _initAudioPlayer();
  }

  void _initAudioPlayer() {
    // Set volume default
    _audioPlayer.setVolume(1.0);

    // Listen to player state changes
    _audioPlayer.playerStateStream.listen((state) {
      if (mounted) {
        setState(() {
          _isPlaying = state.playing;
          _isLoadingAudio =
              state.processingState == ProcessingState.loading ||
              state.processingState == ProcessingState.buffering;
        });
      }
    });

    // Listen to position changes
    _audioPlayer.positionStream.listen((position) {
      if (mounted) {
        setState(() {
          _currentPosition = position;
        });
      }
    });

    // Listen to duration changes
    _audioPlayer.durationStream.listen((duration) {
      if (mounted && duration != null) {
        setState(() {
          _totalDuration = duration;
        });
      }
    });

    // Listen to player completion
    _audioPlayer.processingStateStream.listen((state) async {
      if (state == ProcessingState.completed) {
        if (mounted) {
          // Auto play next audio in playlist
          if (_currentAudioIndex < _currentPlaylist.length - 1) {
            _currentAudioIndex++;
            await _playNextInPlaylist();
          } else {
            setState(() {
              _currentPlaylist = [];
              _currentAudioIndex = 0;
              _isPlaying = false;
              _isLoadingAudio = false;
            });
          }
        }
      }
    });
  }

  @override
  void dispose() {
    _audioPlayer.dispose();
    super.dispose();
  }

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();

    // Get arguments passed from mypage
    final args =
        ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>?;

    if (args != null) {
      _token = args['token'] as String?;
      _tanggal = args['tanggal'] as String?;

      // Load data only once
      if (_isLoading && _token != null && _tanggal != null) {
        _loadData();
      }
    } else {
      setState(() {
        _isLoading = false;
        _errorMessage = 'Token atau tanggal tidak ditemukan';
      });
    }
  }

  Future<void> _loadData() async {
    try {
      final result = await fetchMulaiMembacaAlkitab(
        token: _token!,
        tanggal: _tanggal!,
      );

      if (mounted) {
        setState(() {
          _data = result;
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _errorMessage = e.toString();
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
        child: _isLoading
            ? Center(child: CircularProgressIndicator(color: Colors.white))
            : _errorMessage != null
            ? Center(
                child: Padding(
                  padding: const EdgeInsets.all(24.0),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.error_outline, color: Colors.red, size: 64),
                      SizedBox(height: 16),
                      Text(
                        'Terjadi Kesalahan',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      SizedBox(height: 8),
                      Text(
                        _errorMessage!,
                        style: TextStyle(color: Colors.white70, fontSize: 14),
                        textAlign: TextAlign.center,
                      ),
                    ],
                  ),
                ),
              )
            : Center(
                child: SingleChildScrollView(
                  child: Padding(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 18,
                      vertical: 32,
                    ),
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
                          // Content
                          Padding(
                            padding: const EdgeInsets.all(24),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.stretch,
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                // Image
                                ClipRRect(
                                  borderRadius: BorderRadius.circular(16),
                                  child: Image.asset(
                                    'assets/tema/membaca_alkitab.jpg',
                                    height: 400,
                                    fit: BoxFit.cover,
                                  ),
                                ),
                                const SizedBox(height: 20),
                                // Title
                                Text(
                                  'Gemar Membaca Alkitab',
                                  style: TextStyle(
                                    fontSize: 22,
                                    fontWeight: FontWeight.bold,
                                    color: Color.fromARGB(255, 237, 239, 240),
                                    letterSpacing: 1.2,
                                  ),
                                  textAlign: TextAlign.center,
                                ),
                                const SizedBox(height: 24),
                                // Start Date Section
                                Container(
                                  padding: EdgeInsets.all(16),
                                  decoration: BoxDecoration(
                                    color: Colors.white.withValues(alpha: 0.9),
                                    borderRadius: BorderRadius.circular(12),
                                    boxShadow: [
                                      BoxShadow(
                                        color: Colors.black.withValues(
                                          alpha: 0.1,
                                        ),
                                        blurRadius: 8,
                                        offset: Offset(0, 4),
                                      ),
                                    ],
                                  ),
                                  child: Column(
                                    children: [
                                      Row(
                                        children: [
                                          Icon(
                                            Icons.calendar_today,
                                            color: Color(0xFF485563),
                                            size: 20,
                                          ),
                                          SizedBox(width: 8),
                                          Text(
                                            'Tanggal Mulai Membaca Alkitab',
                                            style: TextStyle(
                                              fontSize: 14,
                                              fontWeight: FontWeight.w600,
                                              color: Color(0xFF485563),
                                            ),
                                          ),
                                        ],
                                      ),
                                      SizedBox(height: 8),
                                      Text(
                                        _data?.tanggalMulai ?? '-',
                                        style: TextStyle(
                                          fontSize: 18,
                                          fontWeight: FontWeight.bold,
                                          color: Color(0xFF232526),
                                        ),
                                      ),
                                      if (_data?.message != null) ...[
                                        SizedBox(height: 8),
                                        Text(
                                          _data!.message,
                                          style: TextStyle(
                                            fontSize: 12,
                                            color: Color(0xFF485563),
                                            fontStyle: FontStyle.italic,
                                          ),
                                          textAlign: TextAlign.center,
                                        ),
                                      ],
                                    ],
                                  ),
                                ),
                                const SizedBox(height: 24),
                                // Button: Membaca Alkitab Pagi Hari
                                _buildActionButton(
                                  context,
                                  'Membaca Alkitab Pagi Hari',
                                  Icons.wb_sunny,
                                  const LinearGradient(
                                    colors: [
                                      Color(0xFFf7971e),
                                      Color(0xFFffd200),
                                    ],
                                  ),
                                  () => _handleMembacaAlkitab('pagi'),
                                ),
                                const SizedBox(height: 16),
                                // Button: Membaca Alkitab Sore Hari
                                _buildActionButton(
                                  context,
                                  'Membaca Alkitab Sore Hari',
                                  Icons.nights_stay,
                                  const LinearGradient(
                                    colors: [
                                      Color(0xFF232526),
                                      Color(0xFF485563),
                                    ],
                                  ),
                                  () => _handleMembacaAlkitab('sore'),
                                ),
                                // Display reading content
                                if (_isLoadingReading) ...[
                                  const SizedBox(height: 24),
                                  Center(
                                    child: CircularProgressIndicator(
                                      color: Color(0xFF485563),
                                    ),
                                  ),
                                ] else if (_readingData != null &&
                                    _selectedReading != null) ...[
                                  const SizedBox(height: 24),
                                  _buildReadingSection(),
                                ],
                              ],
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

  Widget _buildActionButton(
    BuildContext context,
    String text,
    IconData icon,
    Gradient gradient,
    VoidCallback onPressed,
  ) {
    return Container(
      width: double.infinity,
      height: 56,
      decoration: BoxDecoration(
        gradient: gradient,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.2),
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
        onPressed: onPressed,
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 24),
            SizedBox(width: 12),
            Text(
              text,
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
                letterSpacing: 0.5,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Future<void> _handleMembacaAlkitab(String waktu) async {
    if (_token == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Token tidak tersedia'),
          backgroundColor: Colors.red,
        ),
      );
      return;
    }

    setState(() {
      _isLoadingReading = true;
      _selectedReading = waktu;
    });

    try {
      final result = await fetchReadingToday(token: _token!);

      if (mounted) {
        setState(() {
          _readingData = result;
          _isLoadingReading = false;
        });

        // Cek apakah currentDay melebihi totalDays
        if (_readingData != null &&
            _readingData!.progress.currentDay >
                _readingData!.progress.totalDays) {
          // Check mounted before showing dialog
          if (!mounted) return;
          
          // Tampilkan dialog konfirmasi
          final shouldReset = await showDialog<bool>(
            context: context,
            barrierDismissible: false,
            builder: (BuildContext context) {
              return AlertDialog(
                title: Row(
                  children: [
                    Icon(Icons.info_outline, color: Color(0xFF6a3093)),
                    SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        'Periode Selesai',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ],
                ),
                content: Text(
                  'Anda telah menyelesaikan periode membaca alkitab. Apakah Anda ingin memulai periode baru dari hari ini?',
                  style: TextStyle(fontSize: 14),
                ),
                actions: [
                  TextButton(
                    onPressed: () => Navigator.of(context).pop(false),
                    child: Text('Batal', style: TextStyle(color: Colors.grey)),
                  ),
                  ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Color(0xFF6a3093),
                      foregroundColor: Colors.white,
                    ),
                    onPressed: () => Navigator.of(context).pop(true),
                    child: Text('Mulai Periode Baru'),
                  ),
                ],
              );
            },
          );

          // Jika user setuju, reset start_date
          if (shouldReset == true) {
            await _resetStartDate();
          }
        }
      }
    } catch (e) {
      // Cek apakah error karena data tidak ditemukan (periode sudah selesai)
      if (e.toString().contains('tidak ditemukan') || 
          e.toString().contains('404')) {
        if (mounted) {
          setState(() {
            _isLoadingReading = false;
          });
          
          if (!mounted) return;
          
          // Tampilkan dialog untuk reset periode
          final shouldReset = await showDialog<bool>(
            context: context,
            barrierDismissible: false,
            builder: (BuildContext context) {
              return AlertDialog(
                title: Row(
                  children: [
                    Icon(Icons.info_outline, color: Color(0xFF6a3093)),
                    SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        'Periode Selesai',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ],
                ),
                content: Text(
                  'Anda telah menyelesaikan periode membaca alkitab (298 hari). Apakah Anda ingin memulai periode baru dari hari ini?',
                  style: TextStyle(fontSize: 14),
                ),
                actions: [
                  TextButton(
                    onPressed: () => Navigator.of(context).pop(false),
                    child: Text('Batal', style: TextStyle(color: Colors.grey)),
                  ),
                  ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Color(0xFF6a3093),
                      foregroundColor: Colors.white,
                    ),
                    onPressed: () => Navigator.of(context).pop(true),
                    child: Text('Mulai Periode Baru'),
                  ),
                ],
              );
            },
          );

          // Jika user setuju, reset start_date
          if (shouldReset == true) {
            await _resetStartDate();
          }
        }
      } else {
        // Error lainnya, tampilkan error message
        if (mounted) {
          setState(() {
            _isLoadingReading = false;
          });
          if (!mounted) return;
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('Gagal memuat bacaan: ${e.toString()}'),
              backgroundColor: Colors.red,
            ),
          );
        }
      }
    }
  }

  Future<void> _resetStartDate() async {
    if (_token == null) return;

    try {
      setState(() {
        _isLoadingReading = true;
      });

      // Gunakan tanggal hari ini
      final today = DateTime.now();
      final formattedDate =
          '${today.year}-${today.month.toString().padLeft(2, '0')}-${today.day.toString().padLeft(2, '0')}';

      debugPrint('Attempting to reset start_date to: $formattedDate');

      // Panggil service untuk update start_date
      final response = await fetchMulaiMembacaAlkitab(
        token: _token!,
        tanggal: formattedDate,
      );

      if (mounted) {
        debugPrint('Received response: tanggal_mulai=${response?.tanggalMulai}');
        
        // Verifikasi apakah tanggal benar-benar berubah
        if (response != null && response.tanggalMulai != null) {
          // Cek apakah API benar-benar mengupdate tanggal
          if (response.tanggalMulai != formattedDate) {
            debugPrint('WARNING: API tidak mengupdate tanggal!');
            debugPrint('Expected: $formattedDate, Got: ${response.tanggalMulai}');
            throw Exception(
              'API tidak dapat memperbarui tanggal mulai membaca. '
              'Tanggal tetap: ${response.tanggalMulai}. '
              'Silakan hubungi administrator sistem.'
            );
          }
          
          setState(() {
            _data = response;
            _tanggal = formattedDate;
          });

          debugPrint('Start date updated in local state to: $formattedDate');
          debugPrint('Response shows tanggal_mulai: ${response.tanggalMulai}');

          // Reload reading data setelah reset
          try {
            final result = await fetchReadingToday(token: _token!);

            setState(() {
              _readingData = result;
              _isLoadingReading = false;
            });

            if (!mounted) return;
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text('Periode membaca alkitab berhasil diperbarui ke ${response.tanggalMulai}'),
                backgroundColor: Colors.green,
                duration: Duration(seconds: 3),
              ),
            );
          } catch (readingError) {
            // Jika gagal load reading data setelah reset, tidak masalah
            // Periode sudah berhasil direset, user bisa coba lagi
            setState(() {
              _isLoadingReading = false;
              _selectedReading = null;
              _readingData = null;
            });

            if (!mounted) return;
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text(
                  'Periode berhasil diperbarui ke ${response.tanggalMulai}. Silakan pilih waktu bacaan (Pagi/Sore) lagi.',
                ),
                backgroundColor: Colors.green,
                duration: Duration(seconds: 4),
              ),
            );
          }
        } else {
          // Response tidak valid atau tanggal tidak berubah
          throw Exception('API tidak mengembalikan tanggal mulai yang valid');
        }
      }
    } catch (e) {
      debugPrint('Error in _resetStartDate: $e');
      if (mounted) {
        setState(() {
          _isLoadingReading = false;
        });
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Gagal memperbarui periode: ${e.toString()}'),
            backgroundColor: Colors.red,
            duration: Duration(seconds: 3),
          ),
        );
      }
    }
  }

  Future<bool> _requestAudioPermissions() async {
    try {
      // Untuk audio playback, kita tidak perlu microphone permission
      // Cukup check apakah device mendukung audio playback

      // Untuk Android 13+, kita mungkin perlu notification permission untuk media notification
      if (await Permission.notification.isDenied) {
        await Permission.notification.request();
      }

      // Selalu return true untuk audio playback karena tidak memerlukan permission khusus
      return true;
    } catch (e) {
      // Even if permission check fails, allow audio to play
      return true;
    }
  }

  Future<void> _playAudio(List<String> audioUrls, {int startIndex = 0}) async {
    if (audioUrls.isEmpty) {
      return;
    }

    // Convert relative URLs to full URLs if needed
    final List<String> fullAudioUrls = audioUrls.map((url) {
      // If URL doesn't start with http/https, convert it using buildApiUrl
      if (!url.trim().toLowerCase().startsWith('http')) {
        final fullUrl = buildApiUrl(url);
        return fullUrl;
      }
      return url;
    }).toList();

    // Request audio permissions first
    final hasPermission = await _requestAudioPermissions();
    if (!hasPermission) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Izin audio diperlukan untuk memutar bacaan'),
          backgroundColor: Colors.orange,
          duration: Duration(seconds: 3),
        ),
      );
      return;
    }

    try {
      // Check if same playlist is playing (compare using full URLs)
      final isSamePlaylist =
          _currentPlaylist.length == fullAudioUrls.length &&
          _currentPlaylist.asMap().entries.every(
            (entry) => entry.value == fullAudioUrls[entry.key],
          );

      if (isSamePlaylist && _isPlaying) {
        // Pause current audio
        await _audioPlayer.pause();
        setState(() {
          _isPlaying = false;
        });
      } else if (isSamePlaylist && !_isPlaying) {
        // Resume current audio
        await _audioPlayer.play();
        setState(() {
          _isPlaying = true;
        });
      } else {
        // Play new playlist
        setState(() {
          _isLoadingAudio = true;
          _currentPlaylist = fullAudioUrls;
          _currentAudioIndex = startIndex;
        });

        await _playCurrentAudio();
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _isLoadingAudio = false;
          _currentPlaylist = [];
          _currentAudioIndex = 0;
        });

        String errorMessage = 'Gagal memutar audio: ${e.toString()}';
        if (e.toString().contains('AndroidAudioError')) {
          errorMessage = 'Gagal memutar audio. Periksa koneksi internet Anda.';
        } else if (e.toString().contains('404') ||
            e.toString().contains('Not Found')) {
          errorMessage = 'File audio tidak ditemukan di server.';
        } else if (e.toString().contains('Connection')) {
          errorMessage =
              'Gagal terhubung ke server audio. Periksa koneksi internet.';
        } else if (e.toString().contains('HttpException') ||
            e.toString().contains('SocketException')) {
          errorMessage =
              'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
        }

        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(errorMessage),
            backgroundColor: Colors.red,
            duration: Duration(seconds: 5),
          ),
        );
      }
    }
  }

  Future<void> _playCurrentAudio() async {
    if (_currentAudioIndex >= _currentPlaylist.length) {
      return;
    }

    final urlToPlay = _currentPlaylist[_currentAudioIndex];

    try {
      // Stop current playback
      await _audioPlayer.stop();

      // Small delay for cleanup
      await Future.delayed(Duration(milliseconds: 100));

      // Set audio source from URL for streaming
      await _audioPlayer.setAudioSource(AudioSource.uri(Uri.parse(urlToPlay)));

      // Set volume
      await _audioPlayer.setVolume(1.0);

      // Play audio
      await _audioPlayer.play();

      if (mounted) {
        setState(() {
          _isPlaying = true;
          _isLoadingAudio = false;
        });
      }
    } catch (e) {
      rethrow;
    }
  }

  Future<void> _playNextInPlaylist() async {
    try {
      await _playCurrentAudio();
    } catch (e) {
      if (mounted) {
        setState(() {
          _currentPlaylist = [];
          _currentAudioIndex = 0;
          _isPlaying = false;
          _isLoadingAudio = false;
        });
      }
    }
  }

  Future<void> _stopAudio() async {
    await _audioPlayer.stop();
    setState(() {
      _currentPlaylist = [];
      _currentAudioIndex = 0;
      _isPlaying = false;
    });
  }

  Widget _buildReadingSection() {
    final reading = _selectedReading == 'pagi'
        ? _readingData!.morning
        : _readingData!.evening;

    if (reading.data.content.isEmpty) {
      return Container(
        padding: EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white.withValues(alpha: 0.9),
          borderRadius: BorderRadius.circular(12),
        ),
        child: Text(
          'Tidak ada bacaan untuk ${_selectedReading == "pagi" ? "pagi" : "sore"} hari ini.',
          style: TextStyle(fontSize: 14, color: Color(0xFF485563)),
          textAlign: TextAlign.center,
        ),
      );
    }

    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        // Header
        Container(
          padding: EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: _selectedReading == 'pagi'
                ? Color(0xFFf7971e).withValues(alpha: 0.2)
                : Color(0xFF485563).withValues(alpha: 0.2),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Column(
            children: [
              Row(
                children: [
                  Icon(
                    _selectedReading == 'pagi'
                        ? Icons.wb_sunny
                        : Icons.nights_stay,
                    color: _selectedReading == 'pagi'
                        ? Color(0xFFf7971e)
                        : Color(0xFF485563),
                    size: 24,
                  ),
                  SizedBox(width: 12),
                  Expanded(
                    child: Text(
                      'Bacaan ${_selectedReading == "pagi" ? "Pagi" : "Sore"} Hari',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: Color.fromARGB(255, 239, 241, 241),
                      ),
                    ),
                  ),
                ],
              ),
              SizedBox(height: 8),
              Text(
                _readingData!.date,
                style: TextStyle(
                  fontSize: 14,
                  color: Color.fromARGB(255, 238, 239, 240),
                  fontWeight: FontWeight.w500,
                ),
              ),
              // Audio controls
              if (reading.data.audioUrl.isNotEmpty) ...[
                SizedBox(height: 12),
                _buildAudioControls(reading.data.audioUrl),
              ] else ...[
                SizedBox(height: 12),
                Container(
                  padding: EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: Colors.orange.withValues(alpha: 0.2),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Icon(Icons.info_outline, size: 16, color: Colors.orange),
                      SizedBox(width: 8),
                      Text(
                        'Audio tidak tersedia',
                        style: TextStyle(
                          fontSize: 12,
                          color: Colors.orange.shade900,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ],
          ),
        ),
        SizedBox(height: 16),
        // Reference
        Container(
          padding: EdgeInsets.all(12),
          decoration: BoxDecoration(
            color: Color(0xFF6a3093).withValues(alpha: 0.1),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Row(
            children: [
              Icon(Icons.menu_book, color: Color(0xFF6a3093), size: 20),
              SizedBox(width: 8),
              Expanded(
                child: Text(
                  reading.data.reference,
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: Color.fromARGB(255, 240, 242, 243),
                  ),
                ),
              ),
            ],
          ),
        ),
        SizedBox(height: 16),
        // Progress indicator
        Container(
          padding: EdgeInsets.symmetric(horizontal: 16, vertical: 12),
          decoration: BoxDecoration(
            color: Colors.white.withValues(alpha: 0.9),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Row(
            children: [
              Icon(Icons.auto_stories, color: Color(0xFF6a3093), size: 18),
              SizedBox(width: 8),
              Text(
                'Hari ${_readingData!.progress.currentDay} dari ${_readingData!.progress.totalDays}',
                style: TextStyle(
                  fontSize: 13,
                  fontWeight: FontWeight.w600,
                  color: Color(0xFF485563),
                ),
              ),
              Spacer(),
              Text(
                '${((_readingData!.progress.currentDay / _readingData!.progress.totalDays) * 100).toStringAsFixed(1)}%',
                style: TextStyle(
                  fontSize: 13,
                  fontWeight: FontWeight.bold,
                  color: Color(0xFF6a3093),
                ),
              ),
            ],
          ),
        ),
        SizedBox(height: 16),
        // Verses
        _buildVersesList(reading.data.content),
      ],
    );
  }

  Widget _buildVersesList(List<dynamic> verses) {
    return Container(
      padding: EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white.withValues(alpha: 0.95),
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.1),
            blurRadius: 8,
            offset: Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          ...verses.asMap().entries.map((entry) {
            final index = entry.key;
            final verse = entry.value;

            return Padding(
              padding: EdgeInsets.only(
                bottom: index < verses.length - 1 ? 16 : 0,
              ),
              child: _buildVerseItem(verse),
            );
          }),
        ],
      ),
    );
  }

  Widget _buildVerseItem(dynamic verse) {
    return Container(
      padding: EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Color(0xFFf8fafc).withValues(alpha: 0.5),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(
          color: Color(0xFF6a3093).withValues(alpha: 0.1),
          width: 1,
        ),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Verse number
          Container(
            padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
            decoration: BoxDecoration(
              color: Color(0xFF6a3093),
              borderRadius: BorderRadius.circular(6),
            ),
            child: Text(
              '${verse.verse}',
              style: TextStyle(
                fontSize: 12,
                fontWeight: FontWeight.bold,
                color: Colors.white,
              ),
            ),
          ),
          SizedBox(width: 12),
          // Verse text
          Expanded(
            child: Text(
              verse.text,
              style: TextStyle(
                fontSize: 14,
                color: Color(0xFF232526),
                height: 1.6,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildAudioControls(List<String> audioUrls) {
    final isSamePlaylist =
        _currentPlaylist.length == audioUrls.length &&
        _currentPlaylist.asMap().entries.every(
          (entry) => entry.value == audioUrls[entry.key],
        );

    return Container(
      padding: EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        color: Colors.white.withValues(alpha: 0.3),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Column(
        children: [
          Row(
            children: [
              Icon(
                Icons.audiotrack,
                size: 20,
                color: Color.fromARGB(255, 238, 239, 240),
              ),
              SizedBox(width: 8),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Audio Bacaan Alkitab',
                      style: TextStyle(
                        fontSize: 13,
                        fontWeight: FontWeight.w600,
                        color: Color.fromARGB(255, 238, 239, 240),
                      ),
                    ),
                    if (isSamePlaylist && _isPlaying) ...[
                      SizedBox(height: 2),
                      Text(
                        'Memutar ${_currentAudioIndex + 1}/${audioUrls.length}',
                        style: TextStyle(
                          fontSize: 11,
                          color: Color.fromARGB(
                            255,
                            238,
                            239,
                            240,
                          ).withValues(alpha: 0.8),
                        ),
                      ),
                    ],
                  ],
                ),
              ),
              if (_isLoadingAudio && isSamePlaylist) ...[
                SizedBox(
                  width: 24,
                  height: 24,
                  child: CircularProgressIndicator(
                    strokeWidth: 2,
                    color: Color.fromARGB(255, 238, 239, 240),
                  ),
                ),
              ] else ...[
                if (isSamePlaylist && _isPlaying) ...[
                  // Stop button
                  InkWell(
                    onTap: _stopAudio,
                    child: Container(
                      padding: EdgeInsets.all(6),
                      decoration: BoxDecoration(
                        color: Colors.white.withValues(alpha: 0.2),
                        borderRadius: BorderRadius.circular(6),
                      ),
                      child: Icon(
                        Icons.stop,
                        size: 20,
                        color: Color.fromARGB(255, 238, 239, 240),
                      ),
                    ),
                  ),
                  SizedBox(width: 8),
                ],
                // Play/Pause button
                InkWell(
                  onTap: () => _playAudio(audioUrls),
                  child: Container(
                    padding: EdgeInsets.all(6),
                    decoration: BoxDecoration(
                      color: Colors.white.withValues(alpha: 0.2),
                      borderRadius: BorderRadius.circular(6),
                    ),
                    child: Icon(
                      isSamePlaylist && _isPlaying
                          ? Icons.pause
                          : Icons.play_arrow,
                      size: 20,
                      color: Color.fromARGB(255, 238, 239, 240),
                    ),
                  ),
                ),
              ],
            ],
          ),
          // Audio progress bar
          if (isSamePlaylist &&
              (_isPlaying || _currentPosition.inSeconds > 0)) ...[
            SizedBox(height: 8),
            Column(
              children: [
                ClipRRect(
                  borderRadius: BorderRadius.circular(4),
                  child: LinearProgressIndicator(
                    value: _totalDuration.inSeconds > 0
                        ? _currentPosition.inSeconds / _totalDuration.inSeconds
                        : 0,
                    backgroundColor: Colors.white.withValues(alpha: 0.2),
                    valueColor: AlwaysStoppedAnimation<Color>(
                      Color.fromARGB(255, 238, 239, 240),
                    ),
                    minHeight: 4,
                  ),
                ),
                SizedBox(height: 4),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      _formatDuration(_currentPosition),
                      style: TextStyle(
                        fontSize: 10,
                        color: Color.fromARGB(
                          255,
                          238,
                          239,
                          240,
                        ).withValues(alpha: 0.8),
                      ),
                    ),
                    Text(
                      _formatDuration(_totalDuration),
                      style: TextStyle(
                        fontSize: 10,
                        color: Color.fromARGB(
                          255,
                          238,
                          239,
                          240,
                        ).withValues(alpha: 0.8),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ],
        ],
      ),
    );
  }

  String _formatDuration(Duration duration) {
    final minutes = duration.inMinutes;
    final seconds = duration.inSeconds % 60;
    return '${minutes.toString().padLeft(2, '0')}:${seconds.toString().padLeft(2, '0')}';
  }
}
