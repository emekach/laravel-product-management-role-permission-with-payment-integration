

```markdown
# E-Commerce Platform

An E-Commerce platform built with Laravel, Stripe, React, Laravel Breeze, and Laravel Sanctum, featuring role-based access control and email notifications.

## Table of Contents

1. [Overview](#overview)
2. [Features](#features)
3. [Installation](#installation)
4. [Usage](#usage)
5. [Role-Based Access Control](#role-based-access-control)
6. [Stripe Integration](#stripe-integration)
7. [React Front-End](#react-front-end)
8. [Key Actions and Events](#key-actions-and-events)
9. [Testing](#testing)
10. [Security Considerations](#security-considerations)

## Overview

This E-Commerce platform showcases a wide range of products, each categorized as either B2B (Business-to-Business) or B2C (Business-to-Consumer). Users can explore and purchase products with seamless authentication, role assignment, and payment processing. The platform utilizes Laravel Breeze for user authentication and Laravel Sanctum for API protection.

## Features

- Display B2B and B2C products with detailed information.
- User registration and authentication using Laravel Breeze and Sanctum.
- Role-based access control with three distinct roles: Admin, B2C Customer, and B2B Customer.
- Role assignment based on the type of product purchased (B2B or B2C).
- Integrated Stripe payment gateway for secure transactions.
- Automated email notifications to users upon successful purchases.
- Super admin can view all users and revoke access.

## Installation

To run this project locally, follow these steps:

1. Clone the repository:

   ```bash
   git clone https://github.com/your/repository.git
   cd your-repository
   ```

2. Install project dependencies:

   ```bash
   composer install
   npm install
   ```

3. Create a `.env` file and set your environment variables, including Stripe API keys and database configurations.

4. Generate an application key:

   ```bash
   php artisan key:generate
   ```

5. Migrate the database:

   ```bash
   php artisan migrate
   ```

6. Seed the database with sample products and roles:

   ```bash
   php artisan db:seed
   ```

7. Start the development server:

   ```bash
   php artisan serve
   ```

The project should now be accessible at `http://localhost:8000`.

## Usage

### Product Purchase

1. Browse products on the site and select a product for purchase.
2. If not logged in, you'll be redirected to the login or registration page.
3. After a successful purchase, a role is automatically assigned based on the product type (B2B or B2C).
4. An email notification is sent to the user with purchase confirmation.

## Role-Based Access Control

This project leverages Laravel Spatie for role management. Three roles are implemented:

- Admin
- B2C Customer
- B2B Customer

You can assign, modify, or revoke roles for users using Laravel Spatie's role management features.

## Stripe Integration

The Stripe payment gateway is integrated using GuzzleHTTP to ensure secure and seamless transactions. Configure your Stripe API keys in the environment variables for successful payment processing.

## React Front-End

The front-end of this project is built using React, following best practices. Emphasis is placed on pages updating without refreshing and efficient data retrieval from the backend.

### Key React Pages

- **Purchase Page**: Allows users to view and select products for purchase through Stripe. Accepts test credit card and registration details.

- **Login Page**: A secure login page is implemented using Laravel Breeze for user authentication.

- **Dashboard Page**: Accessible after login, this page dynamically displays the last 4 digits of the purchased card number under the label "B2C Purchase Details" for B2C customers. For B2B customers, the same information is shown.

- **Cancellation**: If applicable, users can cancel their purchase, and a cancel button is available on the dashboard page.

## Key Actions and Events

### Purchase

An email notification is sent to the customer upon successful purchase. The email includes the customer's name but omits other details.

### Payment Failure

Consider potential payment revocation scenarios and handle them gracefully.

### Access Cancellation

Notify the customer via email if their access is canceled for any reason.

## Testing

Comprehensive testing is implemented to ensure code reliability. Unit tests and integration tests are performed, using specific testing tools and libraries as required.

## Security Considerations

This project focuses on security best practices, including robust user input validation, strict authorization controls, and data encryption. Sensitive data such as credit card information is handled securely.
```

