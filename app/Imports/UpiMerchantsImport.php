<?php

namespace App\Imports;

use App\Models\UpiMerchant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UpiMerchantsImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        // skip header row
        $rows->shift();

        foreach ($rows as $row) {
            if (empty($row[0]) && empty($row[1])) {
                continue;
            }

            UpiMerchant::updateOrCreate([
                'mid'           => trim($row[0]),
                'vpa'           => trim($row[1])
            ], [
                'limitPerDay'   => $row[2],
                'limitPerMonth' => $row[3],
                'limitPerYear'  => $row[4],
                'status'        => 1
            ]);
        }
    }
}
