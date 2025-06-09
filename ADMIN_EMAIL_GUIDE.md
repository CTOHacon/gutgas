# ADMIN_EMAIL Configuration Guide

## Overview

The `ADMIN_EMAIL` environment variable has been implemented to replace all hardcoded `sale@gutgas.eu` email addresses throughout the application. This provides flexibility for different environments and easier configuration management.

## Configuration

### 1. Environment Setup

Add to your `.env` file:
```env
ADMIN_EMAIL="your-admin@yourdomain.com"
```

If not set, the system will fall back to the default: `sale@gutgas.eu`

### 2. Files Updated

The following files now use the `ADMIN_EMAIL` environment variable:

- **`app/Services/EmailService.php`** - Order notification emails
- **`app/Http/Controllers/MailController.php`** - Consultation and callback request emails
- **`tests/Feature/CompleteOrderFlowTest.php`** - Test assertions
- **`.env.example`** - Example configuration

## Usage Examples

### Production Environment
```env
ADMIN_EMAIL="orders@yourstore.com"
```

### Development Environment
```env
ADMIN_EMAIL="dev-admin@localhost"
```

### Testing Environment
```env
ADMIN_EMAIL="test-admin@example.com"
```

## Email Types That Use ADMIN_EMAIL

1. **Order Notifications** - New order alerts sent to admin
2. **Consultation Requests** - Customer consultation form submissions
3. **Callback Requests** - Customer callback form submissions

## Testing

The system includes comprehensive tests to verify the functionality:

```bash
# Run all email-related tests
php artisan test --filter=Email

# Run admin email configuration tests specifically
php artisan test tests/Unit/AdminEmailConfigTest.php

# Test with custom admin email
ADMIN_EMAIL="custom@example.com" php artisan test
```

## Validation

To verify your configuration is working:

```bash
# Check current admin email setting
php artisan tinker --execute="echo 'Admin Email: ' . env('ADMIN_EMAIL', 'sale@gutgas.eu');"

# Test email functionality
php artisan tinker --execute="
use Illuminate\Support\Facades\Mail;
Mail::fake();
\$order = App\Models\Order::factory()->make(['client_email' => 'test@example.com']);
\$service = new App\Services\EmailService();
\$result = \$service->sendOrderNotification(\$order);
echo 'Email test: ' . (\$result ? 'SUCCESS' : 'FAILED');
"
```

## Backward Compatibility

- ✅ Maintains full backward compatibility
- ✅ Falls back to `sale@gutgas.eu` if `ADMIN_EMAIL` is not set
- ✅ No changes required for existing deployments
- ✅ All existing functionality preserved

## Migration Steps

1. Add `ADMIN_EMAIL="sale@gutgas.eu"` to your `.env` file
2. Update the email address to your desired admin email
3. Test functionality with the new configuration
4. Deploy changes

The system will continue working with the default email if no configuration changes are made.
