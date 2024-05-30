<form action="{{ route('produk.update', Crypt::encrypt($produk->kode_produk)) }}" id="formeditProduk" method="POST">
   @csrf
   @method('PUT')
   <x-input-with-icon icon="ti ti-barcode" label="Kode Produk" name="kode_produk" value="{{ $produk->kode_produk }}"
      dsiabled="true" />
   <x-input-with-icon icon="ti ti-file-text" label="Nama Produk" name="nama_produk" value="{{ $produk->nama_produk }}" />
   <x-select label="Jenis Produk" name="kode_jenis_produk" :data="$jenisproduk" key="kode_jenis_produk"
      textShow="nama_jenis_produk" selected="{{ $produk->kode_jenis_produk }}" />
   <x-select label="Kategori Produk" name="kode_kategori_produk" :data="$kategoriproduk" key="kode_kategori_produk"
      textShow="nama_kategori_produk" selected="{{ $produk->kode_kategori_produk }}" />
   <x-input-with-icon icon="ti ti-file-text" label="Satuan" name="satuan" value="{{ $produk->satuan }}" />
   <div class="row">
      <div class="col-4">
         <x-input-with-icon icon="ti ti-file-text" label="Isi Pcs / Dus" name="isi_pcs_dus"
            value="{{ $produk->isi_pcs_dus }}" />
      </div>
      <div class="col-4">
         <x-input-with-icon icon="ti ti-file-text" label="Isi Pack / Dus" name="isi_pack_dus"
            value="{{ $produk->isi_pack_dus }}" />
      </div>
      <div class="col-4">
         <x-input-with-icon icon="ti ti-file-text" label="Isi Pcs / Pack" name="isi_pcs_pack"
            value="{{ $produk->isi_pcs_pack }}" />
      </div>
   </div>
   <div class="form-group mb-3">
      <select name="status_aktif_produk" id="status_aktif_produk" class="form-select">
         <option value="">Status</option>
         <option {{ $produk->status_aktif_produk === '1' ? 'selected' : '' }} value="1">Aktif</option>
         <option {{ $produk->status_aktif_produk === '0' ? 'selected' : '' }} value="0">Non Aktif</option>
      </select>
   </div>

   <x-input-with-icon icon="ti ti-barcode" label="Kode SKU" name="kode_sku" value="{{ $produk->kode_sku }}" />
   <x-select label="Kategori Diskon" name="kode_kategori_diskon" :data="$kategoridiskon" key="kode_kategori_diskon"
      textShow="nama_kategori" selected="{{ $produk->kode_kategori_diskon }}" upperCase="true" />
   <x-input-with-icon icon="ti ti-file-text" label="Urutan" name="urutan" value="{{ $produk->urutan }}" />
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
<script src="{{ asset('assets/js/pages/produk/edit.js') }}"></script>
