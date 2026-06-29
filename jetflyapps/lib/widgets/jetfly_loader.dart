import 'dart:math' as math;
import 'package:flutter/material.dart';
import '../theme/app_theme.dart';

/// Branded Jet Fly Airways loading indicator (rotating ring + plane on orbit).
class JetFlyLoader extends StatefulWidget {
  const JetFlyLoader({
    super.key,
    this.message,
    this.size = 72,
    this.color,
    this.light = false,
  });

  final String? message;
  final double size;
  final Color? color;
  final bool light;

  static Widget fullscreen({String? message}) {
    return _JetFlyLoaderFullscreen(message: message);
  }

  static Widget center({String? message, Color? color}) {
    return JetFlyLoader(message: message, color: color);
  }

  static Widget button({Color color = Colors.white}) {
    return _JetFlyLoaderButton(color: color);
  }

  @override
  State<JetFlyLoader> createState() => _JetFlyLoaderState();
}

class _JetFlyLoaderState extends State<JetFlyLoader> with SingleTickerProviderStateMixin {
  late final AnimationController _controller;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(vsync: this, duration: const Duration(milliseconds: 1400))..repeat();
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final accent = widget.color ?? (widget.light ? Colors.white : AppColors.primary);
    final subtitleColor = widget.light ? Colors.white70 : AppColors.muted;

    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        RotationTransition(
          turns: _controller,
          child: _JetFlyLoaderRing(size: widget.size, color: accent),
        ),
        if (widget.message != null) ...[
          const SizedBox(height: 16),
          Text(
            widget.message!,
            textAlign: TextAlign.center,
            style: TextStyle(color: subtitleColor, fontSize: 14, fontWeight: FontWeight.w500),
          ),
        ],
      ],
    );
  }
}

class _JetFlyLoaderButton extends StatefulWidget {
  const _JetFlyLoaderButton({required this.color});

  final Color color;

  @override
  State<_JetFlyLoaderButton> createState() => _JetFlyLoaderButtonState();
}

class _JetFlyLoaderButtonState extends State<_JetFlyLoaderButton> with SingleTickerProviderStateMixin {
  late final AnimationController _controller;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(vsync: this, duration: const Duration(milliseconds: 1400))..repeat();
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: 22,
      height: 22,
      child: RotationTransition(
        turns: _controller,
        child: _JetFlyLoaderRing(size: 22, color: widget.color, stroke: 2),
      ),
    );
  }
}

class _JetFlyLoaderRing extends StatelessWidget {
  const _JetFlyLoaderRing({required this.size, required this.color, this.stroke});

  final double size;
  final Color color;
  final double? stroke;

  @override
  Widget build(BuildContext context) {
    final strokeWidth = stroke ?? (size < 30 ? 2.0 : 2.5);
    final planeSize = (size * 0.28).clamp(10.0, 28.0);
    final ringRadius = size / 2 - strokeWidth;
    // Plane sits on top of the ring arc (12 o'clock), not inside the circle.
    final planeTop = (size / 2 - ringRadius) - planeSize / 2;

    return SizedBox(
      width: size,
      height: size,
      child: Stack(
        clipBehavior: Clip.none,
        children: [
          CustomPaint(
            size: Size(size, size),
            painter: _DashedRingPainter(color: color.withValues(alpha: 0.5), strokeWidth: strokeWidth),
          ),
          Positioned(
            top: planeTop,
            left: 0,
            right: 0,
            child: Center(
              child: Transform.rotate(
                angle: math.pi / 2,
                child: Icon(Icons.flight, color: color, size: planeSize),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _DashedRingPainter extends CustomPainter {
  _DashedRingPainter({required this.color, required this.strokeWidth});

  final Color color;
  final double strokeWidth;

  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = color
      ..strokeWidth = strokeWidth
      ..style = PaintingStyle.stroke
      ..strokeCap = StrokeCap.round;

    const dashCount = 12;
    final radius = size.width / 2 - strokeWidth;
    final center = Offset(size.width / 2, size.height / 2);
    final dashAngle = (2 * math.pi) / dashCount;
    const gapRatio = 0.42;

    for (var i = 0; i < dashCount; i++) {
      final start = i * dashAngle;
      final sweep = dashAngle * (1 - gapRatio);
      canvas.drawArc(
        Rect.fromCircle(center: center, radius: radius),
        start,
        sweep,
        false,
        paint,
      );
    }
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}

class _JetFlyLoaderFullscreen extends StatelessWidget {
  const _JetFlyLoaderFullscreen({this.message});

  final String? message;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        width: double.infinity,
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            colors: [AppColors.footerDeep, AppColors.bookingBlue, AppColors.primary],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
        ),
        child: SafeArea(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Text(
                'Jet Fly Airways',
                style: TextStyle(color: Colors.white, fontSize: 26, fontWeight: FontWeight.bold, letterSpacing: 0.3),
              ),
              const SizedBox(height: 8),
              const Text(
                'Fly Beyond Horizons',
                style: TextStyle(color: Colors.white70, fontSize: 13),
              ),
              const SizedBox(height: 36),
              JetFlyLoader(
                message: message ?? 'Loading your travel hub...',
                size: 88,
                light: true,
              ),
            ],
          ),
        ),
      ),
    );
  }
}
