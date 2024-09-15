@extends('layouts.app')
@section('titlepage', 'Edit Outlet')

@section('content')
@section('navigasi')
    <span>Edit Outlet</span>
@endsection
<div class="row">
    <form action="{{ route('sfa.updatepelanggan', Crypt::encrypt($pelanggan->kode_pelanggan)) }}" aria-autocomplete="false" id="formeditPelanggan"
        method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <x-input-with-icon icon="ti ti-barcode" label="Auto" value="{{ $pelanggan->kode_pelanggan }}" disabled="true" name="kode_pelanggan" />
                <x-input-with-icon icon="ti ti-credit-card" label="NIK" name="nik" value="{{ $pelanggan->nik }}" />
                <x-input-with-icon icon="ti ti-file-text" label="No. KK" name="no_kk" value="{{ $pelanggan->no_kk }}" />
                <x-input-with-icon icon="ti ti-user" label="Nama Pelanggan" name="nama_pelanggan" value="{{ $pelanggan->nama_pelanggan }}" />
                <x-input-with-icon icon="ti ti-calendar" label="Tanggal Lahir" name="tanggal_lahir" datepicker="flatpickr-date"
                    value="{{ $pelanggan->tanggal_lahir }}" />
                <x-textarea label="Alamat Pelanggan" name="alamat_pelanggan" value="{{ $pelanggan->alamat_pelanggan }}" />
                <x-textarea label="Alamat Toko" name="alamat_toko" value="{{ $pelanggan->alamat_toko }}" />
                <div class="row">
                    <div class="col-10">
                        <x-input-with-icon icon="ti ti-phone" label="No. HP" name="no_hp_pelanggan" value="{{ $pelanggan->no_hp_pelanggan }}" />
                    </div>
                    <div class="col-2">
                        <div class="form-check">
                            <input class="form-check-input na_nohp" type="checkbox" value="1" id="na_nohp"
                                {{ $pelanggan->no_hp_pelanggan == 'NA' ? 'checked' : '' }}>
                            <label class="form-check-label" for="defaultCheck3"> NA </label>
                        </div>
                    </div>
                </div>
                @hasanyrole($roles_show_cabang)
                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                        selected="{{ $pelanggan->kode_cabang }}" />
                @endhasanyrole

                {{-- <div class="form-group mb-3">
                    <select name="kode_salesman" id="kode_salesman" class="select2Kodesalesman form-select">
                    </select>
                </div> --}}
                <div class="form-group mb-3">
                    <select name="kode_wilayah" id="kode_wilayah" class="select2Kodewilayah form-select">
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label>Hari</label><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="harisenin"> Senin </label>
                        <input class="form-check-input harisenin" name="hari[]" value="Senin" type="checkbox" id="harisenin"
                            {{ str_contains(strtolower($pelanggan->hari), 'senin') ? 'checked' : '' }}>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="hariselasa"> Selasa </label>
                        <input class="form-check-input hariselasa" name="hari[]" value="Selasa" type="checkbox" id="hariselasa"
                            {{ str_contains(strtolower($pelanggan->hari), 'selasa') ? 'checked' : '' }}>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="harirabu"> Rabu </label>
                        <input class="form-check-input harirabu" name="hari[]" value="Rabu" type="checkbox" id="harirabu"
                            {{ str_contains(strtolower($pelanggan->hari), 'rabu') ? 'checked' : '' }}>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="harikamis"> Kamis </label>
                        <input class="form-check-input harikamis" name="hari[]" value="Kamis" type="checkbox" id="harikamis"
                            {{ str_contains(strtolower($pelanggan->hari), 'kamis') ? 'checked' : '' }}>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="harijumat"> Jumat </label>
                        <input class="form-check-input harijumat" name="hari[]" value="Jumat" type="checkbox" id="harijumat"
                            {{ str_contains(strtolower($pelanggan->hari), 'jumat') ? 'checked' : '' }}>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="harisabtu"> Sabtu </label>
                        <input class="form-check-input harisabtu" name="hari[]" value="Sabtu" type="checkbox" id="harisabtu"
                            {{ str_contains(strtolower($pelanggan->hari), 'sabtu') ? 'checked' : '' }}>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="hariminggu"> Minggu </label>
                        <input class="form-check-input hariminggu" name="hari[]" value="Minggu" type="checkbox" id="hariminggu"
                            {{ str_contains(strtolower($pelanggan->hari), 'minggu') ? 'checked' : '' }}>
                    </div>
                </div>
                @hasanyrole($roles_show_cabang)
                    <x-input-with-icon icon="ti ti-moneybag" label="Limit Pelanggan" name="limit_pelanggan" align="right" money="true"
                        value="{{ formatRupiah($pelanggan->limit_pelanggan) }}" />
                @endhasanyrole
                <div class="form-group mb-3">
                    <select name="ljt" id="ljt" class="form-select">
                        <option value="">LJT</option>
                        <option value="14" {{ $pelanggan->ljt == 14 ? 'selected' : '' }}>14</option>
                        <option value="30" {{ $pelanggan->ljt == 30 ? 'selected' : '' }}>30</option>
                        <option value="45" {{ $pelanggan->ljt == 45 ? 'selected' : '' }}>45</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <select name="status_aktif_pelanggan" id="status_aktif_pelanggan" class="form-select">
                        <option value="">Status</option>
                        <option value="1" {{ $pelanggan->status_aktif_pelanggan === '1' ? 'selected' : '' }}>Aktif
                        </option>
                        <option value="0" {{ $pelanggan->status_aktif_pelanggan === '0' ? 'selected' : '' }}>Nonaktif
                        </option>
                    </select>
                </div>
            </div>

            <div class="col-lg-1 col-md-12 col-sm-12">
                <div class="divider divider-vertical">
                    <div class="divider-text">
                        <i class="ti ti-crown"></i>
                    </div>
                </div>
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
                        <option value="SW" {{ $pelanggan->kepemilikan == 'SW' ? 'selected' : '' }}>Sewa
                        </option>
                        <option value="MS" {{ $pelanggan->kepemilikan == 'MS' ? 'selected' : '' }}>Milik Sendiri
                        </option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <select name="lama_berjualan" id="lama_berjualan" class="form-select">
                        <option value="">Lama Usaha</option>
                        <option value="LU01" {{ $pelanggan->lama_berjualan == 'LU01' ? 'selected' : '' }}>
                            < 2 Tahun</option>
                        <option value="LU02" {{ $pelanggan->lama_berjualan == 'LU02' ? 'selected' : '' }}>2 - 5 Tahun
                        </option>
                        <option value="LU03" {{ $pelanggan->lama_berjualan == 'LU03' ? 'selected' : '' }}>> 5 Tahun
                        </option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <select name="status_outlet" id="status_outlet" class="form-select">
                        <option value="">Status Outlet</option>
                        <option value="NO" {{ $pelanggan->status_outlet == 'NO' ? 'selected' : '' }}>New Outlet
                        </option>
                        <option value="EO" {{ $pelanggan->status_outlet == 'EO' ? 'selected' : '' }}>Existing Outlet
                        </option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <select name="type_outlet" id="type_outlet" class="form-select">
                        <option value="">Type Outlet</option>
                        <option value="GR" {{ $pelanggan->type_outlet == 'GR' ? 'selected' : '' }}>Grosir</option>
                        <option value="RT" {{ $pelanggan->type_outlet == 'RT' ? 'selected' : '' }}>Retail</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <x-select name="kode_klasifikasi" label="Klasifikasi Outlet" :data="$klasifikasi_outlet" key="kode_klasifikasi" textShow="klasifikasi"
                        upperCase="true" selected="{{ $pelanggan->kode_klasifikasi }}" />
                </div>
                <div class="form-group mb-3">
                    <select name="cara_pembayaran" id="cara_pembayaran" class="form-select">
                        <option value="">Cara Pembayaran</option>
                        <option value="BT" {{ $pelanggan->cara_pembayaran == 'BT' ? 'selected' : '' }}>Bank Transfer
                        </option>
                        <option value="AC" {{ $pelanggan->cara_pembayaran == 'AC' ? 'selected' : '' }}>Advance Cash
                        </option>
                        <option value="CQ" {{ $pelanggan->cara_pembayaran == 'CQ' ? 'selected' : '' }}>Cheque</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <select name="lama_langganan" id="lama_langganan" class="form-select">
                        <option value="">Lama Langganan</option>
                        <option value="LL01" {{ $pelanggan->lama_langganan == 'LL01' ? 'selected' : '' }}>
                            < 2 Tahun</option>
                        <option value="LL02" {{ $pelanggan->lama_langganan == 'LL02' ? 'selected' : '' }}>> 2 Tahun
                        </option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <select name="jaminan" id="jaminan" class="form-select">
                        <option value="">Jaminan</option>
                        <option value="1" {{ $pelanggan->jaminan === '1' ? 'selected' : '' }}>Ada</option>
                        <option value="0" {{ $pelanggan->jaminan === '0' ? 'selected' : '' }}>Tidak Ada</option>
                    </select>
                </div>
                <x-input-with-icon icon="ti ti-map-pin" label="Titik Koordinat" name="lokasi"
                    value="{{ $pelanggan->latitude }},{{ $pelanggan->longitude }}" />
                <x-input-with-icon icon="ti ti-moneybag" label="Omset Toko" name="omset_toko" align="right" money="true"
                    value="{{ formatRupiah($pelanggan->omset_toko) }}" />
                <x-input-file name="foto" label="Foto" />
                @if (!empty($pelanggan->foto))
                    @if (Storage::disk('public')->exists('/pelanggan/' . $pelanggan->foto))
                        <img src="{{ getfotoPelanggan($pelanggan->foto) }}" alt="user image"
                            class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" width="150">
                    @else
                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="user image"
                            class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" width="150">
                    @endif
                @else
                    <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="user image"
                        class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" width="150">
                @endif


                {{-- @if (!empty($pelanggan->foto))
                @endif --}}
                <div class="form-group mt-3">
                    <button class="btn btn-primary w-100" type="submit">
                        <ion-icon name="send-outline" class="me-1"></ion-icon>
                        Update
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

@endsection
@push('myscript')
<script src="{{ asset('assets/js/pages/pelanggan/edit.js') }}"></script>
<script>
    $(function() {

        $('input[type="checkbox"][name="hari[]"]').on('change', function() {
            var checkedCount = $('input[type="checkbox"][name="hari[]"]:checked').length;

            if (checkedCount > 2) {
                $(this).prop('checked', false); // Batalkan centang terakhir
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Hanya boleh memilih 2 hari',
                })
            } else {
                $('#error-message').text(''); // Hapus pesan error jika valid
            }
        });


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
                    kode_wilayah: "{{ $pelanggan->kode_wilayah }}",
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    $("#kode_wilayah").html(respond);
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
