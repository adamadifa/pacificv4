@extends('layouts.app')
@section('titlepage', 'Backup Database - Streaming Download')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <!-- Header Section -->
                <div class="text-center mb-5">
                    <div class="display-6 text-primary mb-3">
                        <i class="ti ti-database-export"></i>
                    </div>
                    <h1 class="h2 fw-bold text-dark mb-2">Database Backup</h1>
                    <p class="text-muted lead">Download backup database langsung dari server tanpa menyimpan file</p>
                </div>

                <!-- Main Card -->
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <!-- Info Section -->
                        <div class="row mb-5">
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="ti ti-download text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Download Langsung</h6>
                                        <p class="text-muted small mb-0">Streaming download tanpa progress bar</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="ti ti-chart-line text-success fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Download dengan Progress</h6>
                                        <p class="text-muted small mb-0">Streaming download dengan tracking progress</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <a href="{{ route('backup.database.stream.download') }}"
                                    class="btn btn-primary btn-lg w-100 py-3 d-flex align-items-center justify-content-center"
                                    onclick="return confirm('Streaming download langsung dari database. File akan langsung download ke komputer Anda tanpa disimpan di server. Lanjutkan?')">
                                    <i class="ti ti-download me-3 fs-5"></i>
                                    <div class="text-start">
                                        <div class="fw-bold">Download Langsung</div>
                                        <small class="opacity-75">Tanpa progress bar</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('backup.database.stream.download.progress') }}"
                                    class="btn btn-success btn-lg w-100 py-3 d-flex align-items-center justify-content-center"
                                    onclick="return confirm('Streaming download dengan progress tracking. File akan langsung download ke komputer Anda tanpa disimpan di server. Lanjutkan?')">
                                    <i class="ti ti-chart-line me-3 fs-5"></i>
                                    <div class="text-start">
                                        <div class="fw-bold">Download dengan Progress</div>
                                        <small class="opacity-75">Dengan tracking progress</small>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Features Section -->
                        <div class="bg-light rounded-4 p-4">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="ti ti-star text-warning me-2"></i>
                                Fitur Unggulan
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="ti ti-check-circle text-success me-2 mt-1"></i>
                                        <span class="small">Tidak ada file tersimpan di server</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="ti ti-check-circle text-success me-2 mt-1"></i>
                                        <span class="small">Download langsung ke komputer Anda</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="ti ti-check-circle text-success me-2 mt-1"></i>
                                        <span class="small">Mendukung database besar</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="ti ti-check-circle text-success me-2 mt-1"></i>
                                        <span class="small">Tidak ada timeout</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alert Messages -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                                <i class="ti ti-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                                <i class="ti ti-alert-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Help Section -->
                <div class="card border-0 bg-light mt-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="fw-bold text-dark mb-2">
                                    <i class="ti ti-help-circle text-info me-2"></i>
                                    Perlu bantuan?
                                </h6>
                                <p class="text-muted small mb-0">
                                    Fitur ini memungkinkan Anda download backup database langsung ke komputer tanpa
                                    menyimpan file di server Laravel.
                                    Cocok untuk database dengan ukuran besar yang membutuhkan waktu lama untuk di-backup.
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#helpModal">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Pelajari Lebih Lanjut
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-primary" id="helpModalLabel">
                        <i class="ti ti-help-circle me-2"></i>
                        Panduan Streaming Download Database
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center p-4">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 mx-auto mb-3"
                                        style="width: fit-content;">
                                        <i class="ti ti-download text-primary fs-3"></i>
                                    </div>
                                    <h6 class="fw-bold">Download Langsung</h6>
                                    <p class="text-muted small mb-0">
                                        Pilihan terbaik untuk download cepat tanpa monitoring progress.
                                        File akan langsung download ke komputer Anda.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center p-4">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3 mx-auto mb-3"
                                        style="width: fit-content;">
                                        <i class="ti ti-chart-line text-success fs-3"></i>
                                    </div>
                                    <h6 class="fw-bold">Download dengan Progress</h6>
                                    <p class="text-muted small mb-0">
                                        Pilihan terbaik untuk database besar. Anda dapat melihat progress download
                                        dan estimasi waktu selesai.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="fw-bold text-dark mb-3">Keuntungan Streaming Download:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="ti ti-check text-success me-2"></i>
                                <strong>Tidak ada file tersimpan di server</strong> - Hemat storage server
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-check text-success me-2"></i>
                                <strong>Download langsung</strong> - File langsung ke komputer Anda
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-check text-success me-2"></i>
                                <strong>Mendukung database besar</strong> - Tidak ada batasan ukuran
                            </li>
                            <li class="mb-2">
                                <i class="ti ti-check text-success me-2"></i>
                                <strong>Tidak ada timeout</strong> - Proses berjalan sampai selesai
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Auto hide alert after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
@endsection
