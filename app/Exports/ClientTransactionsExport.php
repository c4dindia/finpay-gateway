<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientTransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private string $accountId,
        private Carbon $start,
        private Carbon $end,
        // private int $limit = 300
        private ?string $paymentStatus = null, //optional
    ) {}

    public function collection(): Collection
    {
        return Transaction::query()
            ->where('account_id', $this->accountId)
            ->whereBetween('created_at', [$this->start, $this->end])
            ->when($this->paymentStatus, function ($query) {
                $query->where('payment_status', $this->paymentStatus);
            })
            ->orderByDesc('created_at')
            // ->limit($this->limit)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Checkout ID',
            'Amount',
            'Settled Amount',
            'Currency',
            'Payment ID',
            'Payment Status',
            'Description',
            'Customer Details',
            'Blockchain HashTrxn',
            'Created At',
            'Updated At',
        ];
    }

    public function map($t): array
    {
        return [
            $t->checkout_id ?? '',
            $t->amount ?? '',
            $t->settled_amount ?? '',
            $t->currency ?? '',
            $t->payment_id ?? '',
            $t->payment_status ?? '',
            $t->description ?? '',
            $t->customer_details ?? '',
            $t->transvoucher_blockchainHashTrxn ?? '',
            $t->created_at->format('Y-m-d H:i:s'),
            $t->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
