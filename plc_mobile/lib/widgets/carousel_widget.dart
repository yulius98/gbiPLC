import 'package:flutter/material.dart';
import 'package:video_player/video_player.dart';
import '../models/caraousel.dart';
import '../services/carousel_service.dart';
import '../helpers/file_type_helper.dart';

/// A lightweight carousel widget that fetches items using `fetchCarousel()`.
/// Uses PageView with graceful loading/error states.

class CarouselWidget extends StatelessWidget {
  const CarouselWidget({super.key});

  @override
  Widget build(BuildContext context) {
    final screenWidth = MediaQuery.of(context).size.width;
    // Untuk portrait, lebar lebih kecil, tinggi lebih besar (rasio 3:4)
    final carouselWidth = screenWidth;
    final carouselHeight = screenWidth * 1.6; //carouselWidth * (4 / 3); // rasio portrait 3:4

    return FutureBuilder<List<CarouselItem>>(
      future: fetchCarousel(),
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
                            errorBuilder: (context, error, stackTrace) => Container(
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
  late VideoPlayerController _controller;
  bool _initialized = false;

  @override
  void initState() {
    super.initState();
    _controller = VideoPlayerController.networkUrl(Uri.parse(widget.url))
      ..initialize().then((_) {
        setState(() {
          _initialized = true;
        });
        _controller.setLooping(true);
        _controller.play();
      });
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (!_initialized) {
      return Container(
        color: Colors.black,
        child: const Center(child: CircularProgressIndicator()),
      );
    }
    return AspectRatio(
      aspectRatio: _controller.value.aspectRatio,
      child: VideoPlayer(_controller),
    );
  }
}
