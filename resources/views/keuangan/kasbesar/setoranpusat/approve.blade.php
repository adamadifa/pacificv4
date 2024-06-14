<form action="{{ route('setoranpusat.approvestore', Crypt::encrypt($setoranpusat->kode_setoran)) }}" method="POST" id="formApprovesetoranpusat">
    @csrf
    <div class="row mb-3">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($setoranpusat->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td>{{ textUpperCase($setoranpusat->nama_cabang) }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $setoranpusat->keterangan }}</td>
                </tr>
                <tr>
                    <th>Uang Kertas</th>
                    <td class="text-end fw-bold">{{ formatAngka($setoranpusat->setoran_kertas) }}</td>
                </tr>
                <tr>
                    <th>Uang Logam</th>
                    <td class="text-end fw-bold">{{ formatAngka($setoranpusat->setoran_logam) }}</td>
                </tr>
                <tr>
                    <th>Total Setoran</th>
                    <td class="text-end fw-bold">{{ formatAngka($setoranpusat->setoran_kertas + $setoranpusat->setoran_logam) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <x-input-with-icon label="Tanggal Penerimaan" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
            <div class="form-group mb-3">
                <select name="kode_bank" id="kode_bank" class="form-select select2Kodebank">
                    <option value="">Bank</option>
                    @foreach ($bank as $d)
                        <option value="{{ $d->kode_bank }}">{{ $d->nama_bank }} ({{ $d->no_rekening }})</option>
                    @endforeach
                </select>
            </div>
            <div class="divider text-start">
                <div class="divider-text">
                    <i class="ti ti-sun me-2"></i> Omset
                </div>
            </div>
            <div class="form-group mb-3">
                <select name="omset_bulan" id="omset_bulan" class="form-select">
                    <option value="">Omset Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}" {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}>{{ $d['nama_bulan'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="omset_tahun" id="omset_tahun" class="form-select">
                    <option value="">Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endfor
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
        const form = $("#formApprovesetoranpusat");
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

        const select2Kodebank = $('.select2Kodebank');
        if (select2Kodebank.length) {
            select2Kodebank.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Bank Penerima',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }


        form.submit(function(e) {

            const tanggal = form.find("#tanggal").val();
            const kode_bank = form.find("#kode_bank").val();
            const omset_bulan = $("#omset_bulan").val();
            const omset_tahun = $("#omset_tahun").val();
            if (tanggal === '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Tanggal Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_bank === '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Bank Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_bank").focus();
                    },
                });
                return false;
            } else if (omset_bulan === '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Omset Bulan Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#omset_bulan").focus();
                    },
                });
                return false;
            } else if (omset_tahun === '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Omset Tahun Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#omset_tahun").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
                return true;
            }
        });
    });
</script>
