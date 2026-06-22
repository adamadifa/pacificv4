<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Program Ikatan 2026</title>
    <style>
        .str { mso-number-format:\@; }
        .num { mso-number-format:\#\,\#\#0; }
        .pct { mso-number-format: "0.0%"; }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            font-family: Arial, sans-serif;
            font-size: 11pt;
        }
        th {
            background-color: #d3d3d3;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-end {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div style="margin-bottom: 20px; text-align: center;">
        <h2>MONITORING PROGRAM IKATAN 2026</h2>
        @if (Request('dari') && Request('sampai'))
            <h4>Periode: {{ formatIndo(Request('dari')) }} - {{ formatIndo(Request('sampai')) }}</h4>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Pengajuan</th>
                <th>Tanggal</th>
                <th>Kode Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Kode Program</th>
                <th>Nama Program</th>
                <th>Periode Dari</th>
                <th>Periode Sampai</th>
                <th>Cabang</th>
                <th>Salesman</th>
                <th>Rata-rata (Dus)</th>
                <th>Target (Dus)</th>
                <th>Realisasi (Dus)</th>
                <th>Pencapaian</th>
                <th>Rate (Rp)</th>
                <th>Reward (Rp)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monitoring_data as $d)
                @php
                    $total_target = $d->total_target ?? 0;
                    $avg_target = $d->avg_target ?? 0;
                    $persentase = $total_target > 0 ? ($d->realisasi / $total_target) : 0;
                    
                    $status = '';
                    if ($d->realisasi >= $total_target) {
                        $status = 'Target Achieved';
                    } elseif ($d->realisasi >= $avg_target) {
                        $status = 'Avg Achieved';
                    } elseif ($d->realisasi >= ($avg_target - ($avg_target * 0.10))) {
                        $status = 'Near Avg';
                    } else {
                        $status = 'Below Target';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="str">#{{ $d->no_pengajuan }}</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                    <td class="str">{{ $d->kode_pelanggan }}</td>
                    <td>{{ $d->nama_pelanggan }}</td>
                    <td class="str">{{ $d->kode_program }}</td>
                    <td>{{ $d->nama_program }}</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($d->periode_dari)) }}</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($d->periode_sampai)) }}</td>
                    <td>{{ textUpperCase($d->nama_cabang) }}</td>
                    <td>{{ $d->nama_salesman }}</td>
                    <td class="text-end num">{{ $avg_target }}</td>
                    <td class="text-end num">{{ $total_target }}</td>
                    <td class="text-end num">{{ $d->realisasi }}</td>
                    <td class="text-end pct">{{ $persentase }}</td>
                    <td class="text-end num">{{ $d->reward_rate }}</td>
                    <td class="text-end num">{{ $d->calculated_reward_total }}</td>
                    <td class="text-center">{{ $status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
