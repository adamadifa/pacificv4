@foreach ($mutasi as $d)
   @php
      $mutasi_in = ['RETUR', 'HUTANG KIRIM'];
      if (in_array($d->jenis_mutasi, $mutasi_in)) {
          $color = '#28c76f1a';
      } else {
          $color = '#ea54552e';
      }
   @endphp
   <tr style="background-color:{{ $color }}">
      <td>{{ $d->no_mutasi }}</td>
      <td>{{ DateToIndo($d->tanggal) }}</td>
      <td>{{ $d->jenis_mutasi }}</td>
      <td>
         <div class="d-flex">
            @can('mutasidpb.edit')
               <div>
                  <a href="#" class="me-2 btnEditmutasidpb"
                     no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                     <i class="ti ti-edit text-success"></i>
                  </a>
               </div>
            @endcan
            @can('mutasidpb.show')
               <div>
                  <a href="#" class="me-2 btnShowmutasidpb"
                     no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                     <i class="ti ti-file-description text-info"></i>
                  </a>
               </div>
            @endcan

            @can('mutasidpb.delete')
               <div>
                  <a href="#" class="btnDeletemutasidpb ml-1" no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                     <i class="ti ti-trash text-danger"></i>
                  </a>
               </div>
            @endcan
         </div>
      </td>
   </tr>
@endforeach
