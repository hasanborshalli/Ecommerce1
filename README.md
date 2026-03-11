# E-Commerce Template 1

### Luxury Editorial — by brndng.

A clean, responsive Laravel 11 e-commerce template with session-based cart,
full admin panel, SEO support, and MySQL database via phpMyAdmin.

---

## Stack

| Layer      | Technology                                  |
| ---------- | ------------------------------------------- |
| Backend    | Laravel 11 (PHP 8.2+)                       |
| Database   | MySQL via phpMyAdmin                        |
| Frontend   | Blade + vanilla CSS/JS                      |
| Fonts      | Cormorant Garamond + DM Sans (Google Fonts) |
| Build tool | Vite                                        |

---

## Quick Setup

### 1 — Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+ & npm
- MySQL running (XAMPP / WAMP / Laravel Herd / etc.)

---

### 2 — Clone & Install

```bash
# Install PHP dependencies
composer install

# Install JS dependencies
npm install
```

---

### 3 — Environment

```bash
# Copy the example env file
cp .env.example .env

# Generate app key
php artisan key:generate
```

Then open `.env` and update:

```env
APP_NAME="Your Store Name"
APP_URL=http://localhost:8000

DB_DATABASE=ecom_template1     # ← create this DB in phpMyAdmin first
DB_USERNAME=root
DB_PASSWORD=                   # your MySQL password

ADMIN_EMAIL=admin@yourstore.com
ADMIN_PASSWORD=yourpassword
```

---

### 4 — Database

**In phpMyAdmin:**

1. Create a new database named `ecom_template1` (or whatever you set in `.env`)
2. Collation: `utf8mb4_unicode_ci`

**Then run migrations and seed demo data:**

```bash
php artisan migrate
php artisan db:seed
```

This creates all tables and seeds:

- 6 categories
- 12 demo products
- 2 hero slides
- 5 testimonials
- All site settings

---

### 5 — Storage

```bash
# Link storage for uploaded images
php artisan storage:link
```

Upload placeholder images to:

- `storage/app/public/products/` — product images
- `storage/app/public/categories/` — category images
- `storage/app/public/hero/` — hero slide images
- `storage/app/public/settings/` — logo

---

### 6 — Build Assets

```bash
# Development (with hot reload)
npm run dev

# Production build
npm run build
```

---

### 7 — Run

```bash
php artisan serve
```

Store is live at: **http://localhost:8000**
Admin panel at: **http://localhost:8000/admin**

---

## Admin Panel

| Field    | Default value (change in `.env`) |
| -------- | -------------------------------- |
| Email    | `admin@store.com`                |
| Password | `admin123`                       |

**Admin sections:**

- **Dashboard** — revenue, orders, product stats
- **Products** — full CRUD, image upload, variants, SEO
- **Categories** — manage with images and sort order
- **Orders** — view, filter by status, update fulfillment & payment status
- **Messages** — contact form inbox with mark-read
- **Settings** — store name, logo, contact info, social links, shipping costs, SEO defaults

---

## Customising for a Client

When deploying this template for a client, update:

1. `.env` — `APP_NAME`, `APP_URL`, DB credentials, admin credentials
2. **Admin → Settings** — store name, logo, tagline, contact info, social links
3. **Admin → Categories** — replace demo categories with client's categories
4. **Admin → Products** — add client's real products with images
5. **Hero slides** — update via DB or add a hero slides admin section

No code changes needed for a standard deployment.

---

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   ├── ProductController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── ContactController.php
│   │   └── Admin/
│   │       ├── AdminAuthController.php
│   │       ├── AdminDashboardController.php
│   │       ├── AdminProductController.php
│   │       ├── AdminCategoryController.php
│   │       ├── AdminOrderController.php
│   │       └── AdminSettingController.php
│   └── Middleware/
│       └── AdminAuth.php
├── Models/
│   ├── Category.php
│   ├── Product.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── ContactMessage.php
│   ├── SiteSetting.php
│   ├── HeroSlide.php
│   └── Testimonial.php
└── Providers/
    ├── AppServiceProvider.php
    └── ViewServiceProvider.php

database/
├── migrations/          (5 migration files)
└── seeders/
    └── DatabaseSeeder.php

resources/views/
├── layouts/app.blade.php
├── home.blade.php
├── shop.blade.php
├── product.blade.php
├── cart.blade.php
├── checkout.blade.php
├── order-confirmation.blade.php
├── about.blade.php
├── contact.blade.php
├── partials/
│   ├── product-card.blade.php
│   └── pagination.blade.php
└── admin/
    ├── layout.blade.php
    ├── login.blade.php
    ├── dashboard.blade.php
    ├── settings.blade.php
    ├── messages.blade.php
    ├── products/
    │   ├── index.blade.php
    │   └── form.blade.php
    ├── categories/
    │   ├── index.blade.php
    │   └── form.blade.php
    └── orders/
        ├── index.blade.php
        └── show.blade.php

routes/
├── web.php
└── console.php

config/
└── admin.php
```

---

## Cart Behaviour

- Session-based — no login required
- Supports product variants (size, color, etc.)
- Each unique product+variant combination gets its own cart row
- Cart count in navbar updates live via fetch API
- Free shipping threshold configurable in admin settings

---

## SEO

Every page has:

- `<title>` tag
- `<meta name="description">`
- Open Graph tags (`og:title`, `og:description`, `og:image`, `og:url`)
- Twitter Card tags
- `<link rel="canonical">`

Product and category meta fields are editable per-item in the admin.

---

## Powered by brndng.

All pages display **"Powered by brndng."** in the footer.
Contact: brndnglb.com
