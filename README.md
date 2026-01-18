# Barq Delivery System

District-based delivery application for Nouakchott, Mauritania.

## Features

### Customer Features
- Register and login
- Create new delivery orders
- Select pickup and delivery districts
- See delivery fee in real-time before submitting (100-200 MRU)
- Cancel orders when status is pending or accepted
- View order history
- See driver information after order is accepted
- Get 4-digit delivery code for verification
- WhatsApp contact with driver

### Driver Features
- Register as driver with initial 50 points
- Select operating districts
- View available orders from selected districts
- See complete order details including:
  - Customer name, phone, WhatsApp
  - Pickup and delivery districts
  - Detailed address
  - Order details
  - Delivery fee
- Accept orders (costs 20 points per order)
- Update order status (accepted → picked up → delivered)
- View rating and points balance

### Admin Features
- View dashboard with statistics
- Manage users
- Add points to drivers
- View and manage all orders
- View and manage districts

## Installation

1. **Database Setup**
   - Create a MySQL database named `barq`
   - Update database credentials in `config.php` if needed (default: localhost, root, no password)

2. **Deploy Files**
   - Upload all files to your web server
   - Ensure your web server has PHP 7.4+ with PDO MySQL extension

3. **First Run**
   - Navigate to `index.php` in your browser
   - The database tables will be created automatically
   - Default users will be created:
     - Admin: phone `20000001`, password `123`
     - Driver: phone `30000002`, password `123`, 50 points
     - Customer: phone `40000003`, password `123`

## Districts

The system includes 10 districts of Nouakchott:
1. Tevragh Zeina - تفرغ زينة
2. Ksar - لكصر
3. Sebkha - سبخة
4. Teyarett - تيارت
5. Dar Naïm - دار النعيم
6. Toujounine - توجنين
7. Arafat - عرفات
8. El Mina - الميناء
9. Riyad - الرياض
10. Tarhil - الترحيل

## Pricing

Delivery fees range from 100 to 200 MRU based on district pairs. The exact pricing matrix is stored in the `district_prices` table.

## Technology Stack

- **Backend**: PHP with PDO (MySQL)
- **Frontend**: HTML5, Bootstrap 5, JavaScript
- **Database**: MySQL
- **Languages**: Arabic (primary) and French

## Configuration

Edit `config.php` to customize:
- Database connection settings
- WhatsApp number: `22241312931`
- Help email: `help@barqmr.com`
- Help phone: `+222 41 31 29 31`
- Points cost per order: `20`

## File Structure

```
Barq/
├── config.php              # Database configuration and schema
├── functions.php           # Helper functions and translations
├── api.php                 # API endpoints
├── actions.php             # Form action handlers
├── index.php               # Main application file
├── css/
│   └── style.css          # Stylesheet
├── js/
│   └── app.js             # JavaScript functions
└── pages/
    ├── welcome.php        # Landing page
    ├── login.php          # Login form
    ├── register.php       # Registration form
    ├── profile.php        # User profile
    ├── orders.php         # Customer orders list
    ├── customer_dashboard.php  # Customer main page
    ├── driver_dashboard.php    # Driver main page
    ├── settings.php       # Driver district settings
    ├── admin_dashboard.php     # Admin main page
    ├── admin_users.php         # User management
    ├── admin_orders.php        # Order management
    └── admin_districts.php     # District management
```

## Security Notes

- Passwords are hashed using PHP's `password_hash()`
- SQL injection protection via PDO prepared statements
- XSS protection via `htmlspecialchars()` output escaping
- Session-based authentication

## Support

For help and support:
- WhatsApp: +222 41 31 29 31
- Email: help@barqmr.com
- Phone: +222 41 31 29 31

## License

Proprietary - All rights reserved
