<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $slides      = HeroSlide::active()->get();
        $categories  = Category::where('is_active', true)->orderBy('sort_order')->take(6)->get();
        $featured    = Product::featured()->orderBy('sort_order')->take(8)->get();
        $newArrivals = Product::newArrivals()->orderBy('created_at', 'desc')->take(4)->get();
        $onSale      = Product::onSale()->take(4)->get();
        $testimonials= Testimonial::active()->get();

        $meta = [
            'title'       => SiteSetting::get('meta_title', config('app.name')),
            'description' => SiteSetting::get('meta_description', ''),
        ];

        return view('home', compact(
            'slides', 'categories', 'featured',
            'newArrivals', 'onSale', 'testimonials', 'meta'
        ));
    }

    public function about()
    {
        $meta = [
            'title'       => 'About Us — ' . SiteSetting::get('site_name'),
            'description' => 'Learn about our story and commitment to quality.',
        ];

        return view('about', compact('meta'));
    }
}