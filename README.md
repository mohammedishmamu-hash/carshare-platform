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

### 1. Navigate to the project folder

```bash
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