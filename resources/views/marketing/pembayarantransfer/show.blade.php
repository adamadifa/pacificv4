<div class="row">
   <div class="col">
      <table class="table">
         <tr>
            <th>Kode Transfer</th>
            <td>{{ $transfer->kode_transfer }}</td>
         </tr>
         <tr>
            <th>Tanggal</th>
            <td>{{ DateToIndo($transfer->tanggal) }}</td>
         </tr>
         <tr>
            <th>Kode Pelanggan</th>
            <td>{{ $transfer->kode_pelanggan }}</td>
         </tr>
         <tr>
            <th>Nama Pelanggan</th>
            <td>{{ $transfer->nama_pelanggan }}</td>
         </tr>
         <tr>
            <th>Bank Pengirim</th>
            <td>{{ $transfer->bank_pengirim }}</td>
         </tr>
         <tr>
            <th>Jatuh Tempo</th>
            <td>{{ DateToIndo($transfer->jatuh_tempo) }}</td>
         </tr>
         <tr>
            <th>Status</th>
            <td>
               @if ($transfer->status == '1')
                  <span class="badge bg-success">{{ DateToIndo($transfer->tanggal_diterima) }}</span>
               @elseif($transfer->status == '2')
                  <i class="ti ti-square-rounded-x text-danger"></i>
               @else
                  <i class="ti ti-hourglass-empty text-warning"></i>
               @endif
            </td>
         </tr>
         @if ($transfer->status == '1')
            <tr>
               <th>No. Bukti Ledger</th>
               <td>{{ $transfer->no_bukti }}</td>
            </tr>
         @endif
      </table>
   </div>
</div>
<div class="row mt-3">
   <div class="col">
      <table class="table table-bordered table-striped table-hover">
         <thead class="table-dark">
            <tr>
               <th>No. Faktur</th>
               <th>Jumlah</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($detail as $d)
               <tr>
                  <td>{{ $d->no_faktur }}</td>
                  <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
               </tr>
            @endforeach
         </tbody>
      </table>
   </div>
</div>
