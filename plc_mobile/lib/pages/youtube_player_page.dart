import 'package:flutter/material.dart';
import 'package:youtube_player_flutter/youtube_player_flutter.dart';
import 'package:url_launcher/url_launcher.dart';

class YouTubePlayerPage extends StatefulWidget {
  final String videoId;

  const YouTubePlayerPage({super.key, required this.videoId});

  @override
  State<YouTubePlayerPage> createState() => _YouTubePlayerPageState();
}

class _YouTubePlayerPageState extends State<YouTubePlayerPage> {
  late YoutubePlayerController _controller;
  bool _hasError = false;

  @override
  void initState() {
    super.initState();
    _controller = YoutubePlayerController(
      initialVideoId: widget.videoId,
      flags: const YoutubePlayerFlags(autoPlay: true, mute: false),
    );

    // Listen for errors
    _controller.addListener(() {
      if (_controller.value.hasError) {
        setState(() {
          _hasError = true;
        });
      }
    });
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  Future<void> _openInYouTubeApp() async {
    final youtubeUrl = 'https://www.youtube.com/watch?v=${widget.videoId}';
    final youtubeAppUrl = 'vnd.youtube://${widget.videoId}';

    try {
      // Try to open in YouTube app first
      final Uri appUri = Uri.parse(youtubeAppUrl);
      if (await canLaunchUrl(appUri)) {
        await launchUrl(appUri, mode: LaunchMode.externalApplication);
      } else {
        // Fallback to browser
        final Uri webUri = Uri.parse(youtubeUrl);
        await launchUrl(webUri, mode: LaunchMode.externalApplication);
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Tidak dapat membuka YouTube: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('YouTube Player'),
        backgroundColor: Colors.red,
        actions: [
          IconButton(
            icon: const Icon(Icons.open_in_new),
            tooltip: 'Buka di YouTube',
            onPressed: _openInYouTubeApp,
          ),
        ],
      ),
      body: _hasError
          ? _buildErrorView()
          : Center(
              child: YoutubePlayer(
                controller: _controller,
                showVideoProgressIndicator: true,
                onEnded: (metaData) {
                  // Handle video end
                },
              ),
            ),
    );
  }

  Widget _buildErrorView() {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.error_outline, size: 64, color: Colors.red[300]),
            const SizedBox(height: 24),
            Text(
              'Pemutaran Dibatasi',
              style: TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
                color: Colors.grey[800],
              ),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 16),
            Text(
              'Video ini tidak dapat diputar di aplikasi eksternal karena pembatasan dari pemilik video.',
              style: TextStyle(
                fontSize: 16,
                color: Colors.grey[600],
                height: 1.5,
              ),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 32),
            ElevatedButton.icon(
              onPressed: _openInYouTubeApp,
              icon: const Icon(Icons.play_circle_filled),
              label: const Text('Buka di YouTube'),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.red,
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(
                  horizontal: 32,
                  vertical: 16,
                ),
                textStyle: const TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
            const SizedBox(height: 16),
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Kembali'),
            ),
          ],
        ),
      ),
    );
  }
}
