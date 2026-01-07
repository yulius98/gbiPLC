import 'package:flutter/material.dart';
import 'package:video_player/video_player.dart';
import '../models/caraousel.dart';
import '../services/carousel_service.dart';
import '../helpers/file_type_helper.dart';

/// A lightweight carousel widget that fetches items using `fetchCarousel()`.
/// Uses PageView with graceful loading/error states.
/// Optimized for faster initial load with lazy loading.

class CarouselWidget extends StatefulWidget {
  const CarouselWidget({super.key});

  @override
  State<CarouselWidget> createState() => _CarouselWidgetState();
}

class _CarouselWidgetState extends State<CarouselWidget> {
  Future<List<CarouselItem>>? _carouselFuture;

  @override
  void initState() {
    super.initState();
    // Delay carousel loading to not block initial render
    Future.microtask(() {
      if (mounted) {
        setState(() {
          _carouselFuture = fetchCarousel();
        });
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final screenWidth = MediaQuery.of(context).size.width;
    // Untuk portrait, lebar lebih kecil, tinggi lebih besar (rasio 3:4)
    final carouselWidth = screenWidth;
    final carouselHeight =
        screenWidth * 1.6; //carouselWidth * (4 / 3); // rasio portrait 3:4

    // Show placeholder immediately if data not loaded yet
    if (_carouselFuture == null) {
      return SizedBox(
        width: carouselWidth,
        height: carouselHeight,
        child: Container(
          color: Colors.black,
          child: const Center(child: CircularProgressIndicator()),
        ),
      );
    }

    return FutureBuilder<List<CarouselItem>>(
      future: _carouselFuture,
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return SizedBox(
            width: carouselWidth,
            height: carouselHeight,
            child: const Center(child: CircularProgressIndicator()),
          );
        }

        if (snapshot.hasError) {
          return SizedBox(
            width: carouselWidth,
            height: carouselHeight,
            child: Center(child: Text('Gagal memuat: ${snapshot.error}')),
          );
        }

        final items = snapshot.data;
        if (items == null || items.isEmpty) {
          return SizedBox(
            width: carouselWidth,
            height: carouselHeight,
            child: const Center(child: Text('Tidak ada item')),
          );
        }

        return SizedBox(
          width: carouselWidth,
          height: carouselHeight,
          child: PageView.builder(
            itemCount: items.length,
            controller: PageController(viewportFraction: 1.0), //atur
            itemBuilder: (context, index) {
              final item = items[index];
              return Padding(
                padding: const EdgeInsets.symmetric(
                  horizontal: 0.0,
                  vertical: 0.0,
                ),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(12),
                  child: Material(
                    elevation: 4,
                    color: Colors.black,
                    child: isVideoFile(item.image)
                        ? _VideoPlayerWidget(url: item.image)
                        : Image.network(
                            item.image,
                            width: double.infinity,
                            height: double.infinity,
                            fit: BoxFit.contain,
                            loadingBuilder: (context, child, loadingProgress) {
                              if (loadingProgress == null) return child;
                              return Container(
                                color: Colors.black,
                                child: const Center(
                                  child: CircularProgressIndicator(),
                                ),
                              );
                            },
                            errorBuilder: (context, error, stackTrace) =>
                                Container(
                                  color: Colors.grey[300],
                                  child: Center(
                                    child: Column(
                                      mainAxisSize: MainAxisSize.min,
                                      children: const [
                                        Icon(
                                          Icons.broken_image,
                                          size: 40,
                                          color: Colors.grey,
                                        ),
                                        SizedBox(height: 6),
                                        Text('Gagal memuat gambar'),
                                      ],
                                    ),
                                  ),
                                ),
                          ),
                  ),
                ),
              );
            },
          ),
        );
      },
    );
  }
}

// Widget untuk menampilkan video dari URL
class _VideoPlayerWidget extends StatefulWidget {
  final String url;
  const _VideoPlayerWidget({required this.url});

  @override
  State<_VideoPlayerWidget> createState() => _VideoPlayerWidgetState();
}

class _VideoPlayerWidgetState extends State<_VideoPlayerWidget> {
  VideoPlayerController? _controller;
  bool _initialized = false;
  bool _shouldInitialize = false;

  @override
  void initState() {
    super.initState();
    // Delay video initialization to not block UI
    Future.delayed(const Duration(milliseconds: 500), () {
      if (mounted) {
        setState(() {
          _shouldInitialize = true;
        });
        _initializeVideo();
      }
    });
  }

  void _initializeVideo() {
    _controller = VideoPlayerController.networkUrl(Uri.parse(widget.url))
      ..initialize()
          .then((_) {
            if (mounted) {
              setState(() {
                _initialized = true;
              });
              _controller?.setLooping(true);
              _controller?.play();
            }
          })
          .catchError((error) {
            debugPrint('Error initializing video: $error');
          });
  }

  @override
  void dispose() {
    _controller?.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (!_shouldInitialize || !_initialized || _controller == null) {
      return Container(
        color: Colors.black,
        child: const Center(child: CircularProgressIndicator()),
      );
    }
    return AspectRatio(
      aspectRatio: _controller!.value.aspectRatio,
      child: VideoPlayer(_controller!),
    );
  }
}
