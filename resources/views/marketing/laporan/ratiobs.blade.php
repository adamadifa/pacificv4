@extends('layouts.app')
@section('titlepage', 'Ratio BS')

@section('content')
@section('navigasi')
    <span>Ratio BS</span>
@endsection
<div class="row">
    <div class="col-lg-4 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('laporanmarketing.cetakratiobs') }}" method="POST" target="_blank" id="formRatioBS">
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
                            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonDpp">
                                <i class="ti ti-printer me-1"></i> Cetak
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12">
                            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonDpp">
                                <i class="ti ti-download"></i>
                            </button>
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
        const formRatioBS = $("#formRatioBS");
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
        $("#formRatioBS").submit(function() {
            const bulan = $("#formRatioBS").find('#bulan').val();
            const tahun = $("#formRatioBS").find('#tahun').val();
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Bulan Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#formRatioBS").find('#bulan').focus();
                    },
                });
                return false;
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Tahun Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#formRatioBS").find('#tahun').focus();
                    },
                });
                return false;
            }

        });
    });
</script>
@endpush
