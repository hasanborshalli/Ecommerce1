<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name', 'site_tagline', 'site_email', 'site_phone', 'site_address',
            'currency_symbol', 'currency_code',
            'shipping_cost', 'free_shipping_over',
            'social_instagram', 'social_facebook', 'social_tiktok', 'social_pinterest',
            'meta_title', 'meta_description',
            'footer_about',
        ];

        $values = [];
        foreach ($settings as $key) {
            $values[$key] = SiteSetting::get($key, '');
        }

        return view('admin.settings', compact('values'));
    }

    public function update(Request $request)
    {
        $allowed = [
            'site_name', 'site_tagline', 'site_email', 'site_phone', 'site_address',
            'currency_symbol', 'currency_code',
            'shipping_cost', 'free_shipping_over',
            'social_instagram', 'social_facebook', 'social_tiktok', 'social_pinterest',
            'meta_title', 'meta_description',
            'footer_about',
        ];

        foreach ($allowed as $key) {
            if ($request->has($key)) {
                SiteSetting::set($key, $request->input($key));
            }
        }

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('settings', 'public');
            SiteSetting::set('site_logo', $path);
        }

        return back()->with('success', 'Settings saved.');
    }

    public function messages(Request $request)
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.messages', compact('messages'));
    }

    public function markRead(Request $request, ContactMessage $message)
    {
        $message->update(['is_read' => true]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }
}