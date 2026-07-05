# web-personal-financial-management-system
A secure, web-based personal financial management platform built with Laravel and MySQL using a customized MVC architecture. Features comprehensive transaction CRUD, category management, budget thresholds, and savings goal tracking.

💰 Web-Based Personal Financial Management System

A robust personal finance tracking platform developed as a university group project. Built with the Laravel framework, this system provides a secure environment for users to monitor their income and expenses, manage budgets, and achieve long-term savings goals.

Features

Secure user registration and login (Laravel Authentication)

Strict multi-user data isolation (Users only access their own records)

Comprehensive Transaction Management (Full CRUD for Income/Expenses)

Dynamic Category Management for better organization

Budgeting thresholds with automated tracking

Interactive Savings Goal tracking and progress monitoring

Server-side input validation for all financial data

User profile management and secure logout

Technologies

Laravel Framework (PHP)

MySQL / MariaDB Relational Database

Blade Templating Engine (UI Design)

Customized MVC Architecture

Bootstrap / CSS3

XAMPP Development Suite

Project Structure

routes/web.php (Request mapping and routing logic)

app/Http/Controllers/ (Core business logic, validation, and CRUD operations)

app/Models/ (Eloquent ORM representing database entities and relationships)

resources/views/ (Blade templates for interactive user interfaces)

database/migrations/ (Relational database schema blueprints and ERD configuration)

How to Run

1. Environment Setup

Install XAMPP (with PHP 8.1+ and MySQL), Composer, and Node.js on your local machine.

2. Clone & Setup Project

Move the project folder into your XAMPP root folder:
C:/xampp/htdocs/personal-finance-system

3. Dependency Installation

Navigate to your project directory and run:

composer install


4. Database Configuration

Create a database named personal_finance in phpMyAdmin.

Create your .env file and set your database credentials:

DB_DATABASE=personal_finance
DB_USERNAME=root
DB_PASSWORD=


Generate the application key and run migrations:

php artisan key:generate
php artisan migrate


5. Start the Application

Start Apache and MySQL in XAMPP.

Run the Laravel development server:

php artisan serve


Access via browser: http://127.0.0.1:8000

📸 Screenshots

Authentication & Security

Register Account

Secure Login

Financial Dashboard

Transaction & Budget CRUD

View Records

Add Income/Expense

Data Validation

Input Error Handling

Learning Outcomes

Mastery of Laravel's Model-View-Controller (MVC) architecture

Relational database design and implementation (ERD via Migrations)

Backend authorization and secure routing middleware

Implementation of multi-tenant data privacy logic

Server-side form validation mechanisms

Collaborative team development using Git
