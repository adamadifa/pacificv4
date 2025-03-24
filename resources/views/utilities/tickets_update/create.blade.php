<form action="{{ route('ticketupdate.store') }}" method="POST" id="formTicket">
    @csrf
    <x-input-with-icon label="Tanggal" name="tanggal" value="{{ date('Y-m-d') }}" icon="ti ti-calendar" datepicker="flatpickr-date" readonly />
    <div class="form-group">
        <select name="kategori" id="kategori" class="form-select">
            <option value="">Kategori</option>
            <option value="1">Penjualan</option>
            <option value="2">Pembayaran</option>
            <option value="3">Retur</option>
            <option value="4">DPB</option>
            <option value="5">Mutasi Persediaan</option>
        </select>
    </div>
    <x-input-with-icon label="No. Bukti" name="no_bukti" icon="ti ti-barcode" />
    <x-textarea label="Keterangan" name="keterangan" />
    <x-input-with-icon label="Link Lampiran Dokumen" name="link" icon="ti ti-link" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-ticket me-1"></i>Buat Ticket</button>
    </div>
</form>
<script>
    // $(".flatpickr-date").flatpickr();
    $("#formTicket").submit(function(e) {
        let tanggal = $(this).find("#tanggal").val();
        let keterangan = $(this).find("#keterangan").val();
        let kategori = $(this).find("#kategori").val();
        let no_bukti = $(this).find("#no_bukti").val();
        if (tanggal == "") {
            Swal.fire({
                title: "Oops!",
                text: "Tanggal harus diisi!",
                icon: "warning",
                showConfirmButton: true,
                didClose: () => {
                    $(this).find("#tanggal").focus();
                },
            });
            return false;
        } else if (kategori == "") {
            Swal.fire({
                title: "Oops!",
                text: "Kategori harus diisi!",
                icon: "warning",
                showConfirmButton: true,
                didClose: () => {
                    $(this).find("#kategori").focus();
                },
            });
            return false;
        } else if (no_bukti == "") {
            Swal.fire({
                title: "Oops!",
                text: "No. Bukti harus diisi!",
                icon: "warning",
                showConfirmButton: true,
                didClose: () => {
                    $(this).find("#no_bukti").focus();
                },
            });
            return false;
        } else if (keterangan == "") {
            Swal.fire({
                title: "Oops!",
                text: "Keterangan harus diisi!",
                icon: "warning",
                showConfirmButton: true,
                didClose: () => {
                    $(this).find("#keterangan").focus();
                },
            });
            return false;
        } else {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
        }
    });
</script>
