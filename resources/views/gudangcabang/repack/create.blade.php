<form action="{{ route('repackcbg.store') }}" method="POST" id="formRepack" autocomplete="off" aria-autocomplete="none">
   @csrf
   <div class="row mb-2">
      <div class="col">
         <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" />
         @hasanyrole($roles_show_cabang)
            <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
               upperCase="true" select2="select2Kodecabang" />
         @endrole
         <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />
      </div>
   </div>
   <div class="row mb-2">
      <div class="col">
         <table class="table table-bordered">
            <thead class="table-dark">
               <tr>
                  <th rowspan="2">Kode</th>
                  <th rowspan="2" style="width:40%">Produk</th>
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
                  @if (empty($d->isi_pcs_pack))
                     @php
                        $color = '#ebebebee';
                     @endphp
                  @else
                     @php
                        $color = '';
                     @endphp
                  @endif
                  <tr>
                     <td>
                        <input type="hidden" class="kode_produk" name="kode_produk[]"
                           value="{{ $d->kode_produk }}">
                        <input type="hidden" class="isi_pcs_dus" name="isi_pcs_dus[]"
                           value="{{ $d->isi_pcs_dus }}">
                        <input type="hidden" class="isi_pcs_pack" name="isi_pcs_pack[]"
                           value="{{ $d->isi_pcs_pack }}">

                        {{ $d->kode_produk }}
                     </td>
                     <td>{{ $d->nama_produk }}</td>
                     <td>
                        <input type="text" class="noborder-form text-end jml_dus money" name="jml_dus[]">
                     </td>
                     <td style="background-color: {{ $color }}">
                        <input type="text" class="noborder-form text-end jml_pack money" style="background-color: {{ $color }}"
                           name="jml_pack[]"
                           {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }}>
                     </td>
                     <td>
                        <input type="text" class="noborder-form text-end jml_pcs money" name="jml_pcs[]">
                     </td>
                  </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
   <div class="row">
      <div class="col">
         <button type="submit" class="btn btn-primary w-100" id="btnSubmit"><i
               class="ti ti-send me-1"></i>Submit</button>
      </div>
   </div>
</form>
<script>
   $(function() {
      const form = $("#formRepack");
      $(".money").maskMoney();
      $(".flatpickr-date").flatpickr({
         enable: [{
            from: "{{ $start_periode }}",
            to: "{{ $end_periode }}"
         }, ]
      });

      const select2Kodecabang = $('.select2Kodecabang');
      if (select2Kodecabang.length) {
         select2Kodecabang.each(function() {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
               placeholder: 'Pilih Cabang',
               allowClear: true,
               dropdownParent: $this.parent()
            });
         });
      }

      form.submit(function() {
         const tanggal = $(this).find("#tanggal").val();
         const kode_cabang = $(this).find("#kode_cabang").val();
         const keterangan = $(this).find("#keterangan").val();
         if (tanggal == "") {
            Swal.fire({
               title: "Oops!",
               text: "Tanggal Harus Diisi !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  $(this).find("#tanggal").focus();
               },
            });
            return false;
         } else if (kode_cabang == "") {
            Swal.fire({
               title: "Oops!",
               text: "Cabang Harus Diisi !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  $(this).find("#kode_cabang").focus();
               },

            });
            return false;
         } else {
            $(this).find("#btnSubmit").prop('disabled', true);
         }

      });
   });
</script>
