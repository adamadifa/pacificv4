<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Claim Keuangan - Pembelian {{ $pembelian->no_bukti }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        body {
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            border: 2px solid #002e65;
            padding: 15px;
            box-sizing: border-box;
            background-color: #fff;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header-table td {
            border: none;
            padding: 0;
        }

        .logo-title {
            color: #002e65;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #002e65;
            margin: 10px 0 15px 0;
            text-transform: uppercase;
            border-bottom: 2px double #002e65;
            padding-bottom: 5px;
        }

        .info-box {
            border: 1px solid #002e65;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: none;
            padding: 4px 6px;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            color: #002e65;
            width: 18%;
        }

        .info-sep {
            width: 2%;
            text-align: center;
        }

        .info-value {
            width: 30%;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #002e65;
            padding: 6px 8px;
            vertical-align: middle;
        }

        table.data-table th {
            background: #eef3ff;
            color: #002e65;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            color: #002e65;
            margin-top: 15px;
            margin-bottom: 5px;
            font-size: 12px;
            text-transform: uppercase;
        }

        .approval-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .approval-table td {
            border: 1px solid #002e65;
            text-align: center;
            width: 33.33%;
            padding: 8px;
            vertical-align: top;
        }

        .approval-header {
            background: #eef3ff;
            color: #002e65;
            font-weight: bold;
            padding: 6px;
            border-bottom: 1px solid #002e65;
            text-transform: uppercase;
        }

        .approval-space {
            height: 70px;
        }

        .approval-name {
            font-weight: bold;
            text-decoration: underline;
        }

        @media print {
            body {
                margin: 0;
                background-color: #fff;
            }

            .container {
                border: 2px solid #002e65;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <table class="header-table">
            <tr>
                <td class="logo-title">CV MAKMUR PERMATA</td>
                <td class="text-right" style="color: #666;">Dicetak pada: {{ date('d/m/Y H:i') }}</td>
            </tr>
        </table>

        <div class="info-box">
            <table class="info-table">
                <tr>
                    <td class="info-label">No. Bukti</td>
                    <td class="info-sep">:</td>
                    <td class="info-value fw-bold">{{ $pembelian->no_bukti }}</td>

                    <td class="info-label">Supplier</td>
                    <td class="info-sep">:</td>
                    <td class="info-value">{{ $pembelian->nama_supplier }}</td>
                </tr>
                <tr>
                    <td class="info-label">Tanggal Pembelian</td>
                    <td class="info-sep">:</td>
                    <td class="info-value">{{ formatIndo($pembelian->tanggal) }}</td>

                    <td class="info-label">Asal Ajuan</td>
                    <td class="info-sep">:</td>
                    <td class="info-value"><span
                            style="background: #eef3ff; padding: 2px 6px; border-radius: 3px; font-weight: bold; color: #002e65;">{{ $pembelian->kode_asal_pengajuan }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Jenis Pembayaran</td>
                    <td class="info-sep">:</td>
                    <td class="info-value">{{ $pembelian->jenis_transaksi == 'T' ? 'Tunai' : 'Kredit' }}</td>

                    <td class="info-label">PPN</td>
                    <td class="info-sep">:</td>
                    <td class="info-value">{{ $pembelian->ppn == '1' ? 'PPN' : 'Non PPN' }}</td>
                </tr>
            </table>
        </div>

        <div class="section-title">Detail Barang Pembelian</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 15%">Kode Barang</th>
                    <th>Nama Barang</th>
                    <th style="width: 10%">Qty</th>
                    <th style="width: 15%">Harga Satuan</th>
                    <th style="width: 15%">Subtotal</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_barang = 0;
                @endphp
                @forelse($detail as $index => $row)
                    @php
                        $sub = $row->jumlah * $row->harga;
                        $total_barang += $sub;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $row->kode_barang }}</td>
                        <td>{{ $row->nama_barang }}</td>
                        <td class="text-center">{{ number_format($row->jumlah, 2) }}</td>
                        <td class="text-right">{{ number_format($row->harga, 2) }}</td>
                        <td class="text-right">{{ number_format($sub, 2) }}</td>
                        <td>{{ $row->keterangan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Detail barang tidak tersedia</td>
                    </tr>
                @endforelse
                <tr class="fw-bold">
                    <td colspan="5" class="text-right">Subtotal Barang:</td>
                    <td class="text-right">{{ number_format($total_barang, 2) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        @if (count($potongan) > 0)
            <div class="section-title">Detail Potongan / Penjualan</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 15%">Kode Barang</th>
                        <th>Nama Barang</th>
                        <th style="width: 10%">Qty</th>
                        <th style="width: 15%">Harga Satuan</th>
                        <th style="width: 15%">Subtotal Potongan</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_potongan = 0;
                    @endphp
                    @foreach ($potongan as $index => $row)
                        @php
                            $sub = $row->jumlah * $row->harga;
                            $total_potongan += $sub;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $row->kode_barang }}</td>
                            <td>{{ $row->nama_barang }}</td>
                            <td class="text-center">{{ number_format($row->jumlah, 2) }}</td>
                            <td class="text-right">{{ number_format($row->harga, 2) }}</td>
                            <td class="text-right">{{ number_format($sub, 2) }}</td>
                            <td>{{ $row->keterangan }}</td>
                        </tr>
                    @endforeach
                    <tr class="fw-bold">
                        <td colspan="5" class="text-right">Subtotal Potongan:</td>
                        <td class="text-right">{{ number_format($total_potongan, 2) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        @endif

        <div class="section-title">Ringkasan Klaim</div>
        <table class="data-table" style="width: 50%; float: right; margin-bottom: 20px;">
            <tr>
                <td class="fw-bold" style="width: 60%">Total Barang</td>
                <td class="text-right">{{ number_format($total_barang, 2) }}</td>
            </tr>
            @if (count($potongan) > 0)
                <tr>
                    <td class="fw-bold" style="color: red;">Total Potongan (-)</td>
                    <td class="text-right" style="color: red;">-{{ number_format($total_potongan, 2) }}</td>
                </tr>
            @endif
            @if ($pembelian->penyesuaian_jk != 0)
                <tr>
                    <td class="fw-bold">Penyesuaian Jurnal Koreksi</td>
                    <td class="text-right">{{ number_format($pembelian->penyesuaian_jk, 2) }}</td>
                </tr>
            @endif
            <tr class="fw-bold" style="background: #eef3ff; font-size: 12px;">
                <td style="color: #002e65;">GRAND TOTAL KLAIM</td>
                <td class="text-right" style="color: #002e65;">
                    {{ number_format($total_barang - ($total_potongan ?? 0) + $pembelian->penyesuaian_jk, 2) }}</td>
            </tr>
        </table>
        <div style="clear: both;"></div>

        <table class="approval-table">
            <tr>
                <td>
                    <div class="approval-header">Diajukan Oleh</div>
                    <div class="approval-space"></div>
                    <div class="approval-name">Head Pembelian</div>
                    <div>Staf Pembelian</div>
                </td>
                <td>
                    <div class="approval-header">Diperiksa Oleh</div>
                    <div class="approval-space"></div>
                    <div class="approval-name">GM Operasional</div>
                    <div>General Manager</div>
                </td>
                <td>
                    <div class="approval-header">Disetujui Oleh</div>
                    <div class="approval-space"></div>
                    <div class="approval-name">Direktur</div>
                    <div>Direktur Utama / Perwakilan</div>
                </td>
            </tr>
        </table>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>
