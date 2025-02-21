<form action="{{ route('pencairanprogram.storeapprove', Crypt::encrypt($pencairanprogram->kode_pencairan)) }}" method="POST">
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
                    <th>Program</th>
                    <td class="text-end">{{ $pencairanprogram->kode_program == 'PR001' ? 'BB & DP' : 'AIDA' }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ $pencairanprogram->kode_cabang }}</td>
                </tr>

            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2" class="text-center" valign="middle">Nik</th>
                        <th rowspan="2" class="text-center" valign="middle">Kode Pel</th>
                        <th rowspan="2" valign="middle">Nama Pelanggan</th>
                        <th rowspan="2" class="text-center" valign="middle">Qty</th>
                        <th colspan="2" class="text-center" valign="middle">Diskon</th>
                        <th rowspan="2" class="text-center" valign="middle">Cashback</th>
                        <th rowspan="2" class="text-center" valign="middle"><i class="ti ti-moneybag"></i></th>
                        <th rowspan="2" class="text-center" valign="middle">#</th>
                    </tr>
                    <tr>
                        <th>Reguler</th>
                        <th>Kumulatif</th>
                    </tr>
                </thead>
                <tbody id="loaddetailpencairan">
                    @php
                        $total_cashback = 0;
                        $metode_pembayaran = [
                            'TN' => 'Tunai',
                            'TF' => 'Transfer',
                            'VC' => 'Voucher',
                        ];
                    @endphp
                    @foreach ($detailpencairan as $d)
                        @php
                            $cashback = $d->diskon_kumulatif - $d->diskon_reguler;
                            $total_cashback += $cashback;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                            <td class="text-end">{{ formatAngka($d->diskon_reguler) }}</td>
                            <td class="text-end">{{ formatAngka($d->diskon_kumulatif) }}</td>
                            <td class="text-end">{{ formatAngka($cashback) }}</td>
                            <td>{{ !empty($d->metode_bayar) ? $metode_pembayaran[$d->metode_bayar] : '-' }}</td>
                            <td>
                                <a href="#" class="btnDetailfaktur me-1" kode_pelanggan="{{ $d->kode_pelanggan }}"
                                    kode_pencairan="{{ Crypt::encrypt($pencairanprogram->kode_pencairan) }}">
                                    <i class="ti ti-file-description"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-end">GRAND TOTAL CASHBACK</td>
                        <td class="text-end">{{ formatAngka($total_cashback) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-thumb-up me-1"></i>Approve</button></button>
        </div>
        @if ($level_user == 'manager keuangan')
            <div class="col">
                <button class="btn btn-danger w-100" id="btnSimpan" name="cancel" value="1"><i
                        class="ti ti-thumb-down me-1"></i>Batalkan</button></button>
            </div>
        @else
            <div class="col">
                <button class="btn btn-danger w-100" id="btnSimpan" name="decline" value="1"><i
                        class="ti ti-thumb-down me-1"></i>Tolak</button></button>
            </div>
        @endif

    </div>
</form>
