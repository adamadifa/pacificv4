@extends('layouts.app')
@section('titlepage', 'Setting Komisi Driver Helper')

@section('content')
@section('navigasi')
    <span>Setting Komisi Driver Helper</span>
@endsection
<div class="col-lg-12">
    <div class="nav-align-top nav-tabs-shadow mb-4">
        @include('layouts.navigation_targetkomisi')
        <div class="tab-content">
            <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                @can('ratiodriverhelper.create')
                    <a href="#" class="btn btn-primary btnCreate"><i class="ti ti-settings-2 me-2"></i> Setting Komisi Driver Helper </a>
                @endcan
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="{{ URL::current() }}">
                            <div class="row">
                                @hasanyrole($roles_show_cabang)
                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                        <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                            textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                            select2="select2Kodecabangsearch" />
                                    </div>

                                @endrole
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <select name="bulan" id="bulan" class="form-select">
                                            <option value="">Bulan</option>
                                            @foreach ($list_bulan as $d)
                                                <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }} value="{{ $d['kode_bulan'] }}">
                                                    {{ $d['nama_bulan'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <select name="tahun" id="tahun" class="form-select">
                                            <option value="">Tahun</option>
                                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                                <option
                                                    @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                                @else {{ date('Y') == $t ? 'selected' : '' }} @endif
                                                    value="{{ $t }}">{{ $t }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Cabang</th>
                                        <th>Komisi Salesman</th>
                                        <th>Qty Flat</th>
                                        <th>UMK</th>
                                        <th>Persentase</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{-- {{ $ratiodriverhelper->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $(".btnCreate").click(function(e) {
            e.preventDefault();
            $('#modal').modal("show");
            $("#loadmodal").load("{{ route('settingkomisidriverhelper.create') }}");
            $(".modal-title").text("Tambahkan Setting Komisi Driver Helper");
        });

        // $(".btnShow").click(function(e) {
        //     e.preventDefault();
        //     const kode_ratio = $(this).attr("kode_ratio");
        //     $('#modal').modal("show");
        //     $("#loadmodal").load(`/ratiodriverhelper/${kode_ratio}`);
        //     $(".modal-title").text("Detail Ratio Driver Helper");
        // });


        // $(".btnEdit").click(function(e) {
        //     e.preventDefault();
        //     const kode_ratio = $(this).attr("kode_ratio");
        //     $('#modal').modal("show");
        //     $("#loadmodal").load(`/ratiodriverhelper/${kode_ratio}/edit`);
        //     $(".modal-title").text("Edit Ratio Driver Helpar");
        // });
    });
</script>
@endpush
