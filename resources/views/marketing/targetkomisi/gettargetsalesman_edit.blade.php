@foreach ($detail as $d)
   <tr>
      <td>
         <input type="hidden" name="kode_salesman[]" value="{{ $d->kode_salesman }}">
         {{ $d->kode_salesman }}
      </td>
      <td style="width: 30%">{{ $d->nama_salesman }}</td>
      @foreach ($produk as $p)
         <td>
            <input type="text" class="noborder-form text-end money" value="{{ formatAngka($d->{"target_$p->kode_produk"}) }}" name="{{ $p->kode_produk }}[]">
         </td>
      @endforeach
   </tr>
@endforeach
<script>
   $(".money").maskMoney();
</script>
