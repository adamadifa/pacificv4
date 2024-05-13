@extends('layouts.app')
@section('titlepage', 'Buat Saldo Awal Gudang Cabang')

@section('content')
@section('navigasi')
   <span class="text-muted fw-light">Saldo Awal Gudang Cabang /</span> Buat Saldo Awal
@endsection

<div class="row">
   <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
      <div class="card">
         <div class="card-body">
            <form action="{{ route('sagudangcabang.store') }}" method="POST" id="formCreatesaldoawal" aria-autocomplete="off">
               @csrf
               <div class="row">
                  <div class="col-12">
                     @hasanyrole($roles_show_cabang)
                        <div class="row">
                           <div class="col-lg-12 col-md-12 col-sm-12">
                              <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang"
                                 key="kode_cabang" textShow="nama_cabang" upperCase="true"
                                 select2="select2Kodecabang" />
                           </div>
                        </div>
                     @endrole
                     <div class="row">
                        <div class="form-group mb-3">
                           <select name="bulan" id="bulan" class="form-select">
                              <option value="">Bulan</option>
                              @foreach ($list_bulan as $d)
                                 <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                              @endforeach
                           </select>
                        </div>
                        <div class="form-group mb-3">
                           <select name="tahun" id="tahun" class="form-select">
                              <option value="">Tahun</option>
                              @for ($t = $start_year; $t <= date('Y'); $t++)
                                 <option value="{{ $t }}">{{ $t }}</option>
                              @endfor
                           </select>
                        </div>
                        <div class="form-group mb-3">
                           <select name="kondisi" id="kondisi" class="form-select">
                              <option value="">GOOD STOK / BAD STOK</option>
                              <option value="GS">GOOD STOK</option>
                              <option value="BS">BAD STOK</option>
                           </select>
                        </div>
                        <div class="form-group mb-3">
                           <a href="#" class="btn btn-success w-100" id="getsaldo">
                              <i class="ti  ti-badges me-1"></i> Get Saldo
                           </a>
                        </div>
                     </div>


                  </div>
               </div>
               <div class="row">
                  <div class="col-12">
                     <div class="table-responsive mb-2">
                        <table class="table table-bordered">
                           <thead class="table-dark">
                              <tr>
                                 <th rowspan="2">Kode</th>
                                 <th rowspan="2">Nama Barang</th>
                                 <th colspan="3">Kuantitas</th>
                              </tr>
                              <tr>
                                 <th>Dus</th>
                                 <th>Pack</th>
                                 <th>Pcs</th>
                              </tr>
                           </thead>
                           <tbody id="loaddetailsaldo">
                           </tbody>
                        </table>
                     </div>
                     <div class="form-group">
                        <button class="btn btn-primary w-100" type="submit">
                           <ion-icon name="send-outline" class="me-1"></ion-icon>
                           Submit
                        </button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

@endsection
@push('myscript')
<script>
   $(function() {

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
      //Mendapatkan Data Detail Saldo
      function loaddetailsaldo() {
         var bulan = $("#bulan").val();
         var tahun = $("#tahun").val();
         var kondisi = $("#kondisi").val();
         var kode_cabang = $("#kode_cabang").val();
         if (kode_cabang == "") {
            Swal.fire({
               title: "Oops!",
               text: "Silahkan Pilih dulu Cabang !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  $("#kode_cabang").focus();
               },
            });
            return false;
         } else if (bulan == "") {
            Swal.fire({
               title: "Oops!",
               text: "Silahkan Pilih dulu Bulan !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  $("#bulan").focus();
               },
            });
            return false;
         } else if (tahun == "") {
            Swal.fire({
               title: "Oops!",
               text: "Silahkan Pilih dulu Tahun !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  $("#tahun").focus();
               },
            });
            return false;
         } else if (kondisi == "") {
            Swal.fire({
               title: "Oops!",
               text: "Silahkan Pilih Dulu Good Stok / Bad Stok  !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  $("#kondisi").focus();
               },
            });
            return false;
         } else {
            $.ajax({
               type: "POST",
               url: "{{ route('sagudangcabang.getdetailsaldo') }}",
               data: {
                  _token: "{{ csrf_token() }}",
                  bulan: bulan,
                  tahun: tahun,
                  kondisi: kondisi,
                  kode_cabang: kode_cabang
               },
               cache: false,
               success: function(respond) {
                  if (respond === '1') {
                     Swal.fire({
                        title: "Oops!",
                        text: "Saldo Bulan Sebelumnya Belum di input !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                           $("#bulan").focus();
                        },
                     });
                     $("#loaddetailsaldo").html("");
                  } else {
                     $("#loaddetailsaldo").html(respond);
                  }
               }
            });
         }
      }

      $("#getsaldo").click(function(e) {
         e.preventDefault();
         loaddetailsaldo();
      });

      $("#formCreatesaldoawal").submit(function(e) {
         const form = $("#formCreatesaldoawal");
         if (form.find('#loaddetailsaldo tr').length == 0) {
            Swal.fire({
               title: "Oops!",
               text: "Silakan Get Saldo Terlebih Dahulu !",
               icon: "warning",
               showConfirmButton: true,
               didClose: (e) => {
                  formCreate.find("#kode_barang").focus();
               },
            });

            return false;
         }
      });
   });
</script>
@endpush
