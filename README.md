# ARTFOLIO

ARTFOLIO is an online gallery and store for original digital artwork, built with Laravel and MySQL. Collectors browse artworks and artists, save favorites, and buy one-of-one pieces. Administrators manage the catalog and review payments from an admin dashboard.

## Features

### For collectors (clients)
- Browse the home page, artwork catalog, artwork details, artists, and About page **without logging in**. Each artwork shows a unique catalog number such as `AW-0001`. Logging in is required to add to the cart, check out, save favorites, or view orders.
- Browse artists and read the About page, which includes contact details and a feedback form.
- Save artworks to Favorites.
- Add artworks to a cart and check out with GCash or a card.
- Track orders in **My Orders**. After an admin approves the payment, download the original full-resolution file and a receipt.

### For administrators
- Dashboard with catalog, client, payment, and feedback summaries.
- **Artworks management (full CRUD):** add, view, edit, and delete artworks.
- Categories: add, rename, and show or hide categories.
- Orders: review payments, approve or reject them, mark refunds, and export orders to CSV.
- View customers and artists.

### Business rules
- Every artwork is **one of one**, so its stock is always 1. Checking out reserves it, an approved payment marks it sold, and a rejected payment puts it back on sale.
- Downloads unlock only after an admin approves the payment, and only for the buyer.
- Prices are in Philippine pesos (₱), stored as `DECIMAL(10,2)`. Order totals are added up in whole centavos, so they are always exact to two decimal places.

#### Quantity and stock rules
| Rule | Where it is enforced |
|---|---|
| A quantity must be the whole number `1`. Zero, negative, fractional, and larger quantities are rejected with a field message. | `CartController::validateQuantity()` |
| **Reserved:** at checkout, inside one database transaction, each artwork changes from `available` to `reserved` only if it is still available. If any artwork was taken in the meantime, nothing is saved and the buyer goes back to the cart. | `CheckoutController::store()` |
| **Deducted (sold):** when an admin approves the payment, the artworks become `sold`. | `Order::transitionTo()` |
| **Restored:** when an admin rejects the payment or marks a refund, the artworks become `available` again. | `Order::transitionTo()` |
| Each order line stores the artwork title, artist, unit price, quantity, and subtotal, so later catalog edits never change a past order. | `order_items` table |

#### Order status map
A submitted order is **not** a verified payment. It stays *Awaiting approval* until an admin checks the payment.

| Status (label) | Meaning | Allowed next status |
|---|---|---|
| `pending` (Awaiting approval) | Order placed, payment details submitted, artworks reserved | `completed`, `failed` |
| `completed` (Payment approved) | Admin confirmed the payment; artworks sold; downloads unlocked | `refunded` |
| `failed` (Payment rejected) | Admin rejected the payment with a reason; artworks back on sale | none |
| `refunded` (Refunded) | Approved payment returned; artworks back on sale | none |

Any other change is rejected. Status changes lock the order row, so clicking twice cannot apply the same change twice.

## CRUD module (Week 14): Artworks

The admin **Artworks** module is the project's main CRUD module. It stores records in the MySQL `artworks` table.

| Operation | How it works | Code |
|---|---|---|
| Create | **Add artwork** opens a form. Inputs are validated, the image is uploaded, and the record is saved. | `Admin\ArtworkController@store` |
| Read | The artwork list supports search, category and status filters, sorting, and pagination. **Preview** shows full details. | `Admin\ArtworkController@index` |
| Update | **Edit** loads the record into a form. Changes are validated and saved, and a replacement image is optional. | `Admin\ArtworkController@update` |
| Delete | **Delete** asks for confirmation, then removes the artwork and its files. Artworks that belong to an order cannot be deleted; archive them instead. | `Admin\ArtworkController@destroy` |

