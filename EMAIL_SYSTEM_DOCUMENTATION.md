# Email Receipt System for Orders

## Overview

The application now automatically sends email receipts to customers when orders are created, along with notifications to administrators. This system uses clean abstractions and dependency injection for maintainability.

## Architecture

### Components

1. **EmailServiceInterface** (`app/Interfaces/EmailServiceInterface.php`)
   - Defines contract for email operations
   - Methods: `sendOrderConfirmation()`, `sendOrderNotification()`

2. **EmailService** (`app/Services/EmailService.php`)
   - Implements the email interface
   - Handles both customer receipts and admin notifications
   - Includes error handling and logging
   - Calculates order totals automatically

3. **EmailServiceProvider** (`app/Providers/EmailServiceProvider.php`)
   - Binds interface to implementation
   - Registered in `config/app.php`

4. **Email Templates**
   - Customer receipt: `resources/views/emails/orderReceipt.blade.php`
   - Admin notification: `resources/views/emails/newOrder.blade.php`

5. **Translations**
   - `lang/en/emails.php`
   - `lang/ua/emails.php`
   - `lang/ru/emails.php`

## Usage

### Automatic Order Processing

When an order is created through `OrderController@store`, the system automatically:

1. Saves the order to database
2. Sends receipt email to customer (`client_email`)
3. Sends notification email to admin (configured via `ADMIN_EMAIL` env variable)
4. Sends Telegram notification (existing functionality)

### Manual Email Sending

```php
// Get email service through dependency injection
public function __construct(EmailServiceInterface $emailService)
{
    $this->emailService = $emailService;
}

// Send customer receipt
$success = $this->emailService->sendOrderConfirmation($order);

// Send admin notification  
$success = $this->emailService->sendOrderNotification($order);
```

## Order Data Structure

The system expects orders with these fields:

```php
[
    'client_name' => 'Customer Name',
    'client_email' => 'customer@example.com',
    'client_phone' => '+38 (123) 456-78-90',
    'client_message' => 'Optional customer message',
    'cart_content' => [
        [
            'id' => 1,
            'name' => [
                'ua' => 'Назва українською',
                'en' => 'Name in English',
                'ru' => 'Название на русском'
            ],
            'sku' => 'PRODUCT-001',
            'price' => 100.00,
            'quantity' => 2
        ]
    ],
    'shipping_message' => 'Optional shipping instructions'
]
```

## Email Templates

### Customer Receipt Features

- Professional responsive design
- Order details (number, date, customer info)
- Product listing with names, quantities, and prices
- Total calculation
- Customer message display (if provided)
- Shipping instructions (if provided)
- Multi-language support

### Admin Notification Features

- Simple table format
- All customer and order details
- Product listing
- Suitable for quick order processing

## Configuration

### Mail Settings

Configure in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@domain.com
MAIL_FROM_NAME="Your Store Name"

# Admin email for receiving order notifications
ADMIN_EMAIL="admin@yourdomain.com"
```

### Email Addresses

- Customer receipts: Sent to `order.client_email`
- Admin notifications: Sent to address configured in `ADMIN_EMAIL` environment variable
- From address: `form-manager@gutgas.eu` (admin) or config setting (customer)

## Error Handling

- All email failures are logged but don't prevent order creation
- Failed emails are logged with order ID and error details
- System continues to function even if email service is unavailable

## Testing

Comprehensive tests are included:

- `tests/Unit/EmailServiceTest.php` - Service functionality
- `tests/Unit/EmailTemplateTest.php` - Template rendering
- `tests/Unit/AdminEmailConfigTest.php` - Admin email configuration
- `tests/Feature/OrderCreationTest.php` - Integration tests
- `tests/Feature/CompleteOrderFlowTest.php` - End-to-end flow

Run tests with:
```bash
php artisan test --filter=Email
```

For testing with different admin emails:
```bash
# Set custom admin email for testing
ADMIN_EMAIL="test-admin@example.com" php artisan test
```

## Localization

Email content supports multiple languages:
- Ukrainian (ua) - default
- English (en)
- Russian (ru)

Language is detected from the application locale. Product names in cart support multilingual display.

## Performance Considerations

### Optional Improvements

1. **Email Queuing** - Add to queue for better performance:
   ```php
   Mail::queue('emails.orderReceipt', $data, function($message) {
       // Email configuration
   });
   ```

2. **Email Tracking** - Add database logging of sent emails
3. **Template Caching** - Cache compiled templates for better performance
4. **Attachments** - Add PDF receipts or other attachments

## Troubleshooting

### Common Issues

1. **Emails not sending**
   - Check mail configuration in `.env`
   - Verify SMTP credentials
   - Check logs in `storage/logs/laravel.log`

2. **Template errors**
   - Verify Blade syntax
   - Check translation files exist
   - Ensure all variables are passed to template

3. **Service provider not working**
   - Verify provider is registered in `config/app.php`
   - Clear config cache: `php artisan config:clear`
   - Check interface binding: `php artisan tinker` then `app(EmailServiceInterface::class)`

### Debug Mode

For testing, you can use:
```php
// In your controller or service
Mail::fake(); // Prevents actual sending
// ... perform email operations ...
Mail::assertSent(Mailable::class); // Verify emails were queued
```
