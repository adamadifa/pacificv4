@foreach ($target as $d)
    @php
        $realisasi = $d->realisasi / $d->isi_pcs_dus;
        $persentase = !empty($d->jumlah_target) ? (ROUND($realisasi) / $d->jumlah_target) * 100 : 0;
        if ($persentase < 50) {
            $color = 'danger';
        } elseif ($persentase < 90) {
            $color = 'primary';
        } else {
            $color = 'success';
        }
    @endphp
    <div class="text-light small fw-medium mb-1">{{ $d->nama_produk }} {{ formatRupiah($realisasi) }} /{{ formatAngka($d->jumlah_target) }} </div>
    <div class="progress mb-2 " style="height: 16px;">
        <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ ROUND($persentase) }}%;"
            aria-valuenow="{{ ROUND($persentase) }}" aria-valuemin="0" aria-valuemax="100">
            {{ ROUND($persentase) }} %
        </div>
    </div>
@endforeach
