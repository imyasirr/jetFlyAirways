import 'dart:async';

import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import '../widgets/jetfly_loader.dart';

class BannerCarousel extends StatefulWidget {
  const BannerCarousel({
    super.key,
    required this.banners,
    this.height = 200,
    this.horizontalPadding = 16,
    this.borderRadius = 14,
    this.showIndicators = true,
    this.overlayIndicators = false,
    this.autoPlay = true,
    this.autoPlayInterval = const Duration(seconds: 4),
  });

  final List<Map<String, dynamic>> banners;
  final double height;
  final double horizontalPadding;
  final double borderRadius;
  final bool showIndicators;
  final bool overlayIndicators;
  final bool autoPlay;
  final Duration autoPlayInterval;

  @override
  State<BannerCarousel> createState() => _BannerCarouselState();
}

class _BannerCarouselState extends State<BannerCarousel> {
  final _controller = PageController();
  Timer? _autoPlayTimer;
  int _index = 0;

  @override
  void initState() {
    super.initState();
    _startAutoPlay();
  }

  @override
  void didUpdateWidget(BannerCarousel oldWidget) {
    super.didUpdateWidget(oldWidget);
    final oldUrls = oldWidget.banners.map((b) => b['image_url']).join('|');
    final newUrls = widget.banners.map((b) => b['image_url']).join('|');
    if (oldUrls != newUrls || oldWidget.autoPlay != widget.autoPlay) {
      _startAutoPlay();
    }
  }

  void _startAutoPlay() {
    _autoPlayTimer?.cancel();
    if (!widget.autoPlay || widget.banners.length <= 1) return;

    _autoPlayTimer = Timer.periodic(widget.autoPlayInterval, (_) => _advanceSlide());
  }

  Future<void> _advanceSlide() async {
    if (!mounted || !_controller.hasClients || widget.banners.length <= 1) return;

    final next = (_index + 1) % widget.banners.length;
    await _controller.animateToPage(
      next,
      duration: const Duration(milliseconds: 500),
      curve: Curves.easeInOut,
    );
  }

  void _onPageChanged(int i) {
    setState(() => _index = i);
    _startAutoPlay();
  }

  @override
  void dispose() {
    _autoPlayTimer?.cancel();
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (widget.banners.isEmpty) return const SizedBox.shrink();

    final carousel = PageView.builder(
      controller: _controller,
      onPageChanged: _onPageChanged,
      itemCount: widget.banners.length,
      itemBuilder: (context, i) => _BannerSlide(
        banner: widget.banners[i],
        horizontalPadding: widget.horizontalPadding,
        borderRadius: widget.borderRadius,
      ),
    );

    final pageView = widget.overlayIndicators && widget.banners.length > 1
        ? Stack(
            fit: StackFit.expand,
            children: [
              carousel,
              Positioned(
                left: 0,
                right: 0,
                bottom: 12,
                child: BannerHeroIndicators(count: widget.banners.length, index: _index),
              ),
            ],
          )
        : carousel;

    return RepaintBoundary(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          SizedBox(height: widget.height, width: double.infinity, child: pageView),
          if (widget.showIndicators && widget.banners.length > 1 && !widget.overlayIndicators) ...[
            const SizedBox(height: 10),
            _PageIndicators(count: widget.banners.length, index: _index),
          ],
        ],
      ),
    );
  }
}

class _BannerSlide extends StatelessWidget {
  const _BannerSlide({
    required this.banner,
    required this.horizontalPadding,
    required this.borderRadius,
  });

  final Map<String, dynamic> banner;
  final double horizontalPadding;
  final double borderRadius;

  @override
  Widget build(BuildContext context) {
    final imageUrl = banner['image_url'] as String?;
    final title = banner['title'] as String? ?? '';
    final subtitle = banner['subtitle'] as String?;
    final cacheWidth = (MediaQuery.sizeOf(context).width * MediaQuery.devicePixelRatioOf(context)).round();

    final content = Stack(
      fit: StackFit.expand,
      children: [
        if (imageUrl != null && imageUrl.isNotEmpty)
          CachedNetworkImage(
            imageUrl: imageUrl,
            fit: BoxFit.cover,
            memCacheWidth: cacheWidth,
            fadeInDuration: const Duration(milliseconds: 200),
            placeholder: (_, __) => _gradientFallback(
              child: Center(child: JetFlyLoader(size: 36, color: Colors.white, light: true)),
            ),
            errorWidget: (_, __, ___) => _gradientFallback(),
          )
        else
          _gradientFallback(),
        if (title.isNotEmpty || (subtitle != null && subtitle.isNotEmpty))
          Container(
            decoration: BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.topCenter,
                end: Alignment.bottomCenter,
                colors: [
                  Colors.black.withValues(alpha: 0.1),
                  Colors.black.withValues(alpha: 0.55),
                ],
              ),
            ),
          )
        else
          Container(
            decoration: BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.topCenter,
                end: Alignment.bottomCenter,
                colors: [
                  Colors.black.withValues(alpha: 0.05),
                  Colors.black.withValues(alpha: 0.2),
                ],
              ),
            ),
          ),
        if (title.isNotEmpty || (subtitle != null && subtitle.isNotEmpty))
          Padding(
            padding: const EdgeInsets.fromLTRB(20, 20, 20, 28),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                if (title.isNotEmpty)
                  Text(
                    title,
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                      height: 1.2,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                if (subtitle != null && subtitle.isNotEmpty) ...[
                  const SizedBox(height: 6),
                  Text(
                    subtitle,
                    style: const TextStyle(color: Colors.white70, fontSize: 13),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
              ],
            ),
          ),
      ],
    );

    if (horizontalPadding == 0 && borderRadius == 0) {
      return content;
    }

    return Padding(
      padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(borderRadius),
        child: content,
      ),
    );
  }

  Widget _gradientFallback({Widget? child}) {
    return Container(
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          colors: [AppColors.bookingBlue, AppColors.primary, AppColors.primaryContainer],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
      ),
      child: child,
    );
  }
}

class _PageIndicators extends StatelessWidget {
  const _PageIndicators({required this.count, required this.index});

  final int count;
  final int index;

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: List.generate(count, (i) {
        final active = i == index;
        return AnimatedContainer(
          duration: const Duration(milliseconds: 250),
          margin: const EdgeInsets.symmetric(horizontal: 3),
          width: active ? 18 : 7,
          height: 7,
          decoration: BoxDecoration(
            color: active ? AppColors.primary : AppColors.muted.withValues(alpha: 0.35),
            borderRadius: BorderRadius.circular(8),
          ),
        );
      }),
    );
  }
}

/// Dot indicators overlaid on hero banners (for top carousel).
class BannerHeroIndicators extends StatelessWidget {
  const BannerHeroIndicators({super.key, required this.count, required this.index});

  final int count;
  final int index;

  @override
  Widget build(BuildContext context) {
    if (count <= 1) return const SizedBox.shrink();

    return Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: List.generate(count, (i) {
        final active = i == index;
        return AnimatedContainer(
          duration: const Duration(milliseconds: 250),
          margin: const EdgeInsets.symmetric(horizontal: 3),
          width: active ? 22 : 7,
          height: 7,
          decoration: BoxDecoration(
            color: active ? Colors.white : Colors.white.withValues(alpha: 0.45),
            borderRadius: BorderRadius.circular(8),
          ),
        );
      }),
    );
  }
}
