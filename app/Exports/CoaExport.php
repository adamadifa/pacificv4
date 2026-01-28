<?php

namespace App\Exports;

use App\Models\Coa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CoaExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Coa::orderby('kode_akun')
            ->whereNotIn('kode_akun', ['1', '2', '0-0000'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'Kode Akun',
            'Nama Akun',
            'Sub Akun',
            'Kode Kategori',
        ];
    }

    public function map($coa): array
    {
        return [
            $coa->kode_akun,
            $coa->nama_akun,
            $coa->sub_akun,
            $coa->kode_kategori,
        ];
    }
}
