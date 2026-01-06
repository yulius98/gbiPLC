import '../helpers/url_helper.dart';

class CarouselItem {
  final String image;

  CarouselItem({required this.image});

  factory CarouselItem.fromJson(Map<String, dynamic> json) {
    final raw = json['image_url'] ?? '';
    // Convert relative URL to full URL using buildApiUrl
    final url = raw.startsWith('http') ? raw : buildApiUrl(raw);
    return CarouselItem(image: url);
  }
}

extension CarouselItemHelpers on CarouselItem {
  CarouselItem copyWith({String? image}) => CarouselItem(image: image ?? this.image);

  String asDebugString() => 'CarouselItem(image: $image)';
}