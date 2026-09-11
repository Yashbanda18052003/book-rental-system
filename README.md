# Online Rental Book Management System

## Overview
A web-based platform built with Laravel for managing online book rentals, membership subscriptions, reservations, and overdue fines. The system provides separate workflows for regular readers and platform administrators to streamline inventory management, payments, and activity tracking.

## Features
- **Authentication & Authorization:** Secure user registration, login, session handling, and role-based middleware (Admin vs. User).
- **Book Catalog & Inventory:** Searchable book catalog with rental availability tracking and CRUD management for admins.
- **Rental & Reservation Management:** Book checkout workflow, active rental tracking, reservation queuing, and automated reservation expiry.
- **Fines & Automated Billing:** Scheduled fine calculation commands (`GenerateFines`) for overdue books and automated status updates.
- **Subscriptions & Plans:** Configurable membership tiers with automated subscription lifecycle tracking.
- **Payment Processing:** Integrated Stripe checkout for rentals, fine payments, and membership subscriptions.
- **Reporting & Exports:** Data export functionality (`RentalsExport`, `PaymentsExport`, `FinesExport`, `SubscriptionsExport`) for operational reporting.
- **Notifications:** Transactional email alerts for rental confirmations, returns, fine generation, and reservations.

## Tech Stack
- **Backend:** PHP 8.2+, Laravel 11
- **Database:** MySQL
- **Frontend:** Blade Templates, JavaScript, HTML5, CSS3, Bootstrap
- **Payment Gateway:** Stripe API
- **Packages:** Maatwebsite Excel, Guzzle HTTP

## Project Structure
```
app/
├── Console/Commands/   # Scheduled tasks (GenerateFines, ExpireReservations)
├── Exports/            # Data export definitions for reports
├── Http/
│   ├── Controllers/    # Auth, Admin, Book, Rental, Stripe, Notification controllers
│   └── Middleware/     # Admin protection and cache prevention
├── Models/             # Eloquent models (Book, Rental, Fine, Subscription, Payment)
└── Services/           # Third-party integration services
resources/views/        # Blade templates (admin & user dashboards)
routes/web.php          # Web application routes
```

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Yashbanda18052003/book-rental-system.git
   cd book-rental-system
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install Frontend dependencies:**
   ```bash
   npm install && npm run build
   ```

4. **Environment Setup:**
   Copy `.env.example` to `.env` and generate an application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

## Environment Variables
Configure the following in your `.env` file:
```env
APP_NAME=BookNest
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=book_rental
DB_USERNAME=root
DB_PASSWORD=your_mysql_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password

STRIPE_KEY=your_stripe_public_key
STRIPE_SECRET=your_stripe_secret_key
```

## Database Setup

1. Create a MySQL database named `book_rental`.
2. Run database migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

## How to Run

1. **Start local development server:**
   ```bash
   php artisan serve
   ```
2. Open `http://127.0.0.1:8000` in your browser.
3. **Run Scheduled Commands (Optional):**
   ```bash
   php artisan fines:generate
   php artisan reservations:expire
   ```

## Screenshots
*(Add project screenshots here)*

## Future Improvements
- Implement RESTful API endpoints with Laravel Sanctum for mobile app integration.
- Add advanced search filtering (by genre, author, and availability status).
- Integrate automated SMS reminder notifications for return due dates.

## Author
**Yash Banda**  
LinkedIn: [linkedin.com/in/yash-banda-829633259](https://www.linkedin.com/in/yash-banda-829633259/)
