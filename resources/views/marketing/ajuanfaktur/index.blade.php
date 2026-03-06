@extends('layouts.app')
@section('titlepage', 'Ajuan Faktur Kredit')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Ajuan Faktur Kredit</h4>
            <small class="text-muted">Kelola data transaksi pengajuan faktur kredit pelanggan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Marketing</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-file-description me-1"></i>Ajuan Faktur Kredit</li>
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
        <form action="{{ route('ajuanfaktur.index') }}" id="formSearch">
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
                            <div class="col-lg-2 col-md-4 col-sm-12">
                                <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                    select2="select2Kodecabangsearch" hideLabel="true" />
                            </div>
                        @endhasanyrole
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <x-input-with-icon label="Nama Pelanggan" value="{{ Request('nama_pelanggan') }}" name="nama_pelanggan"
                                icon="ti ti-user" hideLabel="true" />
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <div class="form-group">
                                <select name="posisi_ajuan" id="posisi_ajuan" class="form-select">
                                    <option value="">Posisi Ajuan</option>
                                    @foreach ($roles_approve_ajuanfakturkredit as $role)
                                        <option value="{{ $role }}" {{ Request('posisi_ajuan') == $role ? 'selected' : '' }}>
                                            {{ textUpperCase($role) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-2 col-sm-12">
                            <div class="form-group">
                                <select name="status" id="status" class="form-select">
                                    <option value="">Status</option>
                                    <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="2" {{ Request('status') === '2' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-2 col-sm-12">
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-file-description me-2"></i>Data Ajuan Faktur</h6>
                    <div class="d-flex gap-2">
                        @can('ajuanfaktur.config')
                            <a href="{{ route('ajuanfakturconfig.index') }}" class="btn btn-outline-primary btn-sm shadow-sm"><i class="ti ti-settings me-1"></i> Konfigurasi</a>
                        @endcan
                        @can('ajuanfaktur.create')
                            <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Ajukan Faktur</a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">NO. PENGAJUAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white" style="background-color: #002e65 !important; width: 150px">PELANGGAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">LIMIT</th>
                            <th class="text-white" style="background-color: #002e65 !important;">JML FAKTUR</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">COD</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 200px">KETERANGAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">POSISI</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">STATUS</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
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
                                <td><span class="fw-bold text-primary">{{ $d->no_pengajuan }}</span></td>
                                <td>{{ formatIndo($d->tanggal) }}</td>
                                <td style="width: 150px; white-space: normal !important;">{{ $d->nama_pelanggan }}</td>
                                <td class="text-end fw-bold">{{ formatAngka($d->limit_pelanggan) }}</td>
                                <td class="text-center">{{ formatAngka($d->jumlah_faktur) }}</td>
                                <td class="text-center">
                                    @if ($d->siklus_pembayaran == '1')
                                        <i class="ti ti-square-rounded-check text-success fs-4"></i>
                                    @else
                                        <i class="ti ti-square-rounded-x text-danger fs-4"></i>
                                    @endif
                                </td>
                                <td style="width: 200px; white-space: normal !important;">
                                    <small class="text-muted">{{ $d->keterangan }}</small>
                                </td>
                                <td>
                                    @php
                                        // Set Posisi Role text based on $d->posisi_ajuan and $roles_approve_ajuanfakturkredit array mapping dynamically if needed
                                        $text_role = $d->posisi_ajuan ? textUpperCase($d->posisi_ajuan) : '-';
                                        $color = 'bg-secondary';
                                        if ($d->posisi_ajuan == 'sales marketing manager') {
                                            $color = 'bg-warning';
                                            $text_role = 'SMM';
                                        } elseif ($d->posisi_ajuan == 'regional sales manager') {
                                            $color = 'bg-info';
                                            $text_role = 'RSM';
                                        } elseif ($d->posisi_ajuan == 'gm marketing') {
                                            $color = 'bg-primary';
                                            $text_role = 'GM';
                                        } elseif ($d->posisi_ajuan == 'direktur') {
                                            $color = 'bg-success';
                                            $text_role = 'DIR';
                                        }

                                        if (empty($d->posisi_ajuan) && $d->status == 1) {
                                            $color = 'bg-success';
                                            $text_role = '<i class="ti ti-square-rounded-check text-success fs-4"></i>';
                                        } else if (empty($d->posisi_ajuan) && $d->status == 0) {
                                            $color = 'bg-secondary';
                                            $text_role = 'BELUM DI KONFIGURASI';
                                        }
                                    @endphp
                                    <span class="badge {{ $color }} shadow-sm">
                                        @if (empty($d->posisi_ajuan) && $d->status == 1)
                                            DIR
                                        @else
                                            {{ $text_role }}
                                        @endif
                                    </span>
                                    @if ($d->status == 1)
                                        <i class="ti ti-checks text-success ms-1"></i>
                                    @endif
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
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('ajuanfaktur.approve')
                                            @php
                                                $can_approve = false;
                                                $can_cancel = false;

                                                if ($d->status == '0') {
                                                    if ($level_user == 'operation manager') {
                                                        if ($d->posisi_ajuan == 'sales marketing manager') {
                                                            $can_approve = true;
                                                        }
                                                        // OM bisa cancel jika pengajuan sedang ada di RSM (posisi saat ini) DAN role sebelumnya (yang approve adalah SMM)
                                                        $currentIndex = array_search('sales marketing manager', $roles_approve_ajuanfakturkredit);
                                                        if ($currentIndex !== false && $currentIndex + 1 < count($roles_approve_ajuanfakturkredit)) {
                                                            $nextRoleFromSmm = $roles_approve_ajuanfakturkredit[$currentIndex + 1];
                                                            if ($d->posisi_ajuan == $nextRoleFromSmm) {
                                                                $can_cancel = true;
                                                            }
                                                        }
                                                    } else {
                                                        if ($d->posisi_ajuan == $level_user) {
                                                            $can_approve = true;
                                                        }

                                                        $currentIndex = array_search($level_user, $roles_approve_ajuanfakturkredit);
                                                        if ($currentIndex !== false && $currentIndex + 1 < count($roles_approve_ajuanfakturkredit)) {
                                                            $nextRole = $roles_approve_ajuanfakturkredit[$currentIndex + 1];
                                                            if ($d->posisi_ajuan == $nextRole || $d->posisi_ajuan == $level_user) { // Bisa cancel jika masih di posisinya atau sudah pindah ke next role status pending
                                                                $can_cancel = true;
                                                            }
                                                        } else if ($currentIndex !== false && $currentIndex == count($roles_approve_ajuanfakturkredit) - 1) {
                                                            // For the LAST role, logic needs to be careful because if status is 0, it means it's AT the last role pending approval.
                                                            if ($d->posisi_ajuan == $level_user) {
                                                                 $can_cancel = true; // Wait, if it's pending at the last role, they can cancel it back? Or just decline?
                                                                 // Usually if they haven't approved, they don't 'cancel'. The cancel is for backtracking.
                                                                 // Let's only allow cancel if they ALREADY approved it (status=1). Wait, the check above is for status=='0'.
                                                                 // If they are the last role and status is 0, they should only 'approve'.
                                                                 $can_cancel = false; 
                                                            }
                                                        }
                                                    }
                                                } else if ($d->status == '1') {
                                                    if ($level_user == 'operation manager') {
                                                        // OM bisa cancel jika status 1 asal role terakhir adalah SMM
                                                        $lastRole = end($roles_approve_ajuanfakturkredit);
                                                        if ($lastRole == 'sales marketing manager') {
                                                            $can_cancel = true; // SMM is the last role and it's approved
                                                        }
                                                    } else {
                                                        $lastRole = end($roles_approve_ajuanfakturkredit);
                                                        if ($level_user == $lastRole) {
                                                            $can_cancel = true; // Last role can cancel their final approval
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @if ($can_approve)
                                                <a href="#" class="btnApprove text-info"
                                                    no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}" data-bs-toggle="tooltip"
                                                    title="Approve">
                                                    <i class="ti ti-send fs-5"></i>
                                                </a>
                                            @endif

                                            @if ($can_cancel)
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('ajuanfaktur.cancel', Crypt::encrypt($d->no_pengajuan)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="cancel-confirm bg-transparent border-0 text-danger p-0"
                                                        data-bs-toggle="tooltip" title="Cancel">
                                                        <i class="ti ti-square-rounded-x fs-5"></i>
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
                    {{ $ajuanfaktur->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" show="loadmodal" title="" />
<x-modal-form id="modalApprove" size="modal-xl" show="loadmodalApprove" title="" />
<x-modal-form id="modalEdit" size="modal-sm" show="loadmodalEdit" title="" />
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

        function loading(target = "#loadmodal") {
            $(target).html(`<div class="sk-wave sk-primary" style="margin:auto">
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
            $("#modal").find(".modal-title").text("Buat Ajuan Faktur Kredit");
            $("#loadmodal").load(`/ajuanfaktur/create`);
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            loading("#loadmodalApprove");
            const no_pengajuan = $(this).attr("no_pengajuan");
            $('#modalApprove').modal("show");
            $("#loadmodalApprove").load(`/ajuanfaktur/${no_pengajuan}/approve`);
            $("#modalApprove").find(".modal-title").text("Persetujuan Ajuan Faktur Kredit");
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading("#loadmodalEdit");
            const no_pengajuan = $(this).attr("no_pengajuan");
            $('#modalEdit').modal("show");
            $("#loadmodalEdit").load(`/ajuanfaktur/${no_pengajuan}/edit`);
            $("#modalEdit").find(".modal-title").text("Edit Posisi Ajuan");
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
