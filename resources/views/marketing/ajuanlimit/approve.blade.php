<style>
    .detail-list dl {
        display: grid;
        grid-template-columns: 40% 60%;
        margin-bottom: 0.5rem;
    }

    .detail-list dt {
        font-weight: 500;
        color: #5d596c;
    }

    .detail-list dd {
        margin-bottom: 0;
        color: #6f6b7d;
    }

    .info-wewenang {
        background: #f0f7ff;
        border-left: 4px solid #007bff;
        padding: 1rem;
        border-radius: 4px;
    }

    .timeline-disposisi {
        border-left: 2px solid #e6e6e8;
        padding-left: 1.5rem;
        position: relative;
    }

    .timeline-item {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .timeline-item::before {
        content: "";
        width: 12px;
        height: 12px;
        background: #007bff;
        border-radius: 50%;
        position: absolute;
        left: -21px;
        top: 4px;
    }
</style>

<form action="{{ route('ajuanlimit.approvestore', Crypt::encrypt($ajuanlimit->no_pengajuan)) }}" id="formApprovelimit" method="POST">
    @csrf
    <div class="row g-4">
        <!-- Photo Section -->
        <div class="col-12">
            <div class="d-flex justify-content-around bg-light p-3 rounded shadow-sm">
                <div class="text-center">
                    <small class="text-muted d-block mb-2">Foto Toko</small>
                    @if (!empty($ajuanlimit->foto) && Storage::disk('public')->exists('/pelanggan/' . $ajuanlimit->foto))
                        <img src="{{ getfotoPelanggan($ajuanlimit->foto) }}" class="rounded shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" class="rounded shadow-sm" style="width: 120px;">
                    @endif
                </div>
                <div class="text-center">
                    <small class="text-muted d-block mb-2">Foto Owner</small>
                    @if (!empty($ajuanlimit->foto_owner) && Storage::disk('public')->exists('/pelanggan/owner/' . $ajuanlimit->foto_owner))
                        <img src="{{ getfotoPelangganowner($ajuanlimit->foto_owner) }}" class="rounded shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" class="rounded shadow-sm" style="width: 120px;">
                    @endif
                </div>
            </div>
        </div>

        <!-- Left Column: Primary Data -->
        <div class="col-md-6 border-end detail-list">
            <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary"><i class="ti ti-info-circle me-1"></i>Data Pengajuan</h6>
            <dl>
                <dt>No. Pengajuan</dt>
                <dd class="fw-bold">{{ $ajuanlimit->no_pengajuan }}</dd>
                <dt>Tanggal</dt>
                <dd>{{ DateToIndo($ajuanlimit->tanggal) }}</dd>
                <dt>Kode Pelanggan</dt>
                <dd>{{ $ajuanlimit->kode_pelanggan }}</dd>
                <dt>NIK</dt>
                <dd>{{ $ajuanlimit->nik }}</dd>
                <dt>Nama Pelanggan</dt>
                <dd>{{ $ajuanlimit->nama_pelanggan }}</dd>
                <dt>Alamat</dt>
                <dd>{{ ucwords(strtolower($ajuanlimit->alamat_pelanggan)) }}</dd>
                <dt>No. HP</dt>
                <dd>{{ $ajuanlimit->no_hp_pelanggan }}</dd>
                <dt>Cabang</dt>
                <dd>{{ textUpperCase($ajuanlimit->nama_cabang) }}</dd>
                <dt>Salesman</dt>
                <dd>{{ textUpperCase($ajuanlimit->nama_salesman) }}</dd>
                <dt>Routing</dt>
                <dd>{{ $ajuanlimit->hari }}</dd>
                <dt>Lokasi</dt>
                <dd><a href="https://www.google.com/maps?q={{ $ajuanlimit->latitude }},{{ $ajuanlimit->longitude }}" target="_blank" class="text-info"><i class="ti ti-map-pin me-1"></i>View Map</a></dd>
                <dt>Jumlah Ajuan</dt>
                <dd class="fw-bold text-success">{{ formatAngka($ajuanlimit->jumlah) }}</dd>
                <dt>LJT Ajuan</dt>
                <dd>{{ $ajuanlimit->ljt }} Hari</dd>
            </dl>

            <div class="info-wewenang my-4">
                <h6 class="fw-bold text-primary mb-2"><i class="ti ti-shield-check me-1"></i>Informasi Wewenang</h6>
                @if ($config)
                    <div class="mb-2">
                        <small class="text-muted d-block uppercase">Range Config:</small>
                        <span class="fw-bold">{{ number_format($config->min_limit, 0, ',', '.') }} - {{ number_format($config->max_limit, 0, ',', '.') }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block uppercase mb-1">Alur Persetujuan:</small>
                        <div class="d-flex align-items-center flex-wrap gap-1">
                            @foreach ($roles as $role)
                                <span class="badge {{ auth()->user()->hasRole($role) ? 'bg-primary' : 'bg-label-secondary' }} px-2">
                                    {{ textUpperCase($role) }}
                                </span>
                                @if (!$loop->last)
                                    <i class="ti ti-chevron-right text-muted mx-1"></i>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="pt-2 border-top">
                        @if ($is_final_approver)
                            <span class="text-success fw-bold"><i class="ti ti-check-double me-1"></i>Proses Berhenti di Anda (Final Approval)</span>
                        @else
                            <span class="text-info"><i class="ti ti-arrow-forward me-1"></i>Diteruskan ke: <b class="text-primary">{{ textUpperCase($next_role) }}</b></span>
                        @endif
                    </div>
                @else
                    <span class="text-danger small"><i class="ti ti-alert-triangle me-1"></i>Konfigurasi nominal tidak ditemukan! Alur default digunakan.</span>
                @endif
            </div>

            <!-- Approval Action -->
            <div class="card bg-label-secondary border-0 p-3 shadow-none">
                @if (!$is_final_approver)
                    <div class="mb-3">
                        <x-textarea label="Uraian Analisa / Catatan" name="uraian_analisa" value="{{ optional($lastdisposisi)->id_pengirim == auth()->user()->id ? $lastdisposisi->uraian_analisa : '' }}" />
                    </div>
                @endif
                <div class="row g-2">
                    <div class="col-8">
                        <button type="submit" class="btn btn-primary w-100 shadow-sm py-2">
                            <i class="ti {{ $is_final_approver ? 'ti-circle-check' : 'ti-send' }} me-2"></i>
                            {{ $is_final_approver ? 'Setujui Pengajuan' : 'Setujui & Teruskan' }}
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="submit" name="decline" value="decline" class="btn btn-label-danger w-100 py-2">Tolak</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Secondary Data & History -->
        <div class="col-md-6 detail-list">
            <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary"><i class="ti ti-checklist me-1"></i>Data Pendukung</h6>
            <dl>
                <dt>Kepemilikan</dt>
                <dd>{{ !empty($ajuanlimit->kepemilikan) ? $kepemilikan[$ajuanlimit->kepemilikan] : '-' }}</dd>
                <dt>Lama Berjualan</dt>
                <dd>{{ !empty($ajuanlimit->lama_berjualan) ? $lama_berjualan[$ajuanlimit->lama_berjualan] : '-' }}</dd>
                <dt>Status Outlet</dt>
                <dd>{{ !empty($ajuanlimit->status_outlet) ? $status_outlet[$ajuanlimit->status_outlet] : '-' }}</dd>
                <dt>Type Outlet</dt>
                <dd>{{ !empty($ajuanlimit->type_outlet) ? $type_outlet[$ajuanlimit->type_outlet] : '-' }}</dd>
                <dt>Cara Bayar</dt>
                <dd>{{ !empty($ajuanlimit->cara_pembayaran) ? $cara_pembayaran[$ajuanlimit->cara_pembayaran] : '-' }}</dd>
                <dt>Lama Langganan</dt>
                <dd>{{ !empty($ajuanlimit->lama_langganan) ? $lama_langganan[$ajuanlimit->lama_langganan] : '-' }}</dd>
                <dt>Jaminan</dt>
                <dd>{{ $ajuanlimit->jaminan == '1' ? 'Ada' : 'Tidak Ada' }}</dd>
                <dt>Top Up Terakhir</dt>
                <dd>{{ !empty($ajuanlimit->topup_terakhir) ? DateToIndo($ajuanlimit->topup_terakhir) : '-' }}</dd>
                <dt>Omset Toko</dt>
                <dd>{{ formatAngka($ajuanlimit->omset_toko) }}</dd>
                <dt>Faktur Belum Lunas</dt>
                <dd>{{ $ajuanlimit->jml_faktur }}</dd>
                <dt>Skor Analisa</dt>
                <dd><span class="badge bg-label-info">{{ formatAngkaDesimal($ajuanlimit->skor) }}</span></dd>
                <dt>Rekomendasi</dt>
                <dd>
                    @php
                        if ($ajuanlimit->skor <= 2) { $rekomendasi = 'Tidak Layak'; $bg = 'danger'; }
                        elseif ($ajuanlimit->skor <= 4) { $rekomendasi = 'Tidak Disarankan'; $bg = 'danger'; }
                        elseif ($ajuanlimit->skor <= 6) { $rekomendasi = 'Beresiko'; $bg = 'warning'; }
                        elseif ($ajuanlimit->skor <= 8.5) { $rekomendasi = 'Layak Dengan Pertimbangan'; $bg = 'success'; }
                        else { $rekomendasi = 'Layak'; $bg = 'success'; }
                    @endphp
                    <span class="badge bg-{{ $bg }}">{{ $rekomendasi }}</span>
                </dd>
            </dl>

            <div class="mt-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary"><i class="ti ti-history me-1"></i>Riwayat Persetujuan</h6>
                <div class="timeline-disposisi">
                    @foreach ($disposisi as $index => $d)
                        @php
                            $next_role = @$disposisi[$index + 1]->role;
                            if ($d->role == $next_role) continue;
                        @endphp
                        <div class="timeline-item">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark">{{ $d->username }}</span>
                                <small class="text-muted">{{ date('d/m/y H:i', strtotime($d->created_at)) }}</small>
                            </div>
                            <span class="badge bg-label-primary mb-2">{{ textCamelCase($d->role) }}</span>
                            @if (!empty($d->uraian_analisa))
                                <p class="mb-0 small text-muted font-italic bg-light p-2 rounded">"{{ $d->uraian_analisa }}"</p>
                            @endif
                        </div>
                    @endforeach
                    @if($disposisi->isEmpty())
                        <em class="text-muted small">Belum ada riwayat persetujuan.</em>
                    @endif
                </div>
            </div>

        </div>
    </div>
</form>
