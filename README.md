# Goklean - Smart Car Cleaning & Maintenance Platform

Goklean is a comprehensive management system for car cleaning services, designed to streamline operations between customers and service units. Built with **Laravel 8**, it leverages a modern tech stack to handle subscriptions, real-time service tracking, and integrated payments.

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

## 🚀 Key Modules

### 📱 Mobile-First Service Units

Dedicated API for cleaners to manage their daily schedules, update wash statuses with before/after photos, and manage their availability.

### 💳 Payments & Wallet System

- **Razorpay Integration**: Seamless payment for single washes and subscriptions.
- **Internal Wallet**: Powered by `bavix/laravel-wallet`, allowing users to maintain a balance for quick bookings.

### 📅 Subscriptions & Plans

Flexible billing cycles (weekly/monthly) for recurring car cleaning services, with automated schedule generation.

### 🏷️ Dynamic Pricing

Product-based pricing that automatically adjusts according to car types (e.g., Hatchback, Sedan, SUV).

### 📢 Notification & Support System

- **FCM Push Notifications**: Real-time alerts for booking confirmations and service updates.
- **Service Complaints**: A robust module for customers to report issues and for admins to resolve them.

## 🛠 Tech Stack

- **Core**: Laravel 8.75+, PHP 8.1
- **Auth**: Laravel Jetstream & Sanctum
- **Frontend**: Inertia.js & Tailwind CSS
- **Database**: PostgreSQL
- **API**: Spatie Fractal for consistent JSON transformations
- **State Management**: Spatie Laravel Settings for persistent configurations

## 💻 Installation & Setup

### Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- PostgreSQL 12+

### Quick Start

1.  **Clone the Repo**:

    ```bash
    git clone https://github.com/ermradulsharma/Goklean.git
    cd goklean
    ```

2.  **Dependencies**:

    ```bash
    composer install
    npm install && npm run dev
    ```

3.  **Environment Setup**:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    _Update your `.env` with database and Razorpay credentials._

4.  **Database Initialization**:

    ```bash
    php artisan migrate:fresh --seed
    ```

5.  **Run Application**:
    ```bash
    php artisan serve
    ```

## 🧪 Testing

The project uses PHPUnit for automated testing.

```bash
vendor/bin/phpunit
```

## 📄 License

Goklean is open-sourced software licensed under the [MIT license](LICENSE).
