# FurniCraft

FurniCraft is a Laravel e-commerce application with a modern admin panel for managing catalog, customers, orders, and storefront content. The customer-facing API and database layer are unchanged; the admin experience is built with Blade, Tailwind CSS, Alpine.js, and Chart.js.

## Features

### Admin panel

- **Dashboard** — Revenue, orders, customers, and product metrics with trend indicators, Chart.js charts, recent orders, activity feed, and top products
- **Catalog** — Products (filters, stock badges, drag-and-drop image upload), categories, banners
- **Sales** — Order list with filters, order detail with status workflow, invoice print/PDF
- **Customers** — Search, profile images, active/inactive status

### UI/UX

- Premium SaaS-style layout: collapsible sidebar, sticky header, card-based pages
- Light and dark mode (persisted in `localStorage`)
- Responsive layout (mobile drawer sidebar, scrollable tables)
- Reusable Blade components under `resources/views/components/admin/`
- Toast-style flash messages, modals, and Alpine.js interactions

## Tech stack

| Layer | Technology |
|--------|------------|
| Backend | Laravel (PHP), existing routes & controllers |
| Views | Blade |
| Styling | Tailwind CSS v4 (Vite) |
| Interactivity | Alpine.js |
| Charts | Chart.js |
| Icons | Heroicons (inline SVG) |
| Auth | Laravel session auth (admin) |

## Requirements

- PHP 8.2+
- Composer
- Node.js 20+ (Vite 7 recommends 20.19+ or 22.12+)
- MySQL / MariaDB (or your configured database)

## Installation

```bash
# Clone and enter the project
cd furnicraft

# PHP dependencies
composer install

# Environment
cp .env.example .env
php artisan key:generate

# Configure .env (DB_*, APP_URL, etc.), then:
php artisan migrate
php artisan storage:link

# Frontend dependencies & build
npm install
npm run build
```

## Development

Run the app and Vite dev server in separate terminals:

```bash
php artisan serve
npm run dev
```

Admin login: `/admin/login`  
Dashboard (authenticated): `/admin/dashboard`


## Frontend structure

```
resources/
├── css/app.css              # Tailwind v4, theme, dark mode variant
├── js/app.js                # Alpine.js, Chart.js, adminUi()
└── views/
    ├── layouts/
    │   ├── app.blade.php    # Admin shell (sidebar + header)
    │   └── guest.blade.php  # Login / password reset
    ├── components/admin/    # card, badge, button, field, flash, page-header
    └── admin/               # Feature views (dashboard, products, orders, …)
```

Blade components are invoked as `<x-admin.card>`, `<x-admin.button>`, etc.

## Admin routes (overview)

| Area | Routes |
|------|--------|
| Dashboard | `GET /admin/dashboard` |
| Products | `resources/products` |
| Categories | `resources/categories` |
| Customers | `resources/customers` |
| Banners | `resources/banners` |
| Orders | `GET /orders`, `GET /orders/{order}`, status update, invoice |

API routes for the mobile/storefront app live in `routes/api.php` and are separate from the admin UI.

## Production build

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Ensure `APP_ENV=production` and `APP_DEBUG=false` in `.env`.

## Editor tips

- **Format document (VS Code / Cursor):** `Shift + Alt + F`

## License

This project builds on the [Laravel framework](https://laravel.com), which is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
