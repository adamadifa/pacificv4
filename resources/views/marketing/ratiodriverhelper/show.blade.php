<div class="row">
   <div class="col">
      <table class="table">
         <tr>
            <th style="width: 30%">Kode Ratio</th>
            <td>{{ $ratiodriverhelper->kode_ratio }}</td>
         </tr>
         <tr>
            <th>Bulan</th>
            <td>{{ $namabulan[$ratiodriverhelper->bulan] }}</td>
         </tr>
         <tr>
            <th>Tahun</th>
            <td>{{ $ratiodriverhelper->tahun }}</td>
         </tr>
         <tr>
            <th>Cabang</th>
            <td>{{ $ratiodriverhelper->nama_cabang }}</td>
         </tr>
         <tr>
            <th>Berlaku</th>
            <td>{{ DateToIndo($ratiodriverhelper->tanggal_berlaku) }}</td>
         </tr>
      </table>
   </div>
</div>
<div class="row">
   <div class="col">
      <table class="table table-bordered table-striped table-hover">
         <thead class="table-dark">
            <tr>
               <th>Kode</th>
               <th>Nama Driver / Helper</th>
               <th>Ratio Default</th>
               <th>Ratio Helper</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($detail as $d)
               <tr>
                  <td>{{ $d->kode_driver_helper }}</td>
                  <td>{{ $d->nama_driver_helper }}</td>
                  <td class="text-end">{{ formatAngkaDesimal($d->ratio_default) }}</td>
                  <td class="text-end">{{ formatAngkaDesimal($d->ratio_helper) }}</td>
               </tr>
            @endforeach
         </tbody>
      </table>
   </div>
</div>
