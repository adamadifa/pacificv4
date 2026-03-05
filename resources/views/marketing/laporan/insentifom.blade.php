@extends('layouts.app')
@section('titlepage', 'Cost Ratio')

@section('content')
@section('navigasi')
    <span>Insentif OM</span>
@endsection
<div class="row">
    <div class="col-lg-5 col-sm-12 col-xs-12">
        <div class="card">

            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="{{ route('laporanmarketing.cetakinsentifom') }}" method="POST" target="_blank" id="formInsentifom">
                            @csrf
                            @hasanyrole($roles_show_cabang)
                                <div class="form-group mb-3">
                                    <x-select label="Semua Cabang" name="kode_cabang" id="kode_cabang" :data="$cabang"
                                        key="kode_cabang" textShow="nama_cabang" select2="select2Kodecabang"
                                        upperCase="true" hideLabel="true" />
                                </div>
                            @endrole
                            <div class="row">
                                <div class="col">
                                    @php
                                        $bulan_data = collect($list_bulan)->map(function ($item) {
                                            return (object) $item;
                                        });
                                    @endphp
                                    <x-select label="Bulan" name="bulan" id="bulan" :data="$bulan_data"
                                        key="kode_bulan" textShow="nama_bulan" hideLabel="true" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    @php
                                        $tahun_data = [];
                                        for ($t = $start_year; $t <= date('Y'); $t++) {
                                            $tahun_data[] = (object) ['tahun' => $t];
                                        }
                                    @endphp
                                    <x-select label="Tahun" name="tahun" id="tahun" :data="$tahun_data"
                                        key="tahun" textShow="tahun" hideLabel="true" />
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-lg-10 col-md-12 col-sm-12">
                                    <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonlhp">
                                        <i class="ti ti-printer me-1"></i> Cetak
                                    </button>
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonlhp">
                                        <i class="ti ti-download"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(document).ready(function() {
        const formInsentifom = $("#formInsentifom");
        const select2Kodecabang = $(".select2Kodecabang");
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



        formInsentifom.submit(function(e) {
            const kode_cabang = formInsentifom.find('#kode_cabang').val();
            const bulan = formInsentifom.find('#bulan').val();
            const tahun = formInsentifom.find('#tahun').val();
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#bulan").focus();
                    },
                });
                return false;
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#tahun").focus();
                    },
                })
                return false;
            }
        });
    });
</script>
@endpush
