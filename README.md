# Laravel Project Setup

## Overview

This Laravel project allows users to manage holiday data across multiple countries. Users can easily add, update, and remove holiday information, ensuring that they have the most accurate and up-to-date details for each country. The intuitive interface makes it simple to navigate and maintain holiday records.

### Key Features

- Display Holidays in a Calendar View.
- CRUD Operations for Holidays.
- Multiple Holidays per Date.
- Month and Country Selection.
- Modals for Holiday Management,

## Prerequisites

1. **PHP** (version 8.0 or higher)
2. **Composer** (for managing PHP dependencies)
3. **MySQL** (or any other compatible database)

## Setup Instructions

### 1. Clone the Repository

Clone the repository to your local machine:

```bash
git clone https://github.com/shailendradigarse/custom-calender.git
cd custom-calender
```
### 2. Install PHP Dependencies

Install the PHP dependencies using Composer:

```bash
composer install
```

### Configure Environment Variables

1. **Create the .env File**:

Copy the example environment file to create a new .env file:

```bash
cp .env.example .env
```

2 **Set Up Database Configuration**:

Open the .env file and configure your database settings:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

Make sure to replace your_database_name, your_database_username, and your_database_password with your actual database credentials.

4. **Generate Application Key**:
Generate a new application key for the Laravel project:

```bash
php artisan key:generate
```
5. **Run Migrations**:
Run the database migrations to set up the necessary tables:

```bash
php artisan migrate
```
6. **Start PHP Server**:
Open a new terminal and start the PHP development server:

```bash
php artisan serve
```
The application will be accessible at http://127.0.0.1:8000 (or another port specified in the output).

## Summary
Cloned the repository and installed dependencies.
Configured the .env file for database settings.
Generated application key and ran migrations.
Ran the PHP server to access the application.
