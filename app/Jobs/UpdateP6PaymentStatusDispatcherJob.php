<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateP6PaymentStatusDispatcherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $status)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Transaction::where('status', 'p6')
        ->whereBetween('created_at', ['2026-01-01 00:00:00', now()])
        ->chunkById(50, function ($transactions) {
            foreach ($transactions as $trxn) {
                CheckSingleP6TransactionJob::dispatch($trxn->id, $this->status);
            }
        });
    }
}
