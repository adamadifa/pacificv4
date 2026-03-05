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
