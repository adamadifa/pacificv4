@foreach ($unpaidsales as $d)
   <tr>
      <td>{{ $d->no_faktur }}</td>
      <td class="text-end">{{ formatRupiah($d->sisa_piutang) }}</td>
   </tr>
@endforeach
