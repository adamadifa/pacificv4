@php
    $posisi = [
        'D' => 'Driver',
        'H' => 'Helper',
        'G' => 'Gudang',
    ];
@endphp
@foreach ($detail as $d)
    <tr>
        <td>
            <input type="hidden" name="kode_driver_helper[]" value="{{ $d->kode_driver_helper }}">
            {{ $d->kode_driver_helper }}
        </td>
        <td>{{ $d->nama_driver_helper }}</td>
        <td>{{ $posisi[$d->posisi] }}</td>
        <td>
            <input type="text" class="noborder-form text-end" name="ratio_default[]" value="{{ formatAngkaDesimal($d->ratio_default) }}">
        </td>
        <td>
            <input type="text" class="noborder-form text-end" name="ratio_helper[]" value="{{ formatAngkaDesimal($d->ratio_helper) }}">
        </td>
    </tr>
@endforeach
