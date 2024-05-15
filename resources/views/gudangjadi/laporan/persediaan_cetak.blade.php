<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Laporan Persediaan Gudang Jadi {{ date('Y-m-d H:i:s') }}</title>
   <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
   <div class="header">
      <h4>LAPORAN PERSEDIAAN BARANG</h4>
      <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
      <h4>{{ textUpperCase($produk->nama_produk) }}</h4>
   </div>
   <div class="body">
      <table class="datatable3">
         <thead>
            <tr>

               <th rowspan="2">Tanggal</th>
               <th colspan="5">BUKTI</th>
               <th rowspan="2">KETERANGAN</th>
               <th colspan="3" class="green">PENERIMAAN</th>
               <th colspan="3" class="red">PENGELUARAN</th>
               <th rowspan="2" rowspan="2">SALDO AKHIR
               </th>
            </tr>
            <tr>
               <th>FSTHP</th>
               <th>SURAT JALAN</th>
               <th>REPACK</th>
               <th>REJECT</th>
               <th>LAINLAIN</th>
               <th class="green">PRODUKSI</th>
               <th class="green">REPACK</th>
               <th class="green">LAIN LAIN</th>
               <th class="red">KIRIM KE CABANG</th>
               <th class="red">REJECT</th>
               <th class="red">LAIN LAIN</th>
            </tr>
            </tr>
            <tr>
               <th colspan="6"></th>
               <th>SALDO AWAL</th>
               <th colspan="6"></th>
               <th class="right">{{ formatAngka($saldoawal) }}</th>
            </tr>
         </thead>
         <tbody>
            @php
               $total_fsthp = 0;
               $total_repack = 0;
               $total_lainlain_in = 0;
               $total_surat_jalan = 0;
               $total_reject = 0;
               $total_lainlain_out = 0;
               $saldo_akhir = $saldoawal;
            @endphp
            @foreach ($mutasi as $d)
               @php
                  $total_fsthp += $d->jenis_mutasi == 'FS' ? $d->jumlah : 0;
                  $total_repack += $d->jenis_mutasi == 'RP' ? $d->jumlah : 0;
                  $total_lainlain_in += $d->jenis_mutasi == 'LN' && $d->in_out == 'I' ? $d->jumlah : 0;
                  $total_surat_jalan += $d->jenis_mutasi == 'SJ' ? $d->jumlah : 0;
                  $total_reject += $d->jenis_mutasi == 'RJ' ? $d->jumlah : 0;
                  $total_lainlain_out += $d->jenis_mutasi == 'LN' && $d->in_out == 'O' ? $d->jumlah : 0;
                  $jumlah = $d->in_out == 'I' ? $d->jumlah : -$d->jumlah;
                  $saldo_akhir += $jumlah;
               @endphp
               <tr>
                  <td>{{ DateToIndo($d->tanggal) }}</td>
                  <td>{{ $d->jenis_mutasi == 'FS' ? $d->no_mutasi : '' }}</td>
                  <td>{{ $d->jenis_mutasi == 'SJ' ? $d->no_mutasi : '' }}</td>
                  <td>{{ $d->jenis_mutasi == 'RP' ? $d->no_mutasi : '' }}</td>
                  <td>{{ $d->jenis_mutasi == 'RJ' ? $d->no_mutasi : '' }}</td>
                  <td>{{ $d->jenis_mutasi == 'LN' ? $d->no_mutasi : '' }}</td>
                  <td>
                     @if ($d->jenis_mutasi == 'FS')
                        PRODUKSI
                     @elseif ($d->jenis_mutasi == 'SJ')
                        {{ textUpperCase($d->nama_cabang) }}
                     @elseif ($d->jenis_mutasi == 'RP')
                        REPACK
                     @elseif ($d->jenis_mutasi == 'RJ')
                        REJECT
                     @elseif ($d->jenis_mutasi == 'LN')
                        {{ $d->keterangan }}
                     @endif
                  </td>
                  <td class="right">{{ $d->jenis_mutasi == 'FS' ? formatAngka($d->jumlah) : '' }}</td>
                  <td class="right">{{ $d->jenis_mutasi == 'RP' ? formatAngka($d->jumlah) : '' }}</td>
                  <td class="right">
                     {{ $d->jenis_mutasi == 'LN' && $d->in_out == 'I' ? formatAngka($d->jumlah) : '' }}
                  </td>
                  <td class="right">{{ $d->jenis_mutasi == 'SJ' ? formatAngka($d->jumlah) : '' }}</td>
                  <td class="right">{{ $d->jenis_mutasi == 'RJ' ? formatAngka($d->jumlah) : '' }}</td>
                  <td class="right">
                     {{ $d->jenis_mutasi == 'LN' && $d->in_out == 'O' ? formatAngka($d->jumlah) : '' }}
                  </td>
                  <td class="right">{{ formatAngka($saldo_akhir) }}</td>
               </tr>
            @endforeach
         <tfoot>
            <tr>
               <th colspan="7">TOTAL</th>
               <th style="text-align: right">{{ formatAngka($total_fsthp) }}</th>
               <th style="text-align: right">{{ formatAngka($total_repack) }}</th>
               <th style="text-align: right">{{ formatAngka($total_lainlain_in) }}</th>
               <th style="text-align: right">{{ formatAngka($total_surat_jalan) }}</th>
               <th style="text-align: right">{{ formatAngka($total_reject) }}</th>
               <th style="text-align: right">{{ formatAngka($total_lainlain_out) }}</th>
               <th style="text-align: right">{{ formatAngka($saldo_akhir) }}</th>

            </tr>
         </tfoot>
         </tbody>
      </table>
   </div>
</body>
