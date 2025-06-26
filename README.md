# 🍽️ Restaurant Reservation API

A Laravel 11-based REST API for restaurant reservation, ordering, and checkout, using:

- Laravel Sanctum authentication
- Strategy Pattern for flexible payment calculation
- Docker (via Laravel Sail)
- Pest-based unit tests

## 🔑 Features

- ✅ Check table availability
- ✅ Reserve table for customers
- ✅ List meals with available stock
- ✅ Place orders (authenticated waiters)
- ✅ Pay orders using flexible strategies (tax + service)

## 🚀 Setup Instructions

```bash
git clone https://github.com/your-username/restaurant-reservation-api.git
cd restaurant-reservation-api
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed