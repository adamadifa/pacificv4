@extends('layouts.app')
@section('titlepage', 'Kontrak Kerja')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Kontrak Kerja</h4>
            <small class="text-muted">Mengelola data kontrak kerja karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>HRD</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-file-text me-1"></i>Kontrak Kerja</li>
            </ol>
        </nav>
    </div>
@endsection

    <style>
        .contract-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            border-radius: 12px !important;
            overflow: hidden;
            position: relative;
        }

        .contract-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
            border-color: #002e65 !important;
        }

        .contract-info-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            margin-bottom: 2px;
            display: block;
        }

        .contract-info-value {
            font-weight: 600;
            color: #333;
            font-size: 0.85rem;
        }

        .contract-status-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.7rem;
        }

        .border-left-primary {
            border-left: 5px solid #002e65 !important;
        }

        .border-left-danger {
            border-left: 5px solid #ea5455 !important;
        }

        .card-action-btns {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 5px;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .contract-card:hover .card-action-btns {
            opacity: 1;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }
    </style>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-bold"><i class="ti ti-file-text me-2 text-primary"></i>Riwayat Kontrak Kerja</h5>
                @can('kontrakkerja.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="ti ti-plus me-1"></i> Buat Kontrak</a>
                @endcan
            </div>

            {{-- Filter Section --}}
            <form action="{{ route('kontrakkerja.index') }}" class="mb-2">
                <div class="row g-2">
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" />
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" />
                    </div>
                    <div class="col-lg-5 col-md-4 col-sm-12">
                        <x-input-with-icon label="Nama Karyawan" value="{{ Request('nama_karyawan_search') }}" name="nama_karyawan_search" icon="ti ti-user" />
                    </div>
                    <div class="col-lg-1 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row g-3">
                @foreach ($kontrak as $d)
                    @php
                        $lamabulan = calculateMonthsKontrak($d->dari, $d->sampai);
                        $is_active = $d->status_kontrak === '1';
                        $border_class = $is_active ? 'border-left-primary' : 'border-left-danger';
                        $status_label = $is_active ? 'Aktif' : 'Tidak Aktif';
                        $status_class = $is_active ? 'bg-label-primary' : 'bg-label-danger';
                    @endphp
                    <div class="col-12">
                        <div class="card contract-card bg-white h-100 {{ $border_class }}">
                            <div class="card-body p-3">
                                <div class="card-action-btns">
                                    @can('kontrakkerja.edit')
                                        <a href="#" class="action-btn text-success btnEdit" no_kontrak="{{ Crypt::encrypt($d->no_kontrak) }}" title="Edit">
                                            <i class="ti ti-edit fs-5"></i>
                                        </a>
                                    @endcan
                                    @can('kontrakkerja.show')
                                        <a href="{{ route('kontrakkerja.cetak', Crypt::encrypt($d->no_kontrak)) }}" class="action-btn text-primary" target="_blank" title="Cetak">
                                            <i class="ti ti-printer fs-5"></i>
                                        </a>
                                    @endcan
                                    @can('kontrakkerja.delete')
                                        <form method="POST" name="deleteform" class="deleteform d-inline" action="{{ route('kontrakkerja.delete', Crypt::encrypt($d->no_kontrak)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a href="#" class="action-btn text-danger delete-confirm" title="Hapus">
                                                <i class="ti ti-trash fs-5"></i>
                                            </a>
                                        </form>
                                    @endcan
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-lg-3 col-md-12 mb-lg-0 mb-3 border-end">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md me-3">
                                                @if (!empty($d->foto) && Storage::disk('public')->exists('/karyawan/' . $d->foto))
                                                    <img src="{{ getfotoKaryawan($d->foto) }}" alt="Avatar" class="rounded-circle">
                                                @else
                                                    <span class="avatar-initial rounded-circle bg-label-secondary">
                                                        <i class="ti ti-user fs-4"></i>
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ textUpperCase($d->nama_karyawan) }}</h6>
                                                <span class="text-muted small">{{ $d->nik }}</span>
                                                <div class="mt-1">
                                                    <span class="badge {{ $status_class }} contract-status-badge p-1 px-2" style="font-size: 0.65rem">
                                                        {{ $status_label }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-4 mb-md-0 mb-3 border-end">
                                        <div class="ps-lg-3">
                                            <span class="contract-info-label">No. Kontrak & Tanggal</span>
                                            <div class="contract-info-value text-primary">{{ $d->no_kontrak }}</div>
                                            <div class="text-muted small">{{ formatIndo($d->tanggal) }}</div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-4 mb-md-0 mb-3 border-end">
                                        <div class="ps-lg-3">
                                            <span class="contract-info-label">Jabatan & Penempatan</span>
                                            <div class="contract-info-value">{{ !empty($d->alias_jabatan) ? $d->alias_jabatan : $d->nama_jabatan }}</div>
                                            <div class="text-muted small">{{ $d->kode_dept }} | {{ textUpperCase($d->nama_cabang) }}</div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-4">
                                        <div class="ps-lg-3">
                                            <span class="contract-info-label">Masa Kontrak</span>
                                            <div class="contract-info-value">
                                                {{ formatIndo($d->dari) }} -
                                                @if ($d->masa_kontrak == 'KT' || $d->dari == $d->sampai)
                                                    <span class="text-danger"><i class="ti ti-infinity"></i></span>
                                                @else
                                                    {{ formatIndo($d->sampai) }}
                                                @endif
                                            </div>
                                            <div class="text-muted small">{{ $lamabulan }} Bulan</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                <div style="float: right;">
                    {{ $kontrak->links() }}
                </div>
            </div>
        </div>
    </div>
    <x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection

@push('myscript')
<script>
    $(function() {
        const loading = () => {
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
            $(".modal-title").text("Buat Kontrak");
            $("#loadmodal").load(`/kontrakkerja/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            var no_kontrak = $(this).attr("no_kontrak");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Kontrak");
            $("#loadmodal").load(`/kontrakkerja/${no_kontrak}/edit`);
        });
    });
</script>
@endpush
