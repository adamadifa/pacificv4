@extends('layouts.app')
@section('titlepage', 'Register New Outlet')

@section('content')
@section('navigasi')
    <span>Register New Outlet</span>
@endsection
<div class="row">
    <form action="{{ route('sfa.storepelanggan') }}" aria-autocomplete="false" id="formcreatePelanggan" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <x-input-with-icon icon="ti ti-barcode" label="Auto" disabled="true" name="kode_pelanggan" />
                <x-input-with-icon icon="ti ti-credit-card" label="NIK" name="nik" />
                <x-input-with-icon icon="ti ti-file-text" label="No. KK" name="no_kk" />
                <x-input-with-icon icon="ti ti-user" label="Nama Pelanggan" name="nama_pelanggan" />
                <x-input-with-icon icon="ti ti-calendar" label="Tanggal Lahir" name="tanggal_lahir" datepicker="flatpickr-date" />
                <x-textarea label="Alamat Pelanggan" name="alamat_pelanggan" />
                <x-textarea label="Alamat Toko" name="alamat_toko" />
                <div class="row">
                    <div class="col-10">
                        <x-input-with-icon icon="ti ti-phone" label="No. HP" name="no_hp_pelanggan" />
                    </div>
                    <div class="col-2">
                        <div class="form-check">
                            <input class="form-check-input na_nohp" type="checkbox" value="1" id="na_nohp">
                            <label class="form-check-label" for="defaultCheck3"> NA </label>
                        </div>
                    </div>
                </div>



                <div class="form-group mb-3">
                    <select name="kode_wilayah" id="kode_wilayah" class="select2Kodewilayah form-select">
                    </select>
                </div>
                <div class="form-group mb-3">
                    <select name="hari" id="hari" class="form-select">
                        <option value="">Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <select name="ljt" id="ljt" class="form-select">
                        <option value="">LJT</option>
                        <option value="14">14</option>
                        <option value="30">30</option>
                        <option value="45">45</option>
                    </select>
                </div>
                {{-- <div class="form-group mb-3">
                    <select name="status_aktif_pelanggan" id="status_aktif_pelanggan" class="form-select">
                        <option value="">Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div> --}}
            </div>


            <div class="col-lg-5 col-md-12 col-sm-12">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <span class="alert-icon text-warning me-2">
                        <i class="ti ti-bell ti-xs"></i>
                    </span>
                    Bisa Diisi Saat Melakukan Ajuan Limit Kredit !
                </div>
                <div class="form-group mb-3">
                    <select name="kepemilikan" id="kepemilikan" class="form-select">
                        <option value="">Kepemilikan</option>
                        <option value="SW">Sewa</option>
                        <option value="MS">Milik Sendiri</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <select name="lama_berjualan" id="lama_berjualan" class="form-select">
                        <option value="">Lama Usaha</option>
                        <option value="LU01">
                            < 2 Tahun</option>
                        <option value="LU02">2 - 5 Tahun</option>
                        <option value="LU03">> 5 Tahun</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <select name="status_outlet" id="status_outlet" class="form-select">
                        <option value="">Status Outlet</option>
                        <option value="NO">New Outlet</option>
                        <option value="EO">Existing Outlet</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <x-select name="kode_klasifikasi" label="Klasifikasi Outlet" :data="$klasifikasi_outlet" key="kode_klasifikasi" textShow="klasifikasi"
                        upperCase="true" />
                </div>
                <div class="form-group mb-3">
                    <select name="type_outlet" id="type_outlet" class="form-select">
                        <option value="">Type Outlet</option>
                        <option value="GR">Grosir</option>
                        <option value="RT">Retail</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <select name="cara_pembayaran" id="cara_pembayaran" class="form-select">
                        <option value="">Cara Pembayaran</option>
                        <option value="BT">Bank Transfer</option>
                        <option value="AC">Advance Cash</option>
                        <option value="CQ">Cheque</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <select name="lama_langganan" id="lama_langganan" class="form-select">
                        <option value="">Lama Langganan</option>
                        <option value="LL01">
                            < 2 Tahun</option>
                        <option value="LL02">> 2 Tahun</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <select name="jaminan" id="jaminan" class="form-select">
                        <option value="">Jaminan</option>
                        <option value="1">Ada</option>
                        <option value="0">Tidak Ada</option>
                    </select>
                </div>
                <x-input-with-icon icon="ti ti-map-pin" label="Titik Koordinat" name="lokasi" />
                <x-input-with-icon icon="ti ti-moneybag" label="Omset Toko" name="omset_toko" align="right" money="true" />
                {{-- <x-input-file name="foto" label="Foto" /> --}}
                <div class="form-group">
                    <button class="btn btn-primary w-100" type="submit" id="btnSimpan">
                        <ion-icon name="send-outline" class="me-1"></ion-icon>
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
@push('myscript')
<script src="{{ asset('assets/js/pages/pelanggan/create.js') }}"></script>
<script>
    $(function() {
        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        const select2Kodewilayah = $('.select2Kodewilayah');
        if (select2Kodewilayah.length) {
            select2Kodewilayah.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Wilayah',
                    dropdownParent: $this.parent()
                });
            });
        }

        function getwilayahbyCabang() {

            var kode_cabang = $("#formcreatePelanggan").find("#kode_cabang").val();
            $.ajax({
                type: 'POST',
                url: '/wilayah/getwilayahbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    $("#formcreatePelanggan").find("#kode_wilayah").html(respond);
                }
            });
        }

        getwilayahbyCabang();

        $('.na_nohp').change(function() {
            if (this.checked) {
                $("#no_hp_pelanggan").val("NA");
                $("#no_hp_pelanggan").attr("readonly", true);
            } else {
                $("#no_hp_pelanggan").val("");
                $("#no_hp_pelanggan").attr("readonly", false);
            }

        });





    });
</script>
@endpush
