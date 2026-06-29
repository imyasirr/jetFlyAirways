import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import '../models/models.dart';
import '../theme/app_theme.dart';
import '../utils/media_url.dart';
import '../utils/trust_icons.dart';
import '../widgets/banner_carousel.dart';
import '../widgets/listing_card.dart';
import '../widgets/module_tile.dart';
import '../widgets/section_title.dart';
import 'destination_detail_screen.dart';
import 'module_detail_screen.dart';
import 'module_list_screen.dart';
import 'support_screens.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key, required this.homeData, this.onRefresh});

  final Map<String, dynamic> homeData;
  final Future<void> Function()? onRefresh;

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  late final List<ModuleInfo> _modules;

  @override
  void initState() {
    super.initState();
    final raw = widget.homeData['modules'] as Map<String, dynamic>? ?? {};
    _modules = raw.entries
        .map((e) => ModuleInfo.fromEntry(e.key, e.value as Map<String, dynamic>))
        .toList();
  }

  List<ListingItem> _items(String key) => (widget.homeData[key] as List? ?? [])
      .map((e) => ListingItem.fromJson(e as Map<String, dynamic>))
      .toList();

  void _openModule(String key, String title) {
    Navigator.push(context, MaterialPageRoute(builder: (_) => ModuleListScreen(module: key, title: title)));
  }

  void _openDetail(String module, ListingItem item) {
    Navigator.push(
      context,
      MaterialPageRoute(builder: (_) => ModuleDetailScreen(module: module, slug: item.slug, title: item.title)),
    );
  }

  @override
  Widget build(BuildContext context) {
    final stats = widget.homeData['stats'] as Map<String, dynamic>? ?? {};
    final banners = (widget.homeData['banners'] as List? ?? []).cast<Map<String, dynamic>>();
    final offers = (widget.homeData['offers'] as List? ?? []).cast<Map<String, dynamic>>();
    final testimonials = (widget.homeData['testimonials'] as List? ?? []).cast<Map<String, dynamic>>();
    final trustCards = (widget.homeData['trust_cards'] as List? ?? []).cast<Map<String, dynamic>>();
    final destinations = (widget.homeData['popular_destinations'] as List? ?? []).cast<Map<String, dynamic>>();
    final legacyDestinations = (widget.homeData['top_destinations'] as List? ?? []).cast<String>();

    final featuredFlights = _items('featured_flights');
    final featuredHotels = _items('featured_hotels');
    final featuredPackages = _items('featured_packages');

    final heroBanners = banners.isNotEmpty
        ? banners
        : const [
            {
              'title': 'Fly Beyond Horizons',
              'subtitle': 'Discover flights, hotels and packages at great prices.',
              'image_url': null,
            },
          ];

    return Scaffold(
      appBar: AppBar(
        title: const Text('Jet Fly Airways'),
        actions: [
          IconButton(
            icon: const Icon(Icons.local_offer_outlined),
            onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const OffersScreen())),
          ),
          IconButton(
            icon: const Icon(Icons.help_outline),
            onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const FaqScreen())),
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: widget.onRefresh ?? () async {},
        child: CustomScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          slivers: [
            SliverToBoxAdapter(
              child: BannerCarousel(
                banners: heroBanners,
                height: 220,
                horizontalPadding: 0,
                borderRadius: 0,
                showIndicators: true,
                overlayIndicators: true,
              ),
            ),
            SliverToBoxAdapter(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Padding(
                    padding: const EdgeInsets.fromLTRB(16, 20, 16, 0),
                    child: SingleChildScrollView(
                      scrollDirection: Axis.horizontal,
                      child: Row(
                        children: [
                          _StatChip(label: 'Flights', value: '${stats['flights'] ?? 0}', icon: Icons.flight),
                          _StatChip(label: 'Hotels', value: '${stats['hotels'] ?? 0}', icon: Icons.hotel),
                          _StatChip(label: 'Packages', value: '${stats['packages'] ?? 0}', icon: Icons.card_travel),
                          _StatChip(label: 'Bookings', value: '${stats['bookings'] ?? 0}', icon: Icons.confirmation_number),
                        ],
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const SectionTitle(title: 'Book your trip', subtitle: 'Flights, hotels, packages & more'),
                        GridView.builder(
                          shrinkWrap: true,
                          physics: const NeverScrollableScrollPhysics(),
                          gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                            crossAxisCount: 4,
                            mainAxisSpacing: 12,
                            crossAxisSpacing: 12,
                            childAspectRatio: 0.82,
                          ),
                          itemCount: _modules.length,
                          itemBuilder: (context, index) {
                            final m = _modules[index];
                            return ModuleTile(module: m, onTap: () => _openModule(m.key, m.title));
                          },
                        ),
                        if (offers.isNotEmpty) ...[
                          const SizedBox(height: 24),
                          SectionTitle(
                            title: 'Special Offers',
                            onSeeAll: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const OffersScreen())),
                          ),
                          SizedBox(
                            height: 110,
                            child: ListView.separated(
                              scrollDirection: Axis.horizontal,
                              itemCount: offers.length,
                              separatorBuilder: (_, __) => const SizedBox(width: 12),
                              itemBuilder: (context, i) {
                                final o = offers[i];
                                return Container(
                                  width: 240,
                                  padding: const EdgeInsets.all(16),
                                  decoration: BoxDecoration(
                                    gradient: const LinearGradient(colors: [AppColors.promoYellow, Color(0xFFFFF3B0)]),
                                    borderRadius: BorderRadius.circular(14),
                                  ),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      const Icon(Icons.local_offer, color: AppColors.bookingBlue),
                                      const SizedBox(height: 8),
                                      Text(o['title'] as String? ?? '', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14), maxLines: 2, overflow: TextOverflow.ellipsis),
                                    ],
                                  ),
                                );
                              },
                            ),
                          ),
                        ],
                        if (destinations.isNotEmpty) ...[
                          const SizedBox(height: 24),
                          const SectionTitle(title: 'Popular Destinations', subtitle: 'Trending this season'),
                          SizedBox(
                            height: 210,
                            child: ListView.separated(
                              scrollDirection: Axis.horizontal,
                              itemCount: destinations.length,
                              separatorBuilder: (_, __) => const SizedBox(width: 12),
                              itemBuilder: (context, i) {
                                final dest = destinations[i];
                                final name = dest['name'] as String? ?? '';
                                final slug = dest['slug'] as String? ?? '';
                                final tagLine = dest['tag_line'] as String?;
                                final bannerUrl = dest['banner_url'] as String? ?? MediaUrl.resolve(dest['banner'] as String?);

                                return GestureDetector(
                                  onTap: () {
                                    if (slug.isEmpty) return;
                                    Navigator.push(
                                      context,
                                      MaterialPageRoute(
                                        builder: (_) => DestinationDetailScreen(slug: slug, preview: dest),
                                      ),
                                    );
                                  },
                                  child: SizedBox(
                                    width: 170,
                                    child: ClipRRect(
                                      borderRadius: BorderRadius.circular(16),
                                      child: Stack(
                                        fit: StackFit.expand,
                                        children: [
                                          if (bannerUrl != null && bannerUrl.isNotEmpty)
                                            CachedNetworkImage(imageUrl: bannerUrl, fit: BoxFit.cover, errorWidget: (_, __, ___) => _destinationFallback())
                                          else
                                            _destinationFallback(),
                                          Container(
                                            decoration: BoxDecoration(
                                              gradient: LinearGradient(
                                                begin: Alignment.topCenter,
                                                end: Alignment.bottomCenter,
                                                colors: [Colors.transparent, Colors.black.withValues(alpha: 0.72)],
                                              ),
                                            ),
                                          ),
                                          Padding(
                                            padding: const EdgeInsets.all(14),
                                            child: Column(
                                              mainAxisAlignment: MainAxisAlignment.end,
                                              crossAxisAlignment: CrossAxisAlignment.start,
                                              children: [
                                                Text(name, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 15), maxLines: 2, overflow: TextOverflow.ellipsis),
                                                if (tagLine != null && tagLine.isNotEmpty)
                                                  Text(tagLine, style: const TextStyle(color: Colors.white70, fontSize: 11), maxLines: 1, overflow: TextOverflow.ellipsis),
                                              ],
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                  ),
                                );
                              },
                            ),
                          ),
                        ] else if (legacyDestinations.isNotEmpty) ...[
                          const SizedBox(height: 24),
                          const SectionTitle(title: 'Top Destinations'),
                          Wrap(
                            spacing: 8,
                            runSpacing: 8,
                            children: legacyDestinations.map((d) => Chip(
                              label: Text(d),
                              backgroundColor: AppColors.secondaryContainer,
                              labelStyle: const TextStyle(color: AppColors.bookingBlue, fontWeight: FontWeight.w600),
                            )).toList(),
                          ),
                        ],
                        _buildFeaturedSection('Featured Flights', 'flights', featuredFlights),
                        _buildFeaturedSection('Popular Hotels', 'hotels', featuredHotels),
                        _buildFeaturedSection('Travel Packages', 'packages', featuredPackages),
                        if (trustCards.isNotEmpty) ...[
                          const SizedBox(height: 24),
                          Padding(
                            padding: const EdgeInsets.only(bottom: 12),
                            child: Row(
                              children: [
                                ClipRRect(
                                  borderRadius: BorderRadius.circular(10),
                                  child: Image.asset(
                                    'assets/icon/app_icon.png',
                                    width: 40,
                                    height: 40,
                                    fit: BoxFit.cover,
                                  ),
                                ),
                                const SizedBox(width: 12),
                                const Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        'Why Jet Fly Airways?',
                                        style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppColors.onSurface),
                                      ),
                                      Text(
                                        'Trusted travel partner for every journey',
                                        style: TextStyle(fontSize: 12, color: AppColors.muted),
                                      ),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                          ),
                          ...trustCards.map((c) => Card(
                                margin: const EdgeInsets.only(bottom: 10),
                                child: ListTile(
                                  leading: CircleAvatar(
                                    backgroundColor: AppColors.secondaryContainer,
                                    child: Icon(
                                      trustCardIcon(c['icon'] as String?),
                                      color: AppColors.primary,
                                      size: 22,
                                    ),
                                  ),
                                  title: Text(c['title'] as String? ?? '', style: const TextStyle(fontWeight: FontWeight.w600)),
                                  subtitle: Text(c['description'] as String? ?? ''),
                                ),
                              )),
                        ],
                        if (testimonials.isNotEmpty) ...[
                          const SizedBox(height: 24),
                          const SectionTitle(title: 'What travellers say'),
                          SizedBox(
                            height: 168,
                            child: ListView.separated(
                              scrollDirection: Axis.horizontal,
                              itemCount: testimonials.length,
                              separatorBuilder: (_, __) => const SizedBox(width: 12),
                              itemBuilder: (context, i) {
                                final t = testimonials[i];
                                final name = t['name'] as String? ?? '';
                                final role = t['role'] as String?;
                                final photoUrl = _testimonialPhotoUrl(t);

                                return Container(
                                  width: 280,
                                  padding: const EdgeInsets.all(16),
                                  decoration: BoxDecoration(
                                    color: Colors.white,
                                    borderRadius: BorderRadius.circular(14),
                                    border: Border.all(color: AppColors.secondaryContainer),
                                  ),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Row(
                                        children: List.generate(5, (s) => Icon(
                                              s < ((t['rating'] as num?)?.toInt() ?? 5) ? Icons.star : Icons.star_border,
                                              color: AppColors.promoYellow,
                                              size: 16,
                                            )),
                                      ),
                                      const SizedBox(height: 8),
                                      Expanded(
                                        child: Text(
                                          '"${t['content'] as String? ?? ''}"',
                                          style: const TextStyle(fontSize: 13, color: AppColors.muted, fontStyle: FontStyle.italic),
                                          maxLines: 3,
                                          overflow: TextOverflow.ellipsis,
                                        ),
                                      ),
                                      const SizedBox(height: 10),
                                      Row(
                                        children: [
                                          _TestimonialAvatar(photoUrl: photoUrl, name: name),
                                          const SizedBox(width: 10),
                                          Expanded(
                                            child: Column(
                                              crossAxisAlignment: CrossAxisAlignment.start,
                                              children: [
                                                Text(name, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                                                if (role != null && role.isNotEmpty)
                                                  Text(role, style: const TextStyle(fontSize: 11, color: AppColors.muted), maxLines: 1, overflow: TextOverflow.ellipsis),
                                              ],
                                            ),
                                          ),
                                        ],
                                      ),
                                    ],
                                  ),
                                );
                              },
                            ),
                          ),
                        ],
                        const SizedBox(height: 24),
                        Card(
                          child: ListTile(
                            leading: const CircleAvatar(backgroundColor: AppColors.secondaryContainer, child: Icon(Icons.article_outlined, color: AppColors.primary)),
                            title: const Text('Travel Blog', style: TextStyle(fontWeight: FontWeight.w600)),
                            subtitle: const Text('Tips, guides & inspiration'),
                            trailing: const Icon(Icons.chevron_right),
                            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const BlogsScreen())),
                          ),
                        ),
                        const SizedBox(height: 32),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  String? _testimonialPhotoUrl(Map<String, dynamic> t) {
    final direct = t['photo_url'] as String?;
    if (direct != null && direct.isNotEmpty) return direct;
    return MediaUrl.resolve(t['photo'] as String?);
  }

  Widget _destinationFallback() {
    return Container(
      decoration: const BoxDecoration(
        gradient: LinearGradient(colors: [AppColors.bookingBlue, AppColors.primary]),
      ),
      child: const Center(child: Icon(Icons.place_outlined, color: Colors.white70, size: 36)),
    );
  }

  Widget _buildFeaturedSection(String title, String module, List<ListingItem> items) {
    if (items.isEmpty) {
      final moduleTitle = _modules.firstWhere((m) => m.key == module, orElse: () => ModuleInfo(key: module, title: title, icon: '✈')).title;
      return Padding(
        padding: const EdgeInsets.only(top: 24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            SectionTitle(title: title, onSeeAll: () => _openModule(module, moduleTitle)),
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(14),
                border: Border.all(color: AppColors.secondaryContainer),
              ),
              child: Column(
                children: [
                  Icon(Icons.search_off, color: AppColors.muted.withValues(alpha: 0.6), size: 40),
                  const SizedBox(height: 8),
                  Text('No $title available yet', style: const TextStyle(color: AppColors.muted)),
                  const SizedBox(height: 12),
                  OutlinedButton(onPressed: () => _openModule(module, moduleTitle), child: Text('Browse $moduleTitle')),
                ],
              ),
            ),
          ],
        ),
      );
    }

    return Padding(
      padding: const EdgeInsets.only(top: 24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SectionTitle(title: title, onSeeAll: () => _openModule(module, title)),
          if (items.length <= 2)
            ...items.map((f) => ListingCard(item: f, module: module, onTap: () => _openDetail(module, f)))
          else
            SizedBox(
              height: 140,
              child: ListView.separated(
                scrollDirection: Axis.horizontal,
                itemCount: items.length,
                separatorBuilder: (_, __) => const SizedBox(width: 12),
                itemBuilder: (context, i) => ListingCard(item: items[i], module: module, compact: true, onTap: () => _openDetail(module, items[i])),
              ),
            ),
        ],
      ),
    );
  }
}

