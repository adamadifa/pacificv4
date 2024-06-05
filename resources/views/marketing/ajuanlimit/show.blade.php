<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="row">
            <div class="col">
                <table class="table">
                    <tr>
                        <th style="width:35%">No. Pengajuan</th>
                        <td>{{ $ajuanlimit->no_pengajuan }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ DateToIndo($ajuanlimit->tanggal) }}</td>
                    </tr>
                    <tr>
                        <th>Kode Pelanggan</th>
                        <td>{{ $ajuanlimit->kode_pelanggan }}</td>
                    </tr>
                    <tr>
                        <th>NIK</th>
                        <td>{{ $ajuanlimit->nik }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <td>{{ $ajuanlimit->nama_pelanggan }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ ucwords(strtolower($ajuanlimit->alamat_pelanggan)) }}</td>
                    </tr>
                    <tr>
                        <th>No. HP</th>
                        <td>{{ $ajuanlimit->no_hp_pelanggan }}</td>
                    </tr>
                    <tr>
                        <th>Cabang</th>
                        <td>{{ textUpperCase($ajuanlimit->nama_cabang) }}</td>
                    </tr>
                    <tr>
                        <th>Salesman</th>
                        <td>{{ textUpperCase($ajuanlimit->nama_salesman) }}</td>
                    </tr>
                    <tr>
                        <th>Routing</th>
                        <td>{{ $ajuanlimit->hari }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $ajuanlimit->latitude }},{{ $ajuanlimit->longitude }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Ajuan</th>
                        <td style="font-weight: bold">{{ formatAngka($ajuanlimit->jumlah) }}</td>
                    </tr>
                    <tr>
                        <th>LJT Ajuan</th>
                        <td style="font-weight: bold">{{ $ajuanlimit->ljt }} Hari</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-1 col-md-12 col-sm-12">
        <div class="divider divider-vertical">
            <div class="divider-text">
                <i class="ti ti-crown"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-md-12 col-sm-12">
        <div class="row mb-3">
            <div class="col">
                <table class="table mb-2">
                    <tr>
                        <th>Kepemilikan</th>
                        <td>{{ !empty($ajuanlimit->kepemilikan) ? $kepemilikan[$ajuanlimit->kepemilikan] : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Lama Berjualan</th>
                        <td>{{ !empty($ajuanlimit->lama_berjualan) ? $lama_berjualan[$ajuanlimit->lama_berjualan] : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Status Outlet</th>
                        <td>{{ !empty($ajuanlimit->status_outlet) ? $status_outlet[$ajuanlimit->status_outlet] : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Type Outlet</th>
                        <td>{{ !empty($ajuanlimit->type_outlet) ? $type_outlet[$ajuanlimit->type_outlet] : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Cara Pembayaran</th>
                        <td>{{ !empty($ajuanlimit->cara_pembayaran) ? $cara_pembayaran[$ajuanlimit->cara_pembayaran] : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Lama Langganan</th>
                        <td>{{ !empty($ajuanlimit->lama_langganan) ? $lama_langganan[$ajuanlimit->lama_langganan] : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Jaminan</th>
                        <td>{{ $ajuanlimit->jaminan == '1' ? 'Ada' : 'Tidak Ada' }}</td>
                    </tr>
                    <tr>
                        <th>Top UP Terakhir</th>
                        <td>{{ !empty($ajuanlimit->topup_terakhir) ? DateToIndo($ajuanlimit->topup_terakhir) : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Omset Toko</th>
                        <td>{{ formatAngka($ajuanlimit->omset_toko) }}</td>
                    </tr>
                    <tr>
                        <th>Faktur Belum Lunas</th>
                        <td>{{ $ajuanlimit->jml_faktur }}</td>
                    </tr>
                    <tr>
                        <th>Skor</th>
                        <td>{{ formatAngkaDesimal($ajuanlimit->skor) }}</td>
                    </tr>
                    <tr>
                        <th>Rekomendasi</th>
                        <td>
                            @php
                                if ($ajuanlimit->skor <= 2) {
                                    $rekomendasi = 'Tidak Layak';
                                } elseif ($ajuanlimit->skor > 2 && $ajuanlimit->skor <= 4) {
                                    $rekomendasi = 'Tidak Disarankan';
                                } elseif ($ajuanlimit->skor > 4 && $ajuanlimit->skor <= 6) {
                                    $rekomendasi = 'Beresiko';
                                } elseif ($ajuanlimit->skor > 6 && $ajuanlimit->skor <= 8.5) {
                                    $rekomendasi = 'Layak Dengan Pertimbangan';
                                } elseif ($ajuanlimit->skor > 8.5 && $ajuanlimit->skor <= 10) {
                                    $rekomendasi = 'Layak';
                                }

                                if ($ajuanlimit->skor <= 4) {
                                    $bg = 'danger';
                                } elseif ($ajuanlimit->skor <= 6) {
                                    $bg = 'warning';
                                } else {
                                    $bg = 'success';
                                }
                            @endphp
                            <span class="badge bg-{{ $bg }}">
                                {{ $rekomendasi }}
                            </span>

                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                @foreach ($disposisi as $index => $d)
                    @php
                        $next_role = @$disposisi[$index + 1]->role;
                    @endphp
                    @if ($d->role == $next_role)
                        @php
                            continue;
                        @endphp
                    @endif
                    <h6 style="line-height: 0" class="text-info">{{ $d->username }}
                        ({{ textCamelCase($d->role) }})
                    </h6>
                    <small class="text-muted">{{ $d->created_at }}</small>
                    <p>{{ $d->uraian_analisa }}</p>
                @endforeach
            </div>
        </div>
    </div>
</div>
