<form action="{{ route('setorangiro.store', Crypt::encrypt($giro->kode_giro)) }}" method="POST" id="formSetorantransfer">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode Giro</th>
                    <td>{{ $giro->kode_giro }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($giro->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Kode Pelanggan</th>
                    <td>{{ $giro->kode_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Nama Pelanggan</th>
                    <td>{{ $giro->nama_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Bank Pengirim</th>
                    <td>{{ $giro->bank_pengirim }}</td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td class="text-end fw-bold">{{ formatAngka($giro->total) }}</td>
                </tr>
                <tr>
                    <th>Jatuh Tempo</th>
                    <td>{{ DateToIndo($giro->jatuh_tempo) }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if ($giro->status == '1')
                            <span class="badge bg-success">{{ DateToIndo($giro->tanggal_diterima) }}</span>
                        @elseif($giro->status == '2')
                            <i class="ti ti-square-rounded-x text-danger"></i>
                        @else
                            <i class="ti ti-hourglass-empty text-warning"></i>
                        @endif
                    </td>
                </tr>
                @if ($giro->status == '1')
                    <tr>
                        <th>No. Bukti Ledger</th>
                        <td>{{ $giro->no_bukti }}</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <x-input-with-icon label="Tanggal Disetorkan" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formSetorantransfer");

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }


        $(".flatpickr-date").flatpickr();

        form.submit(function(e) {
            const tanggal = form.find("#tanggal").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Tanggal Disetorkan Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });

                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
