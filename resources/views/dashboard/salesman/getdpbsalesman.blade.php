@foreach ($dpb as $d)
    @php
        $jml_penjualan = $d->jml_penjualan / $d->isi_pcs_dus;
        $jml_ambil = $d->jml_ambil / $d->isi_pcs_dus;
        $persentase = (ROUND($jml_penjualan) / ROUND($jml_ambil)) * 100;
        if ($persentase < 50) {
            $color = 'danger';
        } elseif ($persentase < 90) {
            $color = 'primary';
        } else {
            $color = 'success';
        }
    @endphp
    <div class="text-light small fw-medium mb-1">{{ $d->nama_produk }} {{ formatRupiah($jml_penjualan) }} /{{ formatRupiah($jml_ambil) }} </div>
    <div class="progress mb-2 " style="height: 16px;">
        <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ ROUND($persentase) }}%;"
            aria-valuenow="{{ ROUND($persentase) }}" aria-valuemin="0" aria-valuemax="100">
            {{ ROUND($persentase) }} %
        </div>
    </div>
@endforeach
