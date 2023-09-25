# Development Challenge for PHP

This challenge aims to evaluate basic skills in PHP development, and a bit of data/entity modeling. The idea is to build an HTTP REST API.

## Table of Contents

- [Installation Instructions](#installation-instructions)
- [Specifications](#specifications)
- [API Documentation](#api-documentation)

## Installation Instructions

Follow these steps to set up and run the project in your local environment:

```bash
git clone https://github.com/Jsantangelo83/laravel_challenge
composer install
cp .env.example .env
php artisan key:generate

# Configure environment variables in the .env file
# Generate the secret key for JWT
php artisan jwt:secret

# Run migrations to create database tables
php artisan migrate

# Start the development server
php artisan serve
```

## Specifications

- **Laravel Version:** 10.18.0
- **PHP Version:** 8.2.6
- **Database:** MySQL 
- **Additional Dependencies:** List of packages or libraries used in the project.

## Viewing API Documentation

This project uses [L5 Swagger](https://github.com/DarkaOnLine/L5-Swagger) to generate API documentation from your Laravel application's routes. Follow the steps below to access and view the generated API documentation:

1. Ensure your Laravel application is running on the development server:

```bash
Open a web browser and navigate to the following URL: http://127.0.0.1:8000/api/documentation

```
This URL will take you to the Swagger UI interface, where you can explore and interact with your API endpoints. The Swagger UI provides a user-friendly interface for testing your API endpoints and viewing the documentation.

Please note that the URL might vary based on your application's configuration and the port being used. Adjust the URL accordingly if needed.

