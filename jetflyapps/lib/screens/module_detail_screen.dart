import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:razorpay_flutter/razorpay_flutter.dart';
import '../models/models.dart';
import '../providers/auth_provider.dart';
import '../services/api_service.dart';
import '../services/travel_repository.dart';
import '../theme/app_theme.dart';
import 'booking_success_screen.dart';

class ModuleDetailScreen extends StatefulWidget {
  const ModuleDetailScreen({
    super.key,
    required this.module,
    required this.slug,
    required this.title,
  });

  final String module;
  final String slug;
  final String title;

  @override
  State<ModuleDetailScreen> createState() => _ModuleDetailScreenState();
}

class _ModuleDetailScreenState extends State<ModuleDetailScreen> {
  late final TravelRepository _repo;
  DetailItem? _item;
  double _unitPrice = 0;
  bool _bookable = true;
  bool _inWishlist = false;
  bool _loading = true;

  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _phoneController = TextEditingController();
  final _couponController = TextEditingController();
  int _travellers = 1;
  DateTime _travelDate = DateTime.now().add(const Duration(days: 7));
  bool _booking = false;

  late Razorpay _razorpay;
  BookingModel? _pendingBooking;

  @override
  void initState() {
    super.initState();
    _repo = TravelRepository(context.read<ApiService>());
    _razorpay = Razorpay();
    _razorpay.on(Razorpay.EVENT_PAYMENT_SUCCESS, _onPaymentSuccess);
    _razorpay.on(Razorpay.EVENT_PAYMENT_ERROR, _onPaymentError);
    _load();
    _prefillUser();
  }

  void _prefillUser() {
    final user = context.read<AuthProvider>().user;
    if (user != null) {
      _nameController.text = user.name;
      _emailController.text = user.email;
      if (user.phone != null) _phoneController.text = user.phone!;
    }
  }

  Future<void> _load() async {
    try {
      final result = await _repo.getDetail(widget.module, widget.slug);
      setState(() {
        _item = result.item;
        _unitPrice = result.unitPrice;
        _bookable = result.bookable;
        _inWishlist = result.inWishlist;
      });
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      setState(() => _loading = false);
    }
  }

  Future<void> _book() async {
    if (_nameController.text.isEmpty || _emailController.text.isEmpty || _phoneController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Please fill all contact details')));
      return;
    }

    setState(() => _booking = true);
    try {
      final payload = {
        'name': _nameController.text,
        'email': _emailController.text,
        'phone': _phoneController.text,
        'travellers': _travellers,
        'travel_date': _travelDate.toIso8601String().split('T').first,
        if (_couponController.text.isNotEmpty) 'coupon_code': _couponController.text,
        if (widget.module == 'flights') 'trip_type': 'one_way',
      };

      final booking = await _repo.createBooking(widget.module, widget.slug, payload: payload);
      _pendingBooking = booking;

      try {
        final order = await _repo.createPaymentOrder(booking.id);
        final options = {
          'key': order['razorpay_key'],
          'amount': order['amount_paise'],
          'name': 'JetFly Airways',
          'description': booking.bookingCode,
          'order_id': order['razorpay_order_id'],
          'prefill': order['prefill'],
        };
        _razorpay.open(options);
      } catch (_) {
        if (mounted) {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(builder: (_) => BookingSuccessScreen(booking: booking, paid: false)),
          );
        }
      }
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      setState(() => _booking = false);
    }
  }

  void _onPaymentSuccess(PaymentSuccessResponse response) async {
    if (_pendingBooking == null) return;
    try {
      final booking = await _repo.verifyPayment(
        bookingId: _pendingBooking!.id,
        orderId: response.orderId ?? '',
        paymentId: response.paymentId ?? '',
        signature: response.signature ?? '',
      );
      if (mounted) {
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (_) => BookingSuccessScreen(booking: booking, paid: true)),
        );
      }
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    }
  }

  void _onPaymentError(PaymentFailureResponse response) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(response.message ?? 'Payment failed')),
    );
  }

  @override
  void dispose() {
    _razorpay.clear();
    _nameController.dispose();
    _emailController.dispose();
    _phoneController.dispose();
    _couponController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (_loading) {
      return Scaffold(appBar: AppBar(title: Text(widget.title)), body: const Center(child: CircularProgressIndicator()));
    }

    final item = _item!;
    final total = _unitPrice * _travellers;

    return Scaffold(
      appBar: AppBar(
        title: Text(item.title),
        actions: [
          if (context.watch<AuthProvider>().isLoggedIn)
            IconButton(
              icon: Icon(_inWishlist ? Icons.favorite : Icons.favorite_border),
              onPressed: () async {
                try {
                  await _repo.toggleWishlist(widget.module, item.id, !_inWishlist);
                  setState(() => _inWishlist = !_inWishlist);
                } catch (e) {
                  if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
                }
              },
            ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(item.title, style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                    if (item.meta != null) ...[
                      const SizedBox(height: 8),
                      Text(item.meta!, style: const TextStyle(color: AppColors.muted)),
                    ],
                    const SizedBox(height: 12),
                    Text(item.description),
                    const SizedBox(height: 16),
                    Text('₹${item.price.toStringAsFixed(0)}', style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: AppColors.primary)),
                  ],
                ),
              ),
            ),
            if (_bookable) ...[
              const SizedBox(height: 20),
              const Text('Book Now', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              const SizedBox(height: 12),
              TextField(controller: _nameController, decoration: const InputDecoration(labelText: 'Full Name')),
              const SizedBox(height: 10),
              TextField(controller: _emailController, decoration: const InputDecoration(labelText: 'Email'), keyboardType: TextInputType.emailAddress),
              const SizedBox(height: 10),
              TextField(controller: _phoneController, decoration: const InputDecoration(labelText: 'Phone'), keyboardType: TextInputType.phone),
              const SizedBox(height: 10),
              Row(
                children: [
                  const Text('Travellers:'),
                  const SizedBox(width: 12),
                  IconButton(onPressed: _travellers > 1 ? () => setState(() => _travellers--) : null, icon: const Icon(Icons.remove_circle_outline)),
                  Text('$_travellers', style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  IconButton(onPressed: _travellers < 20 ? () => setState(() => _travellers++) : null, icon: const Icon(Icons.add_circle_outline)),
                ],
              ),
              ListTile(
                contentPadding: EdgeInsets.zero,
                title: const Text('Travel Date'),
                subtitle: Text(_travelDate.toIso8601String().split('T').first),
                trailing: const Icon(Icons.calendar_today),
                onTap: () async {
                  final picked = await showDatePicker(
                    context: context,
                    initialDate: _travelDate,
                    firstDate: DateTime.now(),
                    lastDate: DateTime.now().add(const Duration(days: 365)),
                  );
                  if (picked != null) setState(() => _travelDate = picked);
                },
              ),
              TextField(controller: _couponController, decoration: const InputDecoration(labelText: 'Coupon Code (optional)')),
              const SizedBox(height: 16),
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(14),
                decoration: BoxDecoration(color: AppColors.secondaryContainer, borderRadius: BorderRadius.circular(10)),
                child: Text('Total: ₹${total.toStringAsFixed(0)}', style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              ),
              const SizedBox(height: 16),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _booking ? null : _book,
                  child: _booking
                      ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                      : const Text('Confirm & Pay'),
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }
}
