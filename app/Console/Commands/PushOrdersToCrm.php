<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\SalesDrive;
use Illuminate\Console\Command;

class PushOrdersToCrm extends Command
{
    protected $signature = 'crm:push-orders
                            {--id=* : Push the given order ids}
                            {--failed : Push orders whose previous push failed}';

    protected $description = 'Push orders to the SalesDrive CRM (explicit selection only, never bulk-sends history)';

    public function handle(SalesDrive $salesDrive): int
    {
        $ids    = array_filter((array) $this->option('id'), fn($id) => $id !== null && $id !== '');
        $failed = (bool) $this->option('failed');

        if (!$ids && !$failed) {
            $this->line('Nothing pushed. Choose orders explicitly:');
            $this->line('  php artisan crm:push-orders --id=12 --id=13   push specific orders');
            $this->line('  php artisan crm:push-orders --failed          retry orders whose push failed');
            return self::SUCCESS;
        }

        if (!$salesDrive->isConfigured()) {
            $this->error('SalesDrive is not configured (SALESDRIVE_URL / SALESDRIVE_API_KEY).');
            return self::FAILURE;
        }

        $orders = Order::query()
            ->where(function ($q) use ($ids, $failed) {
                if ($ids) {
                    $q->whereIn('id', $ids);
                }
                if ($failed) {
                    $q->orWhere(fn($f) => $f->whereNotNull('crm_error')->whereNull('crm_order_id'));
                }
            })
            ->orderBy('id')
            ->get();

        foreach (array_diff($ids, $orders->pluck('id')->map(fn($i) => (string) $i)->all()) as $missing) {
            $this->warn("Order #{$missing}: not found");
        }

        $hadFailure = false;
        foreach ($orders as $order) {
            if ($order->crm_order_id) {
                $this->line("Order #{$order->id}: already in CRM (#{$order->crm_order_id}), skipped");
                continue;
            }

            if ($salesDrive->push($order)) {
                $this->info("Order #{$order->id}: sent, CRM id {$order->fresh()->crm_order_id}");
            } else {
                $hadFailure = true;
                $this->error("Order #{$order->id}: failed - {$order->fresh()->crm_error}");
            }
        }

        return $hadFailure ? self::FAILURE : self::SUCCESS;
    }
}
