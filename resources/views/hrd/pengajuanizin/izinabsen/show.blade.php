<form action="{{ route('izinabsen.storeapprove', Crypt::encrypt($izinabsen->kode_izin)) }}" method="POST" id="formApproveizin">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode</th>
                    <td class="text-end">{{ $izinabsen->kode_izin }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($izinabsen->tanggal) }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td class="text-end">{{ $izinabsen->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td class="text-end">{{ $izinabsen->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td class="text-end">{{ $izinabsen->nama_jabatan }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td class="text-end">{{ $izinabsen->nama_dept }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ textUpperCase($izinabsen->nama_cabang) }}</td>
                </tr>
                <tr>
                    <th>Periode Tanggal</th>
                    <td class="text-end">{{ formatIndo($izinabsen->dari) }} - {{ formatIndo($izinabsen->sampai) }}</td>
                </tr>
                <tr>
                    <th>Lama</th>
                    <td class="text-end">{{ hitungHari($izinabsen->dari, $izinabsen->sampai) }} Hari</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td class="text-end">{{ $izinabsen->keterangan }}</td>
                </tr>
            </table>
        </div>
    </div>
</form>
