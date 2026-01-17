# Goklean

Goklean is a car cleaning and maintenance service management application built with Laravel. It provides features for bookings, scheduling, service tracking, and customer management.

## Features

- **Booking Management**: Customers can book single or bulk cleaning services.
- **Service Scheduling**: Automated scheduling for service units.
- **Service Tracking**: Real-time updates on cleaning progress with photo evidence.
- **Customer Portal**: Manage cars, subscriptions, and service history.
- **Wallet and Payments**: Integrated payment systems for seamless transactions.
- **Service Complaints**: Dedicated module for handling customer feedback and complaints.

## Prerequisites

- PHP >= 8.0
- Composer
- Node.js & NPM
- PostgreSQL (or your preferred database)

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/mradulsharma/goklean.git
    cd goklean
    ```

2. Install PHP dependencies:

    ```bash
    composer install
    ```

3. Install JavaScript dependencies:

    ```bash
    npm install
    ```

4. Configure the environment:

    ```bash
    cp .env.example .env
    # Update your DB credentials and other settings in .env
    ```

5. Generate the application key:

    ```bash
    php artisan key:generate
    ```

6. Run migrations and seed the database:

    ```bash
    php artisan migrate:fresh --seed
    ```

7. Start the development server:
    ```bash
    php artisan serve
    ```

## Development

- **Migrations**: Database schema files are located in `database/migrations`.
- **Models**: Business logic and database interactions are in `app/Models`.
- **Controllers**: API and web controllers are in `app/Http/Controllers`.
- **Routes**: API and web routes are in the `routes` directory.

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
