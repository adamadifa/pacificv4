<style>
   .table-modal {
      overflow: hidden;
   }
</style>
<div class="row mb-3">
   <div class="col-12">
      <table class="table">
         <tr>
            <th style="width: 20%">No. DPB</th>
            <td>{{ $dpb->no_dpb }}</td>
         </tr>
         <tr>
            <th>Tanggal</th>
            <td>{{ DateToIndo($dpb->tanggal_ambil) }}</td>
         </tr>
         <tr>
            <th>Salesman</th>
            <td>{{ $dpb->nama_salesman }}</td>
         </tr>
         <tr>
            <th>Cabang</th>
            <td>{{ textUpperCase($dpb->nama_cabang) }}</td>
         </tr>
         <tr>
            <th>No. Kendaraan</th>
            <td>{{ $dpb->no_polisi }}</td>
         </tr>
      </table>

   </div>
</div>
<div class="row mt-3">
   <div class="col">
      <table class="table table-bordered table-hover">
         <thead class="table-dark">
            <tr>
               <th rowspan="3">kode</th>
               <th rowspan="3">Nama Produk</th>
               <th colspan="3" class="text-center">Pengambilan</th>
               <th colspan="3" class="text-center bg-success">Pengembalian</th>
               <th rowspan="2" colspan="3" class="text-center bg-danger">Barang Keluar</th>
            </tr>
            <tr>
               <th colspan="3" class="text-center">{{ DateToIndo($dpb->tanggal_ambil) }}</th>
               <th colspan="3" class="text-center bg-success">{!! !empty($dpb->tanggal_kembali) ? DateToIndo($dpb->tanggal_kembali) : '<span class="badge bg-warning">Waiting</span>' !!} </th>
            </tr>
            <tr>
               <th>Dus / Ball</th>
               <th>Pack</th>
               <th>Pcs</th>

               <th class="bg-success">Dus / Ball</th>
               <th class="bg-success">Pack</th>
               <th class="bg-success">Pcs</th>

               <th class="bg-danger">Dus / Ball</th>
               <th class="bg-danger">Pack</th>
               <th class="bg-danger">Pcs</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($detail as $d)
               @php
                  //Pengambilan

                  $jml_dus_ambil = floor($d->jml_ambil / $d->isi_pcs_dus);
                  $sisa_dus_ambil = $d->jml_ambil % $d->isi_pcs_dus;
                  if (!empty($d->isi_pack_dus)) {
                      $jml_pack_ambil = floor($sisa_dus_ambil / $d->isi_pcs_pack);
                      $sisa_pack_ambil = $sisa_dus_ambil % $d->isi_pcs_pack;
                  } else {
                      $jml_pack_ambil = 0;
                      $sisa_pack_ambil = $sisa_dus_ambil;
                  }
                  $jml_pcs_ambil = $sisa_pack_ambil;

                  //Pengembalian

                  $jml_dus_kembali = floor($d->jml_kembali / $d->isi_pcs_dus);
                  $sisa_dus_kembali = $d->jml_kembali % $d->isi_pcs_dus;
                  if (!empty($d->isi_pack_dus)) {
                      $jml_pack_kembali = floor($sisa_dus_kembali / $d->isi_pcs_pack);
                      $sisa_pack_kembali = $sisa_dus_kembali % $d->isi_pcs_pack;
                  } else {
                      $jml_pack_kembali = 0;
                      $sisa_pack_kembali = $sisa_dus_kembali;
                  }
                  $jml_pcs_kembali = $sisa_pack_kembali;

                  //Barang Kleuar

                  $jml_dus_keluar = floor($d->jml_penjualan / $d->isi_pcs_dus);
                  $sisa_dus_keluar = $d->jml_penjualan % $d->isi_pcs_dus;
                  if (!empty($d->isi_pack_dus)) {
                      $jml_pack_keluar = floor($sisa_dus_keluar / $d->isi_pcs_pack);
                      $sisa_pack_keluar = $sisa_dus_keluar % $d->isi_pcs_pack;
                  } else {
                      $jml_pack_keluar = 0;
                      $sisa_pack_keluar = $sisa_dus_keluar;
                  }
                  $jml_pcs_keluar = $sisa_pack_keluar;
               @endphp
               <tr>
                  <td>{{ $d->kode_produk }}</td>
                  <td>{{ $d->nama_produk }}</td>

                  <td class="text-center">{{ formatAngka($jml_dus_ambil) }}</td>
                  <td class="text-center">{{ formatAngka($jml_pack_ambil) }}</td>
                  <td class="text-center">{{ formatAngka($jml_pcs_ambil) }}</td>

                  <td class="text-center" style="background-color:#28c76f1a">{{ formatAngka($jml_dus_kembali) }}</td>
                  <td class="text-center" style="background-color:#28c76f1a">{{ formatAngka($jml_pack_kembali) }}</td>
                  <td class="text-center" style="background-color:#28c76f1a">{{ formatAngka($jml_pcs_kembali) }}</td>

                  <td class="text-center" style="background-color: #ea54552e">{{ formatAngka($jml_dus_keluar) }}</td>
                  <td class="text-center" style="background-color: #ea54552e">{{ formatAngka($jml_pack_keluar) }}</td>
                  <td class="text-center" style="background-color: #ea54552e">{{ formatAngka($jml_pcs_keluar) }}</td>
               </tr>
            @endforeach
         </tbody>
      </table>
   </div>
</div>
<div class="row mt-2">
   <div class="col">
      <div class="table-modal">

         <table class="table table-bordered table-hover table-stripped" style="width: 150%">
            <thead class="table-dark">
               <tr>
                  <th rowspan="2">Kode</th>
                  <th rowspan="2" style="width: 15%">Nama Produk</th>
                  <th colspan="3" class="text-center">Retur</th>
                  <th colspan="3" class="text-center">Hutang Kirim</th>
                  <th colspan="3" class="text-center">Penjualan</th>
                  <th colspan="3" class="text-center">Pelunasan<br> Hutang Kirim</th>
                  <th colspan="3" class="text-center">Promo</th>
                  <th colspan="3" class="text-center">Ganti Barang</th>
               </tr>
               <tr class="text-center">
                  <th>Dus</th>
                  <th>Pack</th>
                  <th>Pcs</th>

                  <th>Dus</th>
                  <th>Pack</th>
                  <th>Pcs</th>

                  <th>Dus</th>
                  <th>Pack</th>
                  <th>Pcs</th>

                  <th>Dus</th>
                  <th>Pack</th>
                  <th>Pcs</th>

                  <th>Dus</th>
                  <th>Pack</th>
                  <th>Pcs</th>

                  <th>Dus</th>
                  <th>Pack</th>
                  <th>Pcs</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($mutasi_dpb as $d)
                  <tr>
                     <td>{{ $d->kode_produk }}</td>
                     <td>{{ $d->nama_produk }}</td>
                  </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>
<script>
   $(".table-modal").freezeTable({
      'scrollable': true,
      'freezeHead': false,
      'columnNum': 2,
   });
</script>
