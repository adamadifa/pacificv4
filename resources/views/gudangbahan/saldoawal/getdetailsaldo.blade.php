@foreach ($barang as $d)
   <tr>
      <td>
         <input type="hidden" name="kode_barang[]" value="{{ $d->kode_barang }}">
         {{ $d->kode_barang }}
      </td>
      <td>{{ $d->nama_barang }}</td>
      <td>{{ $d->nama_kategori }}</td>
      <td class="text-end">
         @if ($readonly)
            <input type="hidden" name="qty_unit[]" value="{{ formatAngkaDesimal($d->saldo_unit) }}"
               style="text-align: right" class="noborder-form">
            {{ formatAngkaDesimal($d->saldo_unit) }}
         @else
            <input type="text" name="qty_unit[]" value="{{ formatAngkaDesimal($d->saldo_unit) }}"
               style="text-align: right" class="noborder-form money">
         @endif
      </td>
      <td class="text-end">
         @if ($readonly)
            <input type="hidden" name="qty_berat[]" value="{{ formatAngkaDesimal($d->saldo_berat) }}"
               style="text-align: right" class="noborder-form">
            {{ formatAngkaDesimal($d->saldo_berat) }}
         @else
            <input type="text" name="qty_berat[]" value="{{ formatAngkaDesimal($d->saldo_berat) }}"
               style="text-align: right" class="noborder-form money">
         @endif
      </td>
   </tr>
@endforeach
