<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    // casts
    protected $casts = [
        'cart_content' => 'json',
        'crm_sent_at'  => 'datetime',
    ];

    // Defaults for freshly created orders (admin select uses the same values)
    public const DEFAULT_STATUS_NAME  = 'Заявка';
    public const DEFAULT_STATUS_COLOR = 'warning';

    public const SHIPPING_METHOD_DEPARTMENT     = 'np_department';
    public const SHIPPING_METHOD_POSTAL_MACHINE = 'np_postal_machine';
    public const SHIPPING_METHOD_COURIER        = 'np_courier';
    public const SHIPPING_METHOD_OTHER          = 'other';

    /**
     * Localized product name from a cart snapshot item.
     *
     * @param array<string, mixed> $item
     */
    public static function itemName(array $item, ?string $locale = null): string
    {
        $names = $item['name'] ?? [];

        return $names[$locale ?? app()->getLocale()] ?? $names['ua'] ?? (reset($names) ?: 'Product');
    }

    /**
     * Total price of the order, based on the stored server-side snapshot.
     */
    public function total(): float
    {
        $total = 0;
        foreach ($this->cart_content ?? [] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    /**
     * Human readable delivery lines (method, city, warehouse/address).
     * Empty array for legacy orders without shipping data.
     *
     * @return string[]
     */
    public function shippingLines(string $locale = 'ua'): array
    {
        $lines = [];

        if ($this->shipping_method && $this->shipping_method !== self::SHIPPING_METHOD_OTHER) {
            $lines[] = __('emails.shippingMethods.' . $this->shipping_method, [], $locale);
        }

        if ($this->shipping_city) {
            $lines[] = trim($this->shipping_city . ($this->shipping_area ? " ({$this->shipping_area})" : ''));
        }
        if ($this->shipping_warehouse) {
            $lines[] = $this->shipping_warehouse;
        }
        if ($this->shipping_address) {
            $lines[] = $this->shipping_address;
        }

        return $lines;
    }

}