| Part | File |
|---|---|
| Migrations | `database/migrations/2026_10_31_000000_create_artworks_table.php` (and later `artworks` migrations) |
| Model | `app/Models/Artwork.php` |
| Controller | `app/Http/Controllers/Admin/ArtworkController.php` |
| Routes | `routes/web.php`: `Route::resource('artworks', ...)` in the `admin` group |
| Views | `resources/views/admin/artworks/index.blade.php` (list, plus Add and Edit forms in modal dialogs) and `resources/views/admin/artworks/partials/fields.blade.php` (shared form fields) |
| Tests | `tests/Feature/AdminArtworksTest.php` |

## Authentication (Week 15): Laravel Breeze

Authentication uses **[Laravel Breeze](https://laravel.com/docs/starter-kits)** (`laravel/breeze` in `composer.json`). The Breeze pages are restyled to match the ARTFOLIO design.

- **Register** (`/register`): name, username, email, password with confirmation, and a Terms and Conditions checkbox. Public sign-up always creates a client account.
- **Log in** (`/login`) with "Remember me", and **log out**.
- **Forgot and reset password**, **confirm password**, and **email verification** pages from Breeze.
- Passwords are hashed and logins use sessions.
- **Protected pages:** guests are redirected to the login page. Admin pages also require the `admin` role, and clients get "403 Forbidden".
- After logging in, `/dashboard` sends admins to `/admin` and clients to `/home`.

| Part | File |
|---|---|
| Controllers | `app/Http/Controllers/Auth/` |
| Login validation | `app/Http/Requests/Auth/LoginRequest.php` |
| Routes | `routes/auth.php` |
| Views | `resources/views/auth/` and `resources/views/layouts/auth.blade.php` |
| Admin check | `app/Http/Middleware/EnsureUserIsAdmin.php` |
| Tests | `tests/Feature/Auth/` and `tests/Feature/AuthFlowTest.php` |

## Tech stack

Versions installed in the development environment (`php -v`, `composer show --direct`):

| Component | Version | Purpose |
|---|---|---|
| PHP | 8.5.12 (requires 8.3+) | Server-side language |
| Laravel framework (`laravel/framework`) | 13.38.0 | MVC framework: routes, controllers, Eloquent, migrations |
| Laravel Breeze (`laravel/breeze`) | 2.4.2 | Registration, login, logout, password reset |
| Laravel Tinker (`laravel/tinker`) | 3.0.2 | Interactive console for debugging |
| MySQL (XAMPP) | Bundled with your XAMPP install | Application database |
| Bootstrap | 5.3.3 (CDN) | Responsive layout, forms, and modals on client, auth, and admin pages |
| Bootstrap Icons | 1.11.3 (CDN) | Icons on the login and registration pages |
| Chart.js | 4.4.8 (CDN) | Charts on the admin dashboard |
| Pest (`pestphp/pest`) | 4.7.8 | Automated feature tests (runs PHPUnit-style test classes) |
| Laravel Pint (`laravel/pint`) | 1.32.1 | Code style formatter |
| Faker (`fakerphp/faker`) | 1.24.1 | Fake data for test factories |

## Requirements

- [XAMPP](https://www.apachefriends.org/) with PHP 8.3+ and MySQL
- [Composer](https://getcomposer.org/)
- Git

You do not need Node.js or npm to run the app. The pages load their CSS directly from `public/css`.

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/krystelannelina-collab/ARTFOLIO.git
   cd ARTFOLIO
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Create your environment file**

   Copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```
   On Windows PowerShell, use `copy .env.example .env` instead. Then generate the application key:
   ```bash
   php artisan key:generate
   ```
   `.env.example` holds placeholder settings only. Never commit your `.env` file; it is in `.gitignore`.

4. **Set up MySQL**
   - Open the XAMPP Control Panel and start **MySQL**.
   - Open http://localhost/phpmyadmin, click **New**, and create a database named `artfolio` with collation `utf8mb4_unicode_ci`.
   - Check the database settings in `.env`:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=artfolio
     DB_USERNAME=root
     DB_PASSWORD=
     ```
     XAMPP's default `root` user has no password. If yours has one, put it in `DB_PASSWORD`.

5. **Set the first admin account**

   In `.env`, choose the admin's email and password:
   ```env
   ARTFOLIO_ADMIN_NAME="ARTFOLIO Admin"
   ARTFOLIO_ADMIN_EMAIL=admin@artfolio.com
   ARTFOLIO_ADMIN_PASSWORD=choose-a-strong-password
   ```
   No admin password is stored in the code. If `ARTFOLIO_ADMIN_PASSWORD` is empty, seeding skips the admin account.

6. **Run the migrations and sample data**
   ```bash
   php artisan migrate --seed
   ```
   This creates all tables (including the default categories and artists), the admin account, a sample client (`test@example.com` / `password`), and 10 sample artworks.

7. **Link the storage folder**, so uploaded artwork images can be displayed:
   ```bash
   php artisan storage:link
   ```

## Running the application

Start the development server:

```bash
php artisan serve
```

Open http://localhost:8000. Log in as the admin you set in step 5, or create a client account with **Sign Up**.

To make an existing account an admin:
```bash
php artisan app:promote-admin someone@example.com
```

## Optional settings

| Setting | Purpose |
|---|---|
| `ARTFOLIO_CONTACT_EMAIL`, `ARTFOLIO_CONTACT_PHONE`, `ARTFOLIO_FACEBOOK_URL`, `ARTFOLIO_CONTACT_ADDRESS` | Contact details on the About page. Empty values show "coming soon" placeholders. |
| `MAIL_MAILER` | Defaults to `log`: password reset emails are written to `storage/logs/laravel.log` instead of being sent. |
| GD extension | Enable `extension=gd` in `C:\xampp\php\php.ini` to create smaller public previews of uploaded artworks. Without it, the preview is a full-size copy. |

## Running the tests

```bash
php artisan test
```

The tests use a temporary in-memory SQLite database (see `phpunit.xml`), so they never change your MySQL data. PHP's `pdo_sqlite` extension must be enabled; it is enabled in XAMPP by default.

## Troubleshooting

- **"Access denied for user 'root'@'localhost'" or XAMPP MySQL will not start:** another MySQL server may be using port 3306, such as a separately installed MySQL 8.0 service. Stop it in Windows **Services** (`services.msc`), or use that server's password in `DB_PASSWORD`.
- **Uploaded images do not appear:** run `php artisan storage:link`.
- **Changes to `.env` have no effect:** run `php artisan config:clear`.

## Project structure

```
app/Http/Controllers/          Storefront controllers (artworks, cart, checkout, orders, favorites)
app/Http/Controllers/Admin/    Admin controllers (dashboard, artworks, categories, orders, customers, artists)
app/Http/Controllers/Auth/     Laravel Breeze authentication controllers
app/Models/                    Eloquent models (Artwork, Category, Order, OrderItem, User, Feedback, Artist)
database/migrations/           Database tables
database/seeders/              Admin account and sample artworks
resources/views/               Blade templates (auth/, admin/, storefront pages)
public/css/                    Page stylesheets
routes/web.php, routes/auth.php
tests/Feature/                 Automated feature tests
```

## Team

| Member | GitHub account | Main contributions |
|---|---|---|
| Cristine Joy Montes | [cristinmontes](https://github.com/cristinmontes) | Client UI Developer |
| Gian Gomez | [giangomez](https://github.com/giangomez) | Project/Brand Designer |
| Kalvin Dave Ramos | [Kalvindave](https://github.com/Kalvindave) | Admin UI Developer |
| Krystel Anne Lina | [krystelannelina-collab](https://github.com/krystelannelina-collab) | UI/UX Integration Developer |

## Collaboration workflow

1. Create a branch for each feature: `git switch -c feature/short-name`.
2. Commit with your own GitHub account and push the branch.
3. Open a pull request into `main` and ask a teammate to review it before merging.
