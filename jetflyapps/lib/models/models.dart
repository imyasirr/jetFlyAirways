class UserModel {
  final int id;
  final String name;
  final String email;
  final String? phone;
  final String? avatarUrl;
  final String? referralCode;
  final int referralsCount;

  UserModel({
    required this.id,
    required this.name,
    required this.email,
    this.phone,
    this.avatarUrl,
    this.referralCode,
    this.referralsCount = 0,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'] as int,
      name: json['name'] as String,
      email: json['email'] as String,
      phone: json['phone'] as String?,
      avatarUrl: json['avatar_url'] as String?,
      referralCode: json['referral_code'] as String?,
      referralsCount: json['referrals_count'] as int? ?? 0,
    );
  }
}

class ListingItem {
  final int id;
  final String slug;
  final String title;
  final String subtitle;
  final double price;

  ListingItem({
    required this.id,
    required this.slug,
    required this.title,
    required this.subtitle,
    required this.price,
  });

  factory ListingItem.fromJson(Map<String, dynamic> json) {
    return ListingItem(
      id: json['id'] as int,
      slug: json['slug'] as String? ?? '${json['id']}',
      title: json['title'] as String,
      subtitle: json['subtitle'] as String? ?? '',
      price: (json['price'] as num).toDouble(),
    );
  }
}

class DetailItem {
  final int id;
  final String slug;
  final String title;
  final String description;
  final double price;
  final String? meta;

  DetailItem({
    required this.id,
    required this.slug,
    required this.title,
    required this.description,
    required this.price,
    this.meta,
  });

  factory DetailItem.fromJson(Map<String, dynamic> json) {
    return DetailItem(
      id: json['id'] as int,
      slug: json['slug'] as String? ?? '${json['id']}',
      title: json['title'] as String,
      description: json['description'] as String? ?? '',
      price: (json['price'] as num).toDouble(),
      meta: json['meta'] as String?,
    );
  }
}

class BookingModel {
  final int id;
  final String bookingCode;
  final String module;
  final int moduleItemId;
  final String travelDate;
  final int travellersCount;
  final double totalAmount;
  final double subtotalAmount;
  final double discountAmount;
  final String? couponCode;
  final String status;
  final String paymentStatus;
  final String contactName;
  final String contactEmail;
  final String contactPhone;

  BookingModel({
    required this.id,
    required this.bookingCode,
    required this.module,
    required this.moduleItemId,
    required this.travelDate,
    required this.travellersCount,
    required this.totalAmount,
    required this.subtotalAmount,
    required this.discountAmount,
    this.couponCode,
    required this.status,
    required this.paymentStatus,
    required this.contactName,
    required this.contactEmail,
    required this.contactPhone,
  });

  factory BookingModel.fromJson(Map<String, dynamic> json) {
    return BookingModel(
      id: json['id'] as int,
      bookingCode: json['booking_code'] as String,
      module: json['module'] as String,
      moduleItemId: json['module_item_id'] as int,
      travelDate: json['travel_date'] as String,
      travellersCount: json['travellers_count'] as int,
      totalAmount: (json['total_amount'] as num).toDouble(),
      subtotalAmount: (json['subtotal_amount'] as num).toDouble(),
      discountAmount: (json['discount_amount'] as num).toDouble(),
      couponCode: json['coupon_code'] as String?,
      status: json['status'] as String,
      paymentStatus: json['payment_status'] as String,
      contactName: json['contact_name'] as String,
      contactEmail: json['contact_email'] as String,
      contactPhone: json['contact_phone'] as String,
    );
  }

  bool get isPaid => paymentStatus == 'paid';
  bool get isCancelled => status == 'cancelled';
}

class ModuleInfo {
  final String key;
  final String title;
  final String icon;

  ModuleInfo({required this.key, required this.title, required this.icon});

  factory ModuleInfo.fromEntry(String key, Map<String, dynamic> json) {
    return ModuleInfo(
      key: key,
      title: json['title'] as String,
      icon: json['icon'] as String? ?? '✈',
    );
  }
}

class PaginatedMeta {
  final int currentPage;
  final int lastPage;
  final int total;

  PaginatedMeta({
    required this.currentPage,
    required this.lastPage,
    required this.total,
  });

  factory PaginatedMeta.fromJson(Map<String, dynamic> json) {
    return PaginatedMeta(
      currentPage: json['current_page'] as int? ?? 1,
      lastPage: json['last_page'] as int? ?? 1,
      total: json['total'] as int? ?? 0,
    );
  }
}
