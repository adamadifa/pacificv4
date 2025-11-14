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
                            <div class="card">
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
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <span class="avatar-initial rounded bg-label-warning">
                                                <i class="ti ti-cake fs-4"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h4 class="mb-0">Karyawan Ulang Tahun</h4>
                                            <small class="text-muted">Selamat ulang tahun untuk karyawan yang berulang
                                                tahun hari ini</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-label-warning rounded-pill">{{ count($karyawan_ulangtahun) }}
                                        Karyawan</span>
                                </div>
                                <div class="card-body">
                                    @if (count($karyawan_ulangtahun) > 0)
                                        <div
                                            class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                            <div>
                                                <h6 class="mb-0">Kirim Ucapan Ulang Tahun</h6>
                                                <small class="text-muted">Kirim ucapan ulang tahun ke semua karyawan
                                                    yang berulang tahun hari ini</small>
                                            </div>
                                            <div>
                                                <button type="button"
                                                    class="btn btn-success btn-sm waves-effect waves-light"
                                                    id="btnKirimUcapan">
                                                    <i class="ti ti-brand-whatsapp me-1"></i>
                                                    <span id="btnText">Kirim ke Semua</span>
                                                    <span id="btnLoading"
                                                        class="spinner-border spinner-border-sm ms-2 d-none"
                                                        role="status"></span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            @foreach ($karyawan_ulangtahun as $d)
                                                @php
                                                    $umur = hitungUmur($d->tanggal_lahir);
                                                    $tanggal_lahir_formatted = $d->tanggal_lahir
                                                        ? date('d-m-Y', strtotime($d->tanggal_lahir))
                                                        : '-';
                                                @endphp
                                                <div class="col-12">
                                                    <div class="card card-border-shadow-primary birthday-card"
                                                        style="transition: 0.3s; cursor: pointer; transform: translateY(0px); box-shadow: none;"
                                                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.15)';"
                                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar me-3"
                                                                    style="width: 80px; height: 80px; position: relative;">
                                                                    @if (!empty($d->foto) && Storage::disk('public')->exists('/karyawan/' . $d->foto))
                                                                        <img src="{{ getfotoKaryawan($d->foto) }}"
                                                                            alt=""
                                                                            class="rounded-circle border border-primary border-3"
                                                                            style="width: 80px; height: 80px; object-fit: cover;">
                                                                    @else
                                                                        <div class="avatar-initial rounded-circle bg-label-primary d-flex align-items-center justify-content-center border border-primary border-3"
                                                                            style="width: 80px; height: 80px; font-size: 32px;">
                                                                            <i class="ti ti-user"></i>
                                                                        </div>
                                                                    @endif
                                                                    <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border border-white"
                                                                        style="width: 28px; height: 28px; font-size: 14px; border-width: 2px !important;">
                                                                        <i class="ti ti-cake"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between mb-2">
                                                                        <h5 class="mb-0">
                                                                            {{ formatName($d->nama_karyawan) }}</h5>
                                                                        @if ($umur !== null)
                                                                            <span
                                                                                class="badge bg-label-primary rounded-pill">{{ $umur }}
                                                                                Tahun</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="row g-2">
                                                                        <div class="col-md-6">
                                                                            <div class="d-flex align-items-center mb-1">
                                                                                <i
                                                                                    class="ti ti-id me-2 text-primary"></i>
                                                                                <small class="text-muted">NIK:</small>
                                                                                <strong
                                                                                    class="ms-2">{{ $d->nik }}</strong>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="d-flex align-items-center mb-1">
                                                                                <i
                                                                                    class="ti ti-calendar me-2 text-primary"></i>
                                                                                <small class="text-muted">Tanggal
                                                                                    Lahir:</small>
                                                                                <strong
                                                                                    class="ms-2">{{ $tanggal_lahir_formatted }}</strong>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="d-flex align-items-center mb-1">
                                                                                <i
                                                                                    class="ti ti-briefcase me-2 text-primary"></i>
                                                                                <small
                                                                                    class="text-muted">Jabatan:</small>
                                                                                <strong
                                                                                    class="ms-2">{{ $d->nama_jabatan }}</strong>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="d-flex align-items-center mb-1">
                                                                                <i
                                                                                    class="ti ti-building me-2 text-primary"></i>
                                                                                <small class="text-muted">Dept:</small>
                                                                                <strong
                                                                                    class="ms-2">{{ $d->nama_dept }}</strong>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="d-flex align-items-center mb-2">
                                                                                <i
                                                                                    class="ti ti-map-pin me-2 text-primary"></i>
                                                                                <small
                                                                                    class="text-muted">Cabang:</small>
                                                                                <strong
                                                                                    class="ms-2">{{ textUpperCase($d->nama_cabang) }}</strong>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
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
