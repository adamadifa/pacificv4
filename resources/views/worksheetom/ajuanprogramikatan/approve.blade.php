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
                        <th rowspan="2">No.</th>
                        <th rowspan="2">Kode</th>
                        <th rowspan="2" style="width: 15%">Nama </th>
                        <th rowspan="2" class="text-center">Avg </th>
                        <th rowspan="2" class="text-center">Target</th>
                        <th rowspan="2" class="text-center">%</th>
                        <th rowspan="2">Reward</th>
                        <th rowspan="2">TOP</th>
                        <th colspan="3">Budget</th>
                        <th rowspan="2">PMB</th>
                        <th rowspan="2">Pencairan</th>
                        <th rowspan="2">Doc</th>
                        <th rowspan="2">#</th>
                    </tr>
                    <tr>
                        <th>SMM</th>
                        <th>RSM</th>
                        <th>GM</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px !important">
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
                            <td class="text-center">{{ formatAngka($d->qty_rata_rata) }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
                            <td class="text-center">
                                @php
                                    $kenaikan = $d->qty_target - $d->qty_rata_rata;
                                    $persentase = !empty($d->qty_rata_rata) ? ($kenaikan / $d->qty_rata_rata) * 100 : 0;
                                    $persentase = number_format($persentase, 2);
                                @endphp
                                {{ $persentase }}%
                            </td>
                            <td class="text-end">{{ formatAngka($d->reward) }}</td>
                            <td class="text-end">{{ $d->top }}</td>
                            <td class="text-end">{{ formatAngka($d->budget_smm) }}</td>
                            <td class="text-end">{{ formatAngka($d->budget_rsm) }}</td>
                            <td class="text-end">{{ formatAngka($d->budget_gm) }}</td>
                            <td>{{ $metode_pembayaran[$d->metode_pembayaran] }}</td>
                            <td class="text-end">{{ $d->periode_pencairan }} Bulan</td>
                            <td>
                                @if ($d->file_doc != null)
                                    <a href="{{ asset('storage/ajuanprogramikatan/' . $d->file_doc) }}" target="_blank">
                                        <i class="ti ti-file-text"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <a href="#" class="btnDetailTarget" kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}"
                                    no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                    <i class="ti ti-file-description"></i>
                                </a>
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
