<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Site Settings ───────────────────────────────────────────
        $settings = [
            'site_name'          => 'Maison Store',
            'site_tagline'       => 'Curated for the refined',
            'site_email'         => 'hello@maisonstore.com',
            'site_phone'         => '+1 (555) 000-0000',
            'site_address'       => '12 Rue du Commerce, Paris',
            'site_logo'          => '',           // path to logo
            'site_favicon'       => '',
            'currency_symbol'    => '$',
            'currency_code'      => 'USD',
            'shipping_cost'      => '8.00',
            'free_shipping_over' => '100.00',
            'social_instagram'   => 'https://instagram.com',
            'social_facebook'    => 'https://facebook.com',
            'social_tiktok'      => '',
            'social_pinterest'   => '',
            'meta_title'         => 'Maison Store — Curated for the Refined',
            'meta_description'   => 'Discover our curated collection of premium fashion, skincare, and lifestyle products.',
            'footer_about'       => 'We curate the finest products for those who appreciate quality and elegance.',
        ];
        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value);
        }

        // ─── Hero Slides ─────────────────────────────────────────────
        $slides = [
            [
                'title'       => 'New Collection',
                'subtitle'    => 'Discover the art of refined living',
                'button_text' => 'Shop Now',
                'button_link' => '/shop',
                'image'       => 'hero/slide-1.jpg',
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Summer Edit',
                'subtitle'    => 'Thoughtfully crafted essentials',
                'button_text' => 'Explore',
                'button_link' => '/shop?category=new-arrivals',
                'image'       => 'hero/slide-2.jpg',
                'sort_order'  => 2,
            ],
        ];
        foreach ($slides as $slide) {
            HeroSlide::create($slide);
        }

        // ─── Categories ──────────────────────────────────────────────
        $categories = [
            ['name' => 'New Arrivals', 'image' => 'categories/new-arrivals.jpg',  'description' => 'Fresh pieces just landed'],
            ['name' => 'Clothing',     'image' => 'categories/clothing.jpg',       'description' => 'Elevated wardrobe essentials'],
            ['name' => 'Skincare',     'image' => 'categories/skincare.jpg',       'description' => 'Science-backed skin rituals'],
            ['name' => 'Fragrance',    'image' => 'categories/fragrance.jpg',      'description' => 'Rare and storied scents'],
            ['name' => 'Accessories',  'image' => 'categories/accessories.jpg',    'description' => 'The finishing touches'],
            ['name' => 'Home & Life',  'image' => 'categories/home.jpg',           'description' => 'Objects that enrich daily life'],
        ];

        $categoryModels = [];
        foreach ($categories as $i => $cat) {
            $categoryModels[] = Category::create([
                'name'        => $cat['name'],
                'slug'        => Str::slug($cat['name']),
                'description' => $cat['description'],
                'image'       => $cat['image'],
                'sort_order'  => $i,
            ]);
        }

        // ─── Products ────────────────────────────────────────────────
        $products = [
            // Clothing
            [
                'category' => 'Clothing',
                'name'  => 'Linen Oversized Blazer',
                'price' => 189.00,
                'sale_price' => null,
                'is_featured' => true, 'is_new' => true,
                'short_description' => 'Relaxed silhouette in premium Belgian linen.',
                'variants' => ['sizes' => ['XS','S','M','L','XL'], 'colors' => ['Ecru','Sage','Charcoal']],
            ],
            [
                'category' => 'Clothing',
                'name'  => 'Silk Slip Dress',
                'price' => 245.00,
                'sale_price' => 175.00,
                'is_featured' => true, 'is_on_sale' => true,
                'short_description' => '100% mulberry silk in a bias-cut drape.',
                'variants' => ['sizes' => ['XS','S','M','L'], 'colors' => ['Champagne','Ivory','Midnight']],
            ],
            [
                'category' => 'Clothing',
                'name'  => 'Wide-Leg Trousers',
                'price' => 155.00,
                'sale_price' => null,
                'is_new' => true,
                'short_description' => 'Tailored wide-leg cut in Italian wool-blend.',
                'variants' => ['sizes' => ['XS','S','M','L','XL']],
            ],
            // Skincare
            [
                'category' => 'Skincare',
                'name'  => 'Renewal Face Oil',
                'price' => 95.00,
                'sale_price' => null,
                'is_featured' => true,
                'short_description' => 'Rosehip & bakuchiol blend for luminous skin.',
                'variants' => ['size' => ['30ml', '50ml']],
            ],
            [
                'category' => 'Skincare',
                'name'  => 'Gentle Exfoliating Serum',
                'price' => 78.00,
                'sale_price' => 58.00,
                'is_on_sale' => true,
                'short_description' => 'AHA/PHA formula for radiant, smooth texture.',
                'variants' => ['size' => ['30ml']],
            ],
            [
                'category' => 'Skincare',
                'name'  => 'Barrier Repair Cream',
                'price' => 68.00,
                'sale_price' => null,
                'is_new' => true,
                'short_description' => 'Ceramide-rich overnight recovery balm.',
                'variants' => ['size' => ['50ml', '100ml']],
            ],
            // Fragrance
            [
                'category' => 'Fragrance',
                'name'  => 'Oud & Sandalwood EDP',
                'price' => 185.00,
                'sale_price' => null,
                'is_featured' => true,
                'short_description' => 'An intoxicating blend of aged oud and creamy sandalwood.',
                'variants' => ['size' => ['50ml', '100ml']],
            ],
            [
                'category' => 'Fragrance',
                'name'  => 'White Jasmine Eau de Parfum',
                'price' => 140.00,
                'sale_price' => null,
                'is_new' => true,
                'short_description' => 'Delicate jasmine on a warm musk base.',
                'variants' => ['size' => ['30ml', '50ml', '100ml']],
            ],
            // Accessories
            [
                'category' => 'Accessories',
                'name'  => 'Leather Card Holder',
                'price' => 65.00,
                'sale_price' => null,
                'is_featured' => true,
                'short_description' => 'Full-grain leather with 6 card slots.',
                'variants' => ['color' => ['Tan', 'Black', 'Cognac']],
            ],
            [
                'category' => 'Accessories',
                'name'  => 'Silk Scarf',
                'price' => 110.00,
                'sale_price' => 79.00,
                'is_on_sale' => true,
                'short_description' => 'Hand-rolled hem, 90x90cm printed silk twill.',
                'variants' => ['pattern' => ['Floral', 'Abstract', 'Stripes']],
            ],
            // Home & Life
            [
                'category' => 'Home & Life',
                'name'  => 'Soy Candle — Fig & Oakmoss',
                'price' => 48.00,
                'sale_price' => null,
                'is_new' => true,
                'short_description' => '50-hour burn time in a hand-poured matte vessel.',
                'variants' => [],
            ],
            [
                'category' => 'Home & Life',
                'name'  => 'Linen Pillowcase Set',
                'price' => 85.00,
                'sale_price' => null,
                'is_featured' => true,
                'short_description' => 'Stonewashed French linen, set of 2.',
                'variants' => ['color' => ['Oat', 'Sage', 'Terracotta']],
            ],
        ];

        $catMap = $categoryModels ? array_combine(
            array_map(fn($c) => $c->name, $categoryModels),
            $categoryModels
        ) : [];

        foreach ($products as $i => $p) {
            $cat = $catMap[$p['category']] ?? $categoryModels[0];
            Product::create([
                'category_id'       => $cat->id,
                'name'              => $p['name'],
                'slug'              => Str::slug($p['name']),
                'short_description' => $p['short_description'],
                'description'       => '<p>' . $p['short_description'] . '</p><p>Crafted with the finest materials and meticulous attention to detail.</p>',
                'price'             => $p['price'],
                'sale_price'        => $p['sale_price'] ?? null,
                'stock'             => rand(5, 50),
                'sku'               => 'SKU-' . strtoupper(Str::random(6)),
                'main_image'        => 'products/product-' . ($i + 1) . '.jpg',
                'gallery'           => [],
                'variants'          => $p['variants'] ?? [],
                'is_active'         => true,
                'is_featured'       => $p['is_featured'] ?? false,
                'is_new'            => $p['is_new'] ?? false,
                'is_on_sale'        => $p['is_on_sale'] ?? false,
                'meta_title'        => $p['name'] . ' — Maison Store',
                'meta_description'  => $p['short_description'],
                'sort_order'        => $i,
            ]);
        }

        // ─── Testimonials ────────────────────────────────────────────
        $testimonials = [
            ['customer_name' => 'Sophie L.',    'customer_location' => 'Paris, FR',      'rating' => 5, 'review' => 'Absolutely stunning quality. The linen blazer is now my most-worn piece. Worth every penny.'],
            ['customer_name' => 'Maria G.',     'customer_location' => 'Milan, IT',      'rating' => 5, 'review' => 'The skincare range transformed my routine. The face oil is a genuine miracle in a bottle.'],
            ['customer_name' => 'James T.',     'customer_location' => 'London, UK',     'rating' => 5, 'review' => 'Packaging is beautiful and the candle scent fills the entire room. Will definitely reorder.'],
            ['customer_name' => 'Nora K.',      'customer_location' => 'Beirut, LB',     'rating' => 5, 'review' => 'Fast delivery, beautiful packaging, and the silk dress fits like a dream. Highly recommend.'],
            ['customer_name' => 'Carlos M.',    'customer_location' => 'Madrid, ES',     'rating' => 5, 'review' => 'The oud fragrance is complex and long-lasting. People ask me what I\'m wearing every single day.'],
        ];
        foreach ($testimonials as $t) {
            Testimonial::create($t);
        }
    }
}