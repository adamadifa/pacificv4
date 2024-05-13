@foreach ($produk as $d)
   @php
      $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->saldo_akhir));
      $jumlah_dus = $jumlah[0];
      $jumlah_pack = $jumlah[1];
      $jumlah_pcs = $jumlah[2];
      $desimal = formatAngkaDesimal3($d->saldo_akhir);
   @endphp
   <tr>
      <td>
         <input type="hidden" class="kode_produk" name="kode_produk[]"
            value="{{ $d->kode_produk }}">
         <input type="hidden" class="isi_pcs_dus" name="isi_pcs_dus[]"
            value="{{ $d->isi_pcs_dus }}">
         <input type="hidden" class="isi_pcs_pack" name="isi_pcs_pack[]"
            value="{{ $d->isi_pcs_pack }}">
         {{ $d->kode_produk }}
      </td>
      <td>{{ $d->nama_produk }}</td>
      <td class="text-end">
         @if ($readonly)
            <input type="hidden" name="jml_dus[]"
               value="{{ formatAngka($jumlah_dus) }}" style="text-align: right"
               class="noborder-form">
            {{ formatAngka($jumlah_dus) }}
         @else
            <input type="text" name="jml_dus[]" value="{{ formatAngka($jumlah_dus) }}" style="text-align: right"
               class="noborder-form money">
         @endif
      </td>
      <td class="text-end">
         @if ($readonly)
            <input type="hidden" name="jml_pack[]"
               value="{{ formatAngka($jumlah_pack) }}" style="text-align: right"
               class="noborder-form">
            {{ formatAngka($jumlah_pack) }}
         @else
            <input type="text" name="jml_pack[]" value="{{ formatAngka($jumlah_pack) }}" style="text-align: right"
               class="noborder-form money">
         @endif
      </td>
      <td class="text-end">
         @if ($readonly)
            <input type="hidden" name="jml_pcs[]"
               value="{{ formatAngka($jumlah_pcs) }}" style="text-align: right"
               class="noborder-form">
            {{ formatAngka($jumlah_pcs) }}
         @else
            <input type="text" name="jml_pcs[]" value="{{ formatAngka($jumlah_pcs) }}" style="text-align: right"
               class="noborder-form money">
         @endif
      </td>
   </tr>
@endforeach

<script>
   $(".money").maskMoney();
</script>
