<?php

namespace App\Exports;

use App\Models\Bank;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SaldoawalrekeningTemplateExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Bank::orderBy('kode_bank')->get();
    }

    public function headings(): array
    {
        return [
            'Kode Bank',
            'Nama Bank',
            'Jumlah',
        ];
    }

    public function map($bank): array
    {
        return [
            $bank->kode_bank,
            $bank->nama_bank . ' (' . $bank->no_rekening . ')',
            0,
        ];
    }
}
