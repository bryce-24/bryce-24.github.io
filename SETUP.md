# DogPics Weekly - Backend Setup Guide

## Overview
This is a PHP-based backend system for managing dog picture email subscriptions with an MVC architecture.

## Structure
- **Controllers**: Business logic (`controllers/SubscriptionController.php`, `controllers/AdminController.php`)
- **Views**: Presentation layer (`views/subscribe.php`, `views/manage.php`, `views/unsubscribe.php`, `views/confirm.php`, `views/admin.php`)
- **Routes**: File-based routing (PHP files in `business/` directory)

## Database Setup

1. Create the database:
   ```sql
   mysql -u root -p < database.sql
   ```

2. Or manually run the SQL commands from `database.sql`:
   - Creates `portfolio_db` database
   - Creates `users` table (if not exists)
   - Creates `subscriptions` table with all necessary fields

## Configuration

Update `config.php` with your database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'portfolio_db');
```

## Interfaces

The system includes 5 main interfaces:

1. **Subscribe** (`business.php`)
   - Main subscription form
   - Collects user information and preferences
   - Validates and stores subscriptions

2. **Manage** (`business/manage.php`)
   - Allows users to find and update their subscription
   - Update preferences (dog type, delivery day, newsletter)

3. **Unsubscribe** (`business/unsubscribe.php`)
   - Unsubscribe via email or token link
   - Token-based unsubscribe from email links

4. **Confirm** (`business/confirm.php`)
   - Confirmation page after subscription
   - Shows success message

5. **Admin Dashboard** (`business/admin.php`)
   - Requires authentication (uses `auth.php`)
   - View all subscriptions
   - Update subscription status
   - Delete subscriptions
   - Send test emails
   - View statistics

## Features

- MVC architecture (Models, Views, Controllers)
- Session-based messaging
- Email validation
- Token-based unsubscribe links
- Admin authentication
- Subscription status management (active, pending, unsubscribed)
- Statistics dashboard

## File Structure

```
/
├── business.php              # Main subscription page
├── business/
│   ├── manage.php            # Subscription management
│   ├── unsubscribe.php      # Unsubscribe page
│   ├── confirm.php           # Confirmation page
│   └── admin.php             # Admin dashboard
├── controllers/
│   ├── SubscriptionController.php
│   └── AdminController.php
├── views/
│   ├── subscribe.php
│   ├── manage.php
│   ├── unsubscribe.php
│   ├── confirm.php
│   └── admin.php
├── config.php                # Database configuration
├── auth.php                  # Authentication check
└── database.sql              # Database schema
```

## Usage

1. Access the subscription page: `http://yourdomain.com/business.php`
2. Users can subscribe, manage, or unsubscribe
3. Admins can access: `http://yourdomain.com/business/admin.php` (requires login)

## Notes

- Email sending functionality is stubbed (needs implementation)
- Admin routes require authentication via `auth.php`
- All forms include CSRF protection through session validation
- Subscription tokens are generated using `random_bytes()` for security

