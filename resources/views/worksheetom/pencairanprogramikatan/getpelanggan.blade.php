@php
    $total_reward = 0;
    $color_reward = '';
    $status = 0;
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
        $status = $reward == 0 ? 0 : 1;
    @endphp

    <tr class=" {{ $color_reward }}">
        <td>{{ $loop->iteration }}</td>
        <td>
            <input type="hidden" name="kode_pelanggan[]" value="{{ $d->kode_pelanggan }}">
            <input type="hidden" name="status[]" value="{{ $status }}">
            {{ $d->kode_pelanggan }}
        </td>
        <td>{{ $d->nama_pelanggan }}</td>
        <td class="text-center">
            {{ formatAngka($d->qty_target) }}
        </td>
        <td class="text-end">
            <input type="hidden" name="jumlah[]" value="{{ $d->jml_dus }}">
            {{ formatAngka($d->jml_dus) }}
        </td>
        <td class="text-end">
            {{ formatAngka($d->reward) }}
        </td>
        <td class="text-end">
            {{ formatAngka($reward) }}
        </td>
        <td>
            @if ($d->jml_dus >= $d->qty_target)
                <select name="status_pencairan[]" id="status_pencairan" class="form-select">
                    <option value="1">Cairkan</option>
                    <option value="0">Simpan</option>
                </select>
            @else
                <input type="hidden" name="status_pencairan[]" value="0">
            @endif

        </td>
        <td>
            @if ($d->jml_dus >= $d->qty_target)
                <div class="form-check mt-3 mb-2">
                    <input class="form-check-input checkpelanggan" name="checkpelanggan[]" value="1" type="checkbox" id="checkpelanggan">
                </div>
            @endif
        </td>
    </tr>
@endforeach
{{-- <tr class="table-dark">
    <td colspan="6" class="text-end">TOTAL REWARD</td>
    <td class="text-end">{{ formatAngka($total_reward) }}</td>
    <td></td>
</tr> --}}
