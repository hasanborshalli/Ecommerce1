<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share site settings and categories with ALL views
        View::composer('*', function ($view) {
            $view->with([
                'siteName'      => SiteSetting::get('site_name', config('app.name')),
                'siteTagline'   => SiteSetting::get('site_tagline', ''),
                'siteLogo'      => SiteSetting::get('site_logo', ''),
                'currencySymbol'=> SiteSetting::get('currency_symbol', '$'),
                'sitePhone'     => SiteSetting::get('site_phone', ''),
                'siteEmail'     => SiteSetting::get('site_email', ''),
                'socialLinks'   => [
                    'instagram' => SiteSetting::get('social_instagram', ''),
                    'facebook'  => SiteSetting::get('social_facebook', ''),
                    'tiktok'    => SiteSetting::get('social_tiktok', ''),
                    'pinterest' => SiteSetting::get('social_pinterest', ''),
                ],
                'navCategories' => Category::where('is_active', true)
                                           ->orderBy('sort_order')
                                           ->get(),
                'cartCount'     => collect(session('cart', []))
                                           ->sum('quantity'),
            ]);
        });

        // Share unread messages count with admin views only
        View::composer('admin.*', function ($view) {
            $view->with([
                'unreadMessages' => ContactMessage::where('is_read', false)->count(),
            ]);
        });
    }
}