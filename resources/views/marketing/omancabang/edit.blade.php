<style>
   .form-oman {
      width: 100%;
      border: 0px;
   }

   .form-oman:focus {
      outline: none;
   }
</style>
<form action="{{ route('omancabang.update', Crypt::encrypt($oman_cabang->kode_oman)) }}" method="POST"
   id="frmeditOmancabang">
   @csrf
   <div class="row">
      <div class="co-12">
         <table class="table">
            <tr>
               <th>Kode OMAN</th>
               <td>{{ $oman_cabang->kode_oman }}</td>
            </tr>
            <tr>
               <th>Cabang</th>
               <td>{{ textUpperCase($oman_cabang->cabang->nama_cabang) }}</td>
            </tr>
            <tr>
               <th>Bulan</th>
               <td>{{ $namabulan[$oman_cabang->bulan] }}</td>
            </tr>
            <tr>
               <th>Tahun</th>
               <td>{{ $oman_cabang->tahun }}</td>
            </tr>
         </table>
      </div>
   </div>

   <div class="row">
      <div class="col-12">
         <table class="table table-hover table-bordered" id="tableomanCabang">
            <thead class="table-dark">
               <tr>

                  <th rowspan="3" class="align-middle text-center">Kode Produk</th>
                  <th rowspan="3" class="align-middle" style="width: 25%">Nama Produk</th>
                  <th colspan="4" class="text-center">Jumlah Permintaan</th>
                  <th rowspan="3" class="align-middle">Total</th>
               </tr>
               <tr>
                  <th class="text-center">Minggu ke 1</th>
                  <th class="text-center">Minggu ke 2</th>
                  <th class="text-center">Minggu ke 3</th>
                  <th class="text-center">Minggu ke 4</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($detail as $d)
                  <tr>

                     <td class="text-center">
                        <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                        {{ $d->kode_produk }}
                     </td>
                     <td>{{ $d->nama_produk }}</td>
                     <td>
                        <input type="text" id="jmlm1" name="jmlm1[]"
                           class="jmlm1 text-end form-oman number-separator" placeholder="0"
                           autocomplete="false" aria-autocomplete="list"
                           value="{{ formatAngka($d->minggu_1) }}">
                     </td>
                     <td>
                        <input type="text" id="jmlm2" name="jmlm2[]"
                           class="jmlm2 text-end form-oman number-separator" placeholder="0"
                           autocomplete="false" aria-autocomplete="list"
                           value="{{ formatAngka($d->minggu_2) }}" />
                     </td>
                     <td>
                        <input type="text" id="jmlm3" name="jmlm3[]"
                           class="jmlm3 text-end form-oman number-separator" placeholder="0"
                           autocomplete="false" aria-autocomplete="list"
                           value="{{ formatAngka($d->minggu_3) }}" />
                     </td>
                     <td>
                        <input type="text" id="jmlm4" name="jmlm4[]"
                           class="jmlm4 text-end form-oman number-separator" placeholder="0"
                           autocomplete="false" aria-autocomplete="list"
                           value="{{ formatAngka($d->minggu_4) }}" />
                     </td>
                     <td>
                        <input type="text" id="subtotal" name="subtotal[]"
                           class="subtotal text-end form-oman" placeholder="0"
                           value="{{ formatAngka($d->total) }}" readonly />
                     </td>
                  </tr>
               @endforeach
            </tbody>
         </table>

      </div>
   </div>
   <div class="row mt-2">
      <div class="col-12">
         <button class="btn btn-primary w-100" type="submit" name="submit"><i
               class="ti ti-send me-1"></i>Submit</button>
      </div>
   </div>
</form>
<script>
   $(function() {
      const select2Kodecabang = $('.select2Kodecabang');

      function initselect2Kodecabang() {
         if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
               var $this = $(this);
               $this.wrap('<div class="position-relative"></div>').select2({
                  placeholder: 'Pilih Cabang',
                  dropdownParent: $this.parent(),

               });
            });
         }
      }

      initselect2Kodecabang();

      function convertToRupiah(number) {
         if (number) {
            var rupiah = "";
            var numberrev = number
               .toString()
               .split("")
               .reverse()
               .join("");
            for (var i = 0; i < numberrev.length; i++)
               if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
            return (
               rupiah
               .split("", rupiah.length - 1)
               .reverse()
               .join("")
            );
         } else {
            return number;
         }
      }
      var $tblrows = $("#tableomanCabang tbody tr");
      $tblrows.each(function(index) {
         var $tblrow = $(this);
         $tblrow.find('.jmlm1,.jmlm2,.jmlm3,.jmlm4').on('input', function() {
            var jmlm1 = $tblrow.find("[id=jmlm1]").val();
            var jmlm2 = $tblrow.find("[id=jmlm2]").val();
            var jmlm3 = $tblrow.find("[id=jmlm3]").val();
            var jmlm4 = $tblrow.find("[id=jmlm4]").val();



            if (jmlm1.length === 0) {
               var jml1 = 0;
            } else {
               var jml1 = parseInt(jmlm1.replace(/\./g, ''));
            }
            if (jmlm2.length === 0) {
               var jml2 = 0;
            } else {
               var jml2 = parseInt(jmlm2.replace(/\./g, ''));
            }
            if (jmlm3.length === 0) {
               var jml3 = 0;
            } else {
               var jml3 = parseInt(jmlm3.replace(/\./g, ''));
            }

            if (jmlm4.length === 0) {
               var jml4 = 0;
            } else {
               var jml4 = parseInt(jmlm4.replace(/\./g, ''));
            }
            var subTotal = parseInt(jml1) + parseInt(jml2) + parseInt(jml3) + parseInt(
               jml4);

            if (!isNaN(subTotal)) {
               $tblrow.find('.subtotal').val(convertToRupiah(subTotal));
               // var grandTotal = 0;
               // $(".subtotal").each(function() {
               //     var stval = parseInt($(this).val());
               //     grandTotal += isNaN(stval) ? 0 : stval;
               // });
               //$('.grdtot').val(grandTotal.toFixed(2));
            }

         });
      });


      easyNumberSeparator({
         selector: '.number-separator',
         separator: '.',
         decimalSeparator: ',',
      });



   });
</script>
