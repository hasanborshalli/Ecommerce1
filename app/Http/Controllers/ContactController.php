<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $meta = [
            'title'       => 'Contact Us — ' . SiteSetting::get('site_name'),
            'description' => 'Get in touch with our team.',
        ];

        return view('contact', compact('meta'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'required|email|max:120',
            'phone'   => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|min:10|max:2000',
        ]);

        ContactMessage::create($request->only('name', 'email', 'phone', 'subject', 'message'));

        return back()->with('success', 'Your message has been sent. We\'ll be in touch shortly.');
    }
}