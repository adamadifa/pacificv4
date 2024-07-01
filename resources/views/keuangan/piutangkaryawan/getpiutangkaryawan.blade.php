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
</table>
