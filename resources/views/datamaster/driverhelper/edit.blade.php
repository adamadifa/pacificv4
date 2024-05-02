<form action="{{ route('driverhelper.update', Crypt::encrypt($driverhelper->kode_driver_helper)) }}" id="formDriverhelper" method="POST">
   @csrf
   @method('PUT')
   <x-input-with-icon icon="ti ti-barcode" label="Auto" disabled="true" name="kode_driver_helper" value="{{ $driverhelper->kode_driver_helper }}" />
   <x-input-with-icon icon="ti ti-map-pin" label="Nama Driver Helper" name="nama_driver_helper" value="{{ $driverhelper->nama_driver_helper }}" />
   @hasanyrole($roles_show_cabang)
      <x-select label="Cabang" selected="{{ $driverhelper->kode_cabang }}" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true" select2="select2Kodecabang" />
   @endhasanyrole
   <div class="form-group">
      <button class="btn btn-primary w-100" type="submit">
         <ion-icon name="send-outline" class="me-1"></ion-icon>
         Submit
      </button>
   </div>
</form>


<script src="{{ asset('assets/js/pages/driverhelper.js') }}"></script>
<script>
   $(function() {
      const select2Kodecabang = $('.select2Kodecabang');
      if (select2Kodecabang.length) {
         select2Kodecabang.each(function() {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
               placeholder: 'Pilih Cabang',
               dropdownParent: $this.parent()
            });
         });
      }
   });
</script>
