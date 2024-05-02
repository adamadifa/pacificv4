@foreach ($barang as $d)
   @php
      $saldo_akhir = $d->saldo_awal_jumlah + $d->bm_jumlah - $d->bk_jumlah;
   @endphp
   @if (!empty($saldo_akhir))
      <tr>
         <td>
            <input type="hidden" name="kode_barang[]" value="{{ $d->kode_barang }}">
            {{ $d->kode_barang }}
         </td>
         <td>{{ $d->nama_barang }}</td>
         <td class="text-end">
            <input type="text" class="noborder-form text-end number-separator" name="jumlah[]" value="{{ formatAngkaDesimal($saldo_akhir) }}">
         </td>
      </tr>
   @endif
@endforeach
<script>
   easyNumberSeparator({
      selector: '.number-separator',
      separator: '.',
      decimalSeparator: ',',
   });
</script>
