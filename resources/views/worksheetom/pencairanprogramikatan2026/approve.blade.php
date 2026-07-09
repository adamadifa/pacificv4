<form action="{{ route('pencairanprogramikatan2026.storeapprove', Crypt::encrypt($pencairanprogram->kode_pencairan)) }}" method="POST">
    @csrf
    {{-- Info Card --}}
    <div class="card shadow-sm border mb-4">
        <div class="card-body p-4">
            <div class="row g-4 text-nowrap">
                <div class="col-md-4 border-end">
                    <div class="d-flex align-items-start">
                        <i class="ti ti-file-description fs-2 text-primary me-3"></i>
                        <div>
                            <small class="text-muted d-block mb-1">Kode Pencairan</small>
                            <h6 class="mb-0 fw-bold">{{ $pencairanprogram->kode_pencairan }}</h6>
                            <small class="text-secondary">{{ DateToIndo($pencairanprogram->tanggal) }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 border-end">
                    <div class="d-flex align-items-start">
                        <i class="ti ti-files fs-2 text-info me-3"></i>
                        <div>
                            <small class="text-muted d-block mb-1">Program & Cabang</small>
                            <h6 class="mb-0 fw-bold text-truncate" style="max-width: 250px;" title="{{ $pencairanprogram->nama_program }}">
                                {{ $pencairanprogram->nama_program }}</h6>
                            <span class="badge bg-label-info mt-1">{{ strtoupper($pencairanprogram->nama_cabang) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-start">
                        <i class="ti ti-calendar-event fs-2 text-warning me-3"></i>
                        <div>
                            <small class="text-muted d-block mb-1">Periode Penjualan</small>
                            <h6 class="mb-0 fw-bold">Semester {{ $pencairanprogram->semester }}
                                {{ $pencairanprogram->tahun }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Data List Cards --}}
    <div class="row mb-4" id="loaddetailpencairan" style="max-height: 400px; overflow-y: auto;">
        @php
            $metode_pembayaran = [
                'TN' => 'Tunai',
                'TF' => 'Transfer',
                'VC' => 'Voucher',
            ];
        @endphp
        @foreach ($detail as $key => $d)
            @php
                $total_reward = $d->reward;
            @endphp
            <div class="col-12 mb-2">
                <div class="card shadow-sm border">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            {{-- 1. Pelanggan Info --}}
                            <div style="min-width: 200px;">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        @if (!empty($d->foto))
                                            <img src="{{ asset('storage/pelanggan/' . $d->foto) }}" alt="Avatar" class="rounded-circle"
                                                style="width: 40px; height: 40px; object-fit: cover;"
                                                onerror="this.onerror=null;this.src='{{ asset('assets/img/avatars/No_Image_Available.jpg') }}';">
                                        @else
                                            <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="Avatar" class="rounded-circle"
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div>
                                        <span class="badge bg-secondary mb-1">{{ $d->kode_pelanggan }}</span>
                                        <h6 class="mb-0 fw-bold text-wrap" style="max-width: 250px;">{{ $d->nama_pelanggan }}</h6>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. Target Stats --}}
                            <div class="d-flex gap-3 text-center border-end pe-3">
                                <div>
                                    <span class="d-block fw-bold text-dark">{{ formatAngka($d->avg ?? 0) }}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">AVG</small>
                                </div>
                                <div>
                                    <span class="d-block fw-bold text-dark">{{ formatAngka($d->target_perbulan ?? 0) }}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">TARGET</small>
                                </div>
                                <div>
                                    <span class="d-block fw-bold text-primary">{{ formatAngka(($d->avg ?? 0) + ($d->target_perbulan ?? 0)) }}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">TOTAL</small>
                                </div>
                                <div>
                                    <span class="d-block fw-bold text-info">{{ formatAngka($d->kenaikan_per_bulan) }}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">INCR</small>
                                </div>
                            </div>

                            {{-- 3. Realisasi & Reward --}}
                            <div class="d-flex gap-4 border-end pe-3">
                                <div class="text-center">
                                    <h6 class="mb-0 fw-bold text-warning">{{ formatAngka($d->realisasi) }}</h6>
                                    @if(($d->kredit_melebihi_top ?? 0) > 0)
                                        <small class="d-block fw-bold text-danger" style="font-size: 10px; margin-top: -2px;">
                                            -{{ formatAngka($d->kredit_melebihi_top) }}
                                        </small>
                                    @endif
                                    <small class="text-muted" style="font-size: 0.7rem;">REALISASI</small>
                                </div>
                                <div class="text-center">
                                     <h6 class="mb-0 fw-bold text-info">{{ formatAngka($d->rate) }}</h6>
                                     <small class="text-muted" style="font-size: 0.7rem;">RATE</small>
                                </div>
                                <div class="text-center">
                                    <h6 class="mb-0 fw-bold text-success">{{ formatAngka($total_reward) }}</h6>
                                    <small class="text-muted" style="font-size: 0.7rem;">REWARD</small>
                                </div>
                            </div>

                            {{-- 4. Payment Info --}}
                            <div class="" style="min-width: 250px; font-size: 0.85rem;">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ti ti-building-bank me-2 text-secondary"></i>
                                    <span class="fw-bold">{{ $d->bank }}</span>
                                    <span class="mx-1">-</span>
                                    <span>{{ $d->no_rekening }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-user me-2 text-secondary"></i>
                                    <span class="text-truncate me-2" style="max-width: 150px;"
                                        title="{{ $d->pemilik_rekening }}">{{ $d->pemilik_rekening }}</span>
                                    <span class="badge bg-label-primary">{{ $d->metode_pembayaran }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Actions / Approval Buttons --}}
    <div class="row mt-3">
        <div class="col">
            <button type="submit" class="btn btn-primary w-100"><i class="ti ti-thumb-up me-1"></i>Setujui</button>
        </div>
        <div class="col">
            <button type="submit" name="decline" value="1" class="btn btn-danger w-100"><i class="ti ti-thumb-down me-1"></i>Tolak</button>
        </div>
    </div>
</form>
