@extends('layouts.app')
@section('titlepage', 'Pencairan Program Marketing 2026')

@section('content')
@section('navigasi')
    <span>Pencairan Program Marketing 2026</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_marketing_2026_main')
            @include('layouts.navigation_program_marketing_2026')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('pencairanprogramikatan2026.create')
                        <a href="{{ route('pencairanprogramikatan2026.create') }}" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Buat Pencairan</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('pencairanprogramikatan2026.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ Request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved_om" {{ Request('status') == 'approved_om' ? 'selected' : '' }}>Disetujui OM</option>
                                        <option value="approved_rsm" {{ Request('status') == 'approved_rsm' ? 'selected' : '' }}>Disetujui RSM</option>
                                        <option value="approved_gm" {{ Request('status') == 'approved_gm' ? 'selected' : '' }}>Disetujui GM</option>
                                        <option value="approved_direktur" {{ Request('status') == 'approved_direktur' ? 'selected' : '' }}>Disetujui Direktur</option>
                                        <option value="rejected" {{ Request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari
                                                Data</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($pencairanprogramikatan as $d)
                            <div class="col-12 mb-3">
                                <div class="card card-hover shadow-sm border" style="transition: all 0.2s ease-in-out;">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center gx-3">
                                            {{-- Kode Pencairan & Tanggal --}}
                                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 mb-2 mb-md-0">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-primary fs-5">#{{ $d->kode_pencairan }}</span>
                                                    <div class="d-flex align-items-center text-muted">
                                                        <i class="ti ti-calendar me-1" style="font-size: 0.8rem;"></i>
                                                        <small class="text-nowrap">{{ formatIndo($d->tanggal) }}</small>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Program & Cabang --}}
                                            <div class="col-xl-3 col-lg-4 col-md-5 col-sm-12 mb-2 mb-md-0">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-dark text-truncate" style="font-size: 1rem;" title="{{ $d->nama_program }}">{{ $d->nama_program }}</span>
                                                    <small class="text-secondary text-uppercase fw-semibold">{{ $d->nama_cabang }}</small>
                                                    <div class="d-flex align-items-center mt-1 text-muted d-md-none">
                                                        <i class="ti ti-calendar-event me-1" style="font-size: 0.8rem;"></i>
                                                        <small>{{ $d->nama_bulan }} {{ $d->tahun }}</small>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Periode (Bulan/Tahun) --}}
                                            <div class="col-xl-2 col-lg-2 d-none d-lg-block mb-2 mb-md-0">
                                                <div class="d-flex align-items-center text-secondary bg-label-secondary px-2 py-1 rounded" style="width: fit-content;">
                                                    <i class="ti ti-calendar-event me-2"></i>
                                                    <small class="fw-bold text-nowrap">
                                                       {{ $list_bulan[$d->bulan - 1]['nama_bulan'] ?? '' }} {{ $d->tahun }}
                                                    </small>
                                                </div>
                                            </div>
                                             {{-- Status --}}
                                             <div class="col-xl-3 col-lg-2 col-md-4 col-sm-12 mb-2 mb-md-0">
                                                <div class="d-flex justify-content-start align-items-center gap-2">
                                                     @if ($d->status == '0')
                                                        <span class="badge bg-label-warning text-warning d-flex align-items-center gap-1 px-2 py-1"><i class="ti ti-hourglass-empty fs-6"></i> Pending</span>
                                                    @elseif ($d->status == '1')
                                                        <span class="badge bg-success d-flex align-items-center gap-1 px-2 py-1"><i class="ti ti-check fs-6"></i> Disetujui</span>
                                                    @elseif($d->status == '2')
                                                        <span class="badge bg-danger d-flex align-items-center gap-1 px-2 py-1"><i class="ti ti-ban fs-6"></i> Ditolak</span>
                                                    @endif
                                                </div>
                                             </div>


                                            {{-- Actions --}}
                                            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 mt-2 mt-lg-0">
                                                <div class="d-flex justify-content-lg-end justify-content-start gap-1">
                                                    @can('pencairanprogramikatan2026.delete')
                                                       @if ($d->status == '0')
                                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                                action="{{ route('pencairanprogramikatan2026.delete', Crypt::encrypt($d->kode_pencairan)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-icon btn-label-danger delete-confirm" data-bs-toggle="tooltip" title="Hapus">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            </form>
                                                       @endif
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-12 mt-4">
                             {{ $pencairanprogramikatan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function() {
        const select2Kodecabang = $(".select2Kodecabang");
         if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
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
