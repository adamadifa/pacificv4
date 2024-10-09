<form action="{{ route('izinterlambat.storeapprove', Crypt::encrypt($izinterlambat->kode_izin_terlambat)) }}" method="POST" id="formApproveizin">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode</th>
                    <td class="text-end">{{ $izinterlambat->kode_izin_terlambat }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($izinterlambat->tanggal) }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td class="text-end">{{ $izinterlambat->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td class="text-end">{{ $izinterlambat->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td class="text-end">{{ $izinterlambat->nama_jabatan }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td class="text-end">{{ $izinterlambat->nama_dept }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ textUpperCase($izinterlambat->nama_cabang) }}</td>
                </tr>

                <tr>
                    <th>Jam terlambat</th>
                    <td class="text-end">{{ date('H:i', strtotime($izinterlambat->jam_terlambat)) }}</td>
                </tr>

                <tr>
                    <th>Keterangan</th>
                    <td class="text-end">{{ $izinterlambat->keterangan }}</td>
                </tr>
            </table>
        </div>
    </div>
</form>
