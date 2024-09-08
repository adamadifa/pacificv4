@extends('layouts.app')
@section('titlepage', 'Setoran Pusat')

@section('content')
@section('navigasi')
    <span>Setoran Pusat</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_kasbesar')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('setoranpusat.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Input Setoran Pusat
                        </a>
                    @endcan

                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('setoranpusat.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                </div>
                                @hasanyrole($roles_show_cabang)
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                                textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                                select2="select2Kodecabangsearch" />
                                        </div>
                                    </div>
                                @endrole
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i class="ti ti-search me-2"></i>Cari
                                                Data</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table class="table  table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th rowspan="2" class="align-middle">Tanggal</th>
                                            <th rowspan="2" style="width:20%">Keterangan</th>
                                            <th colspan="4" class="text-center">Setoran</th>
                                            <th rowspan="2" class="align-middle">Jumlah</th>
                                            <th rowspan="2" class="text-center align-middle" style="width: 15%">BANK</th>
                                            <th rowspan="2" class="text-center align-middle">Status</th>
                                            <th rowspan="2" class="text-center align-middle">#</th>
                                        </tr>
                                        <tr>
                                            <th>Kertas</th>
                                            <th>Logam</th>
                                            <th>Transfer</th>
                                            <th>Giro</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($setoran_pusat as $d)
                                            <tr>
                                                <td>{{ formatIndo($d->tanggal) }} </td>
                                                <td>{{ textCamelCase($d->keterangan) }}</td>
                                                <td class="text-end">{{ formatAngka($d->setoran_kertas) }}</td>
                                                <td class="text-end">{{ formatAngka($d->setoran_logam) }}</td>
                                                <td class="text-end">{{ formatAngka($d->setoran_transfer) }}</td>
                                                <td class="text-end">{{ formatAngka($d->setoran_giro) }}</td>
                                                <td class="text-end fw-bold">{{ formatAngka($d->total) }}</td>
                                                <td>
                                                    @if ($d->status == '1')
                                                        @php
                                                            $nama_bank = !empty($d->nama_bank_alias) ? $d->nama_bank_alias : $d->nama_bank;
                                                        @endphp
                                                        {{ $nama_bank }}
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($d->status == '1')
                                                        @if (!empty($d->tanggal_diterima))
                                                            <span class="badge bg-success">{{ formatIndo($d->tanggal_diterima) }}</span>
                                                        @elseif(!empty($d->tanggal_diterima_transfer))
                                                            <span class="badge bg-success">{{ formatIndo($d->tanggal_diterima_transfer) }}</span>
                                                        @elseif(!empty($d->tanggal_diterima_giro))
                                                            <span class="badge bg-success">{{ formatIndo($d->tanggal_diterima_giro) }}</span>
                                                        @endif
                                                    @elseif($d->status == '2')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @elseif($d->status == '0')
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex">
                                                        @can('setoranpusat.approve')
                                                            @if ($d->status == '0' && empty($d->setoran_transfer) && empty($d->setoran_giro))
                                                                <div>
                                                                    <a href="#" class="btnApprove me-1"
                                                                        kode_setoran="{{ Crypt::encrypt($d->kode_setoran) }}"><i
                                                                            class="ti ti-external-link text-primary"></i></a>
                                                                </div>
                                                            @else
                                                                @if (empty($d->setoran_transfer) && empty($d->setoran_giro))
                                                                    <form method="POST" name="deleteform" class="deleteform"
                                                                        action="{{ route('setoranpusat.cancel', Crypt::encrypt($d->kode_setoran)) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" class="cancel-confirm me-1">
                                                                            <i class="ti ti-square-rounded-x text-danger"></i>

                                                                        </a>
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        @endcan
                                                        @can('setoranpusat.edit')
                                                            @if ($d->status == '0' && empty($d->setoran_transfer) && empty($d->setoran_giro) && empty($d->no_pengajuan))
                                                                <div>
                                                                    <a href="#" class="btnEdit me-1"
                                                                        kode_setoran="{{ Crypt::encrypt($d->kode_setoran) }}">
                                                                        <i class="ti ti-edit text-success"></i>
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @endcan
                                                        @can('setoranpusat.delete')
                                                            @if ($d->status == '0' && empty($d->setoran_transfer) && empty($d->setoran_giro) && empty($d->no_pengajuan))
                                                                <div>
                                                                    <form method="POST" name="deleteform" class="deleteform me-1"
                                                                        action="{{ route('setoranpusat.delete', Crypt::encrypt($d->kode_setoran)) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" class="delete-confirm ml-1">
                                                                            <i class="ti ti-trash text-danger"></i>
                                                                        </a>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                        @endcan

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
    $(function() {
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
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Input Setoran Pusat");
            $("#loadmodal").load(`/setoranpusat/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const kode_setoran = $(this).attr('kode_setoran');
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Edit Setoran Pusat");
            $("#loadmodal").load(`/setoranpusat/${kode_setoran}/edit`);
        });


        $(".btnApprove").click(function(e) {
            e.preventDefault();
            const kode_setoran = $(this).attr('kode_setoran');
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Approve Setoran Pusat");
            $("#loadmodal").load(`/setoranpusat/${kode_setoran}/approve`);
        });

        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua  Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>
@endpush
