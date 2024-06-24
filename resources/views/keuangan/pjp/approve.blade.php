<form action="{{ route('pjp.approvestore', Crypt::encrypt($pjp->no_pinjaman)) }}" method="POST" id="formApprove">
    @csrf
    <div class="row mb-3">
        <div class="col">
            <table class="table">
                <tr>
                    <th>No. Pinjaman</th>
                    <td class="text-end">{{ $pjp->no_pinjaman }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ date('d-m-Y H:i', strtotime($pjp->created_at)) }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td class="text-end">{{ $pjp->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td class="text-end">{{ textUpperCase($pjp->nama_karyawan) }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td class="text-end">{{ $pjp->nama_jabatan }}</td>
                </tr>
                <tr>
                    <th>Nama Departemen</th>
                    <td class="text-end">{{ textUpperCase($pjp->nama_dept) }}</td>
                </tr>
                <tr>
                    <th>Jumlah Pinjaman</th>
                    <td class="text-end">{{ formatRupiah($pjp->jumlah_pinjaman) }}</td>
                </tr>
                <tr>
                    <th>Angsuran</th>
                    <td class="text-end">{{ formatRupiah($pjp->angsuran) }}</td>
                </tr>
                <tr>
                    <th>Angsuran per Bulan</th>
                    <td class="text-end">{{ formatRupiah($pjp->jumlah_angsuran) }}</td>
                </tr>
                <tr>
                    <th>Mulai Cicilan</th>
                    <td class="text-end">{{ date('d-m-Y', strtotime($pjp->mulai_cicilan)) }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <x-input-with-icon label="Tanggal Proses" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
            <div class="form-group mb-3">
                <select name="kode_bank" id="kode_bank" class="form-select">
                    <option value="">Debet Rekening</option>
                    @foreach ($bank as $d)
                        <option value="{{ $d->kode_bank }}">{{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formApprove");
        $(".flatpickr-date").flatpickr();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        form.submit(function(e) {
            const tanggal = form.find("#tanggal").val();
            const kode_bank = form.find("#kode_bank").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });

                return false;
            } else if (kode_bank == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Bank harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_bank").focus();
                    },
                });

                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
