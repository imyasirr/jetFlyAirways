import '../config/api_config.dart';

class MediaUrl {
  static String? resolve(String? stored) {
    if (stored == null || stored.trim().isEmpty) return null;

    var path = stored.trim().replaceAll('\\', '/');
    if (path.startsWith('http://') || path.startsWith('https://')) return path;

    if (path.startsWith('/')) path = path.substring(1);
    if (path.startsWith('storage/')) path = path.substring('storage/'.length);
    if (path.startsWith('uploads/')) path = path.substring('uploads/'.length);

    return '${ApiConfig.baseUrl}/uploads/$path';
  }
}
