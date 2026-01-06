bool isVideoFile(String url) {
  final videoExtensions = ['.mp4', '.mov', '.avi', '.mkv', '.webm'];
  return videoExtensions.any((ext) => url.toLowerCase().endsWith(ext));
}
