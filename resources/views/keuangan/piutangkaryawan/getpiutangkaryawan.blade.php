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
