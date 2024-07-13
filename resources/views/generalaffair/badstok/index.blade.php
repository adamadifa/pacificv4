@extends('layouts.app')
@section('titlepage', 'Bad Stok')

@section('content')
@section('navigasi')
    <span>Bad Stok</span>
@endsection
<div class="row">
    <div class="col-lg-5 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('badstokga.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Input Bad Stok</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('badstokga.index') }}">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="kode_asal_bs_search" id="" class="form-select select2Kodeasalbssearch">
                                            <option value="">Asal Bad Stok</option>
                                            <option value="GDG" {{ Request('kode_asal_bs_search') == 'GDG' ? 'selected' : '' }}>GUDANG</option>
                                            @foreach ($asalbadstok as $d)
                                                <option {{ Request('kode_asal_bs_search') == $d->kode_cabang ? 'selected' : '' }}
                                                    value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari Data</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kode BS</th>
                                        <th>Tanggal</th>
                                        <th>Asal Badstok</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($badstok as $d)
                                        <tr>
                                            <td>{{ $d->kode_bs }}</td>
                                            <td>{{ DateToIndo($d->tanggal) }}</td>
                                            <td>{{ $d->kode_asal_bs }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('badstokga.show')
                                                        <a href="#" class="btnShow" kode_bs="{{ Crypt::encrypt($d->kode_bs) }}">
                                                            <i class="ti ti-file-description text-info"></i>
                                                        </a>
                                                    @endcan
                                                    @can('badstokga.delete')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('badstokga.delete', Crypt::encrypt($d->kode_bs)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm me-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $badstok->links() }}
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

        const select2Kodeasalbssearch = $('.select2Kodeasalbssearch');
        if (select2Kodeasalbssearch.length) {
            select2Kodeasalbssearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Asal Bad Stok',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }



        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Input Bad Stok");
            $("#loadmodal").load(`/badstokga/create`);
        });

        $(".btnShow").click(function(e) {
            var kode_bs = $(this).attr("kode_bs");
            e.preventDefault();
            $('#modal').modal("show");
            loading();
            $("#modal").find(".modal-title").text("Detail Bad Stok");
            $("#loadmodal").load('/badstokga/' + kode_bs + '/show');
        });

        $(document).on('submit', '#formBadStok', function(e) {
            const tanggal = $(this).find("#tanggal").val();
            const kode_asal_bs = $(this).find("#kode_asal_bs").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_asal_bs == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Asal Bad Stok harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#kode_asal_bs").focus();
                    },
                });
                return false;
            } else {
                buttonDisabled();
                return true;
            }
        });
    });
</script>
@endpush
