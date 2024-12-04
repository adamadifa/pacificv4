@php
    $total_reward = 0;
@endphp
@foreach ($peserta as $d)
    @php
        $color_reward = $d->jml_dus >= $d->qty_target ? 'bg-success text-white' : 'bg-danger text-white';
        if ($d->jml_dus >= $d->qty_target) {
            $reward = $d->reward * $d->jml_dus;
        } else {
            $reward = 0;
        }
        $total_reward += $reward;
    @endphp

    <tr class=" {{ $color_reward }}">
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->kode_pelanggan }}</td>
        <td>{{ $d->nama_pelanggan }}</td>
        <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
        <td class="text-end">{{ formatAngka($d->jml_dus) }}</td>
        <td class="text-end">{{ formatAngka($d->reward) }}</td>
        <td class="text-end">
            {{ formatAngka($reward) }}
        </td>
        <td></td>
    </tr>
@endforeach
<tr class="table-dark">
    <td colspan="6" class="text-end">TOTAL REWARD</td>
    <td class="text-end">{{ formatAngka($total_reward) }}</td>
    <td></td>
</tr>
