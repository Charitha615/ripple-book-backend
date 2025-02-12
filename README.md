<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <a href="https://github.com/laravel/framework/actions">
        <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
    </a>
</p>

## 📖 About This Project

This is a Laravel-based web application with database migrations and seeders to set up a structured user management system.

### Features:

- User authentication
- Role-based permissions
- Database migrations & seeders
- API endpoints for data management
- Optimized for performance

## 🚀 Getting Started

### Prerequisites

Ensure you have the following installed on your system:

- PHP (>=8.0)
- Composer
- MySQL or PostgreSQL (or any supported database)
- Laravel Installer (optional)
## 🛠 All Command

```angular2html
    npm install
    composer install
    php artisan key:generate
    php artisan migrate
    php artisan db:seed --class=UsersTableSeeder
    php artisan serve

```


## 🛠 Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/your-repo-name.git
    cd your-project-folder
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Install front-end dependencies (if applicable):

    ```bash
    npm install && npm run dev
    ```

4. Copy `.env.example` to `.env`:

    ```bash
    cp .env.example .env
    ```

5. Update the `.env` file with database credentials:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```

6. Generate the application key:

    ```bash
    php artisan key:generate
    ```

## 📂 Database Setup

Run the following command to create tables:

```bash
php artisan migrate
