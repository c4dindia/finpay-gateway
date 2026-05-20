<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\UPIPayment;
use App\Models\UpiMerchant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckVpaLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CheckVpaLimit:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Vpa Limit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check for expired transactions
        Transaction::where('status', 'p23')
            ->where('payment_status', 'Pending')
            ->whereNotNull('payment_id')
            ->where('created_at', '<=', Carbon::now()->subMinutes(config('services.p23.payment_expiry_minutes')))
            ->update([
                'payment_status' => 'Expired',
            ]);

        // Check for VPA limits
        // $merchantLimits = UpiMerchant::where('status', '1')
        //     ->whereNotNull('limitPerDay')
        //     ->pluck('limitPerDay', 'vpa')
        //     ->toArray();

        // $exceedVpa = Transaction::where('status', 'p23')
        //     ->where('payment_status', 'Completed')
        //     ->whereNotNull('card_number')
        //     ->whereBetween('created_at', [
        //         Carbon::today()->startOfDay(),
        //         Carbon::today()->endOfDay(),
        //     ])
        //     ->select('card_number')
        //     ->selectRaw('SUM(amount) as total_amount')
        //     ->groupBy('card_number')
        //     ->get()
        //     ->filter(function ($txn) use ($merchantLimits) {
        //         $vpa = $txn->card_number;

        //         if (!isset($merchantLimits[$vpa])) {
        //             return false;
        //         }

        //         return $txn->total_amount >= $merchantLimits[$vpa];
        //     })
        //     ->pluck('card_number')
        //     ->toArray();

        // if (empty($exceedVpa)) {
        //     $this->info('No VPA exceed the limit');
        //     return;
        // }

        // $oldAccounts = UPIPayment::whereIn('vpa', $exceedVpa)
        //     ->where('status', '1')
        //     ->get();

        // foreach ($oldAccounts as $oldAccount) {
        //     $newMerchant = UpiMerchant::where('status', '1')
        //         ->whereNotIn('vpa', $exceedVpa)
        //         ->orderByRaw("CASE WHEN mid = ? THEN 0 ELSE 1 END", [$oldAccount->mid])
        //         ->orderBy('id', 'asc')
        //         ->first();

        //     if (!$newMerchant) {
        //         $oldAccount->mid = null;
        //         $oldAccount->vpa = null;
        //         $oldAccount->status = '0';
        //         $oldAccount->save();

        //         $this->info('No Merchant Found');
        //         continue;
        //     }

        //     $oldAccount->mid = $newMerchant->mid;
        //     $oldAccount->vpa = $newMerchant->vpa;
        //     $oldAccount->status = '1';
        //     $oldAccount->save();
        // }

        // $this->info('VPA Updated Successfully');
    }
}
