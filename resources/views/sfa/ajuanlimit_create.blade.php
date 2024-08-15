@extends('layouts.app')
@section('titlepage', 'Ajuan Limit Pelanggan')

@section('content')
@section('navigasi')
    <span>Ajuan Limit Pelanggan</span>
@endsection
<div class="row">
    <form action="{{ route('sfa.storeajuanlimit', Crypt::encrypt($pelanggan->kode_pelanggan)) }}" aria-autocomplete="false" id="formAjuanlimit"
        method="POST" enctype="multipart/form-data">
        @csrf
        {{-- <input type="text" name="kode_pelanggan" id="kode_pelanggan" value="{{ $pelanggan->kode_pelanggan }}"> --}}
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
                    <select name="hari" id="hari" class="form-select">
                        <option value="">Hari</option>
                        <option value="Senin" {{ $pelanggan->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ $pelanggan->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ $pelanggan->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ $pelanggan->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ $pelanggan->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ $pelanggan->hari == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                        <option value="Minggu" {{ $pelanggan->hari == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                    </select>
                </div>
                <x-input-with-icon icon="ti ti-moneybag" label="Jumlah Ajuan Limit" name="jumlah" align="right" money="true" />
                <div class="form-group mb-3">
                    <select name="ljt" id="ljt" class="form-select">
                        <option value="">LJT</option>
                        <option value="14" {{ $pelanggan->ljt == 14 ? 'selected' : '' }}>14</option>
                        <option value="30" {{ $pelanggan->ljt == 30 ? 'selected' : '' }}>30</option>
                        <option value="45" {{ $pelanggan->ljt == 45 ? 'selected' : '' }}>45</option>
                    </select>
                </div>
                {{-- <div class="form-group mb-3">
                    <select name="status_aktif_pelanggan" id="status_aktif_pelanggan" class="form-select">
                        <option value="">Status</option>
                        <option value="1" {{ $pelanggan->status_aktif_pelanggan === '1' ? 'selected' : '' }}>Aktif
                        </option>
                        <option value="0" {{ $pelanggan->status_aktif_pelanggan === '0' ? 'selected' : '' }}>Nonaktif
                        </option>
                    </select>
                </div> --}}
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
                {{-- <x-input-with-icon icon="ti ti-map-pin" label="Titik Koordinat" name="lokasi"
                    value="{{ $pelanggan->latitude }},{{ $pelanggan->longitude }}" /> --}}

                <div class="form-group mb-3">
                    <select name="histori_transaksi" id="histori_transaksi" class="form-select">
                        <option value="">Histori Pembayaran 6 Bulan Terakhir</option>
                        <option value="HT01">
                            < 14 Hari</option>
                        <option value="HT02"> = 14 Hari</option>
                        <option value="HT03"> > 14 Hari</option>
                    </select>
                </div>
                <x-input-with-icon label="Terakhir Top UP" name="topup_terakhir" icon="ti ti-calendar" datepicker="flatpickr-date" />
                <input type="hidden" id="lama_topup" name="lama_topup">
                <span id="usia_topup" class="mb-3"></span>

                <x-input-with-icon icon="ti ti-moneybag" label="Omset Toko" name="omset_toko" align="right" money="true"
                    value="{{ formatRupiah($pelanggan->omset_toko) }}" />

                <div class="divider">
                    <div class="divider-text">Faktur Belum Lunas</div>
                </div>
                <div class="row mt-2 mb-2">
                    <div class="col">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>No. Faktur</th>
                                    <th>Sisa Piutang</th>
                                </tr>
                            </thead>
                            <tbody id="loadlistfakturkredit"></tbody>
                        </table>
                        <input type="hidden" id="jml_faktur" name="jml_faktur">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <input type="hidden" name="skor" id="skor">
                        <table class="table">
                            <tr>
                                <td>Skor</td>
                                <td id="total_score">

                                </td>
                            </tr>
                            <tr>
                                <td>Rekomendasi</td>
                                <td id="rekomendasi"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <x-textarea label="Uraian Analisa" name="uraian_analisa" />

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
<script src="{{ asset('assets/js/pages/ajuanlimit.js') }}"></script>
<script>
    $(function() {
        const kode_pelanggan = "{{ $pelanggan->kode_pelanggan }}";
        const form = $("#formAjuanlimit");

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


        function getlistFakturkredit(kode_pelanggan) {
            $.ajax({
                type: 'GET',
                url: `/pelanggan/${kode_pelanggan}/getlistfakturkredit`,
                cache: false,
                success: function(respond) {
                    $("#loadlistfakturkredit").html(respond);
                    const jmlfaktur = $('#loadlistfakturkredit').find('tr').length;
                    $("#jml_faktur").val(jmlfaktur);
                    loadSkor();
                }
            });

        }

        getlistFakturkredit(kode_pelanggan);

        form.find("#omset_toko").on('keyup keydown', function() {
            var jumlah = $("#jumlah").val();
            $(this).prop('readonly', true);
            if (jumlah == "" || jumlah == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Ajuan Kredit Harus Diisi Dulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });

            }
            loadSkor();
        });


        form.find("#jumlah").on('keyup keydown', function() {
            var jumlah = $("#jumlah").val();
            if (jumlah == "" || jumlah == 0) {
                $("#omset_toko").prop('readonly', true);
            } else {
                $("#omset_toko").prop('readonly', false);
            }
            loadSkor();
        });


        function gettopupTerakhir() {
            const tanggal = $("#topup_terakhir").val();
            $.ajax({
                type: 'POST',
                url: '/ajuanlimit/gettopupterakhir',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal
                },
                cache: false,
                success: function(response) {
                    $("#usia_topup").text(response.data.usia_topup);
                    $("#lama_topup").val(response.data.lama_topup);
                    loadSkor();
                }
            });
        }

        $("#topup_terakhir").change(function() {
            gettopupTerakhir();
        });

        $("#status_outlet,#type_outlet,#ljt,#cara_pembayaran,#kepemilikan,#lama_topup,#lama_langganan,#lama_berjualan,#jaminan,#histori_transaksi")
            .change(function() {
                loadSkor();
            });

        function loadSkor() {
            let status_outlet = $("#status_outlet").val();
            let type_outlet = $("#type_outlet").val();
            let ljt = $("#ljt").val();
            let cara_pembayaran = $("#cara_pembayaran").val();
            let kepemilikan = $("#kepemilikan").val();
            let lama_topup = $("#lama_topup").val();
            let lama_langganan = $("#lama_langganan").val();
            let lama_berjualan = $("#lama_berjualan").val();
            let jaminan = $("#jaminan").val();
            let histori_transaksi = $("#histori_transaksi").val();
            let omset_toko = $("#omset_toko").val();
            let jumlah = $("#jumlah").val();
            ///var jmlfaktur = $("#jml_faktur").val();
            let ot = omset_toko.replace(/\./g, '');
            let jm = jumlah.replace(/\./g, '');

            let ratioomset = Math.round(parseInt(ot) / parseInt(jm));

            let jmlfaktur = $('#loadlistfakturkredit').find('tr').length;

            let score_omset = 0;
            let score_faktur = 0;
            let score_outlet = 0;
            let score_jaminan = 0;
            let score_typeoutlet = 0;
            let score_top = 0;
            let score_carabayar = 0;
            let score_kepemilikan = 0;
            let score_ht = 0;
            let score_lamausaha = 0;
            let score_lamalangganan = 0;
            let score_lamatopup = 0;

            if (omset_toko != "" || omset_toko !== '0') {
                if (ratioomset < 3) {
                    score_omset = 0.35;
                } else if (ratioomset >= 3) {
                    score_omset = 0.50;
                }
            } else {
                score_omset = 0;
            }


            if (jmlfaktur == 1) {
                score_faktur = 0.50;
            } else if (jmlfaktur == 2) {
                score_faktur = 0.40;
            } else if (jmlfaktur == 3) {
                score_faktur = 0.30;
            } else if (jmlfaktur > 3) {
                score_faktur = 0.20;
            } else {
                score_faktur = 0;
            }

            console.log(score_faktur);
            if (status_outlet == "NO") {
                score_outlet = 1.05;
            } else if (status_outlet == "EO") {
                score_outlet = 1.50;
            } else {
                score_outlet = 0.00;
            }

            if (jaminan == 1) {
                score_jaminan = 1.00;
            } else if (jaminan == 2) {
                score_jaminan = 0.70;
            } else {
                score_jaminan = 0.00;
            }

            if (type_outlet == "GR") {
                score_typeoutlet = 0.50;
            } else if (type_outlet == "RT") {
                score_typeoutlet = 0.35;
            } else {
                score_typeoutlet = 0.00;
            }

            if (ljt == 14) {
                score_top = 1.00;
            } else if (ljt == 30) {
                score_top = 0.70;
            } else if (ljt == 45) {
                score_top = 0.40;
            } else {
                score_top = 0.00;
            }

            if (cara_pembayaran == 'BT') {
                score_carabayar = 0.50;
            } else if (cara_pembayaran == 'AC') {
                score_carabayar = 0.35;
            } else if (cara_pembayaran == 'CQ') {
                score_carabayar = 0.20;
            } else {
                score_carabayar = 0.00;
            }
            //alert(score_carabayar);
            if (kepemilikan == "MS") {
                score_kepemilikan = 1.00;
            } else if (kepemilikan == "SW") {
                score_kepemilikan = 0.70;
            } else {
                score_kepemilikan = 0.00;
            }

            if (histori_transaksi == "HT01") {
                score_ht = 1.00;
            } else if (histori_transaksi == "HT02") {
                score_ht = 0.70;
            } else if (histori_transaksi == "HT03") {
                score_ht = 0.40;
            } else {
                score_ht = 0.00;
            }

            if (lama_berjualan == "LU01") {
                score_lamausaha = 0.40;
            } else if (lama_berjualan == "LU02") {
                score_lamausaha = 0.70;
            } else if (lama_berjualan == "LU03") {
                score_lamausaha = 1.00;
            } else {
                score_lamausaha = 0.00;
            }

            if (lama_langganan == "LL01") {
                score_lamalangganan = 0.70;
            } else if (lama_langganan == "LL02") {
                score_lamalangganan = 1.00;
            } else {
                score_lamalangganan = 0.00;
            }

            if (lama_topup == 0) {
                score_lamatopup = 0.50;
            } else if (lama_topup <= 31) {
                score_lamatopup = 0.50;
            } else if (lama_topup > 31) {
                score_lamatopup = 0.35;
            } else {
                score_lamatopup = 0.00;
            }

            let totalscore = parseFloat(score_outlet) + parseFloat(score_top) + parseFloat(score_carabayar) +
                parseFloat(score_kepemilikan) + parseFloat(score_lamausaha) + parseFloat(
                    score_jaminan) +
                parseFloat(score_lamatopup) + parseFloat(score_lamalangganan) + parseFloat(score_ht) +
                parseFloat(score_omset) + parseFloat(score_faktur) + parseFloat(score_typeoutlet);
            let scoreakhir = totalscore.toFixed(2);
            //$("#skor").val(scoreakhir);
            let rekomendasi = "";
            if (scoreakhir <= 2) {
                rekomendasi = "Tidak Layak";
            } else if (scoreakhir > 2 && scoreakhir <= 4) {
                rekomendasi = "Tidak Disarankan";
            } else if (scoreakhir > 4 && scoreakhir <= 6.75) {
                rekomendasi = "Beresiko";
            } else if (scoreakhir > 6.75 && scoreakhir <= 8.5) {
                rekomendasi = "Layak Dengan Pertimbangan";
            } else if (scoreakhir > 8.5 && scoreakhir <= 10) {
                rekomendasi = "Layak";
            }
            $("#total_score").text(scoreakhir);
            $("#skor").val(scoreakhir);
            $("#rekomendasi").text(rekomendasi);
        }

        $("#cekjmlfaktur").click(function(e) {
            const jmlfaktur = $('#loadlistfakturkredit').find('tr').length;
            console.log(jmlfaktur);
        });

        loadSkor();

    });
</script>
@endpush
