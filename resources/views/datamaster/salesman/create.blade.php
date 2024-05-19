<form action="{{ route('salesman.store') }}" id="formcreateSalesman" method="POST" enctype="multipart/form-data">
   @csrf
   <x-input-with-icon icon="ti ti-barcode" label="Kode Salesman" name="kode_salesman" />
   <x-input-with-icon icon="ti ti-file-text" label="Nama Salesman" name="nama_salesman" />
   <x-input-with-icon icon="ti ti-map-pin" label="Alamat Salesman" name="alamat_salesman" />
   <x-input-with-icon icon="ti ti-phone" label="No. HP Salesman" name="no_hp_salesman" />
   <x-select label="Kategori Salesman" name="kode_kategori_salesman" :data="$kategori_salesman" key="kode_kategori_salesman"
      textShow="nama_kategori_salesman" />
   <div class="form-group mb-3">
      <select name="status_komisi_salesman" id="status_komisi_salesman" class="form-select">
         <option value="">Status Komisi</option>
         <option value="1">Ya</option>
         <option value="0">Tidak</option>
      </select>
   </div>
   <div class="form-group mb-3">
      <select name="status_aktif_salesman" id="status_aktif_salesman" class="form-select">
         <option value="">Status</option>
         <option value="1">Aktif</option>
         <option value="0">Non Aktif</option>
      </select>
   </div>
   @hasanyrole($roles_show_cabang)
      <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" />
   @endhasanyrole
   <x-input-file name="marker" label="Marker" />
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
<script src="{{ asset('assets/js/pages/salesman/create.js') }}"></script>
