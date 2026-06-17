<style>
    .radio-checklist {
        border-radius: .25em !important;
    }
    .radio-checklist:checked {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e") !important;
    }
</style>
<form action="{{ route('visitpelanggan.store', Crypt::encrypt($faktur->no_faktur)) }}" method="POST" id="frmvisitpelanggan">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>No. Faktur</th>
                    <td>{{ $faktur->no_faktur }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($faktur->tanggal) }}</td>
                </tr>
                <tr>
                    <th> Pelanggan</th>
                    <td>{{ $faktur->kode_pelanggan }} {{ textUpperCase($faktur->nama_pelanggan) }}</td>
                </tr>
                <tr>
                    <th>Jenis Transaksi</th>
                    <th>{{ $faktur->jenis_transaksi == 'T' ? 'Tunai' : 'Kredit' }}</th>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $faktur->alamat_pelanggan }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
            <div class="form-group mb-3">
                <label class="form-label">Jenis Kunjungan</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input radio-checklist" type="radio" name="jenis_kunjungan" id="jenis_kunjungan_otd" value="OTD" {{ (isset($visit) && $visit->jenis_kunjungan == 'OTD') ? 'checked' : '' }}>
                        <label class="form-check-label" for="jenis_kunjungan_otd">
                            OTD (On The Desk)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input radio-checklist" type="radio" name="jenis_kunjungan" id="jenis_kunjungan_ots" value="OTS" {{ (!isset($visit) || !$visit->jenis_kunjungan || $visit->jenis_kunjungan == 'OTS') ? 'checked' : '' }}>
                        <label class="form-check-label" for="jenis_kunjungan_ots">
                            OTS (On the Spot)
                        </label>
                    </div>
                </div>
            </div>
            <x-textarea label="Hasil Konfirmasi" name="hasil_konfirmasi" />
            <x-textarea label="Note" name="note" />
            <x-textarea label="Saran / Keluhan" name="saran" />
            <x-textarea label="Action OM" name="act_om" />
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        const frmvisitpelanggan = $("#frmvisitpelanggan");
        $(".flatpickr-date").flatpickr();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }
        frmvisitpelanggan.submit(function(e) {
            const tanggal = frmvisitpelanggan.find("#tanggal").val();
            const hasil_konfirmasi = frmvisitpelanggan.find("#hasil_konfirmasi").val();
            const note = frmvisitpelanggan.find("#note").val();
            const keluhan = frmvisitpelanggan.find("#keluhan").val();
            const action_om = frmvisitpelanggan.find("#action_om").val();
            const saran = frmvisitpelanggan.find("#saran").val();
            const act_om = frmvisitpelanggan.find("#act_om").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#tanggal").focus();
                    },
                });
                return false;
            } else if (note == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Note Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#note").focus();
                    },
                })
                return false;
            } else if (hasil_konfirmasi == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Hasil Konfirmasi Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#hasil_konfirmasi").focus();
                    },
                });
                return false;
            } else if (keluhan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keluhan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#keluhan").focus();
                    },
                });
                return false;
            } else if (saran == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Saran Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#saran").focus();
                    },
                });
                return false;
            } else if (action_om == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Action OM Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#action_om").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        })
    });
</script>
