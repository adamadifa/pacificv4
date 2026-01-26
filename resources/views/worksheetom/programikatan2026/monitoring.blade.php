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
                                    select2="select2Kodeprogram" upperCase="true" selected="{{ Request('kode_program') }}" />
                                <div class="form-group mb-3">
                                   <input type="text" class="form-control" name="nama_pelanggan" placeholder="Nama Pelanggan" value="{{ Request('nama_pelanggan') }}">
                                </div>
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
                        @foreach ($monitoring_data as $d)
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
                                                 <div class="col-4 text-center">
                                                    <small class="d-block {{ $color_reward ? 'text-white' : 'text-muted' }}">Target</small>
                                                    <span class="fw-bold fs-5 {{ $color_reward ? 'text-white' : '' }}">{{ formatAngka($total_target) }}</span>
                                                 </div>
                                                 <div class="col-4 text-center">
                                                    <small class="d-block {{ $color_reward ? 'text-white' : 'text-muted' }}">Realisasi</small>
                                                    <span class="fw-bold fs-5 {{ $color_reward ? 'text-white' : '' }}">{{ formatAngka($d->realisasi) }}</span>
                                                 </div>
                                                  <div class="col-4 text-center">
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
                        @endforeach
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
