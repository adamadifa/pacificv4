<form action="{{ route('visitpelanggan.update', Crypt::encrypt($visit->kode_visit)) }}" method="POST" id="frmvisitpelanggan">
    @csrf
    @method('PUT')
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
            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" value="{{ $visit->tanggal }}" />
            <x-textarea label="Hasil Konfirmasi" name="hasil_konfirmasi" value="{{ $visit->hasil_konfirmasi }}" />
            <x-textarea label="Note" name="note" value="{{ $visit->note }}" />
            <x-textarea label="Saran / Keluhan" name="saran" value="{{ $visit->saran }}" />
            <x-textarea label="Action OM" name="act_om" value="{{ $visit->act_om }}" />
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
