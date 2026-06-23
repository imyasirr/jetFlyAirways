import 'package:flutter/material.dart';
import '../models/models.dart';
import '../theme/app_theme.dart';

class BookingSuccessScreen extends StatelessWidget {
  const BookingSuccessScreen({super.key, required this.booking, required this.paid});

  final BookingModel booking;
  final bool paid;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Booking Confirmed')),
      body: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              paid ? Icons.check_circle : Icons.schedule,
              size: 80,
              color: paid ? AppColors.success : AppColors.primary,
            ),
            const SizedBox(height: 24),
            Text(
              paid ? 'Payment Successful!' : 'Booking Placed!',
              style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 12),
            Text('Booking Code: ${booking.bookingCode}', style: const TextStyle(fontSize: 16, color: AppColors.muted)),
            const SizedBox(height: 8),
            Text('₹${booking.totalAmount.toStringAsFixed(0)}', style: const TextStyle(fontSize: 28, fontWeight: FontWeight.bold, color: AppColors.primary)),
            const SizedBox(height: 8),
            Text(
              paid ? 'Your ticket is confirmed.' : 'Payment pending. Check your email for payment link.',
              textAlign: TextAlign.center,
              style: const TextStyle(color: AppColors.muted),
            ),
            const SizedBox(height: 32),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () => Navigator.popUntil(context, (route) => route.isFirst),
                child: const Text('Back to Home'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
