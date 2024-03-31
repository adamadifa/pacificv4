@foreach ($rekap as $d)
    <tr>
        <td>{{ $d->kode_produk }}</td>
        @for ($i = 1; $i <= 12; $i++)
            @if ($i != 1)
                @if ($d->{$nama_bulan_singkat[$i]} > $d->{$nama_bulan_singkat[$i - 1]})
                    @php
                        $icon = '';
                        $color = 'success';
                    @endphp
                @elseif ($d->{$nama_bulan_singkat[$i]} < $d->{$nama_bulan_singkat[$i - 1]})
                    @php
                        $icon = '';
                        $color = 'danger';
                    @endphp
                @else
                    @php
                        $icon = '';
                        $color = 'primary';
                    @endphp
                @endif
            @else
                @php
                    $icon = '';
                    $color = '';
                @endphp
            @endif
            <td class="text-end text-{{ $color }}">
                @if (!empty($icon))
                    <i class="ti ti-{{ $icon }}"></i>
                @endif
                {{ formatAngka($d->{$nama_bulan_singkat[$i]}) }}
            </td>
        @endfor
    </tr>
@endforeach
