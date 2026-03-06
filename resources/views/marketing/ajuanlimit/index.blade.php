@extends('layouts.app')
@section('titlepage', 'Ajuan Limit Kredit')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Ajuan Limit Kredit</h4>
            <small class="text-muted">Kelola data transaksi pengajuan limit kredit pelanggan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Marketing</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-credit-card me-1"></i>Ajuan Limit Kredit</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_ajuanmarketing')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('ajuanlimit.index') }}" id="formSearch">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2 mb-1">
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                    select2="select2Kodecabangsearch" hideLabel="true" />
                            </div>
                        @endhasanyrole
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <div class="form-group">
                                <select name="posisi_ajuan" id="posisi_ajuan" class="form-select">
                                    <option value="">Posisi Ajuan</option>
                                    @foreach ($roles_approve_ajuanlimitkredit as $role)
                                        <option value="{{ $role }}" {{ Request('posisi_ajuan') == $role ? 'selected' : '' }}>
                                            {{ textUpperCase($role) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <div class="form-group">
                                <select name="status" id="status" class="form-select">
                                    <option value="">Status</option>
                                    <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="2" {{ Request('status') === '2' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-4 col-sm-12">
                            <div class="form-group mb-1">
                                <button class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Card Data --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-credit-card me-2"></i>Data Ajuan Limit</h6>
                    <div class="d-flex gap-2">
                        @can('ajuanlimit.config')
                            <a href="{{ route('ajuanlimitconfig.index') }}" class="btn btn-outline-primary btn-sm shadow-sm"><i class="ti ti-settings me-1"></i> Konfigurasi</a>
                        @endcan
                        @can('ajuanlimit.create')
                            <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Ajukan Limit</a>
                        @endcan
                    </div>
                </div>
            </div>

                        <style>
                            .freeze-first { position: sticky; left: 0; background-color: #002e65 !important; z-index: 10; border-right: 2px solid #dee2e6 !important; }
                            .freeze-last { position: sticky; right: 0; background-color: #002e65 !important; z-index: 10; border-left: 2px solid #dee2e6 !important; }
                            .table-hover tbody tr:hover .freeze-cell-first { background-color: #f5f5f5 !important; }
                            .table-hover tbody tr:hover .freeze-cell-last { background-color: #f5f5f5 !important; }
                            .freeze-cell-first { position: sticky; left: 0; background-color: #fff !important; z-index: 9; border-right: 2px solid #dee2e6 !important; }
                            .freeze-cell-last { position: sticky; right: 0; background-color: #fff !important; z-index: 9; border-left: 2px solid #dee2e6 !important; }
                        </style>

                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover table-bordered">
                                <thead style="background-color: #002e65;">
                                    <tr>
                                        <th class="text-white freeze-first">No. Pengajuan</th>
                                        <th class="text-white">Tanggal</th>
                                        <th class="text-white">Pelanggan</th>
                                        <th class="text-white text-end">Jumlah</th>
                                        <th class="text-white">LJT</th>
                                        <th class="text-white text-center"><i class="ti ti-adjustments"></i></th>
                                        <th class="text-white text-center">Skor</th>
                                        <th class="text-white">Ket</th>
                                        <th class="text-white">Posisi</th>
                                        <th class="text-white text-center">Status</th>
                                        <th class="text-white text-center freeze-last" style="width: 10%">#</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @foreach ($ajuanlimit as $d)
                                        @php
                                            $current_index = array_search($level_user, $roles_approve_ajuanlimitkredit);
                                            $nextlevel = ($current_index !== false && isset($roles_approve_ajuanlimitkredit[$current_index + 1])) 
                                                ? $roles_approve_ajuanlimitkredit[$current_index + 1] 
                                                : '';
                                        @endphp
                                        <tr>
                                            <td class="freeze-cell-first"><span class="fw-bold">{{ $d->no_pengajuan }}</span></td>
                                            <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                            <td>{{ $d->nama_pelanggan }}</td>
                                            <td class="text-end fw-bold">
                                                @if (!empty($d->jumlah_rekomendasi))
                                                    <span class="text-muted text-decoration-line-through me-1" style="font-size: 11px">{{ formatAngka($d->jumlah) }}</span>
                                                    <span class="text-primary">{{ formatAngka($d->jumlah_rekomendasi) }}</span>
                                                @else
                                                    {{ formatAngka($d->jumlah) }}
                                                @endif
                                            </td>
                                            <td>{{ $d->ljt }} Hari</td>
                                            <td class="text-center">
                                                @php
                                                    $selisih = $d->jumlah - $d->jumlah_rekomendasi;
                                                    $selisih = $selisih < 0 ? $selisih * -1 : $selisih;
                                                    $persentase = !empty($d->jumlah) ? ($selisih / $d->jumlah) * 100 : 0;
                                                @endphp
                                                @can('ajuanlimit.adjust')
                                                    @if (empty($d->jumlah_rekomendasi))
                                                        <a href="#" class="adjustlimit" no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                            <i class="ti ti-adjustments text-warning fs-4"></i>
                                                        </a>
                                                    @else
                                                        @if ($d->status != '2')
                                                            <a href="#" class="adjustlimit" no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                @if ($d->jumlah_rekomendasi < $d->jumlah)
                                                                    <span class="text-danger fw-bold"><i class="ti ti-trending-down me-1"></i>{{ ROUND($persentase) }}%</span>
                                                                @else
                                                                    <span class="text-success fw-bold"><i class="ti ti-trending-up me-1"></i>{{ ROUND($persentase) }}%</span>
                                                                @endif
                                                            </a>
                                                        @else
                                                                @if ($d->jumlah_rekomendasi < $d->jumlah)
                                                                    <span class="text-danger fw-bold"><i class="ti ti-trending-down me-1"></i>{{ ROUND($persentase) }}%</span>
                                                                @else
                                                                    <span class="text-success fw-bold"><i class="ti ti-trending-up me-1"></i>{{ ROUND($persentase) }}%</span>
                                                                @endif
                                                        @endif
                                                    @endif
                                                @else
                                                    @if (!empty($d->jumlah_rekomendasi))
                                                        @if ($d->jumlah_rekomendasi < $d->jumlah)
                                                            <span class="text-danger fw-bold"><i class="ti ti-trending-down me-1"></i>{{ ROUND($persentase) }}%</span>
                                                        @else
                                                            <span class="text-success fw-bold"><i class="ti ti-trending-up me-1"></i>{{ ROUND($persentase) }}%</span>
                                                        @endif
                                                    @endif
                                                @endcan
                                            </td>
                                            <td class="text-center fw-bold">{{ formatAngkaDesimal($d->skor) }}</td>
                                            <td class="text-center">
                                                @php
                                                    if ($d->skor <= 2) { $rekomendasi = 'TL'; $bg = 'danger'; }
                                                    elseif ($d->skor <= 4) { $rekomendasi = 'TD'; $bg = 'danger'; }
                                                    elseif ($d->skor <= 6) { $rekomendasi = 'B'; $bg = 'warning'; }
                                                    elseif ($d->skor <= 8.5) { $rekomendasi = 'LDP'; $bg = 'success'; }
                                                    elseif ($d->skor <= 10) { $rekomendasi = 'L'; $bg = 'success'; }
                                                    else { $rekomendasi = ''; $bg = 'secondary'; }
                                                @endphp
                                                <span class="badge bg-{{ $bg }} shadow-sm">
                                                    {{ $rekomendasi }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $jumlah_limit = (int) $d->jumlah;
                                                    $current_config = $all_configs->filter(function($c) use ($jumlah_limit) {
                                                        return $jumlah_limit >= $c->min_limit && $jumlah_limit <= $c->max_limit;
                                                    })->first();
                                                    $roles_row = $current_config ? $current_config->roles : [];
                                                    $last_role_row = !empty($roles_row) ? end($roles_row) : null;

                                                    $posisi_ajuan = $d->posisi_ajuan;
                                                    if (empty($posisi_ajuan) && $d->status == '1') {
                                                        $posisi_ajuan = $last_role_row;
                                                    }

                                                    $posisi_ajuan_lower = strtolower($posisi_ajuan);
                                                    if ($posisi_ajuan_lower == 'sales marketing manager') {
                                                        $color = 'bg-warning';
                                                        $text_role = 'SMM';
                                                    } elseif ($posisi_ajuan_lower == 'regional sales manager') {
                                                        $color = 'bg-info';
                                                        $text_role = 'RSM';
                                                    } elseif ($posisi_ajuan_lower == 'gm marketing') {
                                                        $color = 'bg-primary';
                                                        $text_role = 'GM';
                                                    } elseif ($posisi_ajuan_lower == 'direktur') {
                                                        $color = 'bg-success';
                                                        $text_role = 'DIR';
                                                    } else {
                                                        $text_role = $d->status == '1' ? '-' : 'Belum di Konfigurasi';
                                                        $color = $d->status == '1' ? 'bg-secondary' : 'bg-danger';
                                                    }
                                                @endphp
                                                <span class="badge {{ $color }} shadow-sm">
                                                    {{ $text_role }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if ($d->status === '0')
                                                    <span class="badge bg-label-warning"><i class="ti ti-hourglass-empty me-1"></i>Pending</span>
                                                @elseif($d->status == '1')
                                                    <span class="badge bg-label-success"><i class="ti ti-checks me-1"></i>Approved</span>
                                                @elseif($d->status == '2')
                                                    <span class="badge bg-label-danger"><i class="ti ti-x me-1"></i>Rejected</span>
                                                @endif
                                            </td>
                                            <td class="freeze-cell-last">
                                                <div class="d-flex justify-content-center gap-2">
                                                    @can('ajuanlimit.approve')
                                                        @if ($d->status == '0' && ($d->posisi_ajuan == $level_user || auth()->user()->hasRole('super admin')))
                                                            <a href="#" class="btnApprove text-info" no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}" data-bs-toggle="tooltip" title="Approve">
                                                                <i class="ti ti-send fs-5"></i>
                                                            </a>
                                                        @endif

                                                        @php
                                                            $current_index_row = array_search($level_user, $roles_row);
                                                            $next_role_row = ($current_index_row !== false && isset($roles_row[$current_index_row + 1])) ? $roles_row[$current_index_row + 1] : '';
                                                            
                                                            $is_super_admin = auth()->user()->hasRole('super admin');
                                                            $is_last_role = !empty($roles_row) && $level_user == end($roles_row);

                                                            $can_cancel = ($d->status == '0' && !empty($next_role_row) && $d->posisi_ajuan == $next_role_row) 
                                                                || ($d->status == '1' && $is_last_role) 
                                                                || ($d->status == '2' && $d->posisi_ajuan == $level_user)
                                                                || ($is_super_admin && ($d->status != '0' || (array_search($d->posisi_ajuan, $roles_row) > 0)));
                                                        @endphp

                                                        @if ($can_cancel)
                                                            <form method="POST" name="deleteform" class="deleteform d-inline" action="{{ route('ajuanlimit.cancel', Crypt::encrypt($d->no_pengajuan)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="cancel-confirm bg-transparent border-0 text-danger p-0" data-bs-toggle="tooltip" title="Cancel">
                                                                    <i class="ti ti-square-rounded-x fs-5"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endcan

                                                    @can('ajuanlimit.show')
                                                        <a href="#" class="btnShow text-info" no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}" data-bs-toggle="tooltip" title="Detail">
                                                            <i class="ti ti-file-description fs-5"></i>
                                                        </a>
                                                        <a href="{{ route('ajuanlimit.cetak', Crypt::encrypt($d->no_pengajuan)) }}" class="text-secondary" target="_blank" data-bs-toggle="tooltip" title="Cetak">
                                                            <i class="ti ti-printer fs-5"></i>
                                                        </a>
                                                    @endcan

                                                    @can('ajuanlimit.delete')
                                                        @if (($d->id_user == auth()->user()->id && $d->status == '0') || auth()->user()->hasRole('super admin'))
                                                            <form method="POST" name="deleteform" class="deleteform d-inline" action="{{ route('ajuanlimit.delete', Crypt::encrypt($d->no_pengajuan)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0" data-bs-toggle="tooltip" title="Hapus">
                                                                    <i class="ti ti-trash fs-5"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endcan

                                                    @if ($level_user == 'super admin')
                                                        <a href="#" class="btnEdit text-warning" no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}" data-bs-toggle="tooltip" title="Edit Posisi">
                                                            <i class="ti ti-edit fs-5"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer py-2">
                            <div style="float: right;">
                                {{ $ajuanlimit->links() }}
                            </div>
                        </div>
                    </div>
    </div>
</div>

<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="" />
<x-modal-form id="modalAdjust" size="" show="loadmodalAdjust" title="" />
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
            $("#modal").find(".modal-title").text("Buan Ajuan Limit Kredit");
            $("#loadmodal").load(`/ajuanlimit/create`);
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            loading();
            const no_pengajuan = $(this).attr("no_pengajuan");
            $('#modalApprove').modal("show");
            $("#loadmodalApprove").load(`/ajuanlimit/${no_pengajuan}/approve`);
            $("#modalApprove").find(".modal-title").text("Persetujuan Ajuan Limit Kredit");
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            loading();
            const no_pengajuan = $(this).attr("no_pengajuan");
            $('#modalApprove').modal("show");
            $("#loadmodalApprove").load(`/ajuanlimit/${no_pengajuan}/show`);
            $("#modalApprove").find(".modal-title").text("Data Ajuan Limit Kredit");
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            const no_pengajuan = $(this).attr("no_pengajuan");
            $('#modal').modal("show");
            $("#loadmodal").load(`/ajuanlimit/${no_pengajuan}/edit`);
            $("#modal").find(".modal-title").text("Edit Ajuan Limit Kredit");
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

        $(".adjustlimit").click(function(e) {
            e.preventDefault();
            const no_pengajuan = $(this).attr("no_pengajuan");
            $("#modalAdjust").modal("show");
            $("#modalAdjust").find(".modal-title").text("Penyesuaian Limit");
            $("#loadmodalAdjust").load(`/ajuanlimit/${no_pengajuan}/adjust`);
        });
    });
</script>
@endpush
