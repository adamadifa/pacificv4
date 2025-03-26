<form action="{{ route('ticketupdate.storeapprove', Crypt::encrypt($ticket->kode_pengajuan)) }}" method="POST" id="formApprove">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ $ticket->tanggal }}</td>
                </tr>
                <tr>
                    <th>User</th>
                    <td class="text-end">{{ $ticket->name }}</td>
                </tr>
                <tr>
                    @php
                        $kategoriOptions = [
                            '1' => 'Penjualan',
                            '2' => 'Pembayaran',
                            '3' => 'Retur',
                            '4' => 'DPB',
                            '5' => 'Mutasi Persediaan',
                        ];
                    @endphp
                    <th>Kategori</th>
                    <td class="text-end">{{ $kategoriOptions[$ticket->kategori] }} </td>
                </tr>
                <tr>
                    <th>No. Bukti</th>
                    <td class="text-end">{{ $ticket->no_bukti }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row my-4">
        <div class="col">
            {{ $ticket->keterangan }}
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div class="form-group">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-thumb-up me-1"></i>Approve</button>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <button class="btn btn-danger w-100" id="btnSimpan" name="decline" value="1"><i class="ti ti-thumb-down me-1"></i>Tolak</button>
            </div>
        </div>
    </div>
</form>
