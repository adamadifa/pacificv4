<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Setoran Penjualan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4>MONITORING PROGRAM</h4>
        <h4>{{ $cabang != null ? textUpperCase($cabang->nama_cabang) : '' }}</h4>
        <h4>PERIODE BULAN {{ $namabulan[$bulan] }} TAHUN {{ $tahun }}</h4>
    </div>
    <div class="body">
        <table class="datatable3" border="1">
            <thead class="table-dark">
                <tr>
                    <th rowspan="2">No.</th>
                    <th rowspan="2">Kode</th>
                    <th rowspan="2">Nama Pelanggan</th>
                    <th rowspan="2">Salesman</th>
                    <th rowspan="2">Wilayah</th>
                    <th rowspan="2" class="text-center">Target</th>
                    <th class="text-center" colspan="3">Realisasi</th>
                    <th colspan="3" class="text-center">Reward</th>
                    <th rowspan="2">#</th>
                </tr>
                <tr>
                    <th>Tunai</th>
                    <th>Kredit</th>
                    <th>Total</th>
                    <th>Tunai</th>
                    <th>Kredit</th>
                    <th>Total</th>
                </tr>

            </thead>
            <tbody>
                @php
                    $total_reward = 0;
                    $color_reward = '';
                    $status = 0;
                @endphp
                @foreach ($peserta as $d)
                    @php
                        $color_reward =
                            $d->jml_dus >= $d->qty_target ? 'bg-success text-white' : 'bg-danger text-white';
                        if ($d->jml_dus >= $d->qty_target) {
                            //$reward = $d->reward * $d->jml_dus;
                            $bb_dep = ['PRIK004', 'PRIK001'];
                            $reward_tunai = in_array($d->kode_program, $bb_dep)
                                ? ($d->budget_rsm + $d->budget_gm) * $d->jml_tunai
                                : $d->reward * $d->jml_tunai;
                            $reward_kredit = $d->reward * $d->jml_kredit;
                            $reward = $reward_tunai + $reward_kredit;
                        } else {
                            $reward_tunai = 0;
                            $reward_kredit = 0;
                            $reward = 0;
                        }
                        $total_reward += $reward;
                        $status = $reward == 0 ? 0 : 1;
                    @endphp

                    <tr class=" {{ $color_reward }}">
                        <td>{{ $loop->iteration }} {{ $d->kode_program }}</td>
                        <td>
                            <input type="hidden" name="kode_pelanggan[{{ $loop->index }}]"
                                value="{{ $d->kode_pelanggan }}">
                            <input type="hidden" name="status[{{ $loop->index }}]" value="{{ $status }}">
                            {{ $d->kode_pelanggan }}
                        </td>
                        <td>{{ $d->nama_pelanggan }}</td>
                        <td>{{ $d->nama_salesman }}</td>
                        <td>{{ $d->nama_wilayah }}</td>
                        <td class="text-center">
                            {{ formatAngka($d->qty_target) }}
                        </td>
                        <td class="text-end">
                            {{-- <input type="hidden" name="jumlah[{{ $loop->index }}]" value="{{ $d->jml_dus }}">
                            {{ formatAngka($d->jml_dus) }} --}}

                            <input type="hidden" name="qty_tunai[{{ $loop->index }}]" value="{{ $d->jml_tunai }}">
                            {{ formatAngka($d->jml_tunai) }}
                        </td>
                        <td class="text-end">
                            {{-- <input type="hidden" name="jumlah[{{ $loop->index }}]" value="{{ $d->jml_dus }}">
                            {{ formatAngka($d->jml_dus) }} --}}

                            <input type="hidden" name="qty_kredit[{{ $loop->index }}]" value="{{ $d->jml_kredit }}">
                            {{ formatAngka($d->jml_kredit) }}
                        </td>
                        <td class="text-end">
                            <input type="hidden" name="jumlah[{{ $loop->index }}]" value="{{ $d->jml_dus }}">
                            {{ formatAngka($d->jml_dus) }}
                        </td>
                        <td class="text-end">
                            <input type="hidden" name="reward_tunai[{{ $loop->index }}]"
                                value="{{ $reward_tunai }}">
                            {{ formatAngka($reward_tunai) }}
                        </td>
                        <td class="text-end">
                            <input type="hidden" name="reward_kredit[{{ $loop->index }}]"
                                value="{{ $reward_kredit }}">
                            {{ formatAngka($reward_kredit) }}
                        </td>
                        <td class="text-end">
                            <input type="hidden" name="total_reward[{{ $loop->index }}]"
                                value="{{ $reward }}">
                            {{ formatAngka($reward) }}
                        </td>
                        <td>
                            <a href="#" class="btnDetailfaktur" kode_pelanggan="{{ $d->kode_pelanggan }}"
                                bulan="{{ Request('bulan') }}" tahun="{{ Request('tahun') }}">
                                <i class="ti ti-file-description text-primary"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