class _TestimonialAvatar extends StatelessWidget {
  const _TestimonialAvatar({required this.photoUrl, required this.name});

  final String? photoUrl;
  final String name;

  @override
  Widget build(BuildContext context) {
    final initial = name.isNotEmpty ? name.substring(0, 1).toUpperCase() : '?';

    if (photoUrl == null || photoUrl!.isEmpty) {
      return CircleAvatar(
        radius: 20,
        backgroundColor: AppColors.secondaryContainer,
        child: Text(initial, style: const TextStyle(fontWeight: FontWeight.bold, color: AppColors.primary)),
      );
    }

    return ClipOval(
      child: CachedNetworkImage(
        imageUrl: photoUrl!,
        width: 40,
        height: 40,
        fit: BoxFit.cover,
        memCacheWidth: 120,
        fadeInDuration: const Duration(milliseconds: 200),
        placeholder: (_, __) => CircleAvatar(
          radius: 20,
          backgroundColor: AppColors.secondaryContainer,
          child: Text(initial, style: const TextStyle(fontWeight: FontWeight.bold, color: AppColors.primary, fontSize: 14)),
        ),
        errorWidget: (_, __, ___) => CircleAvatar(
          radius: 20,
          backgroundColor: AppColors.secondaryContainer,
          child: Text(initial, style: const TextStyle(fontWeight: FontWeight.bold, color: AppColors.primary)),
        ),
      ),
    );
  }
}

class _StatChip extends StatelessWidget {
  const _StatChip({required this.label, required this.value, required this.icon});

  final String label;
  final String value;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(right: 10),
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: AppColors.secondaryContainer),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 18, color: AppColors.primary),
          const SizedBox(width: 8),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(value, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15)),
              Text(label, style: const TextStyle(fontSize: 10, color: AppColors.muted)),
            ],
          ),
        ],
      ),
    );
  }
}
