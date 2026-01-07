import 'package:flutter/material.dart';

/// Wrapper untuk lazy loading pages
/// Page hanya di-load saat pertama kali diakses
class LazyLoadPage extends StatefulWidget {
  final Widget Function() builder;
  final String? pageName;

  const LazyLoadPage({super.key, required this.builder, this.pageName});

  @override
  State<LazyLoadPage> createState() => _LazyLoadPageState();
}

class _LazyLoadPageState extends State<LazyLoadPage> {
  Widget? _cachedWidget;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadPage();
  }

  void _loadPage() async {
    // Simulate minimal delay to show loading if needed
    await Future.delayed(const Duration(milliseconds: 50));

    if (mounted) {
      setState(() {
        _cachedWidget = widget.builder();
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return Scaffold(
        backgroundColor: Colors.black,
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const CircularProgressIndicator(
                valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
              ),
              if (widget.pageName != null) ...[
                const SizedBox(height: 16),
                Text(
                  'Loading ${widget.pageName}...',
                  style: const TextStyle(color: Colors.white70),
                ),
              ],
            ],
          ),
        ),
      );
    }

    return _cachedWidget!;
  }
}
