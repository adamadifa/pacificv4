<form action="{{ route('izinkeluar.storeupdatejamkembali', Crypt::encrypt($izinkeluar->kode_izin_keluar)) }}" method="POST" id="formApproveizin">
    @csrf
    <div class="row mb-3">
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
    <x-input-with-icon label="Jam Kembali" name="jam_kembali" icon="ti ti-clock" datepicker="flatpickr-date"
        value="{{ !empty($izinkeluar->jam_kembali) ? $izinkeluar->jam_kembali : '' }}" />

    <div class="row mt-3">
        <div class="col">
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-check me-1"></i>Update</button>
            </div>
        </div>

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

        $(".flatpickr-date").flatpickr({
            enableTime: true,
            minuteIncrement: 1,
            time_24hr: true,
            altInput: true,
            altFormat: "Y-m-d H:i",
            dateFormat: "Y-m-d H:i",
        });

    })
</script>
