<div class="row">
   <div class="col">
      <table class="table">
         <tr>
            <th>No. Surat Jalan</th>
            <td>{{ $surat_jalan->no_mutasi }}</td>
         </tr>
         <tr>
            <th>No. Dokumen</th>
            <td>{{ $surat_jalan->no_dok }}</td>
         </tr>
         <tr>
            <th>Tanggal</th>
            <td>{{ DateToIndo($surat_jalan->tanggal) }}</td>
         </tr>
         <tr>
            <th>No. Permintaan</th>
            <td>{{ $surat_jalan->no_permintaan }}</td>
         </tr>
         <tr>
            <th>Tanggal Permintaan</th>
            <td>{{ DateToIndo($surat_jalan->tanggal_permintaan) }}</td>
         </tr>
         <tr>
            <th>Cabang</th>
            <td>{{ textUpperCase($surat_jalan->nama_cabang) }}</td>
         </tr>
         <tr>
            <th>Keterangan</th>
            <td>{{ $surat_jalan->keterangan }}</td>
         </tr>
         <tr>
            <th>Status</th>
            <td>
               @if ($surat_jalan->status_surat_jalan == 0)
                  <span class="badge bg-danger">Belum Diterima Cabang</span>
               @elseif($surat_jalan->status_surat_jalan == 1)
                  <span class="badge bg-success">Sudah Diterima Cabang</span>
               @elseif($surat_jalan->status_surat_jalan == 2)
                  <span class="badge bg-info">Transit Out</span>
               @endif
            </td>
         </tr>
      </table>
   </div>
</div>
<div class="row">
   <div class="col">
      <table class="table table-bordered table-striped table-hover">
         <thead class="table-dark">
            <tr>
               <th>Kode</th>
               <th style="width:50%">Nama Produk</th>
               <th>Jumlah</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($detail as $d)
               <tr>
                  <td>{{ $d->kode_produk }}</td>
                  <td>{{ $d->nama_produk }}</td>
                  <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
               </tr>
            @endforeach
         </tbody>
      </table>
   </div>
</div>
