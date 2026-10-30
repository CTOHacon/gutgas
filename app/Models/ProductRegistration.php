<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRegistration extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'purchase_date'        => 'date:Y-m-d',
        'price'                => 'integer',
        'subscription_consent' => 'boolean',
    ];
}
