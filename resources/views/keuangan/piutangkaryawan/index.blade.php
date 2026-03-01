@extends('layouts.app')
@section('titlepage', 'Piutang Karyawan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Piutang Karyawan</h4>
            <small class="text-muted">Manajemen data piutang dan pinjaman karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-users me-1"></i>Piutang Karyawan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Filter Section --}}
        <form action="{{ route('piutangkaryawan.index') }}" id="formSearch">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                        </div>
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                    selected="{{ Request('kode_cabang_search') }}" upperCase="true" select2="select2Kodecabangsearch" />
                            </div>
                        @endhasanyrole
                        <div class="{{ auth()->user()->hasAnyRole($roles_show_cabang) ? 'col-lg-4' : 'col-lg-7' }} col-md-12 col-sm-12">
                            <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan_search') }}"
                                name="nama_karyawan_search" icon="ti ti-user" />
                        </div>
                        <div class="col-lg-1 col-md-12 col-sm-12 text-end">
                            <div class="form-group mb-3">
                                <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-users me-2"></i>Data Piutang Karyawan</h6>
                    @can('piutangkaryawan.create')
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Input Piutang
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">NO. PINJAMAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">NIK</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 15%">NAMA KARYAWAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">KANTOR</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">JABATAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">JUMLAH</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">BAYAR</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">SISA TAGIHAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">KAT</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">KET</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 5%;">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($piutangkaryawan as $d)
                            @php
                                $sisatagihan = $d->jumlah - $d->totalpembayaran;
                            @endphp
                            <tr>
                                <td class="text-center fw-bold">{{ $d->no_pinjaman }}</td>
                                <td class="text-center">{{ formatIndo($d->tanggal) }}</td>
                                <td class="text-center">{{ $d->nik }}</td>
                                <td>{{ $d->nama_karyawan }}</td>
                                <td class="text-center">{{ $d->kode_cabang }}</td>
                                <td>{{ singkatString($d->nama_jabatan) }}</td>
                                <td class="text-end fw-bold">{{ formatAngka($d->jumlah) }}</td>
                                <td class="text-end fw-bold text-success">{{ formatAngka($d->totalpembayaran) }}</td>
                                <td class="text-end fw-bold text-danger">{{ formatAngka($sisatagihan) }}</td>
                                <td class="text-center">
                                    @if ($d->kategori == 'KA')
                                        <span class="badge bg-success">Karyawan</span>
                                    @else
                                        <span class="badge bg-danger">Eks Karyawan</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {!! $sisatagihan == 0
                                        ? '<span class="badge bg-success">Lunas</span>'
                                        : '<span class="badge bg-danger">Belum Lunas</span>' !!}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        @can('piutangkaryawan.show')
                                            <a href="#" class="btnShow text-info"
                                                no_pinjaman="{{ Crypt::encrypt($d->no_pinjaman) }}" data-bs-toggle="tooltip" title="Detail">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('piutangkaryawan.delete')
                                            @if ($d->status === '0')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('piutangkaryawan.delete', Crypt::encrypt($d->no_pinjaman)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="delete-confirm text-danger" data-bs-toggle="tooltip" title="Hapus">
                                                        <i class="ti ti-trash fs-5"></i>
                                                    </a>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <div class="float-end">
                    {{ $piutangkaryawan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" show="loadmodal" title="" />
<x-modal-form id="modalBayar" size="" show="loadmodalBayar" title="" />
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
            $("#modal").find(".modal-title").text('Input Piutang Karyawan');
            $("#loadmodal").load('/piutangkaryawan/create');
            $("#modal").find(".modal-dialog").removeClass("modal-xl");
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            loading();
            const no_pinjaman = $(this).attr('no_pinjaman');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Detail Piutang Karyawan');
            $("#modal").find("#loadmodal").load(`/piutangkaryawan/${no_pinjaman}/show`);
            $("#modal").find(".modal-dialog").addClass("modal-xl");
        });


        $(document).on('click', '#btnBayar', function(e) {
            e.preventDefault();
            const no_pinjaman = $(this).attr('no_pinjaman');
            loadingBayar();
            $("#modalBayar").modal("show");
            $("#modalBayar").find(".modal-title").text('Input Pembayaran Piutang Karyawan');
            $("#modalBayar").find("#loadmodalBayar").load(`/pembayaranpiutangkaryawan/${no_pinjaman}/create`);
        });

        $('#tabelkaryawan').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [1, 'asc']
            ],
            ajax: "{{ route('karyawan.getkaryawanpiutangkaryawanjson') }}",
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

        $(document).on('click', '#nik_search', function(e) {
            $("#modalKaryawan").modal("show");

        });

    });
</script>
@endpush
