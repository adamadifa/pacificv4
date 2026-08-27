<form method="POST" action="{{ route('laporangudanglogistik.cetakbpb') }}" id="frmLaporanbpb" target="_blank">
   @csrf
   <div class="row">
      <div class="col-12">
         <div class="form-group mb-3">
            <select name="kode_dept" id="kode_dept" class="form-select">
               <option value="">Semua Departemen</option>
               @foreach ($departemen as $d)
                  <option value="{{ $d->kode_dept }}">
                     {{ $d->nama_dept }}</option>
               @endforeach
            </select>
         </div>
      </div>
   </div>
   
   <div class="row">
      <div class="col-12">
         <div class="form-group mb-3">
            <select name="status" id="status" class="form-select">
               <option value="">Semua Status</option>
               <option value="proses">Proses (Belum Selesai)</option>
               <option value="selesai">Selesai</option>
            </select>
         </div>
      </div>
   </div>

   <div class="row">
      <div class="col-lg-6 col-md-12 col-sm-12">
         <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" hideLabel="true" />
      </div>
      <div class="col-lg-6 col-md-12 col-sm-12">
         <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" hideLabel="true" />
      </div>
   </div>

   <div class="row mt-2">
      <div class="col-lg-10 col-md-12 col-sm-12">
         <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButton">
            <i class="ti ti-printer me-1"></i> Cetak
         </button>
      </div>
      <div class="col-lg-2 col-md-12 col-sm-12">
         <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButton">
            <i class="ti ti-download"></i>
         </button>
      </div>
   </div>
</form>
