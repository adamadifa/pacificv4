@extends('layouts.app')
@section('titlepage', 'Monitoring Program Ikatan 2026')

@section('content')
@section('navigasi')
    <span>Monitoring Program Ikatan 2026</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_marketing_2026_main')
            @include('layouts.navigation_program_marketing_2026')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('programikatan2026.monitoring') }}">
                                @hasanyrole($roles_access_all_cabang)
                                    <div class="form-group mb-3">
                                        <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                                            <option value="">Semua Cabang</option>
                                            @foreach ($cabang as $d)
                                                <option {{ Request('kode_cabang') == $d->kode_cabang ? 'selected' : '' }} value="{{ $d->kode_cabang }}">
                                                    {{ textUpperCase($d->nama_cabang) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endrole
                                <x-select label="Semua Program" name="kode_program" :data="$programikatan" key="kode_program" textShow="nama_program"
                                    select2="select2Kodeprogram" upperCase="true" selected="{{ Request('kode_program') }}" hideLabel="true" />
                                <div class="form-group mb-3">
                                   <input type="text" class="form-control" name="nama_pelanggan" placeholder="Nama Pelanggan" value="{{ Request('nama_pelanggan') }}">
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" hideLabel="true" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" hideLabel="true" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group mb-3">
                                            <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari Data</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group mb-3">
                                            <button type="submit" name="export" value="true" class="btn btn-success w-100"><i class="ti ti-download me-1"></i> Cetak Excel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 mt-2 pb-2 border-bottom">
                        <h5 class="mb-0 fw-bold"><i class="ti ti-list-details me-1 text-primary"></i>Daftar Realisasi Program</h5>
                        <div class="btn-group shadow-sm" role="group" aria-label="View Toggle">
                            <button type="button" class="btn btn-outline-primary" id="btn-card-view">
                                <i class="ti ti-layout-grid me-1"></i> Card
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="btn-table-view">
                                <i class="ti ti-table me-1"></i> Table
                            </button>
                        </div>
                    </div>

                    {{-- CARD VIEW CONTAINER --}}
                    <div id="card-view-container">
                        <div class="row">
                            @forelse ($monitoring_data as $d)
                            @php
                                $total_target = $d->total_target ?? 0;
                                $avg_target = $d->avg_target ?? 0;
                                $persentase = $total_target > 0 ? ($d->realisasi / $total_target) * 100 : 0;
                                
                                $color_reward = '';
                                if ($d->realisasi >= $total_target) {
                                    $color_reward = 'bg-success text-white';
                                } elseif ($d->realisasi >= $avg_target) {
                                    $color_reward = 'bg-primary text-white';
                                } elseif ($d->realisasi >= ($avg_target - ($avg_target * 0.10))) {
                                    $color_reward = 'bg-warning text-dark';
                                } else {
                                    $color_reward = 'bg-danger text-white';
                                }
                            @endphp
                            <div class="col-12 mb-3">
                                <div class="card card-hover shadow-sm border {{ $color_reward }}" style="transition: all 0.2s ease-in-out;">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center gx-3">
                                             {{-- ID & Tanggal (Col-2) --}}
                                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 mb-2 mb-md-0">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold {{ $color_reward ? 'text-white' : 'text-primary' }} fs-5">#{{ $d->no_pengajuan }}</span>
                                                    <div class="d-flex align-items-center {{ $color_reward ? 'text-white' : 'text-muted' }}">
                                                        <i class="ti ti-calendar me-1" style="font-size: 0.8rem;"></i>
                                                        <small class="text-nowrap">{{ formatIndo($d->tanggal) }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                
                                             {{-- Program, Cabang, Salesman, Pelanggan (Col-4) --}}
                                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 mb-2 mb-md-0">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold {{ $color_reward ? 'text-white' : 'text-dark' }} text-truncate" style="font-size: 1rem;" title="{{ $d->nama_program }}">{{ $d->nama_program }}</span>
                                                    <small class="{{ $color_reward ? 'text-white' : 'text-secondary' }} text-uppercase fw-semibold">{{ $d->nama_cabang }} - {{ $d->nama_salesman }}</small>
                                                    <span class="{{ $color_reward ? 'text-white' : 'text-dark' }} fw-bold mt-1">{{ $d->nama_pelanggan }}</span>
                                                    <div class="d-flex align-items-center mt-1 {{ $color_reward ? 'text-white' : 'text-muted' }}">
                                                         <i class="ti ti-clock me-1" style="font-size: 0.8rem;"></i>
                                                         <small>{{ date('M Y', strtotime($d->periode_dari)) }} - {{ date('M Y', strtotime($d->periode_sampai)) }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                
                                            {{-- Target vs Realisasi (Col-6) --}}
                                            <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 mt-2 mt-lg-0">
                                                <div class="row mb-2">
                                                     <div class="col-3 text-center">
                                                        <small class="d-block {{ $color_reward ? 'text-white' : 'text-muted' }}">Rata-rata</small>
                                                        <span class="fw-bold fs-5 {{ $color_reward ? 'text-white' : '' }}">{{ formatAngka($avg_target) }}</span>
                                                     </div>
                                                     <div class="col-3 text-center">
                                                        <small class="d-block {{ $color_reward ? 'text-white' : 'text-muted' }}">Target</small>
                                                        <span class="fw-bold fs-5 {{ $color_reward ? 'text-white' : '' }}">{{ formatAngka($total_target) }}</span>
                                                     </div>
                                                     <div class="col-3 text-center">
                                                        <small class="d-block {{ $color_reward ? 'text-white' : 'text-muted' }}">Realisasi</small>
                                                        <span class="fw-bold fs-5 btnDetailRealisasi cursor-pointer {{ $color_reward ? 'text-white text-decoration-underline' : 'text-primary text-decoration-underline' }}"
                                                              no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}"
                                                              kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}">
                                                            {{ formatAngka($d->realisasi) }}
                                                        </span>
                                                        @if(($d->realisasi_melebihi_top ?? 0) > 0)
                                                             <small class="d-block {{ $color_reward ? 'text-white' : 'text-danger' }} fw-semibold" style="font-size: 0.75rem;">
                                                                 -{{ formatAngka($d->realisasi_melebihi_top) }}
                                                             </small>
                                                         @endif
                                                     </div>
                                                      <div class="col-3 text-center">
                                                        <small class="d-block {{ $color_reward ? 'text-white' : 'text-muted' }}">%</small>
                                                        @if($persentase >= 100)
                                                            <span class="badge bg-white text-success fs-6">{{ formatAngkaDesimal($persentase) }}%</span>
                                                        @else
                                                            <span class="badge bg-white text-danger fs-6">{{ formatAngkaDesimal($persentase) }}%</span>
                                                        @endif
                                                     </div>
                                                </div>
                                                {{-- Rate & Reward --}}
                                                <div class="row align-items-center mb-2">
                                                     <div class="col-6 text-center border-end">
                                                          <small class="d-block {{ $color_reward ? 'text-white' : 'text-muted' }}" style="font-size: 0.7rem;">Rate</small>
                                                          <span class="fw-bold {{ $color_reward ? 'text-white' : 'text-dark' }}">{{ formatAngka($d->reward_rate) }}</span>
                                                     </div>
                                                     <div class="col-6 text-center">
                                                          <small class="d-block {{ $color_reward ? 'text-white' : 'text-muted' }}" style="font-size: 0.7rem;">Reward</small>
                                                          <span class="fw-bold {{ $color_reward ? 'text-white' : 'text-success' }}">{{ formatAngka($d->calculated_reward_total) }}</span>
                                                     </div>
                                                </div>

                                                @php
                                                    $progress_track = 'rgba(255,255,255,0.3)';
                                                    $progress_bar_bg = 'bg-warning'; // Yellow pops on Green/Blue/Red
                                                    
                                                    if(strpos($color_reward, 'bg-warning') !== false){
                                                        $progress_track = 'rgba(0,0,0,0.15)';
                                                        $progress_bar_bg = 'bg-primary'; // Blue pops on Yellow
                                                    }
                                                @endphp

                                                {{-- Progress Bar --}}
                                                <div class="progress" style="height: 15px; background-color: {{ $progress_track }}; box-shadow: 0 1px 2px rgba(0,0,0,0.1) inset;">
                                                    <div class="progress-bar {{ $progress_bar_bg }} progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $persentase }}%" aria-valuenow="{{ $persentase }}" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="fw-bold text-dark" style="font-size: 10px;">{{ round($persentase, 1) }}%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center py-4 bg-light rounded border">
                                <span class="text-muted">Tidak ada data monitoring.</span>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- TABLE VIEW CONTAINER --}}
                    <div id="table-view-container" class="d-none mt-2">
                        <div class="card shadow-sm border mb-3">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-table me-2"></i>Data Realisasi Program</h6>
                            </div>
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover table-bordered mb-0">
                                    <thead style="background-color: #002e65;">
                                        <tr>
                                            <th class="text-white text-center" style="width: 50px;">No.</th>
                                            <th class="text-white">No. Ajuan</th>
                                            <th class="text-white">Pelanggan</th>
                                            <th class="text-white">Program</th>
                                            <th class="text-white">Cabang & Sales</th>
                                            <th class="text-white text-end">Rata-rata</th>
                                            <th class="text-white text-end">Target</th>
                                            <th class="text-white text-end">Realisasi</th>
                                            <th class="text-white text-end">%</th>
                                            <th class="text-white text-end">Rate</th>
                                            <th class="text-white text-end">Reward</th>
                                            <th class="text-white text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($monitoring_data as $d)
                                            @php
                                                $total_target = $d->total_target ?? 0;
                                                $avg_target = $d->avg_target ?? 0;
                                                $persentase = $total_target > 0 ? ($d->realisasi / $total_target) * 100 : 0;
                                                
                                                $status_badge = '';
                                                if ($d->realisasi >= $total_target) {
                                                    $status_badge = '<span class="badge bg-success">Target Achieved</span>';
                                                } elseif ($d->realisasi >= $avg_target) {
                                                    $status_badge = '<span class="badge bg-primary">Avg Achieved</span>';
                                                } elseif ($d->realisasi >= ($avg_target - ($avg_target * 0.10))) {
                                                    $status_badge = '<span class="badge bg-warning text-dark">Near Avg</span>';
                                                } else {
                                                    $status_badge = '<span class="badge bg-danger">Below Target</span>';
                                                }
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration + ($monitoring_data->currentPage() - 1) * $monitoring_data->perPage() }}</td>
                                                <td><span class="fw-bold">#{{ $d->no_pengajuan }}</span><br><small class="text-muted">{{ formatIndo($d->tanggal) }}</small></td>
                                                <td><span class="fw-bold text-dark">{{ $d->nama_pelanggan }}</span></td>
                                                <td><small class="fw-bold text-truncate d-inline-block" style="max-width: 200px;" title="{{ $d->nama_program }}">{{ $d->nama_program }}</small><br><small class="text-muted">{{ date('M Y', strtotime($d->periode_dari)) }} - {{ date('M Y', strtotime($d->periode_sampai)) }}</small></td>
                                                <td><small class="text-uppercase fw-semibold">{{ $d->nama_cabang }}</small><br><small class="text-secondary">{{ $d->nama_salesman }}</small></td>
                                                <td class="text-end fw-bold">{{ formatAngka($avg_target) }}</td>
                                                <td class="text-end fw-bold">{{ formatAngka($total_target) }}</td>
                                                <td class="text-end">
                                                    <span class="fw-bold btnDetailRealisasi text-primary text-decoration-underline cursor-pointer"
                                                          no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}"
                                                          kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}">
                                                        {{ formatAngka($d->realisasi) }}
                                                    </span>
                                                    @if(($d->realisasi_melebihi_top ?? 0) > 0)
                                                        <br><small class="text-danger fw-semibold" style="font-size: 0.75rem;">
                                                            -{{ formatAngka($d->realisasi_melebihi_top) }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge {{ $persentase >= 100 ? 'bg-label-success' : 'bg-label-danger' }} fw-bold">
                                                        {{ formatAngkaDesimal($persentase) }}%
                                                    </span>
                                                </td>
                                                <td class="text-end">{{ formatAngka($d->reward_rate) }}</td>
                                                <td class="text-end text-success fw-bold">{{ formatAngka($d->calculated_reward_total) }}</td>
                                                <td class="text-center">{!! $status_badge !!}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center py-3">Tidak ada data monitoring.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div style="float: right;">
                        {{ $monitoring_data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
<x-modal-form id="modalDetailrealisasi" size="modal-xl" show="loadmodaldetailrealisasi" title="" />
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

        // View Toggle Logic
        const btnCardView = $('#btn-card-view');
        const btnTableView = $('#btn-table-view');
        const cardViewContainer = $('#card-view-container');
        const tableViewContainer = $('#table-view-container');

        function setView(viewType) {
            if (viewType === 'table') {
                btnCardView.removeClass('active');
                btnTableView.addClass('active');
                cardViewContainer.addClass('d-none');
                tableViewContainer.removeClass('d-none');
                localStorage.setItem('monitoring_view_pref_2026', 'table');
            } else {
                btnTableView.removeClass('active');
                btnCardView.addClass('active');
                tableViewContainer.addClass('d-none');
                cardViewContainer.removeClass('d-none');
                localStorage.setItem('monitoring_view_pref_2026', 'card');
            }
        }

        btnCardView.on('click', function() {
            setView('card');
        });
        btnTableView.on('click', function() {
            setView('table');
        });

        // Initialize view based on stored preference
        const savedView = localStorage.getItem('monitoring_view_pref_2026');
        if (savedView) {
            setView(savedView);
        } else {
            setView('card');
        }

        $(document).on('click', '.btnDetailRealisasi', function(e) {
            e.preventDefault();
            let no_pengajuan = $(this).attr('no_pengajuan');
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            $("#modalDetailrealisasi").modal("show");
            $("#modalDetailrealisasi").find(".modal-title").text('Detail Realisasi Penjualan');
            $("#modalDetailrealisasi").find("#loadmodaldetailrealisasi").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#modalDetailrealisasi").find("#loadmodaldetailrealisasi").load(
                `/programikatan2026/${no_pengajuan}/${kode_pelanggan}/detailrealisasi`);
        });
    });
</script>
@endpush
