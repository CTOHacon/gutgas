<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\SalesDrive;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Dispatched ->afterResponse() so the customer never waits on CRM latency.
 */
class PushOrderToCrm
{
    use Dispatchable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function handle(SalesDrive $salesDrive): void
    {
        $salesDrive->push($this->order);
    }
}
