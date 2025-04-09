@extends('layouts.app')
@section('titlepage', 'Dashboard')
@section('content')
    <style>
        #tab-content-main {
            box-shadow: none !important;
            background: none !important;
        }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="nav-align-top mb-4">
                <div class="row">
                    <div class="col">
                        <form action="#" method="GET">
                            <div class="row">
                                <div class="col-md-12">
                                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary w-100" id="showButton"><i
                                        class="ti ti-heart-rate-monitor me-1"></i>Tampilkan</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    @foreach ($bank as $d)
                        <div class="col-lg-3 col-sm-6 mb-2">
                            @php
                                $dari = !empty(Request('dari')) ? Request('dari') : date('Y-m-d');
                                $sampai = !empty(Request('sampai')) ? Request('sampai') : date('Y-m-d');
                            @endphp
                            <a href="{{ route('mutasikeuangan.show', [Crypt::encrypt($d->kode_bank), $dari, $sampai]) }}">
                                <div class="card h-100 border-1 ">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div class="card-title mb-0">
                                            <h5 class="mb-0 me-2">{{ formatRupiah($d->saldo) }}</h5>
                                            <small>{{ $d->nama_bank }}</small>
                                            <br>
                                            <span class="fw-semibold">{{ $d->no_rekening }}</span>
                                            <div class="d-flex align-items-center" style="font-size: 14px">
                                                <div class="d-flex flex-row align-items-center me-2">
                                                    <i class="ti ti-arrow-down class text-success me-1"></i>
                                                    <span class="text-success">{{ formatRupiah($d->rekap_kredit) }}</span>
                                                </div>
                                                <div class="d-flex flex-row align-items-center">
                                                    <i class="ti ti-arrow-up class text-danger me-1"></i>
                                                    <span class="text-danger">{{ formatRupiah($d->rekap_debet) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-icon">
                                            <span class="badge bg-label-primary rounded-pill p-2">
                                                <i class="ti ti-moneybag ti-sm"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
