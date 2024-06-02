<table class="table table-bordered  table-striped table-hover" id="tabelharga">
   <thead class="table-dark">
      <tr>
         <th>Kode</th>
         <th>Nama Produk</th>
         <th>Harga /Dus</th>
         <th>Harga /Pack</th>
         <th>Harga /Pcs</th>
         <th>Kategori</th>
         <th>#</th>
      </tr>
   </thead>
   <tbody>
      @foreach ($harga as $d)
         <tr>
            <td>{{ $d->kode_harga }}</td>
            <td>{{ $d->nama_produk }}</td>
            <td class="text-end">{{ formatAngka($d->harga_retur_dus) }}</td>
            <td class="text-end">{{ formatAngka($d->harga_retur_pack) }}</td>
            <td class="text-end">{{ formatAngka($d->harga_retur_pcs) }}</td>
            <td>{{ $d->kode_kategori_salesman }}</td>
            <td><a href="#" class="pilihProduk"
                  kode_harga="{{ $d->kode_harga }}"
                  nama_produk="{{ $d->nama_produk }}"
                  harga_dus = "{{ formatAngka($d->harga_retur_dus) }}"
                  harga_pack = "{{ formatAngka($d->harga_retur_pack) }}"
                  harga_pcs = "{{ formatAngka($d->harga_retur_pcs) }}"
                  isi_pcs_dus="{{ $d->isi_pcs_dus }}"
                  isi_pcs_pack = "{{ $d->isi_pcs_pack }}"
                  kode_kategori_diskon = "{{ $d->kode_kategori_diskon }}">
                  <i class="ti ti-external-link"></i>
               </a>
            </td>
         </tr>
      @endforeach
   </tbody>
</table>
<script>
   $(function() {
      $("#tabelharga").DataTable({
         bAutoWidth: false,
         order: [
            [1, 'asc']
         ]
      });
   });
</script>
