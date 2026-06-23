import 'package:flutter/material.dart';
import '../models/models.dart';
import '../theme/app_theme.dart';
import '../widgets/listing_card.dart';
import '../widgets/module_tile.dart';
import '../widgets/section_title.dart';
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
  final _bannerController = PageController();

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
    final destinations = (widget.homeData['top_destinations'] as List? ?? []).cast<String>();

    final featuredFlights = _items('featured_flights');
    final featuredHotels = _items('featured_hotels');
    final featuredPackages = _items('featured_packages');

    return Scaffold(
      body: RefreshIndicator(
        onRefresh: widget.onRefresh ?? () async {},
        child: CustomScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          slivers: [
            SliverAppBar(
              expandedHeight: 200,
              pinned: true,
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
              flexibleSpace: FlexibleSpaceBar(
                title: const Text('Jet Fly Airways', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                background: Container(
                  decoration: const BoxDecoration(
                    gradient: LinearGradient(
                      colors: [AppColors.footerDeep, AppColors.bookingBlue, AppColors.primary],
                      begin: Alignment.topLeft,
                      end: Alignment.bottomRight,
                    ),
                  ),
                  child: Stack(
                    children: [
                      Positioned(
                        right: -20,
                        bottom: -10,
                        child: Icon(Icons.flight, color: Colors.white.withValues(alpha: 0.12), size: 140),
                      ),
                      const Positioned(
                        left: 20,
                        bottom: 56,
                        right: 20,
                        child: Text(
                          'Your journey starts here',
                          style: TextStyle(color: Colors.white70, fontSize: 14),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
            SliverToBoxAdapter(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  if (banners.isNotEmpty) ...[
                    const SizedBox(height: 16),
                    SizedBox(
                      height: 150,
                      child: PageView.builder(
                        controller: _bannerController,
                        itemCount: banners.length,
                        itemBuilder: (context, i) {
                          final b = banners[i];
                          return Padding(
                            padding: const EdgeInsets.symmetric(horizontal: 16),
                            child: Card(
                              clipBehavior: Clip.antiAlias,
                              child: Container(
                                decoration: BoxDecoration(
                                  gradient: LinearGradient(
                                    colors: [AppColors.primary, AppColors.primaryContainer],
                                    begin: Alignment.topLeft,
                                    end: Alignment.bottomRight,
                                  ),
                                ),
                                padding: const EdgeInsets.all(20),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  children: [
                                    Text(b['title'] as String? ?? '', style: const TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
                                    if (b['subtitle'] != null) ...[
                                      const SizedBox(height: 6),
                                      Text(b['subtitle'] as String, style: const TextStyle(color: Colors.white70, fontSize: 13), maxLines: 2, overflow: TextOverflow.ellipsis),
                                    ],
                                  ],
                                ),
                              ),
                            ),
                          );
                        },
                      ),
                    ),
                  ],
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
                          const SectionTitle(title: 'Top Destinations'),
                          Wrap(
                            spacing: 8,
                            runSpacing: 8,
                            children: destinations.map((d) => Chip(
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
                          const SectionTitle(title: 'Why Jet Fly Airways?'),
                          ...trustCards.map((c) => Card(
                                margin: const EdgeInsets.only(bottom: 10),
                                child: ListTile(
                                  leading: CircleAvatar(
                                    backgroundColor: AppColors.secondaryContainer,
                                    child: Text(c['icon'] as String? ?? '✓', style: const TextStyle(fontSize: 18)),
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
                            height: 150,
                            child: ListView.separated(
                              scrollDirection: Axis.horizontal,
                              itemCount: testimonials.length,
                              separatorBuilder: (_, __) => const SizedBox(width: 12),
                              itemBuilder: (context, i) {
                                final t = testimonials[i];
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
                                      Expanded(child: Text(t['content'] as String? ?? '', style: const TextStyle(fontSize: 13, color: AppColors.muted), maxLines: 3, overflow: TextOverflow.ellipsis)),
                                      const SizedBox(height: 8),
                                      Text(t['name'] as String? ?? '', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
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
