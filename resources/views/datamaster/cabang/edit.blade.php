<form action="{{ route('cabang.update', Crypt::encrypt($cabang->kode_cabang)) }}" id="formeditCabang" method="POST">
   @csrf
   @method('PUT')
   <x-input-with-icon icon="ti ti-barcode" label="Kode Cabang" name="kode_cabang" value="{{ $cabang->kode_cabang }}"
      disabled="true" />
   <x-input-with-icon icon="ti ti-file-text" label="Nama Cabang" name="nama_cabang" value="{{ $cabang->nama_cabang }}" />
   <x-input-with-icon icon="ti ti-map-pin" label="Alamat Cabang" name="alamat_cabang"
      value="{{ $cabang->alamat_cabang }}" />
   <x-input-with-icon icon="ti ti-phone" label="Telepon Cabang" name="telepon_cabang"
      value="{{ $cabang->telepon_cabang }}" />
   <x-input-with-icon icon="ti ti-map-pin" label="Lokasi Cabang" name="lokasi_cabang"
      value="{{ $cabang->lokasi_cabang }}" />
   <x-input-with-icon icon="ti ti-access-point" label="Radius Cabang" name="radius_cabang"
      value="{{ $cabang->radius_cabang }}" />
   <x-input-with-icon icon="ti ti-barcode" label="Kode PT" name="kode_pt" value="{{ $cabang->kode_pt }}" />
   <x-input-with-icon icon="ti ti-building" label="Nama PT" name="nama_pt" value="{{ $cabang->nama_pt }}" />
   <x-select label="Regional" name="kode_regional" :data="$regional" key="kode_regional" textShow="nama_regional"
      selected="{{ $cabang->kode_regional }}" />
   <x-input-with-icon icon="ti ti-access-point" label="Urutan" name="urutan" value="{{ $cabang->urutan }}" />
   <div class="form-group mb-3">
      <input class="form-control" type="color" name="color_marker" value="{{ $cabang->color_marker }}"
         id="html5-color-input">
   </div>
   <div class="form-group">
      <button class="btn btn-primary w-100" type="submit">
         <ion-icon name="send-outline" class="me-1"></ion-icon>
         Update
      </button>
   </div>
</form>

<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/cabang/edit.js') }}"></script>
