import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/models.dart';
import '../providers/auth_provider.dart';
import '../services/api_service.dart';
import '../services/travel_repository.dart';
import '../theme/app_theme.dart';
import 'module_detail_screen.dart';

class BookingsScreen extends StatefulWidget {
  const BookingsScreen({super.key});

  @override
  State<BookingsScreen> createState() => _BookingsScreenState();
}

class _BookingsScreenState extends State<BookingsScreen> {
  List<BookingModel> _bookings = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    if (!context.read<AuthProvider>().isLoggedIn) {
      setState(() => _loading = false);
      return;
    }
    try {
      final repo = TravelRepository(context.read<ApiService>());
      final bookings = await repo.getBookings();
      setState(() => _bookings = bookings);
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();

    if (!auth.isLoggedIn) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.lock_outline, size: 64, color: AppColors.muted),
            const SizedBox(height: 16),
            const Text('Login to view your bookings'),
            const SizedBox(height: 16),
            ElevatedButton(onPressed: () => Navigator.pushNamed(context, '/login'), child: const Text('Login')),
          ],
        ),
      );
    }

    if (_loading) return const Center(child: CircularProgressIndicator());

    if (_bookings.isEmpty) {
      return const Center(child: Text('No bookings yet'));
    }

    return RefreshIndicator(
      onRefresh: _load,
      child: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _bookings.length,
        itemBuilder: (context, index) {
          final b = _bookings[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 10),
            child: ListTile(
              title: Text(b.bookingCode, style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('${b.module.toUpperCase()} · ${b.travelDate}\n${b.paymentStatus}'),
              isThreeLine: true,
              trailing: Text('₹${b.totalAmount.toStringAsFixed(0)}', style: const TextStyle(fontWeight: FontWeight.bold, color: AppColors.primary)),
              onTap: () {
                if (!b.isCancelled && b.paymentStatus == 'pending') {
                  showDialog(
                    context: context,
                    builder: (ctx) => AlertDialog(
                      title: const Text('Cancel Booking?'),
                      content: Text('Cancel booking ${b.bookingCode}?'),
                      actions: [
                        TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('No')),
                        TextButton(
                          onPressed: () async {
                            Navigator.pop(ctx);
                            try {
                              await TravelRepository(context.read<ApiService>()).cancelBooking(b.id);
                              _load();
                            } catch (e) {
                              if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
                            }
                          },
                          child: const Text('Yes, Cancel'),
                        ),
                      ],
                    ),
                  );
                }
              },
            ),
          );
        },
      ),
    );
  }
}

class WishlistScreen extends StatefulWidget {
  const WishlistScreen({super.key});

  @override
  State<WishlistScreen> createState() => _WishlistScreenState();
}

class _WishlistScreenState extends State<WishlistScreen> {
  List<Map<String, dynamic>> _items = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    if (!context.read<AuthProvider>().isLoggedIn) {
      setState(() => _loading = false);
      return;
    }
    try {
      final items = await TravelRepository(context.read<ApiService>()).getWishlist();
      setState(() => _items = items);
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    if (!context.watch<AuthProvider>().isLoggedIn) {
      return Center(
        child: ElevatedButton(onPressed: () => Navigator.pushNamed(context, '/login'), child: const Text('Login')),
      );
    }
    if (_loading) return const Center(child: CircularProgressIndicator());
    if (_items.isEmpty) return const Center(child: Text('Wishlist is empty'));

    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: _items.length,
      itemBuilder: (context, index) {
        final item = _items[index];
        return Card(
          child: ListTile(
            leading: const Icon(Icons.favorite, color: AppColors.alert),
            title: Text(item['title'] as String),
            subtitle: Text(item['module'] as String),
            onTap: () {
              final slug = item['slug'] as String?;
              if (slug != null) {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (_) => ModuleDetailScreen(
                      module: item['module'] as String,
                      slug: slug,
                      title: item['title'] as String,
                    ),
                  ),
                );
              }
            },
          ),
        );
      },
    );
  }
}

class AccountScreen extends StatelessWidget {
  const AccountScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();

    if (!auth.isLoggedIn) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.person_outline, size: 64, color: AppColors.muted),
            const SizedBox(height: 16),
            const Text('Login to access your account'),
            const SizedBox(height: 16),
            ElevatedButton(onPressed: () => Navigator.pushNamed(context, '/login'), child: const Text('Login')),
            TextButton(onPressed: () => Navigator.pushNamed(context, '/register'), child: const Text('Register')),
          ],
        ),
      );
    }

    final user = auth.user!;

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        Card(
          child: Padding(
            padding: const EdgeInsets.all(20),
            child: Row(
              children: [
                CircleAvatar(
                  radius: 32,
                  backgroundColor: AppColors.secondaryContainer,
                  child: Text(user.name.isNotEmpty ? user.name[0].toUpperCase() : 'U', style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: AppColors.primary)),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(user.name, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                      Text(user.email, style: const TextStyle(color: AppColors.muted)),
                      if (user.referralCode != null)
                        Text('Referral: ${user.referralCode}', style: const TextStyle(fontSize: 12, color: AppColors.primary)),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ),
        const SizedBox(height: 16),
        ListTile(
          leading: const Icon(Icons.card_giftcard),
          title: const Text('Refer & Earn'),
          subtitle: Text('${user.referralsCount} referrals'),
        ),
        const Divider(),
        ListTile(
          leading: const Icon(Icons.logout, color: AppColors.alert),
          title: const Text('Logout'),
          onTap: () async {
            await auth.logout();
          },
        ),
      ],
    );
  }
}
