<form action="{{ route('ajuankumulatif.storeapprove', Crypt::encrypt($programkumulatif->no_pengajuan)) }}" method="POST">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>No. Pengajuan</th>
                    <td class="text-end">{{ $programkumulatif->no_pengajuan }}</td>
                </tr>
                <tr>
                    <th>No. Dokumen</th>
                    <td class="text-end">{{ $programkumulatif->nomor_dokumen }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($programkumulatif->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ $programkumulatif->kode_cabang }}</td>
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
                        <th>Pembayaran</th>
                        <th>No. Rekening</th>
                        <th>Doc</th>

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
                            <td>{{ $metode_pembayaran[$d->metode_pembayaran] }}</td>
                            <td>{{ $d->no_rekening }}</td>
                            <td>
                                @if ($d->file_doc != null)
                                    <a href="{{ asset('storage/ajuanprogramkumulatif/' . $d->file_doc) }}" target="_blank">
                                        <i class="ti ti-file-text"></i>
                                    </a>
                                @endif
                            </td>

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
