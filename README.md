# Carshare Platform

A Laravel + Vue.js web application that displays vehicle fleet, enriched with booking data.

---

## Tech Stack

| Layer      | Technology                        |
|------------|-----------------------------------|
| Backend    | PHP 8.5+, Laravel 13.6            |
| Database   | MySQL 9.6+                        |
| Frontend   | Vue.js 3 (CDN), Bootstrap 5       |

---

## Architecture
External Vehicles API + bookings.json
↓
Laravel Seeders         ← validates & seeds DB
↓
MySQL Database          ← source of truth
↓
VehicleController         ← thin, connects model to view
↓
Vehicle Model           ← query logic lives here
↓
GET /api/vehicles        ← JSON response
↓
Vue.js Frontend          ← renders vehicle cards

---

## Project Structure

app/
Http/
Controllers/
VehicleController.php   ← thin controller, calls model
Middleware/
SecurityHeaders.php     ← security headers on every response
Models/
Vehicle.php               ← query logic, enrichment
Location.php              ← belongs to vehicle
Booking.php               ← belongs to vehicle
Account.php               ← satisfies FK constraint on bookings
database/
migrations/                 ← locations, vehicles, accounts, bookings
seeders/                    ← fetches API + loads bookings.json
resources/views/
app.blade.php               ← SPA shell, loads Vue
public/
js/app.js                   ← Vue 3 app, fetches /api/vehicles
css/app.css                 ← custom styles on top of Bootstrap
routes/
api.php                     ← GET /api/vehicles
web.php                     ← GET / → Blade shell
bookings.json                 ← local booking data source

---
## Prerequisites

- PHP 8.5+
- Composer
- MySQL 9.6+

## Setup

### 1. Clone the repository

```bash
git clone https://github.com/your-username/carshare-platform.git
cd carshare-platform
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your MySQL credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carshare_platform
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Create the database

```bash
mysql -u root -e "CREATE DATABASE carshare_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 5. Run migrations and seed

```bash
php artisan migrate --seed
```

### 6. Start the server

```bash
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000)

---

## API

### `GET /api/vehicles`

Returns all vehicles enriched with booking information.

**Success response:**
```json
{
  "data": [
    {
      "id": 1,
      "make": "Toyota",
      "model": "Camry",
      "year": 2019,
      "colour": "Silver",
      "location": "Burnaby - Metrotown Towers",
      "total_bookings": 3,
      "is_available": true
    }
  ]
}
```

**Error response:**
```json
{
  "error": "Unable to retrieve vehicles. Please try again later."
}
```

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
