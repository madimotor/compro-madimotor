# Midtrans Integration Setup Instructions

## What I've implemented:

✅ **Created all necessary files for Midtrans SNAP API integration:**

1. **Composer configuration** (`composer.json`) - For Midtrans PHP SDK
2. **Midtrans configuration** (`config/midtrans_config.php`) - Loads your API keys from .env
3. **Database table SQL** (`database/transactions_table.sql`) - For storing transaction data
4. **Payment processing** (`process_payment.php`) - Handles payment form submission
5. **Payment page** (`payment.php`) - Shows Midtrans SNAP payment interface
6. **Notification handler** (`midtrans_notification.php`) - Handles Midtrans webhooks
7. **Success page** (`payment_success.php`) - Shows successful payment confirmation
8. **Pending page** (`payment_pending.php`) - Shows pending payment status
9. **Updated detail page** (`detail_motor.php`) - Added "Buy Now" form

✅ **Installed Midtrans PHP SDK** via Composer

## Next Steps for You:

### 1. Create Database Table

Run this SQL command in your MySQL database:

```sql
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(255) NOT NULL UNIQUE,
    motor_id INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'success', 'failed', 'expired') DEFAULT 'pending',
    payment_type VARCHAR(50),
    transaction_time DATETIME,
    snap_token VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 2. Configure Midtrans Dashboard

In your Midtrans Dashboard (https://dashboard.midtrans.com/):

1. Go to **Settings** → **Configuration**
2. Set **Notification URL** to: `https://yourdomain.com/midtrans_notification.php`
3. Set **Finish URL** to: `https://yourdomain.com/payment_success.php`
4. Set **Unfinish URL** to: `https://yourdomain.com/payment_pending.php`
5. Set **Error URL** to: `https://yourdomain.com/payment_pending.php`

### 3. Test the Integration

1. Go to any motor detail page
2. Fill in the "Beli Sekarang" form
3. Click "Beli Sekarang" button
4. You'll be redirected to Midtrans payment page
5. Use test payment methods (in Sandbox mode)

### 4. Switch to Production

When ready for production:

1. Change `$isProduction = false;` to `$isProduction = true;` in `config/midtrans_config.php`
2. Update your .env file with Production API keys
3. Update Midtrans script URL from `app.sandbox.midtrans.com` to `app.midtrans.com` in `payment.php`

## Test Payment Methods (Sandbox)

- **Credit Card**: 4811 1111 1111 1114, CVV: 123, Exp: 01/25
- **Mandiri VA**: Will generate virtual account number
- **BCA VA**: Will generate virtual account number
- **And many more payment methods**

## Features Included:

✅ Complete payment flow
✅ Transaction status tracking
✅ Email notifications ready
✅ Responsive design
✅ Security measures
✅ Error handling
✅ Webhook notifications
✅ Payment confirmation pages

The integration is now complete and ready to use!
