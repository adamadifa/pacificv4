<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenyesuaianupahTemplateExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected $kode_gaji;

    public function __construct($kode_gaji = null)
    {
        $this->kode_gaji = $kode_gaji;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Karyawan::where('status_aktif_karyawan', 1)
            ->leftJoin('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
            ->leftJoin('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');

        if ($this->kode_gaji) {
            $query->leftJoin('hrd_penyesuaian_upah_detail', function ($join) {
                $join->on('hrd_karyawan.nik', '=', 'hrd_penyesuaian_upah_detail.nik')
                    ->where('hrd_penyesuaian_upah_detail.kode_gaji', '=', $this->kode_gaji);
            });
            $query->select(
                'hrd_karyawan.nik',
                'hrd_karyawan.nama_karyawan',
                'hrd_jabatan.nama_jabatan',
                'cabang.nama_cabang',
                'hrd_penyesuaian_upah_detail.penambah',
                'hrd_penyesuaian_upah_detail.pengurang'
            );
        } else {
            $query->select(
                'hrd_karyawan.nik',
                'hrd_karyawan.nama_karyawan',
                'hrd_jabatan.nama_jabatan',
                'cabang.nama_cabang'
            );
        }

        return $query->orderBy('hrd_karyawan.nama_karyawan', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama Karyawan',
            'Jabatan',
            'Cabang',
            'Penambah',
            'Pengurang',
        ];
    }

    public function map($karyawan): array
    {
        return [
            $karyawan->nik,
            $karyawan->nama_karyawan,
            $karyawan->nama_jabatan ?? '-',
            $karyawan->nama_cabang ?? '-',
            isset($karyawan->penambah) ? (int)$karyawan->penambah : 0,
            isset($karyawan->pengurang) ? (int)$karyawan->pengurang : 0,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'E' => '#,##0',
            'F' => '#,##0',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '284C9A'],
                ],
            ],
        ];
    }
}
