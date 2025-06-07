<form action="{{ route('izindinas.storeapprove', Crypt::encrypt($izindinas->kode_izin_dinas)) }}" method="POST" id="formApproveizin">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode</th>
                    <td class="text-end">{{ $izindinas->kode_izin_dinas }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($izindinas->tanggal) }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td class="text-end">{{ $izindinas->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td class="text-end">{{ $izindinas->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td class="text-end">{{ $izindinas->nama_jabatan }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td class="text-end">{{ $izindinas->nama_dept }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ textUpperCase($izindinas->nama_cabang) }}</td>
                </tr>

                <tr>
                    <th>Periode Tanggal</th>
                    <td class="text-end">{{ formatIndo($izindinas->dari) }} - {{ formatIndo($izindinas->sampai) }}</td>
                </tr>
                <tr>
                    <th>Lama</th>
                    <td class="text-end">{{ hitungHari($izindinas->dari, $izindinas->sampai) }} Hari</td>
                </tr>
                <tr>
                    <th>Tujuan</th>
                    <td class="text-end">{{ $izindinas->kode_cabang_tujuan }} Hari</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td class="text-end">{{ $izindinas->keterangan }}</td>
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
                @if ($izindinas->kategori_jabatan == 'MJ')
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
            $('#btnSimpan').prop('readonly', true);
            $('#btnTeruskan').prop('readonly', true);
            $('#btnSimpan').html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
            $('#btnTeruskan').html(`
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
