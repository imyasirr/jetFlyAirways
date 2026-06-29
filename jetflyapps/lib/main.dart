import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'providers/auth_provider.dart';
import 'screens/login_screen.dart';
import 'screens/main_shell.dart';
import 'screens/register_screen.dart';
import 'services/api_service.dart';
import 'theme/app_theme.dart';
import 'widgets/jetfly_loader.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  final api = ApiService();
  runApp(
    MultiProvider(
      providers: [
        Provider<ApiService>.value(value: api),
        ChangeNotifierProvider(create: (_) => AuthProvider(api)..init()),
      ],
      child: const JetFlyApp(),
    ),
  );
}

class JetFlyApp extends StatelessWidget {
  const JetFlyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'JetFly Airways',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.light,
      home: const _SplashGate(),
      routes: {
        '/login': (_) => const LoginScreen(),
        '/register': (_) => const RegisterScreen(),
      },
    );
  }
}

class _SplashGate extends StatelessWidget {
  const _SplashGate();

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    if (auth.loading) {
      return JetFlyLoader.fullscreen(message: 'Preparing your journey...');
    }
    return const MainShell();
  }
}
