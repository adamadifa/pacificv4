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
                                    <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                                        <option value="">Semua Cabang</option>
                                        @foreach ($cabang as $d)
                                            <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endrole
                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <select name="bulan" id="bulan" class="form-select">
                                            <option value="">Bulan</option>
                                            @foreach ($list_bulan as $d)
                                                <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <select name="tahun" id="tahun" class="form-select">
                                            <option value="">Tahun</option>
                                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                                <option value="{{ $t }}">{{ $t }}</option>
                                            @endfor
                                        </select>
                                    </div>
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
