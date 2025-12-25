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
                        $status_pajak = 0;
                        if ($d->kode_sumber == 1 && isset($d->id_kaskecil)) {
                            $kaskecil = $d->kaskecil;
                            $status_pajak = $kaskecil ? $kaskecil->status_pajak ?? 0 : 0;
                        }

                        // Set background color jika status_pajak = 1
                        if ($status_pajak == 1) {
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
                                        data-id-kaskecil="{{ $d->id_kaskecil }}" {{ $status_pajak == 1 ? 'checked' : '' }}>
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
                const idKaskecil = checkbox.data('id-kaskecil');
                const statusPajak = checkbox.is(':checked') ? 1 : 0;

                // Disable checkbox sementara untuk mencegah multiple clicks
                checkbox.prop('disabled', true);

                $.ajax({
                    url: '{{ route('laporankeuangan.updatestatuspajakcostratio') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        kode_cr: kodeCr,
                        id_kaskecil: idKaskecil,
                        status_pajak: statusPajak
                    },
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
