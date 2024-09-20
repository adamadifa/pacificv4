@extends('layouts.app')
@section('titlepage', 'Ajuan Faktur Kredit')

@section('content')
@section('navigasi')
    <span>Ajuan Faktur Kredit</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_ajuanmarketing')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('ajuanfaktur.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Ajukan Faktur Kredit
                        </a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('ajuanfaktur.index') }}">
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
                                <x-input-with-icon label="Nama Pelanggan" value="{{ Request('nama_pelanggan') }}" name="nama_pelanggan"
                                    icon="ti ti-user" />
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <div class="form-grou mb-3">
                                            <select name="posisi_ajuan" id="posisi_ajuan" class="form-select">
                                                <option value="">Poisi Ajuan</option>
                                                @foreach ($roles_approve_ajuanfakturkredit as $role)
                                                    <option value="{{ $role }}" {{ Request('posisi_ajuan') == $role ? 'selected' : '' }}>
                                                        {{ textUpperCase($role) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 co-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <select name="status" id="status" class="form-select">
                                                <option value="">Status</option>
                                                <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>
                                                    Disetujui</option>
                                                <option value="2" {{ Request('status') === '2' ? 'selected' : '' }}>
                                                    Ditolak</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
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
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No. Pengajuan</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Limit</th>
                                            <th>Jml Faktur</th>
                                            <th class="text-center">COD</th>
                                            <th style="width: 20%">Keterangan</th>
                                            <th>Posisi</th>
                                            <th class="text-center">Status</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ajuanfaktur as $d)
                                            @php
                                                if ($level_user == 'sales marketing manager') {
                                                    $nextlevel = 'regional sales manager';
                                                } elseif ($level_user == 'regional sales manager') {
                                                    $nextlevel = 'gm marketing';
                                                } elseif ($level_user == 'gm marketing') {
                                                    $nextlevel = 'direktur';
                                                } else {
                                                    $nextlevel = '';
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $d->no_pengajuan }}</td>
                                                <td>{{ formatIndo($d->tanggal) }}</td>
                                                <td>{{ $d->nama_pelanggan }}</td>
                                                <td class="text-end">{{ formatAngka($d->limit_pelanggan) }}</td>
                                                <td class="text-center">{{ formatAngka($d->jumlah_faktur) }}</td>
                                                <td class="text-center">
                                                    @if ($d->siklus_pembayaran == '1')
                                                        <i class="ti ti-square-rounded-check text-success"></i>
                                                    @else
                                                        <i class="ti ti-square-rounded-x text-danger"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $d->keterangan }}</td>
                                                <td>
                                                    @if ($d->role == 'sales marketing manager')
                                                        @php
                                                            $color = 'bg-warning';
                                                            $text_role = 'SMM';
                                                        @endphp
                                                    @elseif ($d->role == 'regional sales manager')
                                                        @php
                                                            $color = 'bg-info';
                                                            $text_role = 'RSM';
                                                        @endphp
                                                    @elseif($d->role == 'gm marketing')
                                                        @php
                                                            $color = 'bg-primary';
                                                            $text_role = 'GM Marketing';
                                                        @endphp
                                                    @elseif($d->role == 'direktur')
                                                        @php
                                                            $color = 'bg-success';
                                                            $text_role = 'Direktur';
                                                        @endphp
                                                    @else
                                                        @php
                                                            $color = '';
                                                            $text_role = '';
                                                        @endphp
                                                    @endif
                                                    <span class="badge {{ $color }}">
                                                        {{ $text_role }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($d->status === '0')
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @elseif($d->status == '1')
                                                        <i class="ti ti-checks text-success"></i>
                                                    @elseif($d->status == '2')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('ajuanfaktur.approve')
                                                            <div>
                                                                @if ($d->status_disposisi != null)
                                                                    @if ($d->status_disposisi == '0')
                                                                        <a href="#" class="me-2 btnApprove"
                                                                            no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                            <i class="ti ti-send text-info"></i>
                                                                        </a>
                                                                    @else
                                                                        <!-- Proses Cancel -->
                                                                        @if ($level_user == 'direktur')
                                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                                action="{{ route('ajuanfaktur.cancel', Crypt::encrypt($d->no_pengajuan)) }}">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <a href="#" class="cancel-confirm me-1">
                                                                                    <i class="ti ti-square-rounded-x text-danger"></i>

                                                                                </a>
                                                                            </form>
                                                                        @elseif (($d->status_ajuan == '0' && $d->role == $nextlevel) || $d->role == $level_user)
                                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                                action="{{ route('ajuanfaktur.cancel', Crypt::encrypt($d->no_pengajuan)) }}">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <a href="#" class="cancel-confirm me-1">
                                                                                    <i class="ti ti-square-rounded-x text-danger"></i>
                                                                                </a>
                                                                            </form>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if ($d->role == 'sales marketing manager' && $level_user == 'operation manager' && $d->status_ajuan == '0')
                                                                        <a href="#" class="me-2 btnApprove"
                                                                            no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                            <i class="ti ti-send text-info"></i>
                                                                        </a>
                                                                    @elseif(($d->status_ajuan == '0' && $d->role == 'regional sales manager') || $d->role == 'sales marketing manager')
                                                                        <form method="POST" name="deleteform" class="deleteform"
                                                                            action="{{ route('ajuanfaktur.cancel', Crypt::encrypt($d->no_pengajuan)) }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <a href="#" class="cancel-confirm me-1">
                                                                                <i class="ti ti-square-rounded-x text-danger"></i>
                                                                            </a>
                                                                        </form>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="float: right;">
                                {{ $ajuanfaktur->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" show="loadmodal" title="" />
<x-modal-form id="modalApprove" size="modal-xl" show="loadmodalApprove" title="" />
<div class="modal fade" id="modalPelanggan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Data Pelanggan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="tabelpelanggan" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Nama Pelanggan</th>
                                <th>Salesman</th>
                                <th>Wilayah</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
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
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buan Ajuan Faktur Kredit");
            $("#loadmodal").load(`/ajuanfaktur/create`);
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            loading();
            const no_pengajuan = $(this).attr("no_pengajuan");
            $('#modal').modal("show");
            $("#loadmodal").load(`/ajuanfaktur/${no_pengajuan}/approve`);
            $("#modal").find(".modal-title").text("Persetujuan Ajuan Faktur Kredit");
        });


        $(document).on('click', '#kode_pelanggan_search', function(e) {
            $("#modalPelanggan").modal("show");
        });

        $('#tabelpelanggan').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [2, 'asc']
            ],
            ajax: "{{ route('pelanggan.getpelangganjson') }}",
            bAutoWidth: false,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                },
                {
                    data: 'kode_pelanggan',
                    name: 'kode_pelanggan',
                    orderable: true,
                    searchable: true,
                    width: '10%'
                },
                {
                    data: 'nama_pelanggan',
                    name: 'nama_pelanggan',
                    orderable: true,
                    searchable: true,
                    width: '30%'
                },
                {
                    data: 'nama_salesman',
                    name: 'nama_salesman',
                    orderable: true,
                    searchable: false,
                    width: '20%'
                },

                {
                    data: 'nama_wilayah',
                    name: 'nama_wilayah',
                    orderable: true,
                    searchable: false,
                    width: '30%'
                },
                {
                    data: 'status_pelanggan',
                    name: 'status_pelanggan',
                    orderable: true,
                    searchable: false,
                    width: '30%'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                }
            ],

            rowCallback: function(row, data, index) {
                if (data.status_pelanggan == "NonAktif") {
                    $("td", row).addClass("bg-danger text-white");
                }
            }
        });
    });
</script>
@endpush
