<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Cetak Surat Jalan {{ date('Y-m-d H:i:s') }}</title>
   <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
   <style>
      body {
         letter-spacing: 0px;
         font-family: Calibri;
         font-size: 14px;
      }

      table {
         font-family: Tahoma;
         font-size: 14px;
      }

      .garis5,
      .garis5 td,
      .garis5 tr,
      .garis5 th {
         border: 2px solid black;
         border-collapse: collapse;

      }

      .table {
         border: solid 1px #000000;
         width: 100%;
         font-size: 12px;
         margin: auto;
      }

      .table th {
         border: 1px #000000;
         font-size: 12px;

         font-family: Arial;
      }

      .table td {
         border: solid 1px #000000;
      }
   </style>
</head>

<body>
   @foreach ($pj as $penjualan)
      <table border="0" width="100%">
         <tr>
            <td style="width:10%">
               <table class="garis5">
                  <tr>
                     <td>FAKTUR</td>
                  </tr>
                  <tr>
                     <td>NOMOR {{ $penjualan->no_faktur }}</td>
                  </tr>
                  @if (!empty($penjualan->no_po))
                     <tr>
                        <td>PO : {{ $penjualan->no_po }}</td>
                     </tr>
                  @endif
               </table>
            </td>
            <td colspan="6" align="left">
               @if ($penjualan->mp == '1')
                  <b>CV MAKMUR PERMATA </b><br>
                  <b>Jln. Perintis Kemerdekaan RT 001 / RW 003 Kelurahan Karsamenak Kecamatan Kawalu Kota Tasikmalaya
                     46182 <br>
                     NPWP : 863860342425000
                  </b>
               @else
                  <b>{{ textUpperCase($penjualan->nama_pt) }} </b><br>
                  {{ textCamelCase($penjualan->alamat_cabang) }}
               @endif
            </td>
         </tr>
         <tr>
            <td colspan="7" align="center">
               <hr>
            </td>
         </tr>
      </table>
      @if ($penjualan->kode_cabang == 'BDG')
         <table style="width: 100%">
            <tr>
               <td>Tanggal</td>
               <td>:</td>
               <td>{{ DateToIndo($penjualan->tanggal) }}</td>
               <td style="width: 20%"></td>
               <td>Nama Customer</td>
               <td>:</td>
               <td><b>{{ $penjualan->kode_pelanggan }}</b> {{ $penjualan->nama_pelanggan }} ({{ $penjualan->no_hp_pelanggan }})</td>
            </tr>
            <tr>
               <td>Jenis Transaksi</td>
               <td>:</td>
               <td>
                  {{ textUpperCase($penjualan->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT ( JT : ' . DateToIndo(date('Y-m-d', strtotime('14 day', strtotime($penjualan->tanggal)))) . ')') }}

               </td>
               </td>
               <td style="width: 20%"></td>
               <td>Salesman</td>
               <td>:</td>
               <td><b>{{ $penjualan->kode_salesman }}</b> {{ $penjualan->nama_salesman }}</td>
            </tr>
            <tr>
               <td>Pola Operasi</td>
               <td>:</td>
               <td>{{ $penjualan->nama_kategori_salesman }} ({{ $penjualan->pola_operasi }})</td>
               <td style="width: 20%"></td>
               <td>Alamat</td>
               <td>:</td>
               <td>
                  @if (!empty($penjualan->alamat_toko))
                     {{ $penjualan->alamat_toko }}
                  @else
                     {{ $penjualan->alamat_pelanggan }}
                  @endif
                  ({{ $penjualan->nama_wilayah }})
            </tr>
         </table>
      @else
         <table style="width: 100%">
            <tr>
               <td style="width: 10%">Tanggal</td>
               <td style="width: 5%">:</td>
               <td style="width: 15%">{{ DateToIndo($penjualan->tanggal) }}</td>
               <td style="width: 10%"></td>
               <td style="width: 10%">Nama Customer</td>
               <td style="width: 5%">:</td>
               <td style="width: 45%">{{ $penjualan->nama_pelanggan }}</td>
            </tr>
            <tr>
               <td>Jenis Transaksi</td>
               <td>:</td>
               <td>{{ textUpperCase($penjualan->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT') }}</td>
               <td style="width: 20%"></td>
               <td>Alamat</td>
               <td>:</td>
               <td>
                  @if (!empty($penjualan->alamat_toko))
                     {{ $penjualan->alamat_toko }}
                  @else
                     {{ $penjualan->alamat_pelanggan }}
                  @endif
               </td>
            </tr>
         </table>
      @endif
      <table class="garis5" width="100%" style="margin-top:30px">
         <thead>
            <tr style="padding: 10px">
               <th rowspan="2">NO</th>
               <th rowspan="2">KODE BARANG</th>
               <th rowspan="2">NAMA BARANG</th>
               <th rowspan="2">HARGA</th>
               <th colspan="3">JUMLAH</th>
               <th rowspan="2">TOTAL</th>
               @if ($penjualan->kode_cabang == 'BDG')
                  <th rowspan="2">Keterangan</th>
               @endif
            </tr>
            <tr>
               <th>DUS</th>
               <th>PACK</th>
               <th>PCS</th>
            </tr>
         </thead>
         <tbody>
         </tbody>
      </table>
   @endforeach
</body>
