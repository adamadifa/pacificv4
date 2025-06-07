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
    <div class="row mt-3">


        @if ($level_user != 'direktur')
            @if (!in_array($level_user, $level_hrd))
                <div class="col">
                    <button class="btn btn-primary w-100" id="btnSimpan">
                        <i class="ti ti-thumb-up me-1"></i> Setuju,
                        Teruskan ke HRD
                    </button>
                </div>
            @else
                @if ($izincuti->kategori_jabatan == 'MJ')
                    <div class="col-4">
                        <button class="btn btn-success w-100" id="btnSimpan">
                            <i class="ti ti-thumb-up me-1"></i> Setuju
                        </button>
                    </div>
                    <div class="col-8">
                        <button class="btn btn-primary w-100" id="btnTeruskan" name="direktur" value="1">
                            <i class="ti ti-thumb-up me-1"></i> Setuju dan
                            Teruskan ke Direktur
                        </button>
                    </div>
                @else
                    <div class="col">
                        <button class="btn btn-success w-100" id="btnSimpan">
                            <i class="ti ti-thumb-up me-1"></i> Setuju
                        </button>
                    </div>
                @endif
            @endif
        @else
            <div class="col">
                <button class="btn btn-success w-100" id="btnSimpan">
                    <i class="ti ti-thumb-up me-1"></i> Setuju
                </button>
            </div>
        @endif

    </div>
</form>
<script>
    $(document).ready(function() {
        function buttonDisable() {
            $('#btnSimpan').prop('disabled', true);
            $('#btnSimpan').html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        const formApproveizin = $('#formApproveizin');
        formApproveizin.submit(function(e) {
            buttonDisable();
        });
    })
</script>
