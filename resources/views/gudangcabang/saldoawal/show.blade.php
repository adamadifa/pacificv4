<div class="row">
   <div class="col-12">
      <table class="table">
         <tr>
            <th>Kode</th>
            <td>{{ $saldo_awal->kode_saldo_awal }}</td>
         </tr>
         <tr>
            <th>Bulan</th>
            <td>{{ $nama_bulan[$saldo_awal->bulan] }}</td>
         </tr>
         <tr>
            <th>Tahun</th>
            <td>{{ $saldo_awal->tahun }}</td>
         </tr>
         <tr>
            <th>Good / Bad</th>
            <td>
               @if ($saldo_awal->kondisi == 'GS')
                  <span class="badge bg-success">Good Stok</span>
               @else
                  <span class="badge bg-danger">Bad Stok</span>
               @endif
            </td>
         </tr>
         <tr>
            <th>Cabang</th>
            <td>{{ textUpperCase($saldo_awal->nama_cabang) }}</td>
         </tr>
         <tr>
            <th>Tanggal</th>
            <td>{{ DateToIndo($saldo_awal->tanggal) }}</td>
         </tr>
      </table>

   </div>
</div>
<div class="row">
   <div class="col-12">
      <table class="table table-bordered table-striped table-hover">
         <thead class="table-dark">
            <tr>
               <th rowspan="2" class="align-middle">Kode</th>
               <th rowspan="2" class="align-middle">Nama Produk</th>
               <th colspan="3" class="text-center">Kuantitas</th>
            </tr>
            <tr class="text-center">
               <th>Dus</th>
               <th>Pack</th>
               <th>Pcs</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($detail as $d)
               @php
                  $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                  $jumlah_dus = $jumlah[0];
                  $jumlah_pack = $jumlah[1];
                  $jumlah_pcs = $jumlah[2];
               @endphp
               <tr>
                  <td>{{ $d->kode_produk }} </td>
                  <td>{{ $d->nama_produk }}</td>
                  <td class="text-end">{{ formatAngka($jumlah_dus) }}</td>
                  <td class="text-end">{{ formatAngka($jumlah_pack) }}</td>
                  <td class="text-end">{{ formatAngka($jumlah_pcs) }}</td>
               </tr>
            @endforeach
         </tbody>
      </table>
   </div>
</div>
