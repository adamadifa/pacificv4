<form action="{{ route('cabang.store') }}" id="formcreateCabang" method="POST">
   @csrf
   <x-input-with-icon icon="ti ti-barcode" label="Kode Cabang" name="kode_cabang" />
   <x-input-with-icon icon="ti ti-file-text" label="Nama Cabang" name="nama_cabang" />
   <x-input-with-icon icon="ti ti-map-pin" label="Alamat Cabang" name="alamat_cabang" />
   <x-input-with-icon icon="ti ti-phone" label="Telepon Cabang" name="telepon_cabang" />
   <x-input-with-icon icon="ti ti-map-pin" label="Lokasi Cabang" name="lokasi_cabang" />
   <x-input-with-icon icon="ti ti-access-point" label="Radius Cabang" name="radius_cabang" />
   <x-input-with-icon icon="ti ti-barcode" label="Kode PT" name="kode_pt" />
   <x-input-with-icon icon="ti ti-building" label="Nama PT" name="nama_pt" />
   <x-select label="Regional" name="kode_regional" :data="$regional" key="kode_regional" textShow="nama_regional" />
   <x-input-with-icon icon="ti ti-access-point" label="Urutan" name="urutan" />
   <div class="form-group mb-3">
      <input class="form-control" type="color" name="color_marker" value="#666EE8" id="html5-color-input">
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
<script src="{{ asset('assets/js/pages/cabang/create.js') }}"></script>
