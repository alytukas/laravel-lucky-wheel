# Laravel Lucky Wheel

🎲 An interactive, animated, and easily configurable Lucky Wheel package for Laravel e-commerce and web projects. Engage your users by letting them spin the wheel to win discount codes or free shipping, while you manage prizes, probability weights, design themes, and email capture directly from the admin dashboard.

---

## ✨ Features

- **3 Built-in Themes:** Modern (`default`), Festive Christmas with snowflakes (`christmas`), and Bright Summer (`summer`).
- **Anti-Cheat System:** Limits spins based on User ID, IP address, browser fingerprint, and cookies using a customizable cooldown period (`cooldown_hours`).
- **Flexible Email Capture Policy:** 
  - `none`: Not required (guests can spin immediately).
  - `before_spin`: Require an email address before spinning the wheel.
  - `after_win`: Require an email address after winning to reveal/claim the prize code.
- **Weighted Probability Algorithm:** Admins can set a `probability_weight` for each prize. The higher the weight, the higher the chance of winning.
- **Automatic Code Generation:** Automatically generates unique promo codes (e.g., `WHEEL-ABC123`) and links them to your existing discount codes system.
- **Multilingual Support (i18n):** Full support for English (EN) and Lithuanian (LT) out of the box.

---

## 🚀 Installation

You can install the package via composer:

```bash
composer require gwebas/laravel-lucky-wheel

🛠️ Configuration & Migrations

After installing the package, publish the configuration, migrations, and translation files to your main project:
Bash

php artisan vendor:publish --tag=lucky-wheel-config
php artisan vendor:publish --tag=lucky-wheel-migrations
php artisan vendor:publish --tag=lucky-wheel-lang

Run the database migrations (this will create the lucky_wheel_settings, lucky_wheel_prizes, and lucky_wheel_spins tables):
Bash

php artisan migrate



📖 Usage

1. Frontend Integration

To display the wheel on your website, insert the following Blade component into your main layout file (e.g., resources/views/layouts/app.blade.php), just before the closing </body> tag:
Blade

<x-lucky-wheel::wheel />

The component automatically checks if the lucky wheel is enabled in the admin panel, applies the selected theme, and displays a floating button in the bottom corner of the screen.


2. Admin Dashboard

The control panel is accessible via:
Plaintext

GET /admin/lucky-wheel

(Note: This route is protected by web and auth middleware).

From the dashboard, administrators can:

    Enable / Disable the wheel globally with one click.

    Change the active Design Theme (default, christmas, summer).

    Set the Email Requirement Policy (none, before_spin, after_win).

    Adjust the Cooldown Hours for repeat spins.

    Create, edit, and delete prizes, and adjust their winning probabilities.

    

🌐 Localization

The package automatically uses your application's current locale (app()->getLocale()).
Supported languages by default: EN (English) and LT (Lithuanian).

If you want to force a specific language only for the lucky wheel, open config/lucky-wheel.php and change the value:
PHP

'locale' => 'en', // Or 'lt', or null (for auto-detection)

To edit or add translations for other languages, modify the files in:
resources/lang/vendor/lucky-wheel/
📝 License

The MIT License (MIT).