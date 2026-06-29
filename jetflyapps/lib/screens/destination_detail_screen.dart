import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../services/api_service.dart';
import '../services/travel_repository.dart';
import '../theme/app_theme.dart';
import '../utils/media_url.dart';
import '../widgets/empty_state.dart';
import '../widgets/jetfly_loader.dart';
import 'module_list_screen.dart';

class DestinationDetailScreen extends StatefulWidget {
  const DestinationDetailScreen({super.key, required this.slug, this.preview});

  final String slug;
  final Map<String, dynamic>? preview;

  @override
  State<DestinationDetailScreen> createState() => _DestinationDetailScreenState();
}

class _DestinationDetailScreenState extends State<DestinationDetailScreen> {
  Map<String, dynamic>? _data;
  bool _loading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    if (widget.preview != null) {
      _data = widget.preview;
      _loading = false;
      _loadDetail(silent: true);
    } else {
      _loadDetail();
    }
  }

  Future<void> _loadDetail({bool silent = false}) async {
    if (!silent) setState(() { _loading = true; _error = null; });
    try {
      final data = await TravelRepository(context.read<ApiService>()).getDestination(widget.slug);
      if (mounted) setState(() => _data = data);
    } catch (e) {
      if (!silent && mounted) setState(() => _error = e.toString());
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  String? _imageUrl(dynamic value) {
    if (value is String && value.isNotEmpty) {
      return value.startsWith('http') ? value : MediaUrl.resolve(value);
    }
    return null;
  }

  String _plainText(String? html) {
    if (html == null || html.isEmpty) return '';
    return html
        .replaceAll(RegExp(r'<br\s*/?>', caseSensitive: false), '\n')
        .replaceAll(RegExp(r'</p>', caseSensitive: false), '\n\n')
        .replaceAll(RegExp(r'<[^>]*>'), '')
        .replaceAll('&nbsp;', ' ')
        .replaceAll('&amp;', '&')
        .replaceAll('&lt;', '<')
        .replaceAll('&gt;', '>')
        .replaceAll(RegExp(r'\n{3,}'), '\n\n')
        .trim();
  }

  Widget _textSection(String title, String? html) {
    final text = _plainText(html);
    if (text.isEmpty) return const SizedBox.shrink();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const SizedBox(height: 20),
        Text(title, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
        const SizedBox(height: 10),
        Text(text, style: const TextStyle(fontSize: 15, height: 1.6, color: AppColors.onSurface)),
      ],
    );
  }

  @override
  Widget build(BuildContext context) {
    if (_loading && _data == null) {
      return Scaffold(appBar: AppBar(), body: Center(child: JetFlyLoader.center(message: 'Loading destination...')));
    }

    if (_error != null && _data == null) {
      return Scaffold(
        appBar: AppBar(),
        body: EmptyState(icon: Icons.travel_explore, title: 'Destination unavailable', subtitle: _error, actionLabel: 'Retry', onAction: _loadDetail),
      );
    }

    final d = _data!;
    final name = d['name'] as String? ?? 'Destination';
    final tagLine = d['tag_line'] as String?;
    final bestSeason = d['best_season'] as String?;
    final description = d['description'] as String?;
    final details = d['details'] as String?;
    final bannerUrl = _imageUrl(d['banner_url']) ?? _imageUrl(d['banner']);
    final packageDestination = d['package_destination'] as String? ?? name;
    final gallery = (d['gallery'] as List? ?? []).cast<Map<String, dynamic>>();

    return Scaffold(
      body: RefreshIndicator(
        onRefresh: _loadDetail,
        child: CustomScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          slivers: [
            SliverAppBar(
              expandedHeight: 280,
              pinned: true,
              stretch: true,
              flexibleSpace: FlexibleSpaceBar(
                title: Text(name, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                background: Stack(
                  fit: StackFit.expand,
                  children: [
                    if (bannerUrl != null)
                      CachedNetworkImage(imageUrl: bannerUrl, fit: BoxFit.cover, errorWidget: (_, __, ___) => _heroFallback())
                    else
                      _heroFallback(),
                    Container(
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          begin: Alignment.topCenter,
                          end: Alignment.bottomCenter,
                          colors: [Colors.black.withValues(alpha: 0.1), Colors.black.withValues(alpha: 0.65)],
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    if (tagLine != null && tagLine.isNotEmpty)
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(color: AppColors.secondaryContainer, borderRadius: BorderRadius.circular(20)),
                        child: Text(tagLine, style: const TextStyle(color: AppColors.bookingBlue, fontWeight: FontWeight.w600, fontSize: 12)),
                      ),
                    if (bestSeason != null && bestSeason.isNotEmpty) ...[
                      const SizedBox(height: 12),
                      Row(
                        children: [
                          const Icon(Icons.calendar_month_outlined, size: 18, color: AppColors.primary),
                          const SizedBox(width: 8),
                          Text(bestSeason, style: const TextStyle(color: AppColors.muted, fontWeight: FontWeight.w500)),
                        ],
                      ),
                    ],
                    _textSection('Overview', description),
                    if (gallery.isNotEmpty) ...[
                      const SizedBox(height: 28),
                      const Text('Photo gallery', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                      const SizedBox(height: 12),
                      SizedBox(
                        height: 160,
                        child: ListView.separated(
                          scrollDirection: Axis.horizontal,
                          itemCount: gallery.length,
                          separatorBuilder: (_, __) => const SizedBox(width: 12),
                          itemBuilder: (context, i) {
                            final image = gallery[i];
                            final url = _imageUrl(image['image_url']) ?? _imageUrl(image['image']);
                            return ClipRRect(
                              borderRadius: BorderRadius.circular(14),
                              child: AspectRatio(
                                aspectRatio: 1.2,
                                child: url != null
                                    ? CachedNetworkImage(imageUrl: url, fit: BoxFit.cover, errorWidget: (_, __, ___) => _galleryFallback())
                                    : _galleryFallback(),
                              ),
                            );
                          },
                        ),
                      ),
                    ],
                    _textSection('Travel details', details),
                    const SizedBox(height: 28),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton.icon(
                        onPressed: () => Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (_) => ModuleListScreen(
                              module: 'packages',
                              title: 'Holiday Packages',
                              initialFilters: {'destination': packageDestination},
                            ),
                          ),
                        ),
                        icon: const Icon(Icons.card_travel),
                        label: const Text('View holiday packages'),
                      ),
                    ),
                    const SizedBox(height: 32),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _heroFallback() {
    return Container(
      decoration: const BoxDecoration(
        gradient: LinearGradient(colors: [AppColors.footerDeep, AppColors.bookingBlue, AppColors.primary]),
      ),
      child: const Center(child: Icon(Icons.travel_explore, color: Colors.white54, size: 72)),
    );
  }

  Widget _galleryFallback() {
    return Container(color: AppColors.secondaryContainer, child: const Icon(Icons.image_outlined, color: AppColors.muted));
  }
}
