<form action="{{ route('penilaiankaryawan.storeapprove', Crypt::encrypt($penilaiankaryawan->kode_penilaian)) }}" method="POST" id="formApprove">
    @csrf
    <style>
        .approval-header {
            background: linear-gradient(135deg, #002e65 0%, #0056b3 100%);
            border-radius: 12px;
            padding: 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
        }

        .score-circle {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        .info-card {
            border: 1px solid #eef1f6;
            border-radius: 10px;
            padding: 1rem;
            height: 100%;
            background: #fff;
            transition: all 0.2s;
        }

        .info-card:hover {
            border-color: #002e65;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #e9ecef;
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .recommendation-box {
            background: #fcfdfe;
            border-left: 4px solid #002e65;
            padding: 1rem;
            border-radius: 0 8px 8px 0;
        }

        .masa-kontrak-badge {
            padding: 0.6rem 1rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            background: #fff;
        }

        .masa-kontrak-badge.active {
            background: #eef1f6;
            border-color: #002e65;
            color: #002e65;
        }
    </style>

    <div class="approval-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="avatar avatar-xl me-3">
                @if (Storage::disk('public')->exists('/karyawan/' . $penilaiankaryawan->foto))
                    <img src="{{ getfotoKaryawan($penilaiankaryawan->foto) }}" alt="Avatar" class="rounded-circle border border-2 border-white">
                @else
                    <div class="avatar-initial rounded-circle bg-label-light border border-2 border-white">
                        <i class="ti ti-user fs-1"></i>
                    </div>
                @endif
            </div>
            <div>
                <h4 class="mb-1 text-white fw-bold">{{ formatName($penilaiankaryawan->nama_karyawan) }}</h4>
                <div class="d-flex gap-2">
                    <span class="badge bg-white text-primary rounded-pill">{{ $penilaiankaryawan->nik }}</span>
                    <span class="text-white-50 small"><i class="ti ti-calendar me-1"></i>{{ DateToIndo($penilaiankaryawan->tanggal) }}</span>
                </div>
            </div>
        </div>
        <div class="text-center">
            <div class="score-circle mx-auto mb-1">
                <h2 class="mb-0 text-white fw-bold">{{ $total_score->total_score }}</h2>
            </div>
            <small class="text-white-50 text-uppercase fw-semibold ls-1">Total Score</small>
        </div>
    </div>

    <div class="row g-3">
        {{-- Profile Details --}}
        <div class="col-lg-6 col-md-12">
            <div class="info-card">
                <h6 class="fw-bold mb-3 d-flex align-items-center">
                    <i class="ti ti-info-circle me-2 text-primary"></i> Informasi Karyawan
                </h6>
                <div class="stat-item">
                    <span class="text-muted">Jabatan</span>
                    <span class="fw-semibold text-dark">{{ $penilaiankaryawan->nama_jabatan }}</span>
                </div>
                <div class="stat-item">
                    <span class="text-muted">Departemen</span>
                    <span class="fw-semibold text-dark">{{ $penilaiankaryawan->nama_dept }}</span>
                </div>
                <div class="stat-item">
                    <span class="text-muted">Cabang</span>
                    <span class="fw-semibold text-dark">{{ $penilaiankaryawan->nama_cabang }}</span>
                </div>
                <div class="stat-item">
                    <span class="text-muted">Periode Kontrak</span>
                    <span class="fw-semibold text-primary">{{ DateToIndo($penilaiankaryawan->kontrak_dari) }} - {{ DateToIndo($penilaiankaryawan->kontrak_sampai) }}</span>
                </div>
            </div>
        </div>

        {{-- Attendance Stats --}}
        <div class="col-lg-6 col-md-12">
            <div class="info-card">
                <h6 class="fw-bold mb-3 d-flex align-items-center">
                    <i class="ti ti-chart-bar me-2 text-primary"></i> Rekap Presensi
                </h6>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="p-2 border rounded bg-light-info text-center">
                            <h4 class="mb-0 fw-bold">{{ $penilaiankaryawan->sid }}</h4>
                            <small class="text-muted">SID</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 border rounded bg-light-warning text-center">
                            <h4 class="mb-0 fw-bold">{{ $penilaiankaryawan->izin }}</h4>
                            <small class="text-muted">Izin</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 border rounded bg-light-danger text-center">
                            <h4 class="mb-0 fw-bold">{{ $penilaiankaryawan->sakit }}</h4>
                            <small class="text-muted">Sakit</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 border rounded bg-light-dark text-center">
                            <h4 class="mb-0 fw-bold">{{ $penilaiankaryawan->alfa }}</h4>
                            <small class="text-muted">Alfa</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Masa Kontrak --}}
        <div class="col-12 mt-2">
            <div class="info-card">
                <h6 class="fw-bold mb-3 d-flex align-items-center">
                    <i class="ti ti-clock me-2 text-primary"></i> Hasil Penilaian & Rekomendasi
                </h6>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <div class="masa-kontrak-badge {{ $penilaiankaryawan->masa_kontrak == 'TP' ? 'active' : '' }}">
                        <i class="ti {{ $penilaiankaryawan->masa_kontrak == 'TP' ? 'ti-square-check' : 'ti-square' }}"></i> Tidak Di Perpanjang
                    </div>
                    <div class="masa-kontrak-badge {{ $penilaiankaryawan->masa_kontrak == 'K3' ? 'active' : '' }}">
                        <i class="ti {{ $penilaiankaryawan->masa_kontrak == 'K3' ? 'ti-square-check' : 'ti-square' }}"></i> 3 Bulan
                    </div>
                    <div class="masa-kontrak-badge {{ $penilaiankaryawan->masa_kontrak == 'K6' ? 'active' : '' }}">
                        <i class="ti {{ $penilaiankaryawan->masa_kontrak == 'K6' ? 'ti-square-check' : 'ti-square' }}"></i> 6 Bulan
                    </div>
                    <div class="masa-kontrak-badge {{ $penilaiankaryawan->masa_kontrak == 'KT' ? 'active' : '' }}">
                        <i class="ti {{ $penilaiankaryawan->masa_kontrak == 'KT' ? 'ti-square-check' : 'ti-square' }}"></i> Karyawan Tetap
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted fw-semibold mb-1 d-block">Rekomendasi</small>
                        <div class="recommendation-box mb-3">
                            <p class="mb-0 text-dark">{{ $penilaiankaryawan->rekomendasi }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted fw-semibold mb-1 d-block">Evaluasi</small>
                        <div class="recommendation-box mb-3" style="border-left-color: #6c757d;">
                            <p class="mb-0 text-dark">{{ $penilaiankaryawan->evaluasi }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 pt-3 border-top d-flex gap-2">
        <a href="{{ route('penilaiankaryawan.cetak', Crypt::encrypt($penilaiankaryawan->kode_penilaian)) }}" class="btn btn-label-secondary flex-grow-1" target="_blank">
            <i class="ti ti-printer me-1"></i> Preview Detail
        </a>
        <button class="btn btn-primary flex-grow-1" id="btnSimpan">
            <i class="ti ti-thumb-up me-1"></i> 
            @if ($level_user != $end_role)
                Setujui & Teruskan ke {{ textUpperCase($nextrole) }}
            @else
                Setujui (Approval Akhir)
            @endif
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        const form = $("#formApprove");
        form.submit(function(e) {
            $("#btnSimpan").prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                Memproses...
            `);
        });
    });
</script>
