@extends('layouts.app')
@section('titlepage', 'Laporan General Affair')

@section('content')

@section('navigasi')
    <span>Laporan General Affair</span>
@endsection
<div class="row">
    <div class="col-xl-6 col-md-12 col-sm-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('ga.servicekendaraan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#servicekendaraan"
                            aria-controls="servicekendaraan" aria-selected="false" tabindex="-1">
                            Service Kendaraan
                        </button>
                    </li>
                @endcan
                @can('ga.rekapbadstok')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapbadstok"
                            aria-controls="rekapbadstok" aria-selected="false" tabindex="-1">
                            Rekap Bad Stok
                        </button>
                    </li>
                @endcan

            </ul>
            <div class="tab-content">
                <!-- Laporan Persediaan-->
                @can('ga.servicekendaraan')
                    <div class="tab-pane fade active show" id="servicekendaraan" role="tabpanel">
                        @include('generalaffair.laporan.servicekendaraan')
                    </div>
                @endcan
                @can('ga.rekapbadstok')
                    <div class="tab-pane fade" id="rekapbadstok" role="tabpanel">
                        @include('generalaffair.laporan.rekapbadstok')
                    </div>
                @endcan

            </div>
        </div>
    </div>

</div>
@endsection
@push('myscript')
<script>
    $(function() {
        const formServicekendaraan = $('#formLapServicekendaraan');
        const formRekapbadstok = $('#formLapRekapbadstok');
        const select2Kendaraan = $('.select2Kendaraan');
        if (select2Kendaraan.length) {
            select2Kendaraan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Kendaraan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        formServicekendaraan.submit(function(e) {
            const dari = $(this).find("#dari").val();
            const sampai = $(this).find("#sampai").val();
            var start = new Date(dari);
            var end = new Date(sampai);
            if (dari == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Dari Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#dari").focus();
                    },
                });
                return false;
            } else if (sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Sampai Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#sampai").focus();
                    },
                });
                return false;
            } else if (start.getTime() > end.getTime()) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Tidak Valid !, Periode Sampai Harus Lebih Akhir dari Periode Dari',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#sampai").focus();
                    },
                });
                return false;
            }
        });


        function loadRekapbadstokbulan() {
            const formatlaporan = formRekapbadstok.find("#formatlaporan").val();
            if (formatlaporan == '1') {
                formRekapbadstok.find("#bulan").show();
            } else {
                formRekapbadstok.find("#bulan").hide();
            }
        }

        loadRekapbadstokbulan();

        formRekapbadstok.find("#formatlaporan").change(function() {
            loadRekapbadstokbulan();
        });

        formRekapbadstok.submit(function(e) {
            const bulan = $(this).find("#bulan").val();
            const tahun = $(this).find("#tahun").val();
            const formatlaporan = $(this).find("#formatlaporan").val();
            if (formatlaporan == '1' && bulan == '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Bulan Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#bulan").focus();
                    },
                });
                return false;
            } else if (tahun == '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Tahun Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#tahun").focus();
                    },
                });
                return false;
            }
        });
    });
</script>
@endpush
