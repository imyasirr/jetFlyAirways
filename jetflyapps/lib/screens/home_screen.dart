import 'package:flutter/material.dart';
import '../models/models.dart';
import '../theme/app_theme.dart';
import 'module_detail_screen.dart';
import 'module_list_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key, required this.homeData});

  final Map<String, dynamic> homeData;

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  late final List<ModuleInfo> _modules;

  @override
  void initState() {
    super.initState();
    final raw = widget.homeData['modules'] as Map<String, dynamic>;
    _modules = raw.entries
        .map((e) => ModuleInfo.fromEntry(e.key, e.value as Map<String, dynamic>))
        .toList();
  }

  @override
  Widget build(BuildContext context) {
    final stats = widget.homeData['stats'] as Map<String, dynamic>? ?? {};
    final featuredFlights = (widget.homeData['featured_flights'] as List? ?? [])
        .map((e) => ListingItem.fromJson(e as Map<String, dynamic>))
        .toList();

    return Scaffold(
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: 160,
            pinned: true,
            flexibleSpace: FlexibleSpaceBar(
              title: const Text('JetFly Airways'),
              background: Container(
                decoration: const BoxDecoration(
                  gradient: LinearGradient(
                    colors: [AppColors.bookingBlue, AppColors.primary],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                ),
                child: const Align(
                  alignment: Alignment.bottomRight,
                  child: Padding(
                    padding: EdgeInsets.all(16),
                    child: Icon(Icons.flight_takeoff, color: Colors.white24, size: 80),
                  ),
                ),
              ),
            ),
          ),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Book your trip', style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 4),
                  Text(
                    '${stats['flights'] ?? 0}+ flights · ${stats['hotels'] ?? 0}+ hotels · ${stats['packages'] ?? 0}+ packages',
                    style: const TextStyle(color: AppColors.muted),
                  ),
                  const SizedBox(height: 16),
                  GridView.builder(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                      crossAxisCount: 4,
                      mainAxisSpacing: 12,
                      crossAxisSpacing: 12,
                      childAspectRatio: 0.85,
                    ),
                    itemCount: _modules.length,
                    itemBuilder: (context, index) {
                      final m = _modules[index];
                      return _ModuleChip(
                        module: m,
                        onTap: () => Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (_) => ModuleListScreen(module: m.key, title: m.title),
                          ),
                        ),
                      );
                    },
                  ),
                  if (featuredFlights.isNotEmpty) ...[
                    const SizedBox(height: 24),
                    const Text('Featured Flights', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 12),
                    ...featuredFlights.map((f) => _ListingCard(
                          item: f,
                          module: 'flights',
                          onTap: () => Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (_) => ModuleDetailScreen(module: 'flights', slug: f.slug, title: f.title),
                            ),
                          ),
                        )),
                  ],
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _ModuleChip extends StatelessWidget {
  const _ModuleChip({required this.module, required this.onTap});

  final ModuleInfo module;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(12),
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: AppColors.secondaryContainer),
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(module.icon, style: const TextStyle(fontSize: 28)),
            const SizedBox(height: 6),
            Text(
              module.title.split(' ').first,
              textAlign: TextAlign.center,
              style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600),
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
            ),
          ],
        ),
      ),
    );
  }
}

class _ListingCard extends StatelessWidget {
  const _ListingCard({required this.item, required this.module, required this.onTap});

  final ListingItem item;
  final String module;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 10),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(14),
          child: Row(
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(item.title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15)),
                    const SizedBox(height: 4),
                    Text(item.subtitle, style: const TextStyle(color: AppColors.muted, fontSize: 12), maxLines: 2, overflow: TextOverflow.ellipsis),
                  ],
                ),
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text('₹${item.price.toStringAsFixed(0)}', style: const TextStyle(fontWeight: FontWeight.bold, color: AppColors.primary, fontSize: 16)),
                  const Text('per person', style: TextStyle(fontSize: 10, color: AppColors.muted)),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}
