# MarketLink — eGreen Basket

A community-focused web platform connecting local farmers with consumers. The application allows shoppers to find nearby farmers markets, browse seasonal produce, and place pre-orders for convenient pickup at local market stalls.

---

## Project Overview

MarketLink bridges the gap between local growers and community buyers:
- **Farmers** can set up digital stalls, list harvest produce, manage inventory and weekly stock, and receive pickup pre-orders.
- **Shoppers / Customers** can discover neighborhood markets on an interactive map, browse products by category or farm, and reserve fresh harvest for stall pickup.
- **Administrators** can approve new farmer registrations, manage market locations, oversee listings, and review platform activity.

Payment is settled in cash upon collection at the stall, eliminating delivery delays and third-party middleman fees.

---

## Default Login Credentials

All test accounts use the password: `password`

| Role | Email | Password | Details |
| :--- | :--- | :--- | :--- |
| **Admin** | admin@example.com | password | Full administrative access and moderation |
| **Farmer** | farmer@example.com | password | Green Valley Organics stall management |
| **Farmer 2** | farmer2@example.com | password | Sunshine Acres stall management |
| **Farmer 3** | farmer3@example.com | password | Riverside Herb Co. stall management |
| **Customer** | customer@example.com | password | Shopper account with sample order history |

---

## Requirements

- PHP >= 8.1
- Composer
- MySQL Database
- Web server (Apache, Nginx, or PHP built-in server)

---

## Installation & Setup

Follow these steps to set up the project locally:

### 1. Project Directory
Open your terminal in the project folder:
```
cd "techwiz market link"
```

### 2. Install Dependencies
Install PHP dependencies using Composer:
```
composer install
```

### 3. Environment Configuration
Copy the sample environment file to create your `.env` file:
```
copy .env.example .env
```
*(On Linux/macOS, use `cp .env.example .env`)*

Open `.env` and verify your database connection settings:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketlink
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Application Key
Generate the application encryption key:
```
php artisan key:generate
```

### 5. Database Setup & Seeding
Create the database in MySQL (e.g. `marketlink`), then run migrations and seeders:
```
php artisan migrate:fresh --seed
```
This sets up all tables along with demo markets, farmer stalls, categories, products, and test accounts.

### 6. Storage Link
Link the storage folder to make uploaded product images publicly accessible:
```
php artisan storage:link
```

### 7. Run the Application
Start the local development server:
```
php artisan serve
```

The application will be accessible at: `http://127.0.0.1:8000`

---

## Core Features

### Customer Portal
- Browse upcoming farmers markets and see participating growers
- Interactive map view of market locations
- Produce catalog with category filters, organic indicators, and search
- Basket and pre-order reservation for market pickup
- Order tracking and order history
- Stall and product reviews after order completion
- Save favorite growers and products

### Farmer Portal
- Stall profile management (operating days, pickup windows, cutoff times)
- Product management (add, edit, toggle availability, pricing, photos)
- Weekly recurring stock tracking
- Incoming pre-orders management (accept, mark ready for pickup, completed)
- Customer feedback and review responses
- Stall sales and order summary

### Admin Panel
- Platform overview and key metrics
- Grower onboarding review (approve or suspend stalls)
- Market venue management (timings, addresses, geolocation)
- Product and review moderation
- System configuration and announcements

---

## Built With

- **Backend**: Laravel 10 (PHP)
- **Frontend**: Blade Templates, Bootstrap 5, Custom CSS
- **Database**: MySQL
- **Maps**: Leaflet / OpenStreetMap integration
