<?php

use App\Http\Controllers\Account\AnnouncementInboxController;
use App\Http\Controllers\Account\BookingController as AccountBookingController;
use App\Http\Controllers\Account\DashboardController as AccountDashboardController;
use App\Http\Controllers\Account\OffersController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\WishlistController as AccountWishlistController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BusRouteController;
use App\Http\Controllers\Admin\CabServiceController;
use App\Http\Controllers\Admin\CareerApplicationController;
use App\Http\Controllers\Admin\CareerController as AdminCareerController;
use App\Http\Controllers\Admin\ContactInquiryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\FlightController;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PopupMessageController;
use App\Http\Controllers\Admin\PaymentReportController;
use App\Http\Controllers\Admin\BookingReportController;
use App\Http\Controllers\Admin\ApiIntegrationController;
use App\Http\Controllers\Admin\SiteSeoController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TrainRouteController;
use App\Http\Controllers\Admin\TravelAddonController;
use App\Http\Controllers\Admin\TravelPackageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\OtpLoginController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingTicketController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Account\RefundController as AccountRefundController;
use App\Http\Controllers\Account\SavedTravellerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/welcome', [SiteController::class, 'welcome'])->name('welcome');

Route::get('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'hi'], true), 404);
    session(['locale' => $locale]);

    return back();
})->name('locale.switch');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{career}', [JobController::class, 'show'])->name('jobs.show');
Route::post('/jobs/{career}/apply', [JobController::class, 'apply'])->name('jobs.apply');
Route::get('/contact-us', [ContactFormController::class, 'create'])->name('contact.create');
Route::post('/contact-us', [ContactFormController::class, 'store'])->name('contact.store');
Route::get('/refer-earn', [SiteController::class, 'referEarn'])->name('refer-earn');
Route::get('/currency-converter', [SiteController::class, 'currencyConverter'])->name('currency-converter');

Route::post('/payments/verify', [PaymentController::class, 'verify'])->name('payments.verify');

