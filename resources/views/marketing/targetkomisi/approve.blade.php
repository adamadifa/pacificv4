<form action="#">
   <div class="row">
      <div class="col">
         <table class="table">
            <tr>
               <th style="width: 20%">Kode Target</th>
               <td>{{ $targetkomisi->kode_target }}</td>
            </tr>
            <tr>
               <th>Bulan</th>
               <td>{{ $namabulan[$targetkomisi->bulan] }}</td>
            </tr>
            <tr>
               <th>Tahun</th>
               <td>{{ $targetkomisi->tahun }}</td>
            </tr>
            <tr>
               <th>Cabang</th>
               <td>{{ $targetkomisi->nama_cabang }}</td>
            </tr>
         </table>

      </div>
   </div>
   <div class="row mt-2">
      <div class="col">
         <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
               <tr>
                  <th rowspan="2" align="middle">Kode</th>
                  <th rowspan="2" align="middle">Salesman</th>
                  <th colspan="{{ count($produk) }}" class="text-center">Produk</th>
               </tr>
               <tr>
                  @foreach ($produk as $d)
                     <th class="text-center">
                        {{ $d->kode_produk }}
                     </th>
                  @endforeach
               </tr>
            </thead>
            <tbody>
               @foreach ($produk as $d)
                  @php
                     ${"total_$d->kode_produk"} = 0;
                  @endphp
               @endforeach
               @foreach ($detail as $d)
                  <tr>
                     <td>{{ $d->kode_salesman }}</td>
                     <td>{{ $d->nama_salesman }}</td>
                     @foreach ($produk as $p)
                        @php
                           ${"total_$p->kode_produk"} += $d->{"target_$p->kode_produk"};
                        @endphp
                        <td class="text-end">{{ formatAngka($d->{"target_$p->kode_produk"}) }}</td>
                     @endforeach
                  </tr>
               @endforeach
            </tbody>
            <tfoot class="table-dark">
               <tr>
                  <th colspan="2">TOTAL</th>
                  @foreach ($produk as $d)
                     <th class="text-end">{{ formatAngka(${"total_$d->kode_produk"}) }}</th>
                  @endforeach
               </tr>


            </tfoot>
         </table>
      </div>
   </div>
   <div class="row mt-2">
      <div class="col">
         <x-textarea label="Catatan" name="catatan" />
         <div class="form-group mb-3">
            <button class="btn btn-primary w-100"><i class="ti ti-thumb-up me-1"></i>Setuju dan Teruskan Ke </button>
         </div>
      </div>
   </div>

</form>
