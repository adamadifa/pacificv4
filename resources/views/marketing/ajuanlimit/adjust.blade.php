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
</style>

<form action="{{ route('ajuanlimit.adjuststore', Crypt::encrypt($ajuanlimit->no_pengajuan)) }}" id="formAdjustlimit" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-12 detail-list">
            <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary"><i class="ti ti-info-circle me-1"></i>Data Pengajuan</h6>
            <dl>
                <dt>No. Pengajuan</dt>
                <dd class="fw-bold">{{ $ajuanlimit->no_pengajuan }}</dd>
                <dt>Tanggal</dt>
                <dd>{{ DateToIndo($ajuanlimit->tanggal) }}</dd>
                <dt>Kode Pelanggan</dt>
                <dd>{{ $ajuanlimit->kode_pelanggan }}</dd>
                <dt>Nama Pelanggan</dt>
                <dd>{{ $ajuanlimit->nama_pelanggan }}</dd>
                <dt>Jumlah Ajuan</dt>
                <dd class="fw-bold text-success">{{ formatAngka($ajuanlimit->jumlah) }}</dd>
                <dt>LJT Ajuan</dt>
                <dd>{{ $ajuanlimit->ljt }} Hari</dd>
            </dl>
        </div>
    </div>

    <div class="card bg-label-secondary border-0 p-3 shadow-none mt-3">
        <h6 class="fw-bold mb-3 text-dark"><i class="ti ti-adjustments-horizontal me-1"></i>Penyesuaian (Rekomendasi)</h6>
        <div class="row g-2">
            <div class="col-md-7">
                <x-input-with-icon label="Jumlah" name="jumlah_rekomendasi" icon="ti ti-coins" money="true" align="right"
                    value="{{ formatAngka($ajuanlimit->jumlah_rekomendasi) }}" hideLabel="true" />
            </div>
            <div class="col-md-5">
                <div class="form-group mb-0">
                    <select name="ljt_rekomendasi" id="ljt_rekomendasi" class="form-select">
                        <option value="">Pilih LJT</option>
                        <option value="14" {{ $ajuanlimit->ljt == '14' ? 'selected' : '' }}>14 Hari</option>
                        <option value="30" {{ $ajuanlimit->ljt == '30' ? 'selected' : '' }}>30 Hari</option>
                        <option value="45" {{ $ajuanlimit->ljt == '45' ? 'selected' : '' }}>45 Hari</option>
                    </select>
                </div>
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary w-100 shadow-sm">
                    <i class="ti ti-device-floppy me-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</form>
