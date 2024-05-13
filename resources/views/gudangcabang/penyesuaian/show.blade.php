<div class="row mb-3">
   <div class="col">
      <table class="table">
         <tr>
            <th style="width: 20%">No. Mutasi</th>
            <td>{{ $mutasi->no_mutasi }}</td>
         </tr>
         <tr>
            <th>Tanggal</th>
            <td>{{ DateToIndo($mutasi->tanggal) }}</td>
         </tr>
         <tr>
            <th>Cabang</th>
            <td>{{ textUpperCase($mutasi->nama_cabang) }}</td>
         </tr>
         <tr>
            <th>Jenis Mutasi</th>
            <td>{{ $mutasi->jenis_mutasi }}</td>
         </tr>
         <tr>
            <th>IN / OUT</th>
            <td>
               @if ($mutasi->jenis_mutasi == 'PY')
                  @if ($mutasi->in_out_good == 'I')
                     <span class="badge bg-success">IN</span>
                  @else
                     <span class="badge bg-danger">OUT</span>
                  @endif
               @else
                  @if ($mutasi->in_out_bad == 'I')
                     <span class="badge bg-success">IN</span>
                  @else
                     <span class="badge bg-danger">OUT</span>
                  @endif
               @endif
            </td>
         </tr>
         <tr>
            <th>Keterangan</th>
            <td>{{ $mutasi->keterangan }}</td>
         </tr>
      </table>
   </div>
</div>

<div class="row">
   <div class="col">
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
