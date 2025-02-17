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
    <div class="row mt-2">
        <div class="col">
            <div class="table-responsive">
                <table id="example" class="display nowrap table table-striped table-bordered" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Kode</th>
                            <th rowspan="2">Nama Pelanggan</th>
                            <th colspan="3" class="text-center">Budget</th>
                            <th rowspan="2" class="text-center">Target</th>
                            <th class="text-center" colspan="3">Realisasi</th>
                            <th colspan="3" class="text-center">Reward</th>

                            <th rowspan="2">Pembayaran</th>
                            <th rowspan="2">No. Rekening</th>
                            <th rowspan="2">Pemilik</th>
                            <th rowspan="2">Bank</th>
                            <th rowspan="2"><i class="ti ti-moneybag"></i></th>
                        </tr>
                        <tr>
                            <th>SMM</th>
                            <th>RSM</th>
                            <th>GM</th>
                            <th>Tunai</th>
                            <th>Kredit</th>
                            <th>Total</th>
                            <th>Tunai</th>
                            <th>Kredit</th>
                            <th>Total</th>
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
                                $total_reward = $d->total_reward > 1000000 ? 1000000 : $d->total_reward;
                                $subtotal_reward += $total_reward;
                                $grandtotal_reward += $total_reward;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->kode_pelanggan }}</td>
                                <td>{{ $d->nama_pelanggan }}</td>
                                <td class="text-end">{{ formatAngka($d->budget_smm) }}</td>
                                <td class="text-end">{{ formatAngka($d->budget_rsm) }}</td>
                                <td class="text-end">{{ formatAngka($d->budget_gm) }}</td>
                                <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
                                <td class="text-center">{{ formatAngka($d->qty_tunai) }}</td>
                                <td class="text-center">{{ formatAngka($d->qty_kredit) }}</td>
                                <td class="text-center">
                                    <a href="#" class="btnDetailfaktur" kode_pelanggan="{{ $d['kode_pelanggan'] }}">
                                        {{ formatAngka($d->jumlah) }}
                                    </a>
                                </td>
                                <td class="text-end">{{ formatAngka($d->reward_tunai) }}</td>
                                <td class="text-end">{{ formatAngka($d->reward_kredit) }}</td>
                                <td class="text-end">{{ formatAngka($total_reward) }}</td>
                                <td>{{ $metode_pembayaran[$d->metode_pembayaran] }}</td>

                                <td>{{ $d->no_rekening }}</td>
                                <td>{{ $d->pemilik_rekening }}</td>
                                <td>{{ $d->bank }}</td>
                                <td>
                                    @if ($d->status_pencairan == '1')
                                        <i class="ti ti-checks text-success"></i>
                                    @else
                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                    @endif
                                </td>

                            </tr>
                            {{-- @if ($d->metode_pembayaran != $next_metode_pembayaran)
                                <tr class="table-dark">
                                    <td colspan="12">TOTAL REWARD </td>
                                    <td class="text-end">{{ formatAngka($subtotal_reward) }}</td>
                                    <td colspan="8"></td>
                                </tr>
                                @php
                                    $subtotal_reward = 0;
                                @endphp
                            @endif --}}
                        @endforeach
                    </tbody>
                    {{-- <tfoot class="table-dark">
                        <tr>
                            <td colspan="12">GRAND TOTAL REWARD </td>
                            <td class="text-end">{{ formatAngka($grandtotal_reward) }}</td>
                            <td colspan="8"></td>
                        </tr>
                    </tfoot> --}}
                </table>
            </div>
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
