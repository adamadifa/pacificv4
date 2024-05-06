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
<ul class="nav nav-tabs" role="tablist">
   <li class="nav-item" role="presentation">
      <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#detaildpb" aria-controls="detaildpb" aria-selected="true">
         <i class="tf-icons ti ti-file-description ti-xs me-1"></i> Detail DPB
      </button>
   </li>
   <li class="nav-item" role="presentation">
      <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#mutasidpb" aria-controls="mutasidpb" aria-selected="false" tabindex="-1">
         <i class="tf-icons ti ti-stack-push ti-xs me-1"></i> Mutasi DPB
      </button>
   </li>
</ul>
<div class="tab-content">
   <div class="tab-pane fade show active" id="detaildpb" role="tabpanel">
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
                        <th colspan="3" class="text-center bg-success">Retur</th>
                        <th colspan="3" class="text-center bg-success">Hutang Kirim</th>
                        <th colspan="3" class="text-center bg-danger">Penjualan</th>
                        <th colspan="3" class="text-center bg-danger">Pelunasan<br> Hutang Kirim</th>
                        <th colspan="3" class="text-center bg-danger">Promo</th>
                        <th colspan="3" class="text-center bg-danger">Ganti Barang</th>
                     </tr>
                     <tr class="text-center">
                        <th class="bg-success">Dus</th>
                        <th class="bg-success">Pack</th>
                        <th class="bg-success">Pcs</th>

                        <th class="bg-success">Dus</th>
                        <th class="bg-success">Pack</th>
                        <th class="bg-success">Pcs</th>

                        <th class="bg-danger">Dus</th>
                        <th class="bg-danger">Pack</th>
                        <th class="bg-danger">Pcs</th>

                        <th class="bg-danger">Dus</th>
                        <th class="bg-danger">Pack</th>
                        <th class="bg-danger">Pcs</th>

                        <th class="bg-danger">Dus</th>
                        <th class="bg-danger">Pack</th>
                        <th class="bg-danger">Pcs</th>

                        <th class="bg-danger">Dus</th>
                        <th class="bg-danger">Pack</th>
                        <th class="bg-danger">Pcs</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach ($mutasi_dpb as $d)
                        @php
                           //Retur
                           $retur = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_retur));
                           $retur_dus = $retur[0];
                           $retur_pack = $retur[1];
                           $retur_pcs = $retur[2];

                           //Hutang Kirim
                           $hutangkirim = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_hutangkirim));
                           $hutangkirim_dus = $hutangkirim[0];
                           $hutangkirim_pack = $hutangkirim[1];
                           $hutangkirim_pcs = $hutangkirim[2];

                           //Penjualan
                           $penjualan = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_penjualan));
                           $penjualan_dus = $penjualan[0];
                           $penjualan_pack = $penjualan[1];
                           $penjualan_pcs = $penjualan[2];

                           //Pelunasan Hutang Kirim
                           $pelunasanhutangkirim = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_pelunasanhutangkirim));
                           $pelunasanhutangkirim_dus = $pelunasanhutangkirim[0];
                           $pelunasanhutangkirim_pack = $pelunasanhutangkirim[1];
                           $pelunasanhutangkirim_pcs = $pelunasanhutangkirim[2];

                           //Promosi
                           $promosi = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_promosi));
                           $promosi_dus = $promosi[0];
                           $promosi_pack = $promosi[1];
                           $promosi_pcs = $promosi[2];

                           //Ganti Barang
                           $gantibarang = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_gantibarang));
                           $gantibarang_dus = $gantibarang[0];
                           $gantibarang_pack = $gantibarang[1];
                           $gantibarang_pcs = $gantibarang[2];

                        @endphp
                        <tr>
                           <td>{{ $d->kode_produk }}</td>
                           <td>{{ $d->nama_produk }}</td>
                           <td style="background-color:#28c76f1a">{{ formatAngka($retur_dus) }}</td>
                           <td style="background-color:#28c76f1a">{{ formatAngka($retur_pack) }}</td>
                           <td style="background-color:#28c76f1a">{{ formatAngka($retur_pcs) }}</td>

                           <td style="background-color:#28c76f1a">{{ formatAngka($hutangkirim_dus) }}</td>
                           <td style="background-color:#28c76f1a">{{ formatAngka($hutangkirim_pack) }}</td>
                           <td style="background-color:#28c76f1a">{{ formatAngka($hutangkirim_pcs) }}</td>

                           <td style="background-color: #ea54552e">{{ formatAngka($penjualan_dus) }}</td>
                           <td style="background-color: #ea54552e">{{ formatAngka($penjualan_pack) }}</td>
                           <td style="background-color: #ea54552e">{{ formatAngka($penjualan_pcs) }}</td>

                           <td style="background-color: #ea54552e">{{ formatAngka($pelunasanhutangkirim_dus) }}</td>
                           <td style="background-color: #ea54552e">{{ formatAngka($pelunasanhutangkirim_pack) }}</td>
                           <td style="background-color: #ea54552e">{{ formatAngka($pelunasanhutangkirim_pcs) }}</td>

                           <td style="background-color: #ea54552e">{{ formatAngka($promosi_dus) }}</td>
                           <td style="background-color: #ea54552e">{{ formatAngka($promosi_pack) }}</td>
                           <td style="background-color: #ea54552e">{{ formatAngka($promosi_pcs) }}</td>

                           <td style="background-color: #ea54552e">{{ formatAngka($gantibarang_dus) }}</td>
                           <td style="background-color: #ea54552e">{{ formatAngka($gantibarang_pack) }}</td>
                           <td style="background-color: #ea54552e">{{ formatAngka($gantibarang_pcs) }}</td>
                        </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <div class="tab-pane fade" id="mutasidpb" role="tabpanel">
      <div class="row mb-2">
         <div class="col">
            <a href="#" class="btn btn-primary" id="btnCreatemutasidpb"><i class="ti ti-plus me-1"></i>Tambah Data Mutasi</a>
         </div>
      </div>
      <div class="row mb-2">
         <div class="col">
            <x-select label="Jenis Mutasi" name="jenis_mutasi" :data="$jenis_mutasi" key="kode_jenis_mutasi" textShow="jenis_mutasi"
               upperCase="true" select2="select2Jenismutasi" />
         </div>
      </div>
      <div class="row">
         <div class="col">
            <table class="table table-hover table-bordered table-striped">
               <thead class="table-dark">
                  <tr>
                     <th>Tanggal</th>
                     <th>Jenis Mutasi</th>
                     <th>#</th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>

   </div>
</div>
<script>
   $(function() {
      $(".table-modal").freezeTable({
         'scrollable': true,
         'freezeHead': false,
         'columnNum': 2,
      });


      $("#btnCreatemutasidpb").click(function(e) {
         e.preventDefault();
         $("#modalMutasi").modal("show");
         $("#modalMutasi").find(".modal-title").text("Tambah Data Mutasi");
         $("#loadmodalMutasi").load(`/mutasidpb/create`);
      });
   });
</script>
