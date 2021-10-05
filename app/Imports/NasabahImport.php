<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NasabahImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */


    public function headingRow(): int
    {
        return 3;
    }

    public function collection(Collection $collection)
    {
        //
    }
}
