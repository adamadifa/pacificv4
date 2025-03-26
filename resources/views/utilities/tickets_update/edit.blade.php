<form action="{{ route('ticketupdate.update', Crypt::encrypt($ticket->kode_pengajuan)) }}" method="POST" id="formTicket">
    @csrf
    @method('PUT')
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" :value="$ticket->tanggal" readonly />
    <div class="form-group">
        <select name="kategori" id="kategori" class="form-select">
            <option value="">Kategori</option>
            <option value="1" {{ $ticket->kategori == 1 ? 'selected' : '' }}>Penjualan</option>
            <option value="2" {{ $ticket->kategori == 2 ? 'selected' : '' }}>Pembayaran</option>
            <option value="3" {{ $ticket->kategori == 3 ? 'selected' : '' }}>Retur</option>
            <option value="4" {{ $ticket->kategori == 4 ? 'selected' : '' }}>DPB</option>
            <option value="5" {{ $ticket->kategori == 5 ? 'selected' : '' }}>Mutasi Persediaan</option>
        </select>
    </div>
    <x-input-with-icon label="No. Bukti" name="no_bukti" icon="ti ti-barcode" :value="$ticket->no_bukti" />
    <x-textarea label="Keterangan" name="keterangan" :value="$ticket->keterangan" />
    <x-input-with-icon label="Link Lampiran Dokumen" name="link" icon="ti ti-link" :value="$ticket->link" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-ticket me-1"></i>Update</button>
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
