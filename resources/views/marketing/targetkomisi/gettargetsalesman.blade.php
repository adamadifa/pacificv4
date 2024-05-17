@foreach ($salesman as $d)
   <tr>
      <td>
         <input type="hidden" name="kode_salesman[]" value="{{ $d->kode_salesman }}">
         {{ $d->kode_salesman }}
      </td>
      <td style="width: 30%">{{ $d->nama_salesman }}</td>
      @foreach ($produk as $d)
         <td>
            <input type="text" class="noborder-form text-end" name="{{ $d->kode_produk }}[]">
         </td>
      @endforeach
   </tr>
@endforeach
