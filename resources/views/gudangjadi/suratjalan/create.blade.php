<form action="{{ route('suratjalan.store', Crypt::encrypt($pk->no_permintaan)) }}" method="POST"
   id="formcreateSuratjalan">
   <div class="row">
      <div class="col">
         <table class="table">
            <tr>
               <th>No. Permintaan</th>
               <td>{{ $pk->no_permintaan }}</td>
            </tr>
            <tr>
               <th>Tanggal</th>
               <td>{{ DateToIndo($pk->tanggal) }}</td>
            </tr>
            <tr>
               <th>Cabang</th>
               <td>{{ textUpperCase($pk->nama_cabang) }}</td>
            </tr>
            @if (!empty($pk->kode_salesman))
               <tr>
                  <th>Salesman</th>
                  <td>{{ $pk->nama_salesman }}</td>
               </tr>
            @endif
            <tr>
               <th>Keterangan</th>
               <td>{{ $pk->keterangan }}</td>
            </tr>
         </table>

      </div>
   </div>
   <div class="row mt-2">
      <div class="col">

         @csrf
         <input type="hidden" id="cektutuplaporan">
         <x-input-with-icon icon="ti ti-barcode" label="Auto" name="no_mutasi" readonly="true" />
         <x-input-with-icon icon="ti ti-barcode" label="No. Dokumen" name="no_dok" />
         <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" />
         <div class="row">
            <div class="col-lg-6 col-sm-12 col-md-12">
               <div class="form-group mb-3">
                  <select name="kode_tujuan" id="kode_tujuan" class="form-select Select2Kodetujuan">
                     <option value="">Pilih Tujuan</option>
                     @foreach ($tujuan_angkutan as $d)
                        <option value="{{ $d->kode_tujuan }}" data-tarif="{{ formatAngka($d->tarif) }}">
                           {{ textUpperCase($d->tujuan) }}</option>
                     @endforeach
                  </select>
               </div>

            </div>
            <div class="col-lg-6 col-sm-12 col-md-12">
               <x-select label="Angkutan" name="kode_angkutan" :data="$angkutan" key="kode_angkutan"
                  textShow="nama_angkutan" select2="select2Kodeangkutan" upperCase="true" />
            </div>
         </div>
         <x-input-with-icon icon="ti ti-barcode" label="No. Polisi" name="no_polisi" />
         <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12">
               <x-input-with-icon icon="ti ti-file" label="Tarif" name="tarif" money="true" align="right" />
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12">
               <x-input-with-icon icon="ti ti-file" label="Tepung" name="tepung" money="true" align="right" />
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12">
               <x-input-with-icon icon="ti ti-file" label="BS" name="bs" money="true" align="right" />
            </div>
         </div>
         <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />
      </div>
   </div>
   <div class="divider text-start">
      <div class="divider-text">Detail Produk</div>
   </div>
   <div class="row">
      <div class="col-lg-7 col-md-12 col-sm-12">
         <x-select label="Pilih Produk" name="kode_produk" :data="$produk" key="kode_produk" textShow="nama_produk"
            upperCase="true" select2="select2Kodeproduk" />
      </div>
      <div class="col-lg-3 col-md-12 col-sm-12">
         <x-input-with-icon icon="ti ti-box" label="Jumlah" name="jumlah" align="right" money="true" />
      </div>
      <div class="col-lg-2 col-md-12 col-sm-12">
         <a href="#" class="btn btn-primary" id="tambahproduk"><i class="ti ti-plus"></i></a>
      </div>
   </div>
   <div class="row mt-2">
      <div class="col">
         <table class="table table-bordered table-hover table-striped" id="tabledetailProduk">
            <thead class="table-dark">
               <tr>
                  <th>Kode</th>
                  <th style="width:50%">Nama Produk</th>
                  <th>Jumlah</th>
                  <th>#</th>
               </tr>
            </thead>
            <tbody id="loaddetail">
               @foreach ($detail as $d)
                  <tr id={{ 'index_' . $d->kode_produk }}>
                     <td>
                        {{ $d->kode_produk }}
                        <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                     </td>
                     <td>{{ $d->nama_produk }}</td>
                     <td class="text-end">
                        <input type="text" name="jml[]" class="noborder-form text-end jml money"
                           value="{{ formatAngka($d->jumlah) }}">
                     </td>
                     <td>
                        <a href="#" kode_produk="{{ $d->kode_produk }}" class="delete"><i
                              class="ti ti-trash text-danger"></i></a>
                     </td>
                  </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
   <div class="row mt-2">
      <div class="col-12">
         <div class="form-check mt-3 mb-3">
            <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox"
               value="" id="defaultCheck3">
            <label class="form-check-label" for="defaultCheck3"> Yakin Akan Disimpan ? </label>
         </div>
         <div class="form-group" id="saveButton">
            <button class="btn btn-primary w-100" type="submit" id="btnSimpan">
               <ion-icon name="send-outline" class="me-1"></ion-icon>
               Submit
            </button>
         </div>
      </div>
   </div>
