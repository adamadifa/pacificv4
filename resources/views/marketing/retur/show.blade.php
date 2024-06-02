<table class="table ">
   <tr>
      <th style="width: 15%">No. Retur</th>
      <td>{{ $retur->no_retur }}</td>
   </tr>
   <tr>
      <th>Tanggal</th>
      <td>{{ DateToIndo($retur->tanggal) }}</td>
   </tr>
   <tr>
      <th>No. Faktur</th>
      <td>{{ $retur->no_faktur }}</td>
   </tr>
   <tr>
      <th>Pelanggan</th>
      <td>{{ $retur->kode_pelanggan }} - {{ textUpperCase($retur->nama_pelanggan) }}</td>
   </tr>
   <tr>
      <th>Alamat</th>
      <td>{{ ucwords(strtolower($retur->alamat_pelanggan)) }}</td>
   </tr>
   <tr>
      <th>Salesman</th>
      <td>{{ $retur->nama_salesman }}</td>
   </tr>
   <tr>
      <th>Cabang</th>
      <td>{{ textUpperCase($retur->nama_cabang) }}</td>
   </tr>
</table>

<table class="table bordered table-hover table-striped">
   <thead class="table-dark">
      <tr>
         <th>Kode</th>
         <th>Nama Produk</th>
         <th>Dus</th>
         <th>Harga</th>
         <th>Pack</th>
         <th>Harga</th>
         <th>Pcs</th>
         <th>Harga</th>
         <th>Total</th>
      </tr>
   </thead>
   <tbody>
      @php
         $total = 0;
      @endphp
      @foreach ($detail as $d)
         @php
            $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
            $jumlah_dus = $jumlah[0];
            $jumlah_pack = $jumlah[1];
            $jumlah_pcs = $jumlah[2];
            $total += $d->subtotal;

         @endphp
         <tr>
            <td>{{ $d->kode_produk }}</td>
            <td>{{ $d->nama_produk }}</td>
            <td class="text-center">{{ formatAngka($jumlah_dus) }}</td>
            <td class="text-end">{{ formatAngka($d->harga_dus) }}</td>
            <td class="text-center">{{ formatAngka($jumlah_pack) }}</td>
            <td class="text-end">{{ formatAngka($d->harga_pack) }}</td>
            <td class="text-center">{{ formatAngka($jumlah_pcs) }}</td>
            <td class="text-end">{{ formatAngka($d->harga_pcs) }}</td>
            <td class="text-end">{{ formatAngka($d->subtotal) }}</td>
         </tr>
      @endforeach
   </tbody>
   <tfoot class="table-dark">
      <tr>
         <td colspan="8">TOTAL</td>
         <td class="text-end">{{ formatAngka($total) }}</td>
      </tr>
      <tr>
         @php
            if ($retur->jenis_retur == 'GB') {
                $color = 'success';
                $ket = 'GANTI BARANG';
            } else {
                $color = 'danger';
                $ket = 'POTONG FAKTUR';
            }
         @endphp
         <td colspan="9" class="bg-{{ $color }} text-center">
            {{ $ket }}
         </td>
      </tr>
   </tfoot>
</table>
