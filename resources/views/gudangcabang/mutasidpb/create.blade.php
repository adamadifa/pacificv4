<form action="#" method="POST" id="formMutasiDPB">
   @csrf
   <div class="row mb-2">
      <div class="col">
         <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" />
         <x-select label="Jenis Mutasi" name="jenis_mutasi" :data="$jenis_mutasi" key="kode_jenis_mutasi" textShow="jenis_mutasi"
            upperCase="true" select2="select2Jenismutasi" />
      </div>
   </div>
   <div class="row">
      <div class="col">
         <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
               <tr>
                  <th rowspan="2">Kode</th>
                  <th rowspan="2">Produk</th>
                  <th colspan="3" class="text-center">Kuantitas</th>
               </tr>
               <tr>
                  <th class="text-center">Dus</th>
                  <th class="text-center">Pack</th>
                  <th class="text-center">Pcs</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($produk as $d)
                  <tr>
                     <td>{{ $d->kode_produk }}</td>
                     <td>{{ $d->nama_produk }}</td>
                  </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</form>
<script>
   const select2Jenismutasi = $('.select2Jenismutasi');
   if (select2Jenismutasi.length) {
      select2Jenismutasi.each(function() {
         var $this = $(this);
         $this.wrap('<div class="position-relative"></div>').select2({
            placeholder: 'Jenis Mutasi',
            allowClear: true,
            dropdownParent: $this.parent()
         });
      });
   }
</script>
