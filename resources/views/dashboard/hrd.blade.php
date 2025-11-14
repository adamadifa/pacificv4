@extends('layouts.app')
@section('titlepage', 'Dashboard HRD')
@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
    @endphp
    <style>
        #tab-content-main {
            box-shadow: none !important;
            background: none !important;
        }

        .birthday-card-item {
            transition: all 0.3s ease;
        }

        .birthday-card-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15) !important;
        }
    </style>
@section('navigasi')
    @include('dashboard.navigasi')
@endsection
<div class="row">
    <div class="col-xl-12">
        @include('dashboard.welcome')
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                @include('layouts.navigation_dashboard')
            </ul>
            <div class="tab-content" id="tab-content-main">
                <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col">
                            @include('dashboard.hrd.rekapkaryawan')
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-9 col-md-12 col-sm-12">
                            @include('dashboard.hrd.rekapkontrak')
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h4 class="card-title">Karyawan Cabang</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Cabang</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($karyawancabang as $d)
                                                <tr>
                                                    <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                                    <td class="text-center">{{ $d->jml_karyawancabang }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    {{-- Header Section --}}
                                    <div class="position-relative mb-4">
                                        <div class="d-flex align-items-start">
                                            <div class="rounded-3 p-2 me-3 d-flex align-items-center justify-content-center"
                                                style="width: 48px; height: 48px; background-color: #ff9800;">
                                                <i class="ti ti-cake text-white" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h4 class="mb-1 fw-bold text-dark">Karyawan Ulang Tahun</h4>
                                                <p class="text-muted mb-0 small">
                                                    Selamat ulang tahun untuk karyawan yang berulang tahun hari ini
                                                </p>
                                            </div>
                                        </div>
                                        <span class="badge px-3 py-2 rounded-3 position-absolute"
                                            style="font-size: 0.875rem; background-color: #ff9800; color: white; top: 0; right: 0;">
                                            {{ count($karyawan_ulangtahun) }} Karyawan
                                        </span>
                                    </div>

                                    @if (count($karyawan_ulangtahun) > 0)
                                        {{-- Employee Details --}}
                                        @foreach ($karyawan_ulangtahun as $d)
                                            @php
                                                $umur = hitungUmur($d->tanggal_lahir);
                                                $tanggal_lahir_formatted = $d->tanggal_lahir
                                                    ? date('d-m-Y', strtotime($d->tanggal_lahir))
                                                    : '-';
                                            @endphp
                                            <div class="border rounded p-3 mb-3" style="background-color: #ffffff;">
                                                {{-- Name and Age Badge --}}
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h5 class="mb-0 fw-bold text-dark flex-grow-1">
                                                        {{ formatName($d->nama_karyawan) }}
                                                    </h5>
                                                    @if ($umur !== null)
                                                        <span class="badge px-2 py-1 rounded-3"
                                                            style="font-size: 0.75rem; background-color: #b3e5fc; color: #01579b;">
                                                            {{ $umur }} Tahun
                                                        </span>
                                                    @endif
                                                </div>

                                                {{-- Avatar and Info --}}
                                                <div class="d-flex align-items-start">
                                                    {{-- Avatar --}}
                                                    <div class="flex-shrink-0 me-3 position-relative">
                                                        @if (!empty($d->foto) && Storage::disk('public')->exists('/karyawan/' . $d->foto))
                                                            <img src="{{ getfotoKaryawan($d->foto) }}" alt=""
                                                                class="rounded-circle"
                                                                style="width: 80px; height: 80px; object-fit: cover;">
                                                        @else
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                                style="width: 80px; height: 80px; background-color: #e7d5ff;">
                                                                @if ($d->jenis_kelamin == 'L')
                                                                    <img src="{{ asset('assets/img/avatars/male.jpg') }}"
                                                                        alt="" class="rounded-circle"
                                                                        style="width: 80px; height: 80px; object-fit: cover;">
                                                                @else
                                                                    <img src="{{ asset('assets/img/avatars/female.jpg') }}"
                                                                        alt="" class="rounded-circle"
                                                                        style="width: 80px; height: 80px; object-fit: cover;">
                                                                @endif
                                                            </div>
                                                        @endif
                                                        <div class="position-absolute bottom-0 end-0 rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 24px; height: 24px; background-color: #ff9800; border: 2px solid white;">
                                                            <i class="ti ti-cake text-white"
                                                                style="font-size: 0.7rem;"></i>
                                                        </div>
                                                    </div>

                                                    {{-- Employee Info --}}
                                                    <div class="flex-grow-1">
                                                        <div class="row g-2">
                                                            {{-- Left Column --}}
                                                            <div class="col-12">
                                                                <div class="mb-2">
                                                                    <div class="d-flex align-items-center mb-1">
                                                                        <i class="ti ti-id me-1 text-muted"
                                                                            style="font-size: 0.875rem;"></i>
                                                                        <span class="text-muted"
                                                                            style="font-size: 0.75rem;">NIK:</span>
                                                                        <strong class="ms-1"
                                                                            style="font-size: 0.75rem;">{{ $d->nik }}</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <div class="d-flex align-items-center mb-1">
                                                                        <i class="ti ti-calendar me-1 text-muted"
                                                                            style="font-size: 0.875rem;"></i>
                                                                        <span class="text-muted"
                                                                            style="font-size: 0.75rem;">Tanggal
                                                                            Lahir:</span>
                                                                        <strong class="ms-1"
                                                                            style="font-size: 0.75rem;">{{ $tanggal_lahir_formatted }}</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <div class="d-flex align-items-center mb-1">
                                                                        <i class="ti ti-briefcase me-1 text-muted"
                                                                            style="font-size: 0.875rem;"></i>
                                                                        <span class="text-muted"
                                                                            style="font-size: 0.75rem;">Jabatan:</span>
                                                                        <strong class="ms-1"
                                                                            style="font-size: 0.75rem;">{{ singkatString($d->nama_jabatan) }}</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <div class="d-flex align-items-center mb-1">
                                                                        <i class="ti ti-building me-1 text-muted"
                                                                            style="font-size: 0.875rem;"></i>
                                                                        <span class="text-muted"
                                                                            style="font-size: 0.75rem;">Dept:</span>
                                                                        <strong class="ms-1"
                                                                            style="font-size: 0.75rem;">{{ $d->nama_dept }}</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <div class="d-flex align-items-center mb-1">
                                                                        <i class="ti ti-map-pin me-1 text-muted"
                                                                            style="font-size: 0.875rem;"></i>
                                                                        <span class="text-muted"
                                                                            style="font-size: 0.75rem;">Cabang:</span>
                                                                        <strong class="ms-1"
                                                                            style="font-size: 0.75rem;">{{ textUpperCase($d->nama_cabang) }}</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="ti ti-cake-off text-muted"
                                                    style="font-size: 4rem; opacity: 0.3;"></i>
                                            </div>
                                            <p class="text-muted mb-0 fw-medium">Tidak ada karyawan yang ulang tahun
                                                hari
                                                ini</p>
                                            <small class="text-muted">Semoga hari ini menyenangkan!</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</div>
@endsection
@push('myscript')
@endpush
