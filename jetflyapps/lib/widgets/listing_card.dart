import 'package:flutter/material.dart';
import '../models/models.dart';
import '../theme/app_theme.dart';

class ListingCard extends StatelessWidget {
  const ListingCard({
    super.key,
    required this.item,
    required this.module,
    required this.onTap,
    this.compact = false,
  });

  final ListingItem item;
  final String module;
  final VoidCallback onTap;
  final bool compact;

  IconData get _moduleIcon => switch (module) {
        'flights' => Icons.flight,
        'hotels' => Icons.hotel,
        'packages' => Icons.card_travel,
        'buses' => Icons.directions_bus,
        'trains' => Icons.train,
        'cabs' => Icons.local_taxi,
        'visa' => Icons.article,
        'insurance' => Icons.health_and_safety,
        _ => Icons.explore,
      };

  Color get _accent => switch (module) {
        'flights' => AppColors.bookingBlue,
        'hotels' => const Color(0xFF7B1FA2),
        'packages' => const Color(0xFF00897B),
        _ => AppColors.primary,
      };

  @override
  Widget build(BuildContext context) {
    if (compact) {
      return SizedBox(
        width: 260,
        child: Card(
          clipBehavior: Clip.antiAlias,
          child: InkWell(
            onTap: onTap,
            child: Padding(
              padding: const EdgeInsets.all(14),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Icon(_moduleIcon, color: _accent, size: 20),
                      const Spacer(),
                      Text('₹${item.price.toStringAsFixed(0)}', style: TextStyle(fontWeight: FontWeight.bold, color: _accent, fontSize: 15)),
                    ],
                  ),
                  const SizedBox(height: 10),
                  Text(item.title, maxLines: 2, overflow: TextOverflow.ellipsis, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 14)),
                  const SizedBox(height: 4),
                  Text(item.subtitle, maxLines: 2, overflow: TextOverflow.ellipsis, style: const TextStyle(color: AppColors.muted, fontSize: 11)),
                ],
              ),
            ),
          ),
        ),
      );
    }

    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      clipBehavior: Clip.antiAlias,
      child: InkWell(
        onTap: onTap,
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Row(
            children: [
              Container(
                width: 48,
                height: 48,
                decoration: BoxDecoration(
                  color: _accent.withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(_moduleIcon, color: _accent),
              ),
              const SizedBox(width: 14),
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
              const SizedBox(width: 8),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text('₹${item.price.toStringAsFixed(0)}', style: TextStyle(fontWeight: FontWeight.bold, color: _accent, fontSize: 17)),
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
