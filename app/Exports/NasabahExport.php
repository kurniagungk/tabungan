<?php

namespace App\Exports;

use App\Models\Nasabah;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NasabahExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function headings(): array
    {
        return [
            'Rekening',
            'Nama',
            'Alamat',
            'Saldo',
        ];
    }

    public function collection()
    {
        return Nasabah::select("rekening", "nama", "alamat", "saldo")->get();
    }
}
