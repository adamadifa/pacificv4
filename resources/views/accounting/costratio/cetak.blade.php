<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ajuan Transfer Dana {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <style>
        .bg-pajak-success {
            background-color: green !important;
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4>COSTRATIO</h4>
        {{-- <h4>{{ $cabang != null ? textUpperCase($cabang->nama_pt) . '(' . textUpperCase($cabang->nama_cabang) . ')' : '' }}</h4> --}}
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="body">
        <table class="datatable3" border="1">
            <thead>
                <tr>
                <tr>
                    @if (auth()->user()->hasRole(['super admin', 'admin pajak', 'gm administrasi']))
                        <th>Pajak</th>
                    @endif
                    <th style="width: 10%">Kode CR</th>
                    <th style="width: 10%">Tanggal</th>
                    <th style="width: 20%">Akun</th>
                    <th style="width: 25%">Keterangan</th>
                    <th>Jumlah</th>
                    <th>Sumber</th>
                    <th>Cabang</th>
                </tr>
                </tr>
            </thead>
            <tbody>
                @foreach ($costratio as $d)
                    @php
                        // Jika sumber = 1 (kas kecil), ambil data kaskecil
                        $kaskecil = null;
                        $ledger = null;
                        $status_pajak = 0;
                        $sumber_type = null; // 'kaskecil' atau 'ledger'

                        if ($d->kode_sumber == 1 && isset($d->id_kaskecil)) {
                            $kaskecil = $d->kaskecil;
                            $status_pajak = $kaskecil ? $kaskecil->status_pajak ?? 0 : 0;
                            $sumber_type = 'kaskecil';
                        } elseif ($d->kode_sumber == 2 && isset($d->no_bukti_ledger)) {
                            $ledger = $d->ledger;
                            $status_pajak = $ledger ? $ledger->status_pajak ?? 0 : 0;
                            $sumber_type = 'ledger';
                        }

                        // Set background color jika status_pajak = 1
                        if ($status_pajak == 1 && auth()->user()->hasRole(['admin pajak', 'regional operation manager', 'super admin', 'gm administrasi'])) {
                            $bgcolor = 'green';
                            $textcolor = 'white';
                        } else {
                            $bgcolor = '';
                            $textcolor = '';
                        }
                    @endphp
                    <tr style="background-color: {{ $bgcolor }}; {{ !empty($textcolor) ? 'color: ' . $textcolor . ';' : '' }}">
                        @if (auth()->user()->hasRole(['super admin', 'admin pajak', 'gm administrasi']))
                            <td class="center">
                                @if ($d->kode_sumber == 1 && isset($d->id_kaskecil))
                                    <input type="checkbox" class="checkbox-pajak-costratio" data-kode-cr="{{ $d->kode_cr }}"
                                        data-id-kaskecil="{{ $d->id_kaskecil }}" data-sumber-type="kaskecil"
                                        {{ $status_pajak == 1 ? 'checked' : '' }}>
                                @elseif ($d->kode_sumber == 2 && isset($d->no_bukti_ledger))
                                    <input type="checkbox" class="checkbox-pajak-costratio" data-kode-cr="{{ $d->kode_cr }}"
                                        data-no-bukti-ledger="{{ $d->no_bukti_ledger }}" data-sumber-type="ledger"
                                        {{ $status_pajak == 1 ? 'checked' : '' }}>
                                @else
                                    -
                                @endif
                            </td>
                        @endif
                        <td>{{ $d->kode_cr }}</td>
                        <td>{{ formatIndo($d->tanggal) }}</td>
                        <td>{{ $d->kode_akun }}- {{ $d->nama_akun }}</td>
                        <td>{{ textCamelCase($d->keterangan) }}</td>
                        <td style="text-align:right">{{ formatAngka($d->jumlah) }}</td>
                        <td>{{ $d->sumber }}</td>
                        <td>{{ textUpperCase($d->nama_cabang) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

@if (auth()->user()->hasRole(['super admin', 'admin pajak', 'gm administrasi']))
    <script>
        $(document).ready(function() {
            $('.checkbox-pajak-costratio').on('change', function() {
                const checkbox = $(this);
                const kodeCr = checkbox.data('kode-cr');
                const sumberType = checkbox.data('sumber-type'); // 'kaskecil' atau 'ledger'
                const idKaskecil = checkbox.data('id-kaskecil');
                const noBuktiLedger = checkbox.data('no-bukti-ledger');
                const statusPajak = checkbox.is(':checked') ? 1 : 0;

                // Disable checkbox sementara untuk mencegah multiple clicks
                checkbox.prop('disabled', true);

                // Siapkan data berdasarkan sumber type
                const ajaxData = {
                    _token: '{{ csrf_token() }}',
                    kode_cr: kodeCr,
                    sumber_type: sumberType,
                    status_pajak: statusPajak
                };

                // Tambahkan data sesuai sumber
                if (sumberType === 'kaskecil' && idKaskecil) {
                    ajaxData.id_kaskecil = idKaskecil;
                } else if (sumberType === 'ledger' && noBuktiLedger) {
                    ajaxData.no_bukti_ledger = noBuktiLedger;
                }

                $.ajax({
                    url: '{{ route('laporankeuangan.updatestatuspajakcostratio') }}',
                    method: 'POST',
                    data: ajaxData,
                    success: function(response) {
                        if (response.success) {
                            const row = checkbox.closest('tr');

                            if (statusPajak == 1) {
                                // Centang: Ubah background row menjadi hijau dengan text putih
                                row.addClass('bg-pajak-success');
                                row.css({
                                    'background-color': 'green',
                                    'color': 'white'
                                });
                            } else {
                                // Uncheck: Kembalikan background row menjadi putih/normal
                                row.removeClass('bg-pajak-success');
                                row.css({
                                    'background-color': '',
                                    'color': ''
                                });
                            }

                            // Tampilkan pesan sukses
                            alert(response.message);
                            console.log('Status pajak costratio berhasil diupdate');
                        } else {
                            // Revert checkbox jika gagal
                            checkbox.prop('checked', !checkbox.is(':checked'));
                            alert('Gagal mengupdate status pajak costratio: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        // Revert checkbox jika error
                        checkbox.prop('checked', !checkbox.is(':checked'));
                        let errorMessage = 'Terjadi kesalahan saat mengupdate status pajak costratio';
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

</html>