Route::middleware('signed')->group(function () {
    Route::get('/payments/checkout/{booking}', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::get('/booking/{booking}/thanks', [SiteController::class, 'bookingThanks'])->name('booking.thanks');
});

Route::get('/bookings/{booking}/ticket.pdf', [BookingTicketController::class, 'pdf'])->name('bookings.ticket.pdf');

Route::middleware('guest')->group(function () {
    Route::get('login', [UserAuthController::class, 'createLogin'])->name('login');
    Route::post('login', [UserAuthController::class, 'login']);
    Route::get('register', [UserAuthController::class, 'createRegister'])->name('register');
    Route::post('register', [UserAuthController::class, 'register']);

    Route::get('login/otp', [OtpLoginController::class, 'create'])->name('login.otp');
    Route::post('login/otp', [OtpLoginController::class, 'send'])->name('login.otp.send');
    Route::get('login/otp/verify', [OtpLoginController::class, 'verifyForm'])->name('login.otp.verify');
    Route::post('login/otp/verify', [OtpLoginController::class, 'verify'])->name('login.otp.verify.submit');

    Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::post('logout', [UserAuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountDashboardController::class, 'index'])->name('dashboard');
    Route::get('bookings', [AccountBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [AccountBookingController::class, 'show'])->name('bookings.show');
    Route::get('wishlist', [AccountWishlistController::class, 'index'])->name('wishlist.index');
    Route::get('notifications', [AnnouncementInboxController::class, 'index'])->name('announcements.index');
    Route::post('notifications/{announcement}/read', [AnnouncementInboxController::class, 'markRead'])->name('announcements.read');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('password', [ProfileController::class, 'editPassword'])->name('password.edit');
    Route::put('password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::get('offers', [OffersController::class, 'index'])->name('offers');
    Route::get('saved-travellers', [SavedTravellerController::class, 'index'])->name('saved-travellers.index');
    Route::post('saved-travellers', [SavedTravellerController::class, 'store'])->name('saved-travellers.store');
    Route::put('saved-travellers/{savedTraveller}', [SavedTravellerController::class, 'update'])->name('saved-travellers.update');
    Route::delete('saved-travellers/{savedTraveller}', [SavedTravellerController::class, 'destroy'])->name('saved-travellers.destroy');
    Route::post('bookings/{booking}/cancel', [AccountBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('refunds', [AccountRefundController::class, 'index'])->name('refunds.index');
});

Route::middleware('auth')->group(function () {
    Route::post('wishlist/{module}/{id}', [WishlistController::class, 'store'])
        ->whereNumber('id')
        ->whereIn('module', ['flights', 'hotels', 'packages', 'buses', 'trains', 'cabs', 'visa', 'insurance'])
        ->name('wishlist.store');
    Route::delete('wishlist/{module}/{id}', [WishlistController::class, 'destroy'])
        ->whereNumber('id')
        ->whereIn('module', ['flights', 'hotels', 'packages', 'buses', 'trains', 'cabs', 'visa', 'insurance'])
        ->name('wishlist.destroy');
});

Route::get('/p/{slug}', [PageController::class, 'show'])->name('pages.show');

foreach (['about', 'careers', 'contact', 'help', 'refund', 'terms', 'privacy', 'sitemap'] as $legacyPage) {
    Route::permanentRedirect('/'.$legacyPage, '/p/'.$legacyPage);
}

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'create'])->middleware('guest')->name('login');
    Route::post('login', [AdminAuthController::class, 'store'])->middleware('guest');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AdminAuthController::class, 'destroy'])->name('logout');

    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

    Route::resource('flights', FlightController::class);
    Route::resource('hotels', HotelController::class);
    Route::resource('travel-packages', TravelPackageController::class);
    Route::resource('bus-routes', BusRouteController::class);
    Route::resource('train-routes', TrainRouteController::class);
    Route::resource('cab-services', CabServiceController::class);

    Route::resource('menu-items', MenuItemController::class)->except(['show']);
    Route::resource('pages', AdminPageController::class)->except(['show']);
    Route::resource('coupons', CouponController::class)->except(['show']);
    Route::resource('popup-messages', PopupMessageController::class)->except(['show']);
    Route::resource('blogs', AdminBlogController::class);
    Route::resource('offers', OfferController::class)->except(['show']);
    Route::resource('faqs', AdminFaqController::class)->except(['show']);
    Route::resource('testimonials', TestimonialController::class)->except(['show']);
    Route::resource('careers', AdminCareerController::class)->except(['show']);
    Route::get('contact-inquiries', [ContactInquiryController::class, 'index'])->name('contact-inquiries.index');
    Route::get('contact-inquiries/{contact_inquiry}', [ContactInquiryController::class, 'show'])->name('contact-inquiries.show');
    Route::patch('contact-inquiries/{contact_inquiry}', [ContactInquiryController::class, 'update'])->name('contact-inquiries.update');
    Route::get('career-applications', [CareerApplicationController::class, 'index'])->name('career-applications.index');
    Route::get('career-applications/{career_application}', [CareerApplicationController::class, 'show'])->name('career-applications.show');
    Route::get('career-applications/{career_application}/resume', [CareerApplicationController::class, 'resume'])->name('career-applications.resume');
    Route::delete('career-applications/{career_application}', [CareerApplicationController::class, 'destroy'])->name('career-applications.destroy');
    Route::get('site-seo', [SiteSeoController::class, 'edit'])->name('site-seo.edit');
    Route::put('site-seo', [SiteSeoController::class, 'update'])->name('site-seo.update');
    Route::get('site-settings', [SiteSettingController::class, 'edit'])->name('site-settings.edit');
    Route::put('site-settings', [SiteSettingController::class, 'update'])->name('site-settings.update');
    Route::resource('banners', BannerController::class)->except(['show']);
    Route::get('home-sections', [HomeSectionController::class, 'index'])->name('home-sections.index');
    Route::put('home-sections', [HomeSectionController::class, 'update'])->name('home-sections.update');
    Route::resource('announcements', AnnouncementController::class)->except(['show']);
    Route::resource('travel-addons', TravelAddonController::class)->except(['show']);
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('reports/payments', [PaymentReportController::class, 'index'])->name('reports.payments');
    Route::get('reports/bookings', [BookingReportController::class, 'index'])->name('reports.bookings');
    Route::get('integrations', [ApiIntegrationController::class, 'index'])->name('integrations.index');
    Route::put('integrations', [ApiIntegrationController::class, 'update'])->name('integrations.update');
    Route::get('integrations/{integration}/test', [ApiIntegrationController::class, 'test'])->name('integrations.test');
});

Route::get('/{module}', [SiteController::class, 'module'])
    ->whereIn('module', ['flights', 'hotels', 'packages', 'buses', 'trains', 'cabs', 'visa', 'insurance'])
    ->name('module.index');

Route::get('/{module}/{id}', [SiteController::class, 'moduleDetail'])
    ->whereNumber('id')
    ->name('module.show');

Route::get('/{module}/{id}/book', [SiteController::class, 'bookingForm'])
    ->whereNumber('id')
    ->name('booking.form');

Route::post('/{module}/{id}/book', [SiteController::class, 'bookingSubmit'])
    ->whereNumber('id')
    ->name('booking.submit');
