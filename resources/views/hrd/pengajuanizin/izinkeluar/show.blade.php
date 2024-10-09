<form action="{{ route('izinkeluar.storeapprove', Crypt::encrypt($izinkeluar->kode_izin_keluar)) }}" method="POST" id="formApproveizin">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode</th>
                    <td class="text-end">{{ $izinkeluar->kode_izin_keluar }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($izinkeluar->tanggal) }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td class="text-end">{{ $izinkeluar->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td class="text-end">{{ $izinkeluar->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td class="text-end">{{ $izinkeluar->nama_jabatan }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td class="text-end">{{ $izinkeluar->nama_dept }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ textUpperCase($izinkeluar->nama_cabang) }}</td>
                </tr>

                <tr>
                    <th>Jam Keluar</th>
                    <td class="text-end">{{ date('H:i', strtotime($izinkeluar->jam_keluar)) }}</td>
                </tr>
                <tr>
                    <th>Keperluan</th>
                    <td class="text-end">
                        @if ($izinkeluar->keperluan == 'K')
                            <span class="badge bg-success">Kantor</span>
                        @else
                            <span class="badge bg-danger">Pribadi</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td class="text-end">{{ $izinkeluar->keterangan }}</td>
                </tr>
            </table>
        </div>
    </div>
</form>
