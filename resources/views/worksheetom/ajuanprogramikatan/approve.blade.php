<form action="{{ route('ajuanprogramikatan.storeapprove', Crypt::encrypt($programikatan->no_pengajuan)) }}" method="POST">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>No. Pengajuan</th>
                    <td class="text-end">{{ $programikatan->no_pengajuan }}</td>
                </tr>
                <tr>
                    <th>No. Dokumen</th>
                    <td class="text-end">{{ $programikatan->nomor_dokumen }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($programikatan->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Periode Penjualan</th>
                    <td class="text-end">{{ DateToIndo($programikatan->periode_dari) }} s.d
                        {{ DateToIndo($programikatan->periode_sampai) }}</td>
                </tr>
                <tr>
                    <th>Program</th>
                    <td class="text-end">{{ $programikatan->nama_program }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ $programikatan->kode_cabang }}</td>
                </tr>

            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>Kode</th>
                        <th>Nama Pelanggan</th>
                        <th class="text-center">Avg Penjualan</th>
                        <th class="text-center">Qty Target</th>
                        <th>Reward</th>
                        <th>Budget</th>
                        <th>Pembayaran</th>
                        <th>No. Rekening</th>

                    </tr>
                </thead>
                <tbody>
                    @php
                        $metode_pembayaran = [
                            'TN' => 'Tunai',
                            'TF' => 'Transfer',
                            'VC' => 'Voucher',
                        ];
                    @endphp
                    @foreach ($detail as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_avg) }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
                            <td class="text-end">{{ formatAngka($d->reward) }}</td>
                            <td>{{ $d->budget }}</td>
                            <td>{{ $metode_pembayaran[$d->metode_pembayaran] }}</td>
                            <td>{{ $d->no_rekening }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-thumb-up me-1"></i>Approve</button></button>
        </div>
        <div class="col">
            <button class="btn btn-danger w-100" id="btnSimpan" name="decline" value="1"><i
                    class="ti ti-thumb-down me-1"></i>Tolak</button></button>
        </div>
    </div>
</form>
