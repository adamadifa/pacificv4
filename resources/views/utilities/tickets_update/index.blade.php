@extends('layouts.app')
@section('titlepage', 'Ticket Perubahan Data')

@section('content')
@section('navigasi')
    <span>Ticket Perubahan Data</span>
@endsection

<style>
    .ticket-header-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 16px;
        padding: 24px 28px;
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.3);
    }

    .stat-card-modern {
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 14px;
        background: #ffffff;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }

    .stat-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .table-ticket-modern {
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .table-ticket-modern tbody tr {
        background: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .table-ticket-modern tbody tr:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .table-ticket-modern td {
        padding: 16px 18px;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-ticket-modern td:first-child {
        border-left: 1px solid #f1f5f9;
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .table-ticket-modern td:last-child {
        border-right: 1px solid #f1f5f9;
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .avatar-user-badge {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .btn-action-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .btn-action-circle:hover {
        transform: scale(1.1);
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_ticket')
            <div class="tab-content border-0 p-0 bg-transparent">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">

                    {{-- Banner Header --}}
                    <div class="ticket-header-banner mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <span class="badge bg-label-info text-info px-3 py-1 rounded-pill fw-bold mb-2">
                                    <i class="ti ti-file-diff me-1"></i>Data Modification Request
                                </span>
                                <h3 class="fw-bold text-white mb-1">Ticket Perubahan Data</h3>
                                <p class="text-white-50 mb-0">Kelola ajuan koreksi dan perubahan data Penjualan,
                                    Pembayaran, Retur, DPB, & Mutasi Persediaan.</p>
                            </div>
                            <div>
                                <button class="btn btn-primary btn-lg shadow-lg fw-bold px-4 py-3 rounded-3"
                                    id="btnCreate">
                                    <i class="ti ti-plus me-2 fs-4"></i>Buat Ajuan Perubahan Data
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- KPI Stat Cards --}}
                    <div class="row g-3 mb-4">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="card stat-card-modern p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="text-muted small fw-bold text-uppercase">Total Ajuan</span>
                                        <h2 class="fw-bold text-dark mb-0 mt-1">
                                            {{ number_format($stats['total'] ?? 0) }}</h2>
                                    </div>
                                    <div class="stat-icon-box bg-label-primary text-primary">
                                        <i class="ti ti-files"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="card stat-card-modern p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="text-muted small fw-bold text-uppercase">Menunggu Approval</span>
                                        <h2 class="fw-bold text-warning mb-0 mt-1">
                                            {{ number_format($stats['pending'] ?? 0) }}</h2>
                                    </div>
                                    <div class="stat-icon-box bg-label-warning text-warning">
                                        <i class="ti ti-hourglass-low"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="card stat-card-modern p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="text-muted small fw-bold text-uppercase">Disetujui / Selesai</span>
                                        <h2 class="fw-bold text-success mb-0 mt-1">
                                            {{ number_format($stats['selesai'] ?? 0) }}</h2>
                                    </div>
                                    <div class="stat-icon-box bg-label-success text-success">
                                        <i class="ti ti-circle-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Filter Panel --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-3">
                            <form action="{{ route('ticketupdate.index') }}" method="GET">
                                <div class="row g-2 align-items-end">
                                    @hasanyrole($roles_show_cabang)
                                        <div class="col-lg-5 col-md-6">
                                            <label class="form-label small fw-bold text-uppercase text-muted"><i
                                                    class="ti ti-building me-1"></i>Cabang</label>
                                            <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang"
                                                key="kode_cabang" textShow="nama_cabang" upperCase="true"
                                                selected="{{ Request('kode_cabang_search') }}"
                                                select2="select2Kodecabangsearch" />
                                        </div>
                                    @endrole

                                    <div class="col-lg-5 col-md-6">
                                        <label class="form-label small fw-bold text-uppercase text-muted"><i
                                                class="ti ti-filter me-1"></i>Status Approval</label>
                                        <select name="status_search" id="status_search" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="pending"
                                                {{ Request('status_search') == 'pending' ? 'selected' : '' }}>Belum
                                                Selesai (Pending)</option>
                                            <option value="selesai"
                                                {{ Request('status_search') == 'selesai' ? 'selected' : '' }}>Selesai
                                                (Approved)</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-2 col-md-12">
                                        <button class="btn btn-primary w-100 fw-bold" type="submit">
                                            <i class="ti ti-search me-1"></i>Filter
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Data Table --}}
                    <div class="table-responsive">
                        <table class="table table-ticket-modern align-middle mb-0">
                            <thead>
                                <tr class="text-uppercase text-muted small fw-bold">
                                    <th style="width: 12%">No. Ticket</th>
                                    <th style="width: 15%">Kategori & No. Bukti</th>
                                    <th>Keterangan / Alasan Perubahan</th>
                                    <th style="width: 16%">Pengaju (User)</th>
                                    <th style="width: 6%" class="text-center">Cabang</th>
                                    <th style="width: 16%" class="text-center">Approval & Status</th>
                                    <th style="width: 12%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $kategori = [
                                        '1' => 'Penjualan',
                                        '2' => 'Pembayaran',
                                        '3' => 'Retur',
                                        '4' => 'DPB',
                                        '5' => 'Mutasi Persediaan',
                                    ];
                                @endphp
                                @forelse ($ticket as $d)
                                    <tr>
                                        <td>
                                            <span
                                                class="badge bg-primary-subtle text-primary border border-primary font-monospace fs-6 px-2 py-1">
                                                {{ $d->kode_pengajuan }}
                                            </span>
                                            <div class="small text-muted mt-1">
                                                <i
                                                    class="ti ti-calendar me-1"></i>{{ date('d/m/Y', strtotime($d->tanggal)) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-label-info text-info fw-bold mb-1 d-inline-block text-truncate">
                                                <i class="ti ti-tag me-1"></i>{{ $kategori[$d->kategori] ?? 'Umum' }}
                                            </span>
                                            <div class="small font-monospace text-dark fw-bold">
                                                <i
                                                    class="ti ti-file-description me-1 text-muted"></i>{{ $d->no_bukti }}
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-dark small mb-1 fw-medium">{{ $d->keterangan }}</p>
                                            @if (!empty($d->link))
                                                <a href="{{ url($d->link) }}" target="_blank"
                                                    class="badge bg-info-subtle text-info border border-info text-decoration-none mt-1">
                                                    <i class="ti ti-paperclip me-1"></i>Lampiran Bukti
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-user-badge">
                                                    {{ strtoupper(substr($d->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong
                                                        class="text-dark small d-block">{{ formatName2($d->name ?? '-') }}</strong>
                                                    <span
                                                        class="badge bg-light text-dark border px-2 py-0 fs-7">{{ $d->kode_cabang }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-dark px-2 py-1 fs-7">{{ $d->kode_cabang }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="mb-1">
                                                @if ($d->status == '1')
                                                    <span class="badge bg-success text-white fw-bold"><i
                                                            class="ti ti-check me-1"></i>Selesai</span>
                                                @elseif($d->status == '2')
                                                    <span class="badge bg-danger text-white fw-bold"><i
                                                            class="ti ti-x me-1"></i>Ditolak</span>
                                                @else
                                                    <span class="badge bg-warning text-dark fw-bold"><i
                                                            class="ti ti-clock me-1"></i>Menunggu</span>
                                                @endif
                                            </div>
                                            <div>
                                                @if ($d->approval)
                                                    <span
                                                        class="badge bg-label-primary text-primary fw-bold">Disetujui:
                                                        {{ formatName2($d->approval) }}</span>
                                                @else
                                                    <span class="badge bg-label-warning text-dark fw-bold">Menunggu
                                                        Approval GM</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center gap-1">
                                                {{-- Edit Button --}}
                                                @if ($d->gm == null)
                                                    <button class="btn btn-outline-warning btn-action-circle btnEdit"
                                                        kode_pengajuan="{{ $d->kode_pengajuan }}"
                                                        title="Edit Ticket">
                                                        <i class="ti ti-edit fs-5"></i>
                                                    </button>
                                                @endif

                                                {{-- Approval Button --}}
                                                @can('ticket.approve')
                                                    @if (in_array($level_user, ['gm administrasi', 'regional operation manager', 'super admin']))
                                                        @if ($d->status == '0')
                                                            <button
                                                                class="btn btn-success btn-action-circle shadow-sm btnApprove"
                                                                kode_pengajuan="{{ $d->kode_pengajuan }}"
                                                                title="Approve Ticket">
                                                                <i class="ti ti-check fs-5"></i>
                                                            </button>
                                                        @else
                                                            <form method="POST" name="deleteform"
                                                                class="deleteform d-inline"
                                                                action="{{ route('ticketupdate.cancel', Crypt::encrypt($d->kode_pengajuan)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-outline-danger btn-action-circle cancel-confirm"
                                                                    title="Batalkan Approval">
                                                                    <i class="ti ti-square-x fs-5"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                @endcan

                                                {{-- Delete Button --}}
                                                @if ($d->gm == null)
                                                    <form method="POST" name="deleteform"
                                                        class="deleteform d-inline"
                                                        action="{{ route('ticketupdate.delete', Crypt::encrypt($d->kode_pengajuan)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-outline-danger btn-action-circle delete-confirm"
                                                            title="Hapus Ticket">
                                                            <i class="ti ti-trash fs-5"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="py-4">
                                                <i
                                                    class="ti ti-file-off text-muted opacity-50 display-4 d-block mb-3"></i>
                                                <h5 class="fw-bold text-secondary">Belum Ada Ticket Perubahan Data</h5>
                                                <p class="text-muted small">Tidak ada data tiket perubahan data yang
                                                    cocok dengan kriteria filter.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="small text-muted">
                            Menampilkan data ticket perubahan data terbaru
                        </div>
                        <div>
                            {{ $ticket->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Ticket Modal --}}
<x-modal-form id="mdlCreate" size="modal-lg" show="loadCreate" title="Ticket Perubahan Data" />

@endsection

@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $('#mdlCreate').modal("show");
            $('#mdlCreate').find('.modal-title').text('Buat Ticket Perubahan Data');
            $("#loadCreate").load('/ticketupdate/create');
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const kode_pengajuan = $(this).attr('kode_pengajuan');
            $('#mdlCreate').modal("show");
            $('#mdlCreate').find('.modal-title').text('Edit Ticket Perubahan Data');
            $("#loadCreate").load(`/ticketupdate/${kode_pengajuan}/edit`);
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            const kode_pengajuan = $(this).attr('kode_pengajuan');
            $('#mdlCreate').modal("show");
            $('#mdlCreate').find('.modal-title').text('Approve Ticket Perubahan Data');
            $("#loadCreate").load(`/ticketupdate/${kode_pengajuan}/approve`);
        });

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
    });
</script>
@endpush
