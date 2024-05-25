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
    @php
      $detail = json_decode($penjualan->detail, true);
    @endphp
    <table border="0" width="100%">
      <tr>
        <td style="width:15%">
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
          <td style="width: 10%">Tanggal</td>
          <td style="width: 5%">:</td>
          <td style="width: 15%">{{ DateToIndo($penjualan->tanggal) }}</td>
          <td style="width: 5%"></td>
          <td style="width: 15%">Nama Customer</td>
          <td style="width: 5%">:</td>
          <td><b>{{ $penjualan->kode_pelanggan }}</b> {{ $penjualan->nama_pelanggan }} ({{ $penjualan->no_hp_pelanggan }})</td>
        </tr>
        <tr>
          <td>Jenis Transaksi</td>
          <td>:</td>
          <td>
            {{ textUpperCase($penjualan->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT ( JT : ' . DateToIndo(date('Y-m-d', strtotime('14 day', strtotime($penjualan->tanggal)))) . ')') }}

          </td>
          </td>
          <td style="width: 5%"></td>
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
          <td style="width: 5%"></td>
          <td style="width: 15%">Nama Customer</td>
          <td style="width: 5%">:</td>
          <td style="width: 45%">{{ $penjualan->nama_pelanggan }}</td>
        </tr>
        <tr>
          <td>Jenis Transaksi</td>
          <td>:</td>
          <td>{{ textUpperCase($penjualan->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT') }}</td>
          <td style="width: 5%"></td>
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
        @php
          $no = 1;
          $subtotal = 0;
        @endphp
        @foreach ($detail as $d)
          @php
            $jumlah = explode('|', convertToduspackpcsv2($d['isi_pcs_dus'], $d['isi_pcs_pack'], $d['jumlah']));
            $jumlah_dus = $jumlah[0];
            $jumlah_pack = $jumlah[1];
            $jumlah_pcs = $jumlah[2];
            $subtotal += $d['subtotal'];
          @endphp
          <tr>
            <td>{{ $no }}</td>
            <td>{{ $d['kode_harga'] }}</td>
            <td>{{ $d['nama_produk'] }}</td>
            <td align="right">{{ formatAngka($d['harga_dus']) }}</td>
            <td align="center">{{ formatAngka($jumlah_dus) }}</td>
            <td align="center">{{ formatAngka($jumlah_pack) }}</td>
            <td align="center">{{ formatAngka($jumlah_pcs) }}</td>
            <td align="right">{{ formatAngka($d['subtotal']) }}</td>
            @if ($penjualan->kode_cabang == 'BDG')
              <td></td>
            @endif
          </tr>
          @php
            $no++;
          @endphp
        @endforeach
        <tr>
          <td colspan="4"></td>
          <td colspan="3" align="center">Jumlah</td>
          <td align="right">{{ formatAngka($subtotal) }}</td>
        </tr>
        <tr>
          <td colspan="4"></td>
          <td colspan="3" align="center">Diskon</td>
          <td align="right">{{ formatAngka($penjualan->potongan) }}</td>
        </tr>
        @if ($penjualan->potongan_istimewa != 0)
          <tr>
            <td colspan="4"></td>
            <td colspan="3" align="center">Potongan Istimewa</td>
            <td align="right">{{ formatAngka($penjualan->potongan_istimewa) }}</td>
          </tr>
        @endif
        <tr>
          <td colspan="4"></td>
          <td colspan="3" align="center">Penyesuaian</td>
          <td align="right">{{ formatAngka($penjualan->penyesuaian) }}</td>
        </tr>
        @if (!empty($penjualan->ppn))
          @php
            $dpp = $penjualan->total_bruto - $penjualan->potongan - $penjualan->penyesuaian - $penjualan->potongan_istimewa;
          @endphp
          <tr>
            <td colspan="4"></td>
            <td colspan="3" align="center">DPP</td>
            <td align="right">
              {{ formatAngka($dpp) }}
            </td>
          </tr>
          <tr>
            <td colspan="4"></td>
            <td colspan="3" align="center">PPN</td>
            <td align="right">{{ formatAngka($penjualan->ppn) }}</td>
          </tr>
        @endif
        <tr>
          <td colspan="4"></td>
          <td colspan="3" align="center">Retur</td>
          <td align="right">{{ formatAngka($penjualan->total_retur) }}</td>
        </tr>
        <tr>
          <td colspan="4"></td>
          <td colspan="3" align="center">Total Pembayaran</td>
          <td align="right">
            @php
              $total_netto = $penjualan->total_bruto - $penjualan->total_retur - $penjualan->potongan - $penjualan->potongan_istimewa - $penjualan->penyesuaian + $penjualan->ppn;
            @endphp
            {{ formatAngka($total_netto) }}
          </td>
        </tr>
        @if ($penjualan->kode_cabang == 'BDG')
          <tr>
            <td colspan="4"></td>
            <td colspan="3" align="center">Terbilang</td>
            <td align="right"><i>{{ ucwords(terbilang($total_netto)) }}</i></td>
          </tr>
        @endif
      </tbody>
    </table>
    @if ($penjualan->kode_cabang == 'BDG')
      <table class="garis5" width="100%" style="margin-top:10px">
        <tr style="font-weight:bold; text-align:center">
          <td>Dibuat</td>
          <td>Diserahkan</td>
          <td>Diterima</td>
          <td>Mengetahui</td>
          <td rowspan="3">
            <div style="display: flex; align-items: center; height:20px;">
              <div
                style="width:10px; height:10px; border:1px solid black; margin-bottom:5px; margin-left:5px">
              </div>
              <div style="margin-left: 10px; margin-bottom:5px">Cash</div>
            </div>
            <div style="display: flex; align-items: center; height:20px;">
              <div
                style="width:10px; height:10px; border:1px solid black; margin-bottom:5px; margin-left:5px">
              </div>
              <div style="margin-left: 10px; margin-bottom:5px">Transfer</div>
            </div>
            <div style="display: flex; align-items: center; height:20px;">
              <div
                style="width:10px; height:10px; border:1px solid black; margin-bottom:5px; margin-left:5px">
              </div>
              <div style="margin-left: 10px; margin-bottom:5px">Check/Giro</div>
            </div>
          </td>
        </tr>
        <tr style="font-weight:bold;">
          <td style="height: 40px"></td>
          <td></td>
          <td>
            @if (!empty($penjualan->signature))
              @php
                $path = Storage::url('signature/' . $penjualan->signature);
              @endphp
              <img src="{{ url($path) }}" alt="" style="width:100px; height:100px">
            @endif
          </td>
          <td></td>
        </tr>
        <tr style="font-weight:bold; text-align:center">
          <td>Penjualan</td>
          <td>Pengirim</td>
          <td>Pelanggan</td>
          <td>Pejabat Cabang</td>
        </tr>
      </table>
    @else
      <table class="garis5" width="100%" style="margin-top:10px">
        <tr style="font-weight:bold; text-align:center">
          <td>Dibuat</td>
          <td>Diserahkan</td>
          <td>Diterima</td>
          <td>Mengetahui</td>
          <td>Jam Masuk</td>
        </tr>
        <tr style="font-weight:bold;">
          <td rowspan="3"></td>
          <td rowspan="3" style="width:20%; text-align:center"></td>
          <td rowspan="3" style="width:20%; text-align:center">
            @if (Auth::user()->kode_cabang != 'SKB')
              @if (!empty($penjualan->signature))
                @php
                  $path = Storage::url('signature/' . $penjualan->signature);
                @endphp
                <img src="{{ url($path) }}" alt="" style="width:100px; height:100px">
              @endif
            @endif
          </td>
          <td rowspan="3"></td>
        </tr>
        <tr>
          <td style="height: 20px"></td>
        </tr>
        <tr>
          <td style="font-weight:bold; text-align:center">Jam Keluar</td>
        </tr>
        <tr style="font-weight:bold; text-align:center">
          <td>Penjualan</td>
          <td>Pengirim</td>
          <td>Pelanggan</td>
          <td>Security</td>
          <td></td>
        </tr>
      </table>
    @endif
    <div style="margin-bottom: 30px"></div>
    <div style="page-break-before:always;"></div>
  @endforeach

</body>
