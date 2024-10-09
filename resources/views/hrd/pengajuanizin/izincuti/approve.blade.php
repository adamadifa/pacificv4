<form action="{{ route('izincuti.storeapprove', Crypt::encrypt($izincuti->kode_izin_cuti)) }}" method="POST" id="formApproveizin">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode</th>
                    <td class="text-end">{{ $izincuti->kode_izin_cuti }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($izincuti->tanggal) }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td class="text-end">{{ $izincuti->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td class="text-end">{{ $izincuti->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td class="text-end">{{ $izincuti->nama_jabatan }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td class="text-end">{{ $izincuti->nama_dept }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ textUpperCase($izincuti->nama_cabang) }}</td>
                </tr>

                <tr>
                    <th>Periode Tanggal</th>
                    <td class="text-end">{{ formatIndo($izincuti->dari) }} - {{ formatIndo($izincuti->sampai) }}</td>
                </tr>
                <tr>
                    <th>Lama</th>
                    <td class="text-end">{{ hitungHari($izincuti->dari, $izincuti->sampai) }} Hari</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td class="text-end">{{ $izincuti->keterangan }}</td>
                </tr>
            </table>
        </div>
    </div>
</form>
