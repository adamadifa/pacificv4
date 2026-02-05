<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jurnal Umum {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
    .freeze-table {
      height: auto;
      max-height: 795px;
      overflow: auto;
    }
  </style> --}}
    <style>
        .datatable3 th {
            font-size: 11px !important;
        }

        .text-red {
            background-color: red;
            color: white;
        }

        .bg-pajak-success {
            background-color: green !important;
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            JURNAL UMUM <br>
        </h4>
        @if (auth()->user()->hasRole(['super admin', 'admin pajak', 'gm administrasi']))
            <button id="btnSyncAll" class="btn btn-primary" style="margin-bottom: 5px;">Sync All Pajak</button>
        @endif
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>

    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        @if (auth()->user()->hasRole(['super admin', 'admin pajak', 'gm administrasi']))
                            <th>Pajak</th>
                        @endif
                        <th style="width: 10%;">TGL</th>
                        <th>NO BUKTI</th>
                        <th>KETERANGAN</th>
                        <th>PERUNTUKAN</th>
                        <th>KODE AKUN</th>
                        <th>NAMA AKUN</th>
                        <th>DEBET</th>
                        <th>KREDIT</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_debet = 0;
                        $total_kredit = 0;
                    @endphp
                    @foreach ($jurnalumum as $d)
                        @php
                            if ($d->debet_kredit == 'D') {
                                $debet = $d->jumlah;
                                $kredit = 0;
                            } else {
                                $debet = 0;
                                $kredit = $d->jumlah;
                            }
                            $total_debet += $debet;
                            $total_kredit += $kredit;

                            // Status pajak
                            $status_pajak = isset($d->status_pajak) ? $d->status_pajak : 0;

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
                                    <input type="checkbox" class="checkbox-pajak-jurnalumum" data-kode-ju="{{ $d->kode_ju }}"
                                        {{ $status_pajak == 1 ? 'checked' : '' }}>
                                </td>
                            @endif
                            <td>{{ DateToIndo($d->tanggal) }}</td>
                            <td>{{ $d->kode_ju }}</td>
                            <td>{{ $d->keterangan }}</td>
                            <td>{{ $d->kode_peruntukan == 'PC' ? $d->kode_cabang : $d->kode_peruntukan }}</td>
                            <td>'{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            <td class="right">{{ formatAngka($debet) }}</td>
                            <td class="right">{{ formatAngka($kredit) }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        @php
                            $colspan_total = auth()
                                ->user()
                                ->hasRole(['super admin', 'admin pajak', 'gm administrasi'])
                                ? '7'
                                : '6';
                        @endphp
                        <th colspan="{{ $colspan_total }}">TOTAL</th>
                        <th class="right">{{ formatAngka($total_debet) }}</th>
                        <th class="right">{{ formatAngka($total_kredit) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</body>

@if (auth()->user()->hasRole(['super admin', 'admin pajak', 'gm administrasi']))
    <script>
        $(document).ready(function() {
            $('.checkbox-pajak-jurnalumum').on('change', function() {
                const checkbox = $(this);
                const kodeJu = checkbox.data('kode-ju');
                const statusPajak = checkbox.is(':checked') ? 1 : 0;

                // Disable checkbox sementara untuk mencegah multiple clicks
                checkbox.prop('disabled', true);

                $.ajax({
                    url: '{{ route('laporanaccounting.updatestatuspajakjurnalumum') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        kode_ju: kodeJu,
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
                            console.log('Status pajak jurnal umum berhasil diupdate');
                        } else {
                            // Revert checkbox jika gagal
                            checkbox.prop('checked', !checkbox.is(':checked'));
                            alert('Gagal mengupdate status pajak jurnal umum: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        // Revert checkbox jika error
                        checkbox.prop('checked', !checkbox.is(':checked'));
                        let errorMessage = 'Terjadi kesalahan saat mengupdate status pajak jurnal umum';
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

            $('#btnSyncAll').click(function(e) {
                e.preventDefault();
                if(!confirm('Apakah anda yakin ingin melakukan sinkronisasi ulang semua data status pajak jurnal umum sesuai filter yang aktif?')) return;
                
                var btn = $(this);
                var originalText = btn.text();
                btn.prop('disabled', true).text('Syncing...');

                $.ajax({
                    type: 'POST',
                    url: '{{ route("laporanaccounting.syncallpajakjurnalumum") }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        dari: '{{ $dari }}',
                        sampai: '{{ $sampai }}'
                    },
                    success: function(response) {
                        btn.prop('disabled', false).text(originalText);
                        alert(response.message);
                    },
                    error: function(xhr) {
                         btn.prop('disabled', false).text(originalText);
                         var msg = 'Gagal';
                         if(xhr.responseJSON && xhr.responseJSON.message) {
                             msg += ': ' + xhr.responseJSON.message;
                         }
                         alert(msg);
                    }
                });
            });
        });
    </script>
@endif
