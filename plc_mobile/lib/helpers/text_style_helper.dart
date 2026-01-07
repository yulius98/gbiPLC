import 'package:flutter/material.dart';

/// Helper untuk text styles yang konsisten di seluruh aplikasi
/// Menghindari download Google Fonts saat runtime untuk performa lebih baik

class AppTextStyles {
  // Base font family - gunakan system font yang sudah tersedia
  static const String _fontFamily = 'sans-serif';

  // AppBar title style
  static const TextStyle appBarTitle = TextStyle(
    fontSize: 18,
    fontWeight: FontWeight.bold,
    letterSpacing: 1.2,
    color: Colors.white,
    fontFamily: _fontFamily,
  );

  // Heading styles
  static const TextStyle heading1 = TextStyle(
    fontSize: 24,
    fontWeight: FontWeight.bold,
    fontFamily: _fontFamily,
  );

  static const TextStyle heading2 = TextStyle(
    fontSize: 20,
    fontWeight: FontWeight.bold,
    fontFamily: _fontFamily,
  );

  static const TextStyle heading3 = TextStyle(
    fontSize: 18,
    fontWeight: FontWeight.bold,
    fontFamily: _fontFamily,
  );

  // Body text styles
  static const TextStyle bodyLarge = TextStyle(
    fontSize: 16,
    fontFamily: _fontFamily,
  );

  static const TextStyle bodyMedium = TextStyle(
    fontSize: 14,
    fontFamily: _fontFamily,
  );

  static const TextStyle bodySmall = TextStyle(
    fontSize: 12,
    fontFamily: _fontFamily,
  );

  // Button text
  static const TextStyle button = TextStyle(
    fontSize: 16,
    fontWeight: FontWeight.w600,
    fontFamily: _fontFamily,
  );

  // Caption/Label
  static const TextStyle caption = TextStyle(
    fontSize: 12,
    fontWeight: FontWeight.w400,
    fontFamily: _fontFamily,
  );

  // Custom copyWith untuk modifikasi mudah
  static TextStyle custom({
    double? fontSize,
    FontWeight? fontWeight,
    Color? color,
    double? letterSpacing,
    double? height,
  }) {
    return TextStyle(
      fontSize: fontSize ?? 14,
      fontWeight: fontWeight ?? FontWeight.normal,
      color: color,
      letterSpacing: letterSpacing,
      height: height,
      fontFamily: _fontFamily,
    );
  }
}
