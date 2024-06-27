<form action="{{ route('kasbon.approvestore', Crypt::encrypt($kasbon->no_kasbon)) }}" method="POST" id="formApprove">
    @csrf
    <div class="row mb-3">
        <div class="col-12">
            <table class="table">
                <tr>
                    <th>No. Kasbon</th>
                    <td class="text-end">{{ $kasbon->no_kasbon }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($kasbon->tanggal) }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td class="text-end">{{ $kasbon->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td class="text-end">{{ $kasbon->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td class="text-end">{{ $kasbon->nama_jabatan }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td class="text-end">{{ $kasbon->nama_dept }}</td>
                </tr>
                <tr>
                    <th>Kantor</th>
                    <td class="text-end">{{ textUppercase($kasbon->nama_cabang) }}</td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td class="text-end">{{ formatAngka($kasbon->jumlah) }}</td>
                </tr>
                <tr>
                    <th>Jatuh Tempo</th>
                    <td class="text-end">{{ DateToIndo($kasbon->jatuh_tempo) }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col">
                    <x-input-with-icon label="Tanggal Proses" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
                    <div class="form-group mb-3">
                        <select name="kode_bank" id="kode_bank" class="form-select select2Kodebank">
                            <option value="">Debet Rekening</option>
                            @foreach ($bank as $d)
                                <option value="{{ $d->kode_bank }}">{{ $d->nama_bank }}
                                    {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
                    </div>
                </div>
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

        const select2Kodebank = $('.select2Kodebank');
        if (select2Kodebank.length) {
            select2Kodebank.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Debet Rekening',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
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
                    text: "Debet Rekening harus diisi !",
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
