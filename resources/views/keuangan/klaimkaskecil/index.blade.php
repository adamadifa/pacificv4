@extends('layouts.app')
@section('titlepage', 'Kas Kecil')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Klaim Kas Kecil</h4>
            <small class="text-muted">Manajemen pengajuan klaim kas kecil.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-report-money me-1"></i>Klaim Kas Kecil</li>
            </ol>
        </nav>
    </div>
@endsection

<style>
    .badge {
        padding: 0.25rem 0.4rem !important;
    }
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_kaskecil')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('klaimkaskecil.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari"
                                datepicker="flatpickr-date" value="{{ Request('dari') }}" hideLabel="true" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai"
                                datepicker="flatpickr-date" value="{{ Request('sampai') }}" hideLabel="true" />
                        </div>
                    </div>
                    <div class="row g-2 align-items-end">
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg-11 col-md-10 col-sm-12">
                                <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                    select2="select2Kodecabangsearch" hideLabel="true" />
                            </div>
                        @endrole
                        <div class="{{ auth()->user()->hasAnyRole($roles_show_cabang) ? 'col-lg-1' : 'col-lg-12' }} col-md-2 col-sm-12">
                            <div class="form-group mb-3 text-end">
                                <button class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-report-money me-2"></i>Data Klaim Kas Kecil</h6>
                    @can('klaimkaskecil.create')
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Buat Klaim
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered table-striped align-middle" style="font-size: 13px !important;">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">KODE</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 30%;">KETERANGAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">STATUS</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">NO. BUKTI</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">DIPROSES</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">VALIDASI</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">JUMLAH</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($klaimkaskecil as $d)
                            <tr>
                                <td class="text-center fw-bold">{{ $d->kode_klaim }}</td>
                                <td class="text-center">{{ formatIndo($d->tanggal) }}</td>
                                <td style="white-space: normal;">{{ $d->keterangan }}</td>
                                <td class="text-center">
                                    @if ($d->status == 0)
                                        <i class="ti ti-hourglass-empty text-warning fs-4" data-bs-toggle="tooltip" title="Menunggu"></i>
                                    @else
                                        <i class="ti ti-circle-check text-success fs-4" data-bs-toggle="tooltip" title="Selesai"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (!empty($d->no_bukti))
                                        <span class="badge bg-primary">{{ $d->no_bukti }}</span>
                                    @else
                                        <i class="ti ti-hourglass-empty text-warning fs-4"></i>
                                    @endif
                                </td>
                                <td class="text-center">{{ !empty($d->tgl_proses) ? formatIndo($d->tgl_proses) : '-' }}</td>
                                <td class="text-center">
                                    @if (!empty($d->cekvalidasi))
                                        <i class="ti ti-checks text-success fs-4" data-bs-toggle="tooltip" title="Valid"></i>
                                    @else
                                        <i class="ti ti-hourglass-empty text-warning fs-4" data-bs-toggle="tooltip" title="Belum Validasi"></i>
                                    @endif
                                </td>
                                <td class="text-end fw-bold {{ !empty($d->no_bukti) ? 'text-primary' : 'text-center' }}">
                                    @if (!empty($d->no_bukti))
                                        {{ formatAngka($d->jumlah) }}
                                    @else
                                        <i class="ti ti-hourglass-empty text-warning fs-4"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        @can('klaimkaskecil.show')
                                            <a href="{{ route('klaimkaskecil.cetak', ['kode_klaim' => Crypt::encrypt($d->kode_klaim), 'export' => 0]) }}"
                                                target="_blank" class="text-primary" data-bs-toggle="tooltip" title="Cetak">
                                                <i class="ti ti-printer fs-4"></i>
                                            </a>
                                            <a href="{{ route('klaimkaskecil.cetak', ['kode_klaim' => Crypt::encrypt($d->kode_klaim), 'export' => true]) }}"
                                                target="_blank" class="text-success" data-bs-toggle="tooltip" title="Export Excel">
                                                <i class="ti ti-file-spreadsheet fs-4"></i>
                                            </a>
                                        @endcan
                                        @can('klaimkaskecil.proses')
                                            @if (empty($d->no_bukti))
                                                <a href="#" class="btnProses text-info"
                                                    kode_klaim="{{ Crypt::encrypt($d->kode_klaim) }}" data-bs-toggle="tooltip"
                                                    title="Proses">
                                                    <i class="ti ti-settings fs-4"></i>
                                                </a>
                                            @else
                                                @if (empty($d->cekvalidasi))
                                                    <form method="POST" name="deleteform" class="deleteform d-inline"
                                                        action="{{ route('klaimkaskecil.cancelproses', Crypt::encrypt($d->no_bukti)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="cancel-confirm text-danger" data-bs-toggle="tooltip"
                                                            title="Batal Proses">
                                                            <i class="ti ti-square-rounded-x fs-4"></i>
                                                        </a>
                                                    </form>
                                                @endif
                                            @endif
                                        @endcan
                                        @can('klaimkaskecil.approve')
                                            @if (!empty($d->no_bukti) && empty($d->cekvalidasi))
                                                <a href="{{ route('klaimkaskecil.approve', Crypt::encrypt($d->no_bukti)) }}"
                                                    class="text-success" data-bs-toggle="tooltip" title="Approve">
                                                    <i class="ti ti-square-rounded-check fs-4"></i>
                                                </a>
                                            @else
                                                @if (!empty($d->cekvalidasi))
                                                    <form method="POST" name="deleteform" class="deleteform d-inline"
                                                        action="{{ route('klaimkaskecil.cancelapprove', Crypt::encrypt($d->no_bukti)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="cancel-confirm text-danger" data-bs-toggle="tooltip"
                                                            title="Batal Approve">
                                                            <i class="ti ti-square-x fs-4"></i>
                                                        </a>
                                                    </form>
                                                @endif
                                            @endif
                                        @endcan
                                        @can('klaimkaskecil.delete')
                                            @if (empty($d->no_bukti))
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('klaimkaskecil.delete', Crypt::encrypt($d->kode_klaim)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="delete-confirm text-danger" data-bs-toggle="tooltip"
                                                        title="Hapus">
                                                        <i class="ti ti-trash fs-4"></i>
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
                    {{ $klaimkaskecil->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-xxxl" show="loadmodal" title="" />
<x-modal-form id="modalEdit" show="loadmodalEdit" title="" />

@endsection
@push('myscript')
<script>
    $(function() {

        function loading() {
            $("#loadmodal,#loadmodalEdit").html(`<div class="sk-wave sk-primary" style="margin:auto">
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
            $("#modal").find(".modal-title").text('Buat Klaim');
            $("#loadmodal").load('/klaimkaskecil/create');
        });

        $(".btnProses").click(function(e) {
            e.preventDefault();
            loading();
            const kode_klaim = $(this).attr('kode_klaim');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Proses Kas Kecil');
            $("#modal").find("#loadmodal").load(`/klaimkaskecil/${kode_klaim}/proses`);
        });

    });
</script>
@endpush
