import 'package:http/http.dart' as http;

/// Base URL configuration - Change this manually for different environments:
/// 
/// For emulator: 'http://10.0.2.2:8000'
/// For phone/LAN: 'http://192.168.1.5:8000' 
/// For production: 'https://philadelphialifecenter.com'
String baseUrl = 'http://10.0.2.2:8000';

/// Initialize base URL. Call this in main() to set the base URL.
void initBaseUrl([String? url]) {
  if (url != null) {
    baseUrl = url;
  }
}

/// Build a full API url from a relative [path].
///
/// Uses the global [baseUrl] variable and adds the path to it.
String buildApiUrl(String path) {
  try {
    // If path already contains a scheme, return as-is
    if (path.trim().toLowerCase().startsWith('http')) {
      return path;
    }

    // Normalize path (ensure it starts with /)
    final normalized = path.startsWith('/') ? path : '/$path';
    
    // Remove trailing slash from baseUrl to avoid double slashes
    final trimmedBase = baseUrl.endsWith('/') 
        ? baseUrl.substring(0, baseUrl.length - 1) 
        : baseUrl;
    
    return '$trimmedBase$normalized';
  } catch (_) {
    return path;
  }
}

/// Build a [Uri] for an API [path]. Prefer this when making http requests.
Uri buildApiUri(String path) => Uri.parse(buildApiUrl(path));

/// Perform an HTTP HEAD request against [url]. Returns the http.Response.
Future<http.Response> headWithConversion(
  String url, {
  Duration timeout = const Duration(seconds: 10),
}) async {
  final uri = Uri.parse(url);
  return await http.head(uri).timeout(timeout);
}