</form>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script>
   $(function() {
      const formCreate = $("#formcreateSuratjalan");
      $(".flatpickr-date").flatpickr({
         enable: [{
            from: "{{ $start_periode }}",
            to: "{{ $end_periode }}"
         }, ]
      });

      $(".money").maskMoney();
      const Select2Kodetujuan = $('.Select2Kodetujuan');
      if (Select2Kodetujuan.length) {
         Select2Kodetujuan.each(function() {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
               placeholder: 'Pilih Tujuan',
               dropdownParent: $this.parent()
            });
         });
      }

      const select2Kodeangkutan = $('.select2Kodeangkutan');
      if (select2Kodeangkutan.length) {
         select2Kodeangkutan.each(function() {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
               placeholder: 'Pilih Angkutan',
               dropdownParent: $this.parent()
            });
         });
      }

      const select2Kodeproduk = formCreate.find('.select2Kodeproduk');
      if (select2Kodeproduk.length) {
         select2Kodeproduk.each(function() {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
               placeholder: 'Pilih Produk',
               allowClear: true,
               dropdownParent: $this.parent()
            });
         });
      }

      function cektutuplaporan(tanggal, jenis_laporan) {
         $.ajax({

            type: "POST",
            url: "/tutuplaporan/cektutuplaporan",
            data: {
               _token: "{{ csrf_token() }}",
               tanggal: tanggal,
               jenis_laporan: jenis_laporan
            },
            cache: false,
            success: function(respond) {
               $("#cektutuplaporan").val(respond);
            }
         });
      }

      function addProduk() {
         const dataProduk = formCreate.find("#kode_produk :selected").select2(this.data);
         const kode_produk = $(dataProduk).val();
         const nama_produk = $(dataProduk).text();
         const jumlah = formCreate.find("#jumlah").val();

         let produk = `
                    <tr id="index_${kode_produk}">
                        <td>
                            <input type="hidden" name="kode_produk[]" value="${kode_produk}"/>
                            ${kode_produk}
                        </td>
                        <td>${nama_produk}</td>
                        <td>
                            <input type="text" name="jml[]" value="${jumlah}" class="noborder-form text-end jml money" />
                        </td>
                        <td class="text-center">
                            <a href="#" kode_produk="${kode_produk}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                        </td>
                    </tr>
                `;

         //append to table
         $('#loaddetail').prepend(produk);
         $(".money").maskMoney();
         $('.select2Kodeproduk').val('').trigger("change");
         $("#jumlah").val("");
         $("#kode_produk").focus();
      }

      $("#tanggal").change(function(e) {
         cektutuplaporan($(this).val(), "gudangjadi");
      });

      $("#kode_tujuan").change(function(e) {
         var tarif = $('option:selected', this).attr('data-tarif');
         $("#tarif").val(tarif);
      });
      formCreate.on('click', '.delete', function(e) {
         e.preventDefault();
         var kode_produk = $(this).attr("kode_produk");
         event.preventDefault();
         Swal.fire({
            title: `Apakah Anda Yakin Ingin Menghapus Data Ini ?`,
            text: "Jika dihapus maka data akan hilang permanent.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            showCancelButton: true,
            confirmButtonColor: "#554bbb",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Hapus Saja!"
         }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
               $(`#index_${kode_produk}`).remove();
            }
         });
      });
      formCreate.find("#saveButton").hide();

      formCreate.find('.agreement').change(function() {
         if (this.checked) {
            formCreate.find("#saveButton").show();
         } else {
            formCreate.find("#saveButton").hide();
         }
      });

      formCreate.find("#tambahproduk").click(function(e) {
         e.preventDefault();
         const kode_produk = formCreate.find("#kode_produk").val();
         const jumlah = formCreate.find("#jumlah").val();
         if (kode_produk == "") {
            Swal.fire({
               title: "Oops!",
               text: "Silahkan Pilih dulu Kode Produk !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  formCreate.find("#kode_produk").focus();
               },

            });

         } else if (jumlah == "" || jumlah === "0") {
            Swal.fire({
               title: "Oops!",
               text: "Jumlah Tidak Boleh Kosong!",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  formCreate.find("#jumlah").focus();
               },

            });

         } else {
            formCreate.find("#tambahproduk").prop('disabled', true);
            if (formCreate.find('#tabledetailProduk').find('#index_' + kode_produk).length > 0) {
               Swal.fire({
                  title: "Oops!",
                  text: "Data Sudah Ada!",
                  icon: "warning",
                  showConfirmButton: true,
                  didClose: (e) => {
                     formCreate.find("#kode_produk").focus();
                  },

               });
            } else {
               addProduk();
            }
         }
      });

      formCreate.submit(function() {
         //return false;
         const tanggal = formCreate.find("#tanggal").val();
         const no_dok = formCreate.find("#no_dok").val();
         const kode_tujuan = formCreate.find("#kode_tujuan").val();
         const kode_angkutan = formCreate.find("#kode_angkutan").val();
         const no_polisi = formCreate.find("#no_polisi").val();
         const tarif = formCreate.find("#tarif").val();
         const tepung = formCreate.find("#tepung").val();
         const bs = formCreate.find("#bs").val();
         const keterangan = formCreate.find("#keterangan").val();
         const cektutuplaporan = formCreate.find("#cektutuplaporan").val();
         if (formCreate.find('#loaddetail tr').length == 0) {
            Swal.fire({
               title: "Oops!",
               text: "Data Produk Masih Kosong !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  formCreate.find("#kode_produk").focus();
               },
            });

            return false;
         } else if (tanggal == "") {
            Swal.fire({
               title: "Oops!",
               text: "Tanggal Harus Diisi !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  formCreate.find("#tanggal").focus();
               },
            });

            return false;
         } else if (no_dok == "") {
            Swal.fire({
               title: "Oops!",
               text: "No. Dokumen Harus Diisi !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  formCreate.find("#no_dok").focus();
               },
            });

            return false;
         } else if (kode_tujuan == "") {
            Swal.fire({
               title: "Oops!",
               text: "Tujuan  Harus Diisi !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  formCreate.find("#kode_tujuan").focus();
               },
            });

            return false;
         } else if (kode_angkutan != "" && no_polisi == "") {
            Swal.fire({
               title: "Oops!",
               text: "No. Polisi  Harus Diisi !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  formCreate.find("#no_polisi").focus();
               },
            });

            return false;
         } else if (kode_angkutan != "" && tarif == "") {
            Swal.fire({
               title: "Oops!",
               text: "Tarif  Harus Diisi !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  formCreate.find("#tarif").focus();
               },
            });

            return false;
         } else if (cektutuplaporan === '1') {
            Swal.fire("Oops!", "Laporan Untuk Periode Ini Sudah Ditutup", "warning");
            return false;
         }


      });

   });
</script>
