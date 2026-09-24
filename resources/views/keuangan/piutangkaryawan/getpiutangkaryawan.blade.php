<table class="table">
    <tr>
        <th>Jumlah Pinjaman</th>
        <td class="text-end fw-bold">{{ formatAngka($piutangkaryawan->jumlah) }}</td>
    </tr>

    <tr>
        <th>Jumlah Pembayaran</th>
        <td class="text-end fw-bold">{{ formatAngka($piutangkaryawan->totalpembayaran) }}</td>
    </tr>
    <tr>
        <th>Sisa Tagihan</th>
        <td class="text-end fw-bold">{{ formatAngka($piutangkaryawan->jumlah - $piutangkaryawan->totalpembayaran) }}</td>
    </tr>
    <tr>
        <th>Kategori Piutang</th>
        <td class="text-end">
            <div class="d-flex justify-content-end align-items-center gap-2">
                <span id="badge-kategori" class="badge {{ $piutangkaryawan->kategori == 'EK' ? 'bg-danger' : 'bg-success' }}">
                    {{ $piutangkaryawan->kategori == 'EK' ? 'Eks Karyawan' : 'Karyawan' }}
                </span>
                @can('piutangkaryawan.edit')
                    <div class="form-check form-switch m-0 p-0" style="min-height: auto;">
                        <input class="form-check-input ms-0" type="checkbox" role="switch" id="switchKategori"
                            style="cursor: pointer; width: 2.2rem; height: 1.15rem;"
                            data-kategori="{{ $piutangkaryawan->kategori }}"
                            {{ $piutangkaryawan->kategori == 'EK' ? 'checked' : '' }}
                            title="Aktifkan untuk Eks Karyawan, matikan untuk Karyawan">
                    </div>
                @endcan
            </div>
        </td>
    </tr>
    <tr>
        <th>Status Akses</th>
        <td class="text-end">
            @if ($piutangkaryawan->status == 1)
                <span class="badge bg-info"><i class="ti ti-check me-1"></i>Hanya Bisa Dilihat Keuangan</span>
            @else
                <span class="badge bg-secondary">Normal</span>
            @endif
        </td>
    </tr>
</table>
