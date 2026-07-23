<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pembelian {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    <style>
        .freeze-table {
            height: auto;
            max-height: 830px;
            overflow: auto;
        }
    </style>
</head>

<body>
    @if (auth()->user()->hasRole(['super admin', 'admin pajak']))
    <div style="background: #f8f9fa; padding: 15px; border-bottom: 1px solid #ddd; display: flex; gap: 10px; align-items: center; position: sticky; top: 0; z-index: 1000;" class="no-print">
        <button id="btnSyncToPortal" style="background-color: #28a745; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 6px;">
            <svg style="width:16px; height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.24"></path></svg>
            Sync Selected
        </button>
        <button id="btnSyncAll" style="background-color: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 6px;">
            <svg style="width:16px; height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            Sync All (PPN)
        </button>
        <span id="syncStatus" style="font-size: 13px; font-weight: bold; color: #6c757d;"></span>
    </div>
    <style>
        @media print {
            .no-print { display: none !important; }
        }
    </style>
    @endif

    <div class="header">
        <h4 class="title">
            LAPORAN PEMBELIAN<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($supplier != null)
            <h4>
                {{ $supplier->kode_supplier }} - {{ $supplier->nama_supplier }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width: 125%">
                <thead>
                    <tr>
                        @if (auth()->user()->hasRole(['super admin', 'admin pajak']))
                            <th style="width: 5%">
                                <input type="checkbox" id="selectAllSync" class="no-print" style="margin-right: 5px;">
                                STATUS
                            </th>
                        @endif
                        <th style="width:1%">NO</th>
                        <th style="width:4%">TGL</th>
                        <th style="width:4%">NO BUKTI</th>
                        <th style="width:10%">SUPPLIER</th>
                        <th style="width:10%">NAMA BARANG</th>
                        <th style="width:10%">KETERANGAN</th>
                        <th style="width:2%">JT</th>
                        <th style="width:2%">PCF/MP</th>
                        <th style="width:3%">AKUN</th>
                        <th style="width:8%">JURNAL</th>
                        <th style="width:2%">PPN</th>
                        <th style="width:4%">QTY</th>
                        <th style="width:5%">HARGA</th>
                        <th style="width:5%">SUBTOTAL</th>
                        <th style="width: 3%">PENY</th>
                        <th>TOTAL</th>
                        <th>DEBET</th>
                        <th>KREDIT</th>
                        <th>KATEGORI</th>
                        <th style="width: 5%">DIBUAT</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotal_transaksi = 0;
                        $total_debet = 0;
                        $total_kredit = 0;
                        $total_dk = 0;
                        $grandtotal = 0;
                    @endphp
                    @foreach ($pembelian as $key => $d)
                        @php
                            $no_bukti = @$pembelian[$key + 1]->no_bukti;
                            $subtotal = ROUND($d->jumlah * $d->harga, 2);
                            $total = $subtotal + $d->penyesuaian;
                            if ($d->ppn == '1') {
                                $cekppn = '&#10004;';
                                $bgcolor = '#ececc8';
                                // $dpp = (100 / 110) * $totalharga;
                                // $ppn = (10 / 100) * $dpp;
                            } else {
                                $bgcolor = '';
                                $cekppn = '';
                                // $dpp = '';
                                // $ppn = '';
                            }

                            if ($d->kode_transaksi == 'PNJ') {
                                $totalharga = -$total;
                                $debet = 0;
                                $kredit = $total;
                                $namabarang = $d->ket_penjualan;
                            } else {
                                $totalharga = $total;
                                $debet = $total;
                                $kredit = 0;
                                $namabarang = $d->nama_barang;
                            }

                            if ($d->kode_asal_pengajuan != 'GDB') {
                                $akun = '2-1300';
                                $namaakun = 'Hutang Lainnya';
                            } else {
                                $akun = '2-1200';
                                $namaakun = 'Hutang Dagang';
                            }
                            $subtotal_transaksi += $totalharga;
                            $total_debet += $debet;
                            $total_kredit += $kredit;
                            $total_dk += $totalharga;

                            $grandtotal += $total;
                        @endphp
                        <tr style="background-color: {{ $bgcolor }}">
                            @if (auth()->user()->hasRole(['super admin', 'admin pajak']))
                                <td class="center" style="background-color: {{ $d->is_tax_mp == 1 ? '#d4edda' : '#f8d7da' }};">
                                    @if ($d->ppn == '1')
                                        <input type="checkbox" class="sync-checkbox no-print" value="{{ $d->no_bukti }}" {{ $d->is_tax_mp == 1 ? 'checked' : '' }}>
                                    @endif
                                </td>
                            @endif
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_bukti }}</td>
                            <td>{{ $d->nama_supplier }}</td>
                            <td>{{ $namabarang }}</td>
                            <td>{{ $d->keterangan ?? $d->keterangan_penjualan }}</td>
                            <td class="center">{{ $d->jenis_transaksi }}</td>
                            <td class="center">{{ $d->kode_cabang }}</td>
                            <td class="center">'{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            <td class="center">{!! $cekppn !!}</td>
                            <td class="right">{{ formatAngkaDesimal($d->jumlah) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->harga) }}</td>
                            <td class="right">{{ formatAngkaDesimal($subtotal) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->penyesuaian) }}</td>
                            <td class="right">{{ formatAngkaDesimal($total) }}</td>
                            <td class="right">{{ formatAngkaDesimal($debet) }}</td>
                            <td class="right">{{ formatAngkaDesimal($kredit) }}</td>
                            <td class="center">{{ $d->kategori_transaksi }}</td>
                            <td>{{ date('d-m-Y H:i', strtotime($d->created_at)) }}</td>
                        </tr>

                        @if ($no_bukti != $d->no_bukti)
                            <tr bgcolor="#a7efe4" style="color:black; font-weight:bold">
                                @if (auth()->user()->hasRole(['super admin', 'admin pajak']))
                                    <td></td>
                                @endif
                                <td></td>
                                <td></td>
                                <td>{{ $d->no_bukti }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="center">{{ $d->jenis_transaksi }}</td>
                                <td></td>
                                <td class="center">{{ $akun }}</td>
                                <td>{{ $namaakun }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="right">{{ formatAngkaDesimal($subtotal_transaksi) }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            @php
                                $subtotal_transaksi = 0;
                            @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <th colspan="{{ auth()->user()->hasRole(['super admin', 'admin pajak']) ? 15 : 14 }}" align="center"><b>TOTAL</b></th>
                        <th align="right"><b></b></th>
                        <th class="right">{{ formatAngkaDesimal($grandtotal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_debet) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_kredit + $total_dk) }}</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
<script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 10,
        'shadow': true,
    });

    $(function() {
        $('#selectAllSync').change(function() {
            var isChecked = $(this).prop('checked');
            $('.sync-checkbox').prop('checked', isChecked).trigger('change-bulk');
        });

        $('.sync-checkbox').on('change-bulk', function() {
            var checkbox = $(this);
            var td = checkbox.closest('td');
            if (checkbox.prop('checked')) {
                td.css('background-color', '#d4edda');
            } else {
                td.css('background-color', '#f8d7da');
            }
        });

        $('.sync-checkbox').change(function(e) {
            var checkbox = $(this);
            var noBukti = checkbox.val();
            var isChecked = checkbox.prop('checked');
            var td = checkbox.closest('td');

            if (isChecked) {
                td.css('opacity', '0.5');
                $.ajax({
                    url: "{{ route('pembelian.syncToPortalMp') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        no_bukti: [noBukti]
                    },
                    success: function(response) {
                        td.css({
                            'opacity': '1',
                            'background-color': '#d4edda'
                        });
                    },
                    error: function(xhr) {
                        var err = xhr.responseJSON;
                        alert(err && err.message ? err.message : 'Gagal mensinkronisasi data.');
                        checkbox.prop('checked', false);
                        td.css({
                            'opacity': '1',
                            'background-color': '#f8d7da'
                        });
                    }
                });
            } else {
                if (confirm('Apakah Anda yakin ingin membatalkan sinkronisasi data untuk No. Bukti: ' + noBukti + '? Data di Portal MP akan ikut terhapus.')) {
                    td.css('opacity', '0.5');
                    $.ajax({
                        url: "{{ route('pembelian.unsyncFromPortalMp') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            no_bukti: noBukti
                        },
                        success: function(response) {
                            td.css({
                                'opacity': '1',
                                'background-color': '#f8d7da'
                            });
                        },
                        error: function(xhr) {
                            var err = xhr.responseJSON;
                            alert(err && err.message ? err.message : 'Gagal membatalkan sinkronisasi.');
                            checkbox.prop('checked', true);
                            td.css({
                                'opacity': '1',
                                'background-color': '#d4edda'
                            });
                        }
                    });
                } else {
                    checkbox.prop('checked', true);
                }
            }
        });

        $('#btnSyncToPortal').click(function() {
            var checkedBukti = [];
            $('.sync-checkbox:checked').each(function() {
                checkedBukti.push($(this).val());
            });

            if (checkedBukti.length === 0) {
                alert('Silakan pilih setidaknya satu transaksi pembelian (checklist) yang ingin disinkronisasi!');
                return;
            }

            var btn = $(this);
            var statusText = $('#syncStatus');
            btn.prop('disabled', true).css('opacity', '0.6').text('Syncing...');
            statusText.css('color', '#6c757d').text('Memulai sinkronisasi ' + checkedBukti.length + ' data...');
            
            $.ajax({
                url: "{{ route('pembelian.syncToPortalMp') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    no_bukti: checkedBukti
                },
                success: function(response) {
                    statusText.css('color', '#28a745').text(response.message || 'Sinkronisasi berhasil!');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    var err = xhr.responseJSON;
                    statusText.css('color', '#dc3545').text(err && err.message ? err.message : 'Terjadi kesalahan saat sinkronisasi.');
                    btn.prop('disabled', false).css('opacity', '1').html('Sync Selected');
                }
            });
        });

        $('#btnSyncAll').click(function() {
            var allBukti = [];
            $('.sync-checkbox').each(function() {
                allBukti.push($(this).val());
            });

            if (allBukti.length === 0) {
                alert('Tidak ada transaksi PPN = 1 pada periode ini.');
                return;
            }

            if (confirm('Apakah Anda yakin ingin mensinkronisasi seluruh ' + allBukti.length + ' transaksi PPN pada periode ini?')) {
                var btn = $(this);
                var statusText = $('#syncStatus');
                btn.prop('disabled', true).css('opacity', '0.6').text('Syncing All...');
                statusText.css('color', '#6c757d').text('Mensinkronisasi seluruh data PPN...');

                $.ajax({
                    url: "{{ route('pembelian.syncToPortalMp') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        no_bukti: allBukti
                    },
                    success: function(response) {
                        statusText.css('color', '#28a745').text(response.message || 'Sinkronisasi berhasil!');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        var err = xhr.responseJSON;
                        statusText.css('color', '#dc3545').text(err && err.message ? err.message : 'Terjadi kesalahan saat sinkronisasi.');
                        btn.prop('disabled', false).css('opacity', '1').html('Sync All (PPN)');
                    }
                });
            }
        });
    });
</script>
