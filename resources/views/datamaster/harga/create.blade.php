<form action="{{ route('harga.store') }}" id="formcreateHarga" method="POST">
   @csrf
   <x-input-with-icon icon="ti ti-barcode" label="Kode Harga" name="kode_harga" />
   <x-select label="Produk" name="kode_produk" :data="$produk" key="kode_produk" textShow="nama_produk" />
   <div class="row">
      <div class="col-lg-4 col-md-12 col-sm-12">
         <x-input-with-icon label="Harga Dus" icon="ti ti-file" name="harga_dus" align="right" money="true" />
      </div>
      <div class="col-lg-4 col-md-12 col-sm-12">
         <x-input-with-icon label="Harga Pack" icon="ti ti-file" name="harga_pack" align="right" money="true" />
      </div>
      <div class="col-lg-4 col-md-12 col-sm-12">
         <x-input-with-icon label="Harga Pcs" icon="ti ti-file" name="harga_pcs" align="right" money="true" />
      </div>
   </div>
   <div class="row">
      <div class="col-lg-4 col-md-12 col-sm-12">
         <x-input-with-icon label="Harga Retur Dus" icon="ti ti-file" name="harga_retur_dus" align="right"
            money="true" />
      </div>
      <div class="col-lg-4 col-md-12 col-sm-12">
         <x-input-with-icon label="Harga Retur Pack" icon="ti ti-file" name="harga_retur_pack" align="right"
            money="true" />
      </div>
      <div class="col-lg-4 col-md-12 col-sm-12">
         <x-input-with-icon label="Harga Retur Pcs" icon="ti ti-file" name="harga_retur_pcs" align="right"
            money="true" />
      </div>
   </div>
   <div class="form-group mb-3">
      <select name="status_aktif_harga" id="status_aktif_harga" class="form-select">
         <option value="">Status</option>
         <option value="1">Aktif</option>
         <option value="0">Non Aktif</option>
      </select>
   </div>
   <div class="form-group mb-3">
      <select name="status_ppn" id="status_ppn" class="form-select">
         <option value="">Status PPN</option>
         <option value="IN">INCLUDE</option>
         <option value="EX">EXCLUDE</option>
      </select>
   </div>
   <div class="form-group mb-3">
      <select name="status_promo" id="status_promo" class="form-select">
         <option value="">Status Promo</option>
         <option value="1">Aktif</option>
         <option value="0">Non Aktif</option>
      </select>
   </div>

   <x-select label="Kategori" name="kode_kategori_salesman" :data="$kategori_salesman" key="kode_kategori_salesman"
      textShow="nama_kategori_salesman" />
   <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" />
   <div class="form-group mb-3">
      <select name="kode_pelanggan" id="kode_pelanggan" class="form-select">
         <option value="">Kode Pelanggan</option>
      </select>
   </div>
   <div class="form-group">
      <button class="btn btn-primary w-100" type="submit">
         <ion-icon name="send-outline" class="me-1"></ion-icon>
         Submit
      </button>
   </div>
</form>

<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/harga/create.js') }}"></script>
<script>
   $(".money").maskMoney();
</script>
