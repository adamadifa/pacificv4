<form action="{{ route('targetkomisi.approvestore', Crypt::encrypt($targetkomisi->kode_target)) }}">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th style="width: 20%">Kode Target</th>
                    <td>{{ $targetkomisi->kode_target }}</td>
                </tr>
                <tr>
                    <th>Bulan</th>
                    <td>{{ $namabulan[$targetkomisi->bulan] }}</td>
                </tr>
                <tr>
                    <th>Tahun</th>
                    <td>{{ $targetkomisi->tahun }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td>{{ $targetkomisi->nama_cabang }}</td>
                </tr>
            </table>

        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th style="width: 20%">Kode Target</th>
                    <td>{{ $targetkomisi->kode_target }}</td>
                </tr>
                <tr>
                    <th>Bulan</th>
                    <td>{{ $namabulan[$targetkomisi->bulan] }}</td>
                </tr>
                <tr>
                    <th>Tahun</th>
                    <td>{{ $targetkomisi->tahun }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td>{{ $targetkomisi->nama_cabang }}</td>
                </tr>
            </table>

        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <table class="table table-bordered  table-hover">
                <thead class="table-dark" style="width: 150%">
                    <tr>
                        <th rowspan="2" align="middle">Kode</th>
                        <th rowspan="2" align="middle">NIK</th>
                        <th rowspan="2" align="middle">Salesman</th>
                        <th rowspan="2" align="middle">Masa Kerja</th>
                        <th colspan="{{ count($produk) * 3 }}" class="text-center">Produk</th>
                    </tr>
                    <tr>
                        @foreach ($produk as $d)
                            <th class="text-center" colspan="3">
                                {{ $d->kode_produk }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail as $d)
                        <tr>
                            <td>{{ $d->kode_salesman }}</td>
                            <td>{{ $d->nik }}</td>
                            <td>{{ $d->nama_salesman }}</td>
                            <td>
                                @php
                                    $end_date = $targetkomisi->tahun . '-' . $targetkomisi->bulan . '-01';
                                    $masakerja = hitungMasakerja($d->tanggal_masuk, $end_date);
                                @endphp
                                @if (!empty($d->tanggal_masuk))
                                    {{ $masakerja['tahun'] }} Tahun {{ $masakerja['bulan'] }} Bulan
                                @endif
                            </td>
                            @foreach ($produk as $p)
                                @php
                                    $rata_rata_penjualan = $d->{"penjualan_$p->kode_produk"} / $p->isi_pcs_dus / 3;
                                    $jml_penjualan = $d->{"penjualan_$p->kode_produk"} / $p->isi_pcs_dus;
                                @endphp
                                <td class="text-end bg-success text-white">{{ formatAngka($rata_rata_penjualan) }}</td>
                                <td class="text-end bg-info text-white">{{ formatAngka($jml_penjualan) }}</td>
                                <td class="text-end">{{ formatAngka($d->{"target_$p->kode_produk"}) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <td class="bg-success"></td>
                    <td>Rata Rata Penjualan 3 Bulan Terakhir</td>
                </tr>
                <tr>
                    <td class="bg-info"></td>
                    <td>Realisasi Selama 3 Bulan</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <x-textarea label="Catatan" name="catatan" />
            <div class="form-group mb-3">

                @if (auth()->user()->roles->pluck('name')[0] == 'regional sales manager')
                    <button class="btn btn-primary w-100"><i class="ti ti-thumb-up me-1"></i>Setuju dan Teruskan Ke GM Marketing </button>
                @elseif (auth()->user()->roles->pluck('name')[0] == 'gm marketing')
                    <button class="btn btn-primary w-100"><i class="ti ti-thumb-up me-1"></i>Setuju dan Teruskan Ke Direktur </button>
                @else
                    <button class="btn btn-primary w-100"><i class="ti ti-thumb-up me-1"></i>Setuju </button>
                @endif
            </div>
        </div>
    </div>

</form>
