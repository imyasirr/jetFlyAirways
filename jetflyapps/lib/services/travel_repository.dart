import '../models/models.dart';
import 'api_service.dart';
import 'home_cache_service.dart';

class TravelRepository {
  TravelRepository(this._api);

  final ApiService _api;
  final HomeCacheService _homeCache = HomeCacheService();

  Future<bool> hasHomeCache() => _homeCache.hasCache();

  Future<Map<String, dynamic>> getHome({bool forceRefresh = false}) async {
    if (!forceRefresh) {
      final cached = await _homeCache.load();
      if (cached != null) return cached;
    }

    final data = await _api.get('/home');
    await _homeCache.save(data);
    return data;
  }

  Future<List<ModuleInfo>> getModules() async {
    final data = await _api.get('/modules');
    final modules = data['modules'] as Map<String, dynamic>;
    return modules.entries
        .map((e) => ModuleInfo.fromEntry(e.key, e.value as Map<String, dynamic>))
        .toList();
  }

  Future<({List<ListingItem> items, PaginatedMeta meta})> getModuleList(
    String module, {
    Map<String, String>? filters,
    int page = 1,
  }) async {
    final query = {...?filters, 'page': '$page'};
    final data = await _api.get('/modules/$module', query: query);
    final items = (data['items'] as List? ?? [])
        .map((e) => ListingItem.fromJson(e as Map<String, dynamic>))
        .toList();
    final meta = PaginatedMeta.fromJson(data['meta'] as Map<String, dynamic>? ?? {});
    return (items: items, meta: meta);
  }

  Future<({DetailItem item, double unitPrice, bool bookable, bool inWishlist})> getDetail(
    String module,
    String item,
  ) async {
    final data = await _api.get('/modules/$module/$item');
    return (
      item: DetailItem.fromJson(data['item'] as Map<String, dynamic>),
      unitPrice: (data['unit_price'] as num).toDouble(),
      bookable: data['bookable'] as bool? ?? true,
      inWishlist: data['in_wishlist'] as bool? ?? false,
    );
  }

  Future<BookingModel> createBooking(
    String module,
    String item, {
    required Map<String, dynamic> payload,
  }) async {
    final data = await _api.post('/modules/$module/$item/book', body: payload);
    return BookingModel.fromJson(data['booking'] as Map<String, dynamic>);
  }

  Future<({double discount, double total})> validateCoupon(
    String code,
    double subtotal,
  ) async {
    final data = await _api.post('/coupons/validate', body: {
      'coupon_code': code,
      'subtotal': subtotal,
    });
    return (
      discount: (data['discount'] as num).toDouble(),
      total: (data['total'] as num).toDouble(),
    );
  }

  Future<List<BookingModel>> getBookings() async {
    final data = await _api.get('/account/bookings');
    return (data['bookings'] as List? ?? [])
        .map((e) => BookingModel.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<void> cancelBooking(int id) async {
    await _api.post('/account/bookings/$id/cancel');
  }

  Future<Map<String, dynamic>> createPaymentOrder(int bookingId) async {
    return _api.post('/bookings/$bookingId/payment/order');
  }

  Future<BookingModel> verifyPayment({
    required int bookingId,
    required String orderId,
    required String paymentId,
    required String signature,
  }) async {
    final data = await _api.post('/payments/verify', body: {
      'booking_id': bookingId,
      'razorpay_order_id': orderId,
      'razorpay_payment_id': paymentId,
      'razorpay_signature': signature,
    });
    return BookingModel.fromJson(data['booking'] as Map<String, dynamic>);
  }

  Future<void> toggleWishlist(String module, int id, bool add) async {
    if (add) {
      await _api.post('/account/wishlist/$module/$id');
    } else {
      await _api.delete('/account/wishlist/$module/$id');
    }
  }

  Future<List<Map<String, dynamic>>> getWishlist() async {
    final data = await _api.get('/account/wishlist');
    return (data['items'] as List? ?? []).cast<Map<String, dynamic>>();
  }

  Future<List<Map<String, dynamic>>> getFaqs() async {
    final data = await _api.get('/faqs');
    return (data['faqs'] as List? ?? []).cast<Map<String, dynamic>>();
  }

  Future<List<Map<String, dynamic>>> getOffers() async {
    final data = await _api.get('/offers');
    return (data['offers'] as List? ?? []).cast<Map<String, dynamic>>();
  }

  Future<Map<String, dynamic>> getSiteInfo() async {
    final data = await _api.get('/site');
    return data['site'] as Map<String, dynamic>? ?? {};
  }

  Future<String> submitContact(Map<String, dynamic> payload) async {
    final data = await _api.post('/contact', body: payload);
    return data['message'] as String? ?? 'Message sent successfully.';
  }

  Future<({List<Map<String, dynamic>> blogs, PaginatedMeta meta})> getBlogs({int page = 1}) async {
    final data = await _api.get('/blogs', query: {'page': '$page'});
    final blogs = (data['blogs'] as List? ?? []).cast<Map<String, dynamic>>();
    final meta = PaginatedMeta.fromJson(data['meta'] as Map<String, dynamic>? ?? {});
    return (blogs: blogs, meta: meta);
  }

  Future<Map<String, dynamic>> getBlog(String slug) async {
    final data = await _api.get('/blogs/$slug');
    return data['blog'] as Map<String, dynamic>;
  }

  Future<UserModel> updateProfile({required String name, required String email, String? phone}) async {
    final body = <String, dynamic>{
      'name': name,
      'email': email,
    };
    if (phone != null) body['phone'] = phone;
    final data = await _api.put('/account/profile', body: body);
    return UserModel.fromJson(data['user'] as Map<String, dynamic>);
  }

  Future<UserModel> uploadAvatar(String filePath) async {
    final data = await _api.postMultipart(
      '/account/profile/avatar',
      fileField: 'avatar',
      filePath: filePath,
    );
    return UserModel.fromJson(data['user'] as Map<String, dynamic>);
  }

  Future<UserModel> removeAvatar() async {
    final data = await _api.post('/account/profile/avatar', body: {'clear_avatar': true});
    return UserModel.fromJson(data['user'] as Map<String, dynamic>);
  }

  Future<List<Map<String, dynamic>>> getAnnouncements() async {
    final data = await _api.get('/account/announcements');
    return (data['announcements'] as List? ?? []).cast<Map<String, dynamic>>();
  }

  Future<List<Map<String, dynamic>>> getDestinations() async {
    final data = await _api.get('/destinations');
    return (data['destinations'] as List? ?? []).cast<Map<String, dynamic>>();
  }

  Future<Map<String, dynamic>> getDestination(String slug) async {
    final data = await _api.get('/destinations/$slug');
    return data['destination'] as Map<String, dynamic>;
  }
}
