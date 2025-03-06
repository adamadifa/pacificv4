<form action="{{ route('ticket.update', Crypt::encrypt($ticket->kode_pengajuan)) }}" method="POST" id="formTicket" autocomplete="off">
    @csrf
    @method('PUT')
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" :value="$ticket->tanggal" readonly />
    <x-textarea label="Keterangan" name="keterangan" :value="$ticket->keterangan" />
    <x-input-with-icon label="Link" name="link" icon="ti ti-link" :value="$ticket->link" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-ticket me-1"></i>Update</button>
    </div>
</form>
<script>
    // $(".flatpickr-date").flatpickr();
    $("#formTicket").submit(function(e) {
        let tanggal = $(this).find("#tanggal").val();
        let keterangan = $(this).find("#keterangan").val();
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
