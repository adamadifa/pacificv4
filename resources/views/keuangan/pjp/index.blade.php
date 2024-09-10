@extends('layouts.app')
@section('titlepage', 'Pinjaman Jangka Panjang (PJP)')

@section('content')
@section('navigasi')
    <span>Pinjaman Jangka Panjang (PJP)</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_pjp')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('pjp.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Input PJP
                        </a>
                    @endcan

                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('pjp.index') }}">
                                {{-- {{ auth()->user()->roles->pluck('name')[0] }} --}}
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
                                @hasanyrole($roles_show_cabang_pjp)
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <x-select label="Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                                selected="{{ Request('kode_cabang_search') }}" upperCase="true" select2="select2Kodecabangsearch" />
                                        </div>
                                    </div>
                                @endhasanyrole
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan_search') }}"
                                            name="nama_karyawan_search" icon="ti ti-user" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <button class="btn btn-primary w-100"><i class="ti ti-icons ti-search me-1"></i>Cari</button>
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
                                            <th>No. PJP</th>
                                            <th>Tanggal</th>
                                            <th>NIK</th>
                                            <th style="width: 15%">Nama Karyawan</th>
                                            <th>Kantor</th>
                                            <th>Jabatan</th>
                                            <th>Jumlah</th>
                                            <th>Bayar</th>
                                            <th>Sisa Tagihan</th>
                                            <th>Ket</th>
                                            <th class="text-center">Status</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pjp as $d)
                                            @php
                                                $sisatagihan = $d->jumlah_pinjaman - $d->totalpembayaran;
                                            @endphp
                                            <tr>
                                                <td>{{ $d->no_pinjaman }}</td>
                                                <td>{{ formatIndo($d->tanggal) }}</td>
                                                <td>{{ $d->nik }}</td>
                                                <td>{{ $d->nama_karyawan }}</td>
                                                <td>{{ $d->kode_cabang }}</td>
                                                <td>{{ singkatString($d->nama_jabatan) }}</td>
                                                <td class="text-end">{{ formatAngka($d->jumlah_pinjaman) }}</td>
                                                <td class="text-end">{{ formatRupiah($d->totalpembayaran) }}</td>
                                                <td class="text-end">{{ formatRupiah($sisatagihan) }}</td>
                                                <td>{!! $d->jumlah_pinjaman - $d->totalpembayaran == 0 ? '<span class="badge bg-success">L</span>' : '<span class="badge bg-danger">BL</span>' !!}</td>
                                                <td class="text-center">
                                                    @if ($d->tanggal == '2023-05-01')
                                                        <span class="badge bg-info">Koperasi</span>
                                                    @else
                                                        @if ($d->status == 0)
                                                            <i class="ti ti-hourglass-empty text-warning"></i>
                                                        @else
                                                            <span class="badge bg-success">{{ formatIndo($d->tanggal_proses) }}</span>
                                                        @endif
                                                    @endif

                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('pjp.approve')
                                                            @if ($d->status === '0')
                                                                <div>
                                                                    <a href="#" class="btnApprove"
                                                                        no_pinjaman="{{ Crypt::encrypt($d->no_pinjaman) }}">
                                                                        <i class="ti ti-external-link text-success me-1"></i>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <div>
                                                                    <form method="POST" name="deleteform" class="deleteform"
                                                                        action="{{ route('pjp.cancel', Crypt::encrypt($d->no_pinjaman)) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" class="cancel-confirm me-1">
                                                                            <i class="ti ti-square-rounded-x text-danger"></i>

                                                                        </a>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                        @endcan
                                                        @can('pjp.show')
                                                            <div>
                                                                <a href="#" class="btnShow" no_pinjaman="{{ Crypt::encrypt($d->no_pinjaman) }}"><i
                                                                        class="ti ti-file-description text-info me-1"></i></a>
                                                            </div>
                                                            <div>
                                                                <a href="{{ route('pjp.cetak', Crypt::encrypt($d->no_pinjaman)) }}" target="_blank"><i
                                                                        class="ti ti-printer text-primary me-1"></i></a>
                                                            </div>
                                                        @endcan
                                                        @can('pjp.delete')
                                                            @if ($d->status === '0' && $d->tanggal > '2023-05-01')
                                                                <div>
                                                                    <form method="POST" name="deleteform" class="deleteform"
                                                                        action="{{ route('pjp.delete', Crypt::encrypt($d->no_pinjaman)) }}">
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
                            <div style="float: right;">
                                {{ $pjp->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="" />
<x-modal-form id="modalBayar" size="" show="loadmodalBayar" title="" />
<x-modal-form id="modalApprove" size="" show="loadmodalApprove" title="" />
<x-modal-form id="modalShow" size="modal-xl" show="loadmodal" title="" />
<div class="modal fade" id="modalKaryawan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Data Karyawan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="tabelkaryawan" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Departemen</th>
                                <th>Kantor</th>
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

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };

        function loadingBayar() {
            $("#loadmodalBayar").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };

        function loadingApprove() {
            $("#loadmodalApprove").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };
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

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Input PJP');
            $("#loadmodal").load('/pjp/create');
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            loadingApprove();
            const no_pinjaman = $(this).attr('no_pinjaman');
            $("#modalApprove").modal("show");
            $("#modalApprove").find(".modal-title").text('Approve PJP');
            $("#loadmodalApprove").load(`/pjp/${no_pinjaman}/approve`);
        });


        $(".btnShow").click(function(e) {
            e.preventDefault();
            loading();
            const no_pinjaman = $(this).attr('no_pinjaman');
            $("#modalShow").modal("show");
            $("#modalShow").find(".modal-title").text('Detail PJP');
            $("#modalShow").find("#loadmodal").load(`/pjp/${no_pinjaman}/show`);
        });

        $(document).on('click', '#nik_search', function(e) {
            $("#modalKaryawan").modal("show");

        });


        $(document).on('click', '#btnBayar', function(e) {
            e.preventDefault();
            const no_pinjaman = $(this).attr('no_pinjaman');
            loadingBayar();
            $("#modalBayar").modal("show");
            $("#modalBayar").find(".modal-title").text('Input Pembayaran PJP');
            $("#modalBayar").find("#loadmodalBayar").load(`/pembayaranpjp/${no_pinjaman}/create`);
        });

        $('#tabelkaryawan').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [1, 'asc']
            ],
            ajax: "{{ route('karyawan.getkaryawanjson') }}",
            bAutoWidth: false,
            columns: [{
                    data: 'nik',
                    name: 'nik',
                    orderable: true,
                    searchable: true,
                    width: '10%'
                },
                {
                    data: 'nama_karyawan',
                    name: 'nama_karyawan',
                    orderable: true,
                    searchable: true,
                    width: '30%'
                },
                {
                    data: 'nama_jabatan',
                    name: 'nama_jabatan',
                    orderable: true,
                    searchable: false,
                    width: '20%'
                },

                {
                    data: 'nama_dept',
                    name: 'nama_dept',
                    orderable: true,
                    searchable: false,
                    width: '20%'
                },
                {
                    data: 'nama_cabang',
                    name: 'nama_cabang',
                    orderable: true,
                    searchable: false,
                    width: '30%'
                },
                {
                    data: 'statuskaryawan',
                    name: 'statuskaryawan',
                    orderable: true,
                    searchable: false,
                    width: '10%'
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
