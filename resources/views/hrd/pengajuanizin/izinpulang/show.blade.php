<form action="{{ route('izinpulang.storeapprove', Crypt::encrypt($izinpulang->kode_izin_pulang)) }}" method="POST" id="formApproveizin">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode</th>
                    <td class="text-end">{{ $izinpulang->kode_izin_pulang }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($izinpulang->tanggal) }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td class="text-end">{{ $izinpulang->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td class="text-end">{{ $izinpulang->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td class="text-end">{{ $izinpulang->nama_jabatan }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td class="text-end">{{ $izinpulang->nama_dept }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ textUpperCase($izinpulang->nama_cabang) }}</td>
                </tr>

                <tr>
                    <th>Jam pulang</th>
                    <td class="text-end">{{ date('H:i', strtotime($izinpulang->jam_pulang)) }}</td>
                </tr>

                <tr>
                    <th>Keterangan</th>
                    <td class="text-end">{{ $izinpulang->keterangan }}</td>
                </tr>
            </table>
        </div>
    </div>
</form>
