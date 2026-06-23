class ApiConfig {
  /// Production: https://jetflyairways.nectradigital.com
  /// Override at build: --dart-define=API_BASE_URL=https://...
  static const String baseUrl = String.fromEnvironment(
    'API_BASE_URL',
    defaultValue: 'https://jetflyairways.nectradigital.com',
  );

  static String get apiUrl => '$baseUrl/api/v1';
}
