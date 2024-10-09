<form action="{{ route('izinkoreksi.storeapprove', Crypt::encrypt($izinkoreksi->kode_izin_koreksi)) }}" method="POST" id="formApproveizin">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode</th>
                    <td class="text-end">{{ $izinkoreksi->kode_izin_koreksi }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($izinkoreksi->tanggal) }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td class="text-end">{{ $izinkoreksi->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td class="text-end">{{ $izinkoreksi->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td class="text-end">{{ $izinkoreksi->nama_jabatan }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td class="text-end">{{ $izinkoreksi->nama_dept }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ textUpperCase($izinkoreksi->nama_cabang) }}</td>
                </tr>
                <tr>
                    <th>Jadwal</th>
                    <td class="text-end">{{ $izinkoreksi->nama_jadwal }} {{ date('H:i', strtotime($izinkoreksi->jam_mulai)) }} -
                        {{ date('H:i', strtotime($izinkoreksi->jam_selesai)) }} </td>
                </tr>
                <tr>
                    <th>Jam Masuk</th>
                    <td class="text-end">{{ date('H:i', strtotime($izinkoreksi->jam_masuk)) }}</td>
                </tr>
                <tr>
                    <th>Jam Pulang</th>
                    <td class="text-end">{{ date('H:i', strtotime($izinkoreksi->jam_pulang)) }}</td>
                </tr>

                <tr>
                    <th>Keterangan</th>
                    <td class="text-end">{{ $izinkoreksi->keterangan }}</td>
                </tr>
            </table>
        </div>
    </div>
</form>
