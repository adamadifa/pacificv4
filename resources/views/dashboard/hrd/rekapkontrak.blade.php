<h4>Karyawan Habis Kontrak</h4>
<div class="nav-align-top nav-tabs-shadow mb-4">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#lewatjatuhtempo" aria-controls="lewatjatuhtempo"
                aria-selected="true">
                Jatuh Tempo
                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1">
                    {{ count($kontrak_lewat) }}
                </span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#bulanini" aria-controls="bulanini"
                aria-selected="false" tabindex="-1">
                Bulan Ini
                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1">
                    {{ count($kontrak_bulanini) }}
                </span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#bulandepan" aria-controls="bulandepan"
                aria-selected="false" tabindex="-1">
                Bulan Depan
                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-warning ms-1">
                    {{ count($kontrak_bulandepan) }}
                </span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#duabulan" aria-controls="duabulan"
                aria-selected="false" tabindex="-1">
                2 Bulan
                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-success ms-1">
                    {{ count($kontrak_duabulan) }}
                </span>
            </button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade " id="lewatjatuhtempo" role="tabpanel">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No. Kontrak</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Dept</th>
                        <th>Cabang</th>
                        <th>Akhir Kontrak</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kontrak_lewat as $d)
                        <tr>
                            <td>{{ $d->no_kontrak }}</td>
                            <td>{{ $d->nik }}</td>
                            <td>{{ formatName($d->nama_karyawan) }}</td>
                            <td>{{ singkatString($d->nama_jabatan) }}</td>
                            <td>{{ $d->kode_dept }}</td>
                            <td>{{ textupperCase($d->nama_cabang) }}</td>
                            <td>{{ formatIndo($d->sampai) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade show active" id="bulanini" role="tabpanel">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No. Kontrak</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Dept</th>
                        <th>Cabang</th>
                        <th>Akhir Kontrak</th>
                        <th>Sisa Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kontrak_bulanini as $d)
                        @php
                            $sisahari = hitungSisahari($d->sampai);
                            $color = $sisahari < 0 ? 'bg-danger text-white' : '';
                        @endphp
                        <tr class="{{ $color }}">
                            <td>{{ $d->no_kontrak }}</td>
                            <td>{{ $d->nik }}</td>
                            <td>{{ formatName($d->nama_karyawan) }}</td>
                            <td>{{ singkatString($d->nama_jabatan) }}</td>
                            <td>{{ $d->kode_dept }}</td>
                            <td>{{ textupperCase($d->nama_cabang) }}</td>
                            <td>{{ formatIndo($d->sampai) }}</td>
                            <td class="text-center">{{ $sisahari }} Hari</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="bulandepan" role="tabpanel">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No. Kontrak</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Dept</th>
                        <th>Cabang</th>
                        <th>Akhir Kontrak</th>
                        <th>Sisa Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kontrak_bulandepan as $d)
                        @php
                            $sisahari = hitungSisahari($d->sampai);
                            $color = $sisahari < 0 ? 'bg-danger text-white' : '';
                        @endphp
                        <tr class="{{ $color }}">
                            <td>{{ $d->no_kontrak }}</td>
                            <td>{{ $d->nik }}</td>
                            <td>{{ formatName($d->nama_karyawan) }}</td>
                            <td>{{ singkatString($d->nama_jabatan) }}</td>
                            <td>{{ $d->kode_dept }}</td>
                            <td>{{ textupperCase($d->nama_cabang) }}</td>
                            <td>{{ formatIndo($d->sampai) }}</td>
                            <td class="text-center">{{ $sisahari }} Hari</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="duabulan" role="tabpanel">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No. Kontrak</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Dept</th>
                        <th>Cabang</th>
                        <th>Akhir Kontrak</th>
                        <th>Sisa Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kontrak_duabulan as $d)
                        @php
                            $sisahari = hitungSisahari($d->sampai);
                            $color = $sisahari < 0 ? 'bg-danger text-white' : '';
                        @endphp
                        <tr class="{{ $color }}">
                            <td>{{ $d->no_kontrak }}</td>
                            <td>{{ $d->nik }}</td>
                            <td>{{ formatName($d->nama_karyawan) }}</td>
                            <td>{{ singkatString($d->nama_jabatan) }}</td>
                            <td>{{ $d->kode_dept }}</td>
                            <td>{{ textupperCase($d->nama_cabang) }}</td>
                            <td>{{ formatIndo($d->sampai) }}</td>
                            <td class="text-center">{{ $sisahari }} Hari</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
