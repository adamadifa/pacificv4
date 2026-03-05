@foreach ($rekap as $d)
    <tr class="border-bottom-soft">
        <td class="ps-4 fw-bold text-dark table-sticky-first">
            <div class="d-flex align-items-center">
                <i class="ti ti-box me-2 text-info opacity-75"></i>
                {{ $d->kode_produk }}
            </div>
        </td>
        @for ($i = 1; $i <= 12; $i++)
            @php
                $currentVal = $d->{$nama_bulan_singkat[$i]};
                $prevVal = $i > 1 ? $d->{$nama_bulan_singkat[$i - 1]} : null;
                $color = 'dark';
                $trendIcon = '';

                if ($prevVal !== null) {
                    if ($currentVal > $prevVal) {
                        $color = 'success';
                        $trendIcon = '<i class="ti ti-trending-up fs-tiny me-1"></i>';
                    } elseif ($currentVal < $prevVal) {
                        $color = 'danger';
                        $trendIcon = '<i class="ti ti-trending-down fs-tiny me-1"></i>';
                    }
                }
            @endphp
            <td class="text-end pe-3 py-2">
                <div class="d-flex flex-column align-items-end">
                    <span class="fw-bold text-{{ $color }}">{{ formatAngka($currentVal) }}</span>
                    @if ($trendIcon)
                        <span class="text-{{ $color }} d-flex align-items-center" style="font-size: 0.65rem;">
                            {!! $trendIcon !!}
                        </span>
                    @endif
                </div>
            </td>
        @endfor
    </tr>
@endforeach

<style>
    .border-bottom-soft {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
    }
    .fs-tiny {
        font-size: 0.7rem;
    }
</style>
