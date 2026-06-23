import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/models.dart';
import '../providers/auth_provider.dart';
import '../services/api_service.dart';
import '../services/travel_repository.dart';
import '../theme/app_theme.dart';
import '../widgets/empty_state.dart';
import 'module_detail_screen.dart';
import 'profile_screen.dart';
import 'support_screens.dart';

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
      final bookings = await TravelRepository(context.read<ApiService>()).getBookings();
      setState(() => _bookings = bookings);
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      setState(() => _loading = false);
    }
  }

  Color _statusColor(BookingModel b) {
    if (b.isCancelled) return AppColors.alert;
    if (b.isPaid) return AppColors.success;
    return AppColors.promoYellow;
  }

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();

    return Scaffold(
      appBar: AppBar(title: const Text('My Bookings')),
      body: !auth.isLoggedIn
          ? EmptyState(
              icon: Icons.lock_outline,
              title: 'Login to view bookings',
              subtitle: 'Sign in to see your trips and manage reservations.',
              actionLabel: 'Login',
              onAction: () => Navigator.pushNamed(context, '/login'),
            )
          : _loading
              ? const Center(child: CircularProgressIndicator())
              : _bookings.isEmpty
                  ? const EmptyState(icon: Icons.confirmation_number_outlined, title: 'No bookings yet', subtitle: 'Book a flight, hotel or package to get started.')
                  : RefreshIndicator(
                      onRefresh: _load,
                      child: ListView.builder(
                        padding: const EdgeInsets.all(16),
                        itemCount: _bookings.length,
                        itemBuilder: (context, index) {
                          final b = _bookings[index];
                          return Card(
                            margin: const EdgeInsets.only(bottom: 12),
                            child: Padding(
                              padding: const EdgeInsets.all(16),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Row(
                                    children: [
                                      Container(
                                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                        decoration: BoxDecoration(
                                          color: _statusColor(b).withValues(alpha: 0.15),
                                          borderRadius: BorderRadius.circular(20),
                                        ),
                                        child: Text(
                                          b.isCancelled ? 'Cancelled' : b.paymentStatus.toUpperCase(),
                                          style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: _statusColor(b)),
                                        ),
                                      ),
                                      const Spacer(),
                                      Text('₹${b.totalAmount.toStringAsFixed(0)}', style: const TextStyle(fontWeight: FontWeight.bold, color: AppColors.primary, fontSize: 18)),
                                    ],
                                  ),
                                  const SizedBox(height: 12),
                                  Text(b.bookingCode, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                                  const SizedBox(height: 4),
                                  Text('${b.module.toUpperCase()} · ${b.travelDate} · ${b.travellersCount} traveller(s)', style: const TextStyle(color: AppColors.muted, fontSize: 13)),
                                  if (!b.isCancelled && b.paymentStatus == 'pending') ...[
                                    const SizedBox(height: 12),
                                    Align(
                                      alignment: Alignment.centerRight,
                                      child: TextButton(
                                        onPressed: () => _confirmCancel(b),
                                        child: const Text('Cancel booking', style: TextStyle(color: AppColors.alert)),
                                      ),
                                    ),
                                  ],
                                ],
                              ),
                            ),
                          );
                        },
                      ),
                    ),
    );
  }

  void _confirmCancel(BookingModel b) {
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
    return Scaffold(
      appBar: AppBar(title: const Text('Wishlist')),
      body: !context.watch<AuthProvider>().isLoggedIn
          ? EmptyState(
              icon: Icons.favorite_border,
              title: 'Save your favourites',
              subtitle: 'Login to save flights, hotels and packages.',
              actionLabel: 'Login',
              onAction: () => Navigator.pushNamed(context, '/login'),
            )
          : _loading
              ? const Center(child: CircularProgressIndicator())
              : _items.isEmpty
                  ? const EmptyState(icon: Icons.favorite_outline, title: 'Wishlist is empty', subtitle: 'Tap the heart on any listing to save it here.')
                  : RefreshIndicator(
                      onRefresh: _load,
                      child: ListView.builder(
                        padding: const EdgeInsets.all(16),
                        itemCount: _items.length,
                        itemBuilder: (context, index) {
                          final item = _items[index];
                          return Card(
                            margin: const EdgeInsets.only(bottom: 10),
                            child: ListTile(
                              leading: const Icon(Icons.favorite, color: AppColors.alert),
                              title: Text(item['title'] as String, style: const TextStyle(fontWeight: FontWeight.w600)),
                              subtitle: Text((item['module'] as String).toUpperCase()),
                              trailing: const Icon(Icons.chevron_right),
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
                      ),
                    ),
    );
  }
}

class AccountScreen extends StatelessWidget {
  const AccountScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();

    if (!auth.isLoggedIn) {
      return Scaffold(
        appBar: AppBar(title: const Text('Account')),
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const EmptyState(
                icon: Icons.person_outline,
                title: 'Welcome to Jet Fly Airways',
                subtitle: 'Login or create an account to manage bookings, wishlist and profile.',
              ),
              const SizedBox(height: 8),
              ElevatedButton(onPressed: () => Navigator.pushNamed(context, '/login'), child: const Text('Login')),
              TextButton(onPressed: () => Navigator.pushNamed(context, '/register'), child: const Text('Create Account')),
            ],
          ),
        ),
      );
    }

    final user = auth.user!;

    return Scaffold(
      appBar: AppBar(title: const Text('Account')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Card(
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Row(
                children: [
                  CircleAvatar(
                    radius: 36,
                    backgroundColor: AppColors.secondaryContainer,
                    child: Text(user.name.isNotEmpty ? user.name[0].toUpperCase() : 'U', style: const TextStyle(fontSize: 28, fontWeight: FontWeight.bold, color: AppColors.primary)),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(user.name, style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                        Text(user.email, style: const TextStyle(color: AppColors.muted)),
                        if (user.phone != null && user.phone!.isNotEmpty)
                          Text(user.phone!, style: const TextStyle(color: AppColors.muted, fontSize: 13)),
                        if (user.referralCode != null)
                          Padding(
                            padding: const EdgeInsets.only(top: 6),
                            child: Container(
                              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                              decoration: BoxDecoration(color: AppColors.secondaryContainer, borderRadius: BorderRadius.circular(8)),
                              child: Text('Referral: ${user.referralCode}', style: const TextStyle(fontSize: 12, color: AppColors.primary, fontWeight: FontWeight.w600)),
                            ),
                          ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),
          _MenuSection(title: 'Account', items: [
            _MenuItem(icon: Icons.edit_outlined, label: 'Edit Profile', onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ProfileScreen()))),
            _MenuItem(icon: Icons.campaign_outlined, label: 'Announcements', onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const AnnouncementsScreen()))),
            _MenuItem(icon: Icons.card_giftcard, label: 'Refer & Earn', subtitle: '${user.referralsCount} referrals'),
          ]),
          _MenuSection(title: 'Support', items: [
            _MenuItem(icon: Icons.local_offer_outlined, label: 'Offers', onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const OffersScreen()))),
            _MenuItem(icon: Icons.help_outline, label: 'FAQ', onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const FaqScreen()))),
            _MenuItem(icon: Icons.article_outlined, label: 'Travel Blog', onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const BlogsScreen()))),
            _MenuItem(icon: Icons.mail_outline, label: 'Contact Us', onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ContactScreen()))),
          ]),
          const SizedBox(height: 8),
          Card(
            child: ListTile(
              leading: const Icon(Icons.logout, color: AppColors.alert),
              title: const Text('Logout', style: TextStyle(color: AppColors.alert, fontWeight: FontWeight.w600)),
              onTap: () async => auth.logout(),
            ),
          ),
        ],
      ),
    );
  }
}

class _MenuSection extends StatelessWidget {
  const _MenuSection({required this.title, required this.items});

  final String title;
  final List<_MenuItem> items;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.fromLTRB(4, 8, 4, 8),
          child: Text(title.toUpperCase(), style: const TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: AppColors.muted, letterSpacing: 1)),
        ),
        Card(
          child: Column(
            children: items.map((item) {
              final isLast = item == items.last;
              return Column(
                children: [
                  ListTile(
                    leading: Icon(item.icon, color: AppColors.primary),
                    title: Text(item.label, style: const TextStyle(fontWeight: FontWeight.w500)),
                    subtitle: item.subtitle != null ? Text(item.subtitle!) : null,
                    trailing: item.onTap != null ? const Icon(Icons.chevron_right, color: AppColors.muted) : null,
                    onTap: item.onTap,
                  ),
                  if (!isLast) const Divider(height: 1, indent: 56),
                ],
              );
            }).toList(),
          ),
        ),
        const SizedBox(height: 8),
      ],
    );
  }
}

class _MenuItem {
  const _MenuItem({required this.icon, required this.label, this.subtitle, this.onTap});

  final IconData icon;
  final String label;
  final String? subtitle;
  final VoidCallback? onTap;
}
