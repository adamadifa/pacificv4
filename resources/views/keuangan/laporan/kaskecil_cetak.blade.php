<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kas Kecil {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
        .freeze-table {
            height: auto;
            max-height: 830px;
            overflow: auto;
        }
    </style>
    <style>
        .text-red {
            background-color: red;
            color: white;
        }
    </style> --}}
    <style>
        .bg-pajak-success {
            background-color: green !important;
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="header"></div>
    <h4 class="title">
        KAS KECIL<br>
    </h4>
    <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    @if ($cabang != null)
        <h4>
            {{ $cabang->nama_cabang }}
        </h4>
    @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>No</th>
                        @if (auth()->user()->hasRole(['super admin', 'admin pajak']))
                            <th>Pajak</th>
                        @endif
                        <th>Tanggal</th>
                        <th>No. Bukti</th>
                        <th style="width: 400px;">Keterangan</th>
                        <th>Kode Akun</th>
                        <th>Akun</th>
                        <th>Penerimaan</th>
                        <th>Pengeluaran</th>
                        <th>Saldo</th>
                        <th rowspan="2">Dibuat</th>
                    </tr>
                    <tr>
                        <th colspan="{{ auth()->user()->hasRole(['super admin', 'admin pajak'])? '9': '8' }}"><b>SALDO AWAL</b></th>
                        <th class="right">{{ $saldoawal != null ? formatAngka($saldoawal->saldo_awal) : 0 }}</th>
                    </tr>
                <tbody>
                    @php
                        $saldo = $saldoawal != null ? $saldoawal->saldo_awal : 0;
                        $total_penerimaan = 0;
                        $total_pengeluaran = 0;
                    @endphp
                    @foreach ($kaskecil as $d)
                        @php
                            $penerimaan = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                            $pengeluaran = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                            $color = $d->debet_kredit == 'K' ? 'green' : 'red';
                            $saldo += $penerimaan - $pengeluaran;
                            $total_penerimaan += $penerimaan;
                            $total_pengeluaran += $pengeluaran;
                            $colorklaim = !empty($d->kode_klaim) ? 'background-color: green; color: white' : '';

                            // Jika status_pajak = 1, set background hijau dengan text putih (kecuali jika sudah ada warna dari klaim)
                            if (isset($d->status_pajak) && $d->status_pajak == 1) {
                                $bgcolor = 'green';
                                $textcolor = 'white';
                            } else {
                                $bgcolor = '';
                                $textcolor = '';
                            }
                        @endphp
                        <tr style="background-color: {{ $bgcolor }}; {{ !empty($textcolor) ? 'color: ' . $textcolor . ';' : '' }}">
                            <td style="{{ $colorklaim }}">{{ $loop->iteration }}</td>
                            @if (auth()->user()->hasRole(['super admin', 'admin pajak']))
                                <td class="center">
                                    <input type="checkbox" class="checkbox-pajak-kaskecil" data-id="{{ $d->id }}"
                                        {{ isset($d->status_pajak) && $d->status_pajak == 1 ? 'checked' : '' }}>
                                </td>
                            @endif
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_bukti }}</td>
                            <td>{{ $d->keterangan }}</td>
                            <td>'{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            <td class="right" style="color: {{ $color }}">{{ formatAngka($penerimaan) }}</td>
                            <td class="right" style="color: {{ $color }}">{{ formatAngka($pengeluaran) }}</td>
                            <td class="right">{{ formatAngka($saldo) }}</td>
                            <td>{{ !empty($d->created_at) ? date('d-m-Y H:i:s', strtotime($d->created_at)) : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <th colspan="{{ auth()->user()->hasRole(['super admin', 'admin pajak'])? '7': '6' }}">TOTAL</th>
                        <th class="right">{{ formatAngka($total_penerimaan) }}</th>
                        <th class="right">{{ formatAngka($total_pengeluaran) }}</th>
                        <th class="right">{{ formatAngka($saldo) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

@if (auth()->user()->hasRole(['super admin', 'admin pajak']))
    <script>
        $(document).ready(function() {
            $('.checkbox-pajak-kaskecil').on('change', function() {
                const checkbox = $(this);
                const id = checkbox.data('id');
                const statusPajak = checkbox.is(':checked') ? 1 : 0;

                // Disable checkbox sementara untuk mencegah multiple clicks
                checkbox.prop('disabled', true);

                $.ajax({
                    url: '{{ route('laporankeuangan.updatestatuspajakkaskecil') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        status_pajak: statusPajak
                    },
                    success: function(response) {
                        if (response.success) {
                            const row = checkbox.closest('tr');

                            if (statusPajak == 1) {
                                // Centang: Ubah background row menjadi hijau dengan text putih
                                // KECUALI kolom nomor (td pertama)
                                row.addClass('bg-pajak-success');
                                row.find('td:not(:first-child)').css({
                                    'background-color': 'green',
                                    'color': 'white'
                                });
                            } else {
                                // Uncheck: Kembalikan background row menjadi putih/normal
                                row.removeClass('bg-pajak-success');
                                row.find('td:not(:first-child)').css({
                                    'background-color': '',
                                    'color': ''
                                });
                            }

                            // Tampilkan pesan sukses
                            alert(response.message);
                            console.log('Status pajak kas kecil berhasil diupdate');
                        } else {
                            // Revert checkbox jika gagal
                            checkbox.prop('checked', !checkbox.is(':checked'));
                            alert('Gagal mengupdate status pajak kas kecil: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        // Revert checkbox jika error
                        checkbox.prop('checked', !checkbox.is(':checked'));
                        let errorMessage = 'Terjadi kesalahan saat mengupdate status pajak kas kecil';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    },
                    complete: function() {
                        // Enable checkbox kembali
                        checkbox.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endif
