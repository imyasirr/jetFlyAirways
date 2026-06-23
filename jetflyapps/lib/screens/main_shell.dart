import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../services/api_service.dart';
import '../services/travel_repository.dart';
import 'account_screens.dart';
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
    try {
      final data = await TravelRepository(context.read<ApiService>()).getHome();
      setState(() => _homeData = data);
    } catch (e) {
      setState(() => _error = e.toString());
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_loading) {
      return const Scaffold(body: Center(child: CircularProgressIndicator()));
    }

    if (_error != null) {
      return Scaffold(
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.wifi_off, size: 64, color: Colors.grey),
              const SizedBox(height: 16),
              Text('Cannot connect to server', style: Theme.of(context).textTheme.titleMedium),
              const SizedBox(height: 8),
              Padding(padding: const EdgeInsets.symmetric(horizontal: 32), child: Text(_error!, textAlign: TextAlign.center)),
              const SizedBox(height: 16),
              ElevatedButton(onPressed: () { setState(() { _loading = true; _error = null; }); _loadHome(); }, child: const Text('Retry')),
            ],
          ),
        ),
      );
    }

    final screens = [
      HomeScreen(homeData: _homeData!),
      const BookingsScreen(),
      const WishlistScreen(),
      const AccountScreen(),
    ];

    return Scaffold(
      body: IndexedStack(index: _index, children: screens),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _index,
        onTap: (i) => setState(() => _index = i),
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Home'),
          BottomNavigationBarItem(icon: Icon(Icons.confirmation_number), label: 'Bookings'),
          BottomNavigationBarItem(icon: Icon(Icons.favorite), label: 'Wishlist'),
          BottomNavigationBarItem(icon: Icon(Icons.person), label: 'Account'),
        ],
      ),
    );
  }
}
