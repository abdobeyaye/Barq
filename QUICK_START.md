# Quick Start Guide - Barq Delivery System

## Installation Steps

1. **Setup Database**
   ```sql
   CREATE DATABASE barq CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Configure Database Connection**
   Edit `config.php` lines 5-8:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'barq');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

3. **Upload Files**
   - Upload all files to your web server
   - Ensure PHP 7.4+ with PDO MySQL extension

4. **First Visit**
   - Navigate to: `http://yourserver.com/install.php`
   - Verify all checks pass (green checkmarks)
   - Database tables will be created automatically

5. **Start Using**
   - Go to: `http://yourserver.com/index.php`
   - Login with default users

## Default Login Credentials

### Admin Account
- **Phone:** 20000001
- **Password:** 123
- **Access:** Full system management

### Driver Account
- **Phone:** 30000002
- **Password:** 123
- **Points:** 50 (initial balance)
- **Access:** Accept and deliver orders

### Customer Account
- **Phone:** 40000003
- **Password:** 123
- **Access:** Place and track orders

## How to Use

### As Customer:
1. Login with customer credentials
2. Fill order form (details, phone, districts, address)
3. See delivery fee automatically calculated
4. Submit order
5. Track order status
6. Cancel if needed (pending/accepted status only)
7. Contact driver via WhatsApp when accepted

### As Driver:
1. Login with driver credentials
2. Go to Settings → Select your operating districts
3. View available orders from your districts
4. Accept order (costs 20 points)
5. Update status: Accept → Pickup → Deliver
6. Contact customer via WhatsApp

### As Admin:
1. Login with admin credentials
2. View dashboard statistics
3. Manage users (add points to drivers)
4. Monitor all orders
5. View districts and pricing

## Important Features

### Order Cancellation
- Customers can cancel orders with status:
  - ✅ Pending (before driver accepts)
  - ✅ Accepted (after driver accepts, before pickup)
  - ❌ Picked up (cannot cancel)
  - ❌ Delivered (cannot cancel)

### Delivery Fee
- Automatically calculated based on districts
- Range: 100-200 MRU
- Shown in real-time before order submission

### Driver Points System
- New drivers start with 50 points
- Each order acceptance costs 20 points
- Admin can add more points

### Languages
- Switch between Arabic (عربي) and French (FR) in navbar
- Full interface translations
- RTL support for Arabic

## Contact Information

For support:
- **WhatsApp:** +222 41 31 29 31
- **Email:** help@barqmr.com
- **Phone:** +222 41 31 29 31

## Security Note

After successful installation, delete `install.php` for security:
```bash
rm install.php
```

## Troubleshooting

**Database Connection Error:**
- Check database credentials in config.php
- Ensure database exists
- Verify PDO MySQL extension is installed

**Tables Not Created:**
- Database user needs CREATE, INSERT, SELECT, UPDATE privileges
- Check error messages in install.php

**Login Issues:**
- Use phone numbers exactly as specified (no spaces or dashes)
- Password is case-sensitive (all lowercase "123")

**No Orders Showing (Driver):**
- Select operating districts in Settings first
- Only orders from selected districts will appear

## File Permissions

Ensure web server has read access to all files:
```bash
chmod 644 *.php
chmod 644 css/*.css
chmod 644 js/*.js
chmod 755 . css js pages
```

## Production Checklist

Before going live:
- [ ] Change default user passwords
- [ ] Update contact information in config.php
- [ ] Delete install.php
- [ ] Set up HTTPS/SSL
- [ ] Configure database backups
- [ ] Set proper file permissions
- [ ] Test all features thoroughly

## Next Steps

1. Change default passwords
2. Add real driver and customer accounts
3. Test order flow end-to-end
4. Configure for production environment
5. Set up monitoring and backups

---

**System ready to use! Visit index.php to get started.**
