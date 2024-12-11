<form action="{{ route('pencairanprogramikatan.storeapprove', Crypt::encrypt($pencairanprogram->kode_pencairan)) }}" method="POST">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode Pencairan</th>
                    <td class="text-end">{{ $pencairanprogram->kode_pencairan }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($pencairanprogram->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Periode Penjualan</th>
                    <td class="text-end">{{ $namabulan[$pencairanprogram->bulan] }} {{ $pencairanprogram->tahun }}</td>
                </tr>
                <tr>
                    <th>No. Dokumen</th>
                    <td class="text-end">{{ $pencairanprogram->nomor_dokumen }}</td>
                </tr>
                <tr>
                    <th>Program</th>
                    <td class="text-end">{{ $pencairanprogram->nama_program }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ strtoupper($pencairanprogram->nama_cabang) }}</td>
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
                        <th>Kode </th>
                        <th> Pelanggan</th>
                        <th class="text-center">Target</th>
                        <th class="text-center">Realisasi</th>
                        <th>Reward</th>
                        <th>Budget</th>
                        <th>Pembayaran</th>
                        <th>No. Rekening</th>
                        <th>Pemilik</th>
                        <th>Bank</th>
                        <th>Total Reward</th>
                    </tr>

                </thead>
                <tbody id="loaddetailpencairan">
                    @php
                        $metode_pembayaran = [
                            'TN' => 'Tunai',
                            'TF' => 'Transfer',
                            'VC' => 'Voucher',
                        ];
                        $subtotal_reward = 0;
                        $grandtotal_reward = 0;
                    @endphp
                    @foreach ($detail as $key => $d)
                        @php
                            $next_metode_pembayaran = @$detail[$key + 1]->metode_pembayaran;
                            $total_reward = $d->reward * $d->jumlah;
                            $subtotal_reward += $total_reward;
                            $grandtotal_reward += $total_reward;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
                            <td class="text-center">{{ formatAngka($d->jumlah) }}</td>
                            <td class="text-end">{{ formatAngka($d->reward) }}</td>
                            <td class="text-center">{{ $d->budget }}</td>
                            <td>{{ $metode_pembayaran[$d->metode_pembayaran] }}</td>
                            <td>{{ $d->no_rekening }}</td>
                            <td></td>
                            <td></td>
                            <td class="text-end">{{ formatAngka($total_reward) }}</td>
                        </tr>
                        @if ($d->metode_pembayaran != $next_metode_pembayaran)
                            <tr class="table-dark">
                                <td colspan="11">TOTAL REWARD </td>
                                <td class="text-end">{{ formatAngka($subtotal_reward) }}</td>
                            </tr>
                            @php
                                $subtotal_reward = 0;
                            @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="11">GRAND TOTAL REWARD </td>
                        <td class="text-end">{{ formatAngka($grandtotal_reward) }}</td>
                    </tr>
                </tfoot>
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
