<?php

namespace App\Providers;

use App\Contracts\GdsBookingClient;
use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\MenuItem;
use App\Models\PopupMessage;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use App\Services\Gds\NullGdsBookingClient;
use App\Services\Sms\LogSmsSender;
use App\Services\Sms\SmsSender;
use App\Services\Sms\TwilioSmsSender;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GdsBookingClient::class, NullGdsBookingClient::class);
        $this->app->bind(SmsSender::class, function () {
            return match (config('jetfly.sms.driver')) {
                'twilio' => new TwilioSmsSender,
                default => new LogSmsSender,
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            if (! Schema::hasTable('menu_items')) {
                $view->with('headerMenu', collect());
                $view->with('footerMenu', collect());
            } else {
                $view->with('headerMenu', MenuItem::treeFor('header'));
                $view->with('footerMenu', MenuItem::treeFor('footer'));
            }

            if (Schema::hasTable('popup_messages')) {
                $view->with('welcomePopup', PopupMessage::query()->activeForDisplay()->first());
            } else {
                $view->with('welcomePopup', null);
            }

            $siteSeo = null;
            if (Schema::hasTable('seo_meta')) {
                $siteSeo = SeoMeta::query()
                    ->where('entity_type', SeoMeta::SITE_ENTITY_TYPE)
                    ->where('entity_id', SeoMeta::SITE_ENTITY_ID)
                    ->first();
            }
            $view->with('siteSeo', $siteSeo);

            $siteSetting = null;
            if (Schema::hasTable('site_settings')) {
                $siteSetting = SiteSetting::query()->first();
            }
            $view->with('siteSetting', $siteSetting);

            $unreadAnnouncements = 0;
            if (auth()->check() && Schema::hasTable('announcements') && Schema::hasTable('announcement_reads')) {
                $publishedIds = Announcement::query()->published()->pluck('id');
                $readIds = AnnouncementRead::query()
                    ->where('user_id', auth()->id())
                    ->whereIn('announcement_id', $publishedIds)
                    ->pluck('announcement_id');
                $unreadAnnouncements = $publishedIds->diff($readIds)->count();
            }
            $view->with('unreadAnnouncements', $unreadAnnouncements);
        });
    }
}
