<form action="{{ route('pencairanprogramikatan.store') }}" method="POST" id="formPencairanProgramikatan">
    @csrf
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" value="{{ date('Y-m-d') }}" readonly />
    <div class="input-group mb-2">
        <input type="text" class="form-control" name="no_pengajuan" id="no_pengajuan" readonly placeholder="Cari Ajuan Program"
            aria-label="Cari Ajuan Program" aria-describedby="no_pengajuan">
        <a class="btn btn-primary waves-effect" id="no_pengajuan_search"><i class="ti ti-search text-white"></i></a>
    </div>
    <table class="table table-striped mb-2" id="tabeldataajuan">
        <tr>
            <th>No. Pengajuan</th>
            <td id="no_pengajuan_text"></td>
        </tr>
        <tr>
            <th>No. Dokumen</th>
            <td id="nomor_dokumen"></td>
        </tr>
        <tr>
            <th>Program</th>
            <td id="nama_program"></td>
        </tr>
        <tr>
            <th>Cabang</th>
            <td id="nama_cabang"></td>
        </tr>
        <tr>
            <th>Periode</th>
            <td id="periode"></td>
        </tr>
    </table>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="periodepencairan" id="periodepencairan" class="form-select">
                    <option value="">Periode</option>
                </select>
            </div>
        </div>
    </div>

    <x-textarea label="Keterangan" name="keterangan" />
    <div class="form-group mb3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>


</form>
<script>
    $(function() {
        // $(".flatpickr-date").flatpickr();
        $("#formPencairanProgramikatan").submit(function(e) {
            let tanggal = $(this).find('input[name="tanggal"]').val();
            let no_pengajuan = $(this).find('input[name="no_pengajuan"]').val();
            let keterangan = $(this).find('textarea[name="keterangan"]').val();
            let bulan = $(this).find('select[name="bulan"]').val();
            let tahun = $(this).find('select[name="tahun"]').val();
            if (tanggal == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#tanggal").focus();
                    },
                });
                return false;
            } else if (no_pengajuan == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "No Pengajuan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#no_pengajuan").focus();
                    },
                });
                return false;
            } else if (bulan = '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#bulan").focus();
                    },
                })
            } else if (tahun = '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#tahun").focus();
                    },
                })
            } else if (keterangan == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#keterangan").focus();
                    },
                });
                return false;
            } else {
                $(this).find('#btnSimpan').attr('disabled', true);
                $(this).find('#btnSimpan').html(` <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
            }
        });
    });
</script>
