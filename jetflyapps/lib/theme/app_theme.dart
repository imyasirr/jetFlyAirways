import 'package:flutter/material.dart';

class AppColors {
  static const bookingBlue = Color(0xFF003B95);
  static const primary = Color(0xFF005BBF);
  static const primaryContainer = Color(0xFF1A73E8);
  static const secondaryContainer = Color(0xFFD2E0FE);
  static const promoYellow = Color(0xFFFFE16D);
  static const onSurface = Color(0xFF1A1C1C);
  static const muted = Color(0xFF515F78);
  static const surfaceLow = Color(0xFFF3F3F4);
  static const success = Color(0xFF008009);
  static const alert = Color(0xFFD93025);
  static const footerDeep = Color(0xFF00297A);
}

class AppTheme {
  static ThemeData get light {
    return ThemeData(
      useMaterial3: true,
      colorScheme: ColorScheme.fromSeed(
        seedColor: AppColors.primary,
        primary: AppColors.primary,
        secondary: AppColors.primaryContainer,
        surface: Colors.white,
      ),
      scaffoldBackgroundColor: AppColors.surfaceLow,
      appBarTheme: const AppBarTheme(
        backgroundColor: AppColors.bookingBlue,
        foregroundColor: Colors.white,
        elevation: 0,
        centerTitle: false,
      ),
      cardTheme: CardThemeData(
        color: Colors.white,
        elevation: 0,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.primary,
          foregroundColor: Colors.white,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
        ),
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: Colors.white,
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
      ),
      bottomNavigationBarTheme: const BottomNavigationBarThemeData(
        selectedItemColor: AppColors.primary,
        unselectedItemColor: AppColors.muted,
        type: BottomNavigationBarType.fixed,
        backgroundColor: Colors.white,
      ),
      navigationBarTheme: NavigationBarThemeData(
        indicatorColor: AppColors.secondaryContainer,
        labelTextStyle: WidgetStateProperty.resolveWith((states) {
          if (states.contains(WidgetState.selected)) {
            return const TextStyle(fontSize: 12, fontWeight: FontWeight.w600, color: AppColors.primary);
          }
          return const TextStyle(fontSize: 12, color: AppColors.muted);
        }),
      ),
    );
  }
}
