<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Laporan Barang Keluar Gudang Logistik {{ date('Y-m-d H:i:s') }}</title>
   <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
   <div class="header">
      <h4 class="title">
         LAPORAN BARANG KELUAR GUDANG LOGISTIK<br>
      </h4>
      <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
      <h4>{{ !empty($kode_jenis_pengeluaran) ? 'JENIS PENGELURAN : ' . textUpperCase($jenis_pengeluaran[$kode_jenis_pengeluaran]) : '' }}</h4>
      <h4>{{ $kategori != null ? 'KATEGORI : ' . textUpperCase($kategori->nama_kategori) : '' }}</h4>
      @if ($barang != null)
         <h4>KODE BARANG : {{ $barang->kode_barang }}</h4>
         <h4>NAMA BARANG : {{ textUpperCase($barang->nama_barang) }}</h4>
      @endif
   </div>
   <div class="content">
      <table class="datatable3">
         <thead>
            <tr>
               <th rowspan="2">NO</th>
               <th rowspan="2">TANGGAL</th>
               <th rowspan="2">BUKTI</th>
               <th rowspan="2">JENIS PENGELUARAN</th>
               <th colspan="7">BARANG KELUAR</th>
            </tr>
            <tr>
               <th>KODE</th>
               <th>NAMA BARANG</th>
               <th>SATUAN</th>
               <th style="width: 200px">KETERANGAN</th>
               <th>CABANG</th>
               <th>QTY</th>
            </tr>
         </thead>
         <tbody>
            @php
               $total_qty = 0;
            @endphp
            @foreach ($barangkeluar as $d)
               @php
                  $total_qty += $d->jumlah;
               @endphp
               <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ DateToIndo($d->tanggal) }}</td>
                  <td>{{ $d->no_bukti }}</td>
                  <td>{{ $jenis_pengeluaran[$d->kode_jenis_pengeluaran] }}</td>
                  <td>{{ $d->kode_barang }}</td>
                  <td>{{ textUpperCase($d->nama_barang) }}</td>
                  <td>{{ textUpperCase($d->satuan) }}</td>
                  <td>{{ $d->keterangan }}</td>
                  <td>{{ $d->nama_cabang }}</td>
                  <td class="right">{{ formatAngkaDesimal($d->jumlah) }}</td>
               </tr>
            @endforeach
         </tbody>
         <tfoot>
            <th colspan="9"></th>
            <th class="right">{{ formatAngkaDesimal($total_qty) }}</th>
         </tfoot>
      </table>
   </div>
</body>
