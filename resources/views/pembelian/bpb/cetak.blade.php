<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bukti Pengajuan dan Penerimaan Barang</title>

    <style>
        body {
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .container {
            border: 2px solid #2b5dab;
            padding: 12px;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #2b5dab;
            margin-bottom: 12px;
        }

        .info-box {
            border: 1px solid #2b5dab;
            padding: 6px;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #2b5dab;
            padding: 6px;
        }

        th {
            background: #eef3ff;
            text-align: center;
        }

        .info td {
            border: none;
            padding: 4px;
            vertical-align: top;
        }

        .info-label {
            width: 18%;
            font-weight: 600;
        }

        .info-sep {
            width: 2%;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        tfoot th {
            background: #dfe8ff;
            font-size: 13px;
        }

        .sign {
            margin-top: 25px;
        }

        .sign td {
            border: none;
            text-align: center;
            height: 75px;
            width: 33%;
        }

        .sign-title {
            margin-bottom: 55px;
            font-weight: 600;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="title">BUKTI PENGAJUAN BARANG</div>

        <div class="info-box">
            <table class="info">
                <tr>
                    <td class="info-label">No. BPB</td>
                    <td class="info-sep">:</td>
                    <td>{{ $bpb->no_bpb ?? '-' }}</td>

                    <td class="info-label">Eksternal Provider</td>
                    <td class="info-sep">:</td>
                    <td>{{ $bpb->nama_supplier ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Tanggal Pengajuan</td>
                    <td class="info-sep">:</td>
                    <td>{{ DateToIndo($bpb->tanggal) ?? '-' }}</td>

                    <td class="info-label">No. PPB</td>
                    <td class="info-sep">:</td>
                    <td>{{ $bpb->no_ref ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <table>
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Kode Barang</th>
                    <th rowspan="2">Nama Barang</th>
                    <th rowspan="2">Satuan</th>
                    <th rowspan="2">Spesifikasi</th>
                    <th colspan="3">Jumlah</th>
                    <th rowspan="2">Harga</th>
                    <th rowspan="2">Total</th>
                    <th colspan="2">Tanggal</th>
                </tr>
                <tr>
                    <th>Diajukan</th>
                    <th>Diterima</th>
                    <th>Selisih</th>
                    <th>Terima</th>
                    <th>Bayar</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp

                @forelse($items as $i => $row)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="text-center">{{ $row->kode_barang }}</td>
                        <td>{{ $row->nama_barang }}</td>
                        <td class="text-center">{{ $row->satuan }}</td>
                        <td>{{ $row->keterangan }}</td>
                        <td class="text-center">{{ $row->jumlah }}</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center">Data tidak tersedia</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="9" class="text-right">TOTAL</th>
                    <th class="text-right">{{ number_format($grandTotal, 0, ',', '.') }}</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>

        <table class="sign">
            <tr>
                <td class="sign-title">Diserahkan</td>
                <td class="sign-title">Diterima</td>
                <td class="sign-title">Mengetahui</td>
            </tr>
            <tr>
                <td><u>Pembelian</u></td>
                <td><u>Gudang</u></td>
                <td><u>Manager</u></td>
            </tr>
        </table>

    </div>

</body>

</html>
