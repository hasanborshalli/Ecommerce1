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
    ['name' => 'T-Shirts', 'image' => 'categories/tshirts.jpg', 'description' => 'Clean branded everyday tees'],
    ['name' => 'Hoodies',  'image' => 'categories/hoodies.jpg', 'description' => 'Premium heavyweight logo hoodies'],
    ['name' => 'Caps',     'image' => 'categories/caps.jpg',    'description' => 'Minimal embroidered branded caps'],
    ['name' => 'Mugs',     'image' => 'categories/mugs.jpg',    'description' => 'Desk-ready ceramic logo mugs'],
    ['name' => 'Jackets',  'image' => 'categories/jackets.jpg', 'description' => 'Sharp outerwear with subtle identity'],
    ['name' => 'Featured', 'image' => 'categories/featured.jpg','description' => 'Signature brndng. essentials'],
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
      // ─── Products ────────────────────────────────────────────────
$products = [
    // T-Shirts
    [
        'category' => 'T-Shirts',
        'name'  => 'Heather Grey Logo T-Shirt',
        'price' => 32.00,
        'sale_price' => null,
        'is_featured' => true,
        'is_new' => true,
        'short_description' => 'Soft heather grey tee with oversized b. front logo.',
        'variants' => [
            'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
            'colors' => ['Heather Grey'],
        ],
    ],
    [
        'category' => 'T-Shirts',
        'name'  => 'Charcoal Chest Logo T-Shirt',
        'price' => 30.00,
        'sale_price' => 24.00,
        'is_featured' => true,
        'is_on_sale' => true,
        'short_description' => 'Minimal charcoal tee with subtle left chest b. mark.',
        'variants' => [
            'sizes' => ['S', 'M', 'L', 'XL'],
            'colors' => ['Charcoal'],
        ],
    ],

    // Hoodies
    [
        'category' => 'Hoodies',
        'name'  => 'Charcoal Logo Hoodie',
        'price' => 79.00,
        'sale_price' => null,
        'is_featured' => true,
        'is_new' => true,
        'short_description' => 'Heavyweight charcoal hoodie with bold front b. branding.',
        'variants' => [
            'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
            'colors' => ['Charcoal'],
        ],
    ],
    [
        'category' => 'Hoodies',
        'name'  => 'Heather Grey Logo Hoodie',
        'price' => 75.00,
        'sale_price' => null,
        'is_featured' => true,
        'short_description' => 'Classic heather grey hoodie with oversized front logo.',
        'variants' => [
            'sizes' => ['S', 'M', 'L', 'XL'],
            'colors' => ['Heather Grey'],
        ],
    ],

    // Caps
    [
        'category' => 'Caps',
        'name'  => 'Embroidered Logo Cap',
        'price' => 29.00,
        'sale_price' => null,
        'is_featured' => true,
        'is_new' => true,
        'short_description' => 'Grey structured cap with embroidered b. logo.',
        'variants' => [
            'size' => ['One Size'],
            'color' => ['Grey'],
        ],
    ],

    // Mugs
    [
        'category' => 'Mugs',
        'name'  => 'Ceramic Logo Mug',
        'price' => 18.00,
        'sale_price' => null,
        'is_featured' => true,
        'short_description' => 'White ceramic mug featuring the signature b. logo.',
        'variants' => [
            'size' => ['11oz'],
            'color' => ['White'],
        ],
    ],

    // Jackets
    [
        'category' => 'Jackets',
        'name'  => 'Charcoal Logo Bomber Jacket',
        'price' => 110.00,
        'sale_price' => null,
        'is_featured' => true,
        'is_new' => true,
        'short_description' => 'Modern charcoal bomber jacket with front b. branding.',
        'variants' => [
            'sizes' => ['S', 'M', 'L', 'XL'],
            'colors' => ['Charcoal'],
        ],
    ],

    // Featured
    [
        'category' => 'Featured',
        'name'  => 'Minimal Logo Hoodie',
        'price' => 72.00,
        'sale_price' => null,
        'is_featured' => true,
        'short_description' => 'Clean everyday hoodie with understated brndng. identity.',
        'variants' => [
            'sizes' => ['S', 'M', 'L', 'XL'],
            'colors' => ['Ash Grey', 'Charcoal'],
        ],
    ],
    [
        'category' => 'Featured',
        'name'  => 'Studio Coffee Mug',
        'price' => 20.00,
        'sale_price' => null,
        'is_new' => true,
        'short_description' => 'Minimal branded mug designed for studio and desk setups.',
        'variants' => [
            'size' => ['11oz', '15oz'],
            'color' => ['White'],
        ],
    ],
    [
        'category' => 'Featured',
        'name'  => 'Essential Logo Tee',
        'price' => 28.00,
        'sale_price' => 22.00,
        'is_on_sale' => true,
        'short_description' => 'Essential everyday tee with clean brndng. front print.',
        'variants' => [
            'sizes' => ['S', 'M', 'L', 'XL'],
            'colors' => ['Heather Grey', 'Charcoal'],
        ],
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