import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';
import '../services/api_service.dart';
import '../services/travel_repository.dart';
import '../theme/app_theme.dart';
import '../widgets/empty_state.dart';
import '../widgets/jetfly_loader.dart';
import 'account_screens.dart';
import 'explore_screen.dart';
import 'home_screen.dart';

class MainShell extends StatefulWidget {
  const MainShell({super.key});

  @override
  State<MainShell> createState() => _MainShellState();
}

class _MainShellState extends State<MainShell> {
  int _index = 0;
  Map<String, dynamic>? _homeData;
  bool _loading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadHome();
  }

  Future<void> _loadHome() async {
    final repo = TravelRepository(context.read<ApiService>());
    final hasCache = await repo.hasHomeCache();
    if (!hasCache && mounted) setState(() => _loading = true);

    try {
      final data = await repo.getHome();
      if (mounted) setState(() => _homeData = data);
    } catch (e) {
      if (mounted) setState(() => _error = e.toString());
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  Future<void> _refreshHome() async {
    final data = await TravelRepository(context.read<ApiService>()).getHome(forceRefresh: true);
    if (mounted) setState(() => _homeData = data);
  }

  Future<bool> _onWillPop() async {
    if (_index != 0) {
      setState(() => _index = 0);
      return false;
    }

    final exit = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Row(
          children: [
            Icon(Icons.flight_takeoff, color: AppColors.primary),
            SizedBox(width: 10),
            Text('Exit app?'),
          ],
        ),
        content: const Text('Are you sure you want to close Jet Fly Airways?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Cancel')),
          ElevatedButton(
            onPressed: () => Navigator.pop(ctx, true),
            style: ElevatedButton.styleFrom(backgroundColor: AppColors.alert),
            child: const Text('Exit'),
          ),
        ],
      ),
    );

    if (exit == true) {
      SystemNavigator.pop();
    }
    return false;
  }

  @override
  Widget build(BuildContext context) {
    if (_loading) {
      return JetFlyLoader.fullscreen(message: 'Loading your travel hub...');
    }

    if (_error != null) {
      return Scaffold(
        body: EmptyState(
          icon: Icons.wifi_off,
          title: 'Cannot connect to server',
          subtitle: _error,
          actionLabel: 'Retry',
          onAction: () {
            setState(() {
              _loading = true;
              _error = null;
            });
            _loadHome();
          },
        ),
      );
    }

    final screens = [
      HomeScreen(homeData: _homeData!, onRefresh: _refreshHome),
      const ExploreScreen(),
      BookingsScreen(active: _index == 2),
      const WishlistScreen(),
      const AccountScreen(),
    ];

    return PopScope(
      canPop: false,
      onPopInvokedWithResult: (didPop, _) {
        if (!didPop) _onWillPop();
      },
      child: Scaffold(
        body: IndexedStack(index: _index, children: screens),
        bottomNavigationBar: NavigationBar(
          selectedIndex: _index,
          onDestinationSelected: (i) => setState(() => _index = i),
          destinations: const [
            NavigationDestination(icon: Icon(Icons.home_outlined), selectedIcon: Icon(Icons.home), label: 'Home'),
            NavigationDestination(icon: Icon(Icons.explore_outlined), selectedIcon: Icon(Icons.explore), label: 'Explore'),
            NavigationDestination(icon: Icon(Icons.confirmation_number_outlined), selectedIcon: Icon(Icons.confirmation_number), label: 'Bookings'),
            NavigationDestination(icon: Icon(Icons.favorite_outline), selectedIcon: Icon(Icons.favorite), label: 'Wishlist'),
            NavigationDestination(icon: Icon(Icons.person_outline), selectedIcon: Icon(Icons.person), label: 'Account'),
          ],
        ),
      ),
    );
  }
}
