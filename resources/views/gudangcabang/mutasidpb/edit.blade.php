<form action="#" method="POST" id="formupdateMutasiDPB" autocomplete="off" aria-autocomplete="none">
   @csrf
   <div class="row mb-2">
      <div class="col">
         <input type="hidden" name="no_mutasi" id="no_mutasi" value="{{ Crypt::encrypt($mutasi->no_mutasi) }}">
         <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date"
            value="{{ $mutasi->tanggal }}" />
         <x-select label="Jenis Mutasi" name="jenis_mutasi" :data="$jenis_mutasi" key="kode_jenis_mutasi"
            textShow="jenis_mutasi" upperCase="true" select2="select2Jenismutasi"
            selected="{{ $mutasi->jenis_mutasi }}" />
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
                  @php
                     $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                     $jumlah_dus = $jumlah[0];
                     $jumlah_pack = $jumlah[1];
                     $jumlah_pcs = $jumlah[2];
                  @endphp
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
                        <input type="text" class="noborder-form text-end jml_dus money" name="jml_dus[]"
                           value="{{ formatAngka($jumlah_dus) }}">
                     </td>
                     <td style="background-color:{{ $color }}">
                        <input type="text" class="noborder-form text-end jml_pack money" style="background-color:{{ $color }}"
                           name="jml_pack[]"
                           {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }}
                           value="{{ formatAngka($jumlah_pack) }}">
                     </td>
                     <td>
                        <input type="text" class="noborder-form text-end jml_pcs money" name="jml_pcs[]"
                           value="{{ formatAngka($jumlah_pcs) }}">
                     </td>
                  </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
   <div class="row">
      <div class="col">
         <button type="submit" class="btn btn-primary w-100" id="submitMutasiDpb"><i
               class="ti ti-send me-1"></i>Submit</button>
      </div>
   </div>
</form>
<script>
   $(function() {
      $(".money").maskMoney();
      $(".flatpickr-date").flatpickr({
         enable: [{
            from: "{{ $start_periode }}",
            to: "{{ $end_periode }}"
         }, ]
      });

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
   });
</script>
