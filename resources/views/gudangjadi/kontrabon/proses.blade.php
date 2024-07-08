<form action="{{ route('kontrabonangkutan.storeproses', Crypt::encrypt($kontrabon->no_kontrabon)) }}" method="POST" id="formProseskontrabon">
    @csrf
    <div class="row mb-2">
        <div class="col">
            <table class="table">
                <tr>
                    <th>No. Kontrabon</th>
                    <td class="text-end">{{ $kontrabon->no_kontrabon }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($kontrabon->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Angkutan</th>
                    <td class="text-end">{{ $kontrabon->nama_angkutan }}</td>
                </tr>
            </table>

        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No. Dok</th>
                        <th>Tanggal</th>
                        <th>No. Polisi</th>
                        <th>Tujuan</th>
                        <th>Tarif</th>
                        <th>Tepung</th>
                        <th>BS</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandtotaltarif = 0;
                    @endphp
                    @foreach ($detail as $d)
                        @php
                            $totaltarif = $d->tarif + $d->tepung + $d->bs;
                            $grandtotaltarif += $totaltarif;
                        @endphp
                        <tr>
                            <td>{{ $d->no_dok }}</td>
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_polisi }}</td>
                            <td>{{ $d->tujuan }}</td>
                            <td class="text-end">{{ formatAngka($d->tarif) }}</td>
                            <td class="text-end">{{ formatAngka($d->tepung) }}</td>
                            <td class="text-end">{{ formatAngka($d->bs) }}</td>
                            <td class="text-end">{{ formatAngka($totaltarif) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="7">TOTAL</td>
                        <td class="text-end">{{ formatAngka($grandtotaltarif) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
            <div class="form-group mb-3">
                <select name="kode_bank" id="kode_bank" class="form-select select2Kodebank">
                    <option value="">Bank</option>
                    @foreach ($bank as $d)
                        <option value="{{ $d->kode_bank }}">{{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" />
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submti</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formProseskontrabon");

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
                    placeholder: 'Bank',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }



        form.submit(function(e) {
            const tanggal = form.find("#tanggal").val();
            const kode_bank = form.find("#kode_bank").val();
            const keterangan = form.find("#keterangan").val();


            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
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
                    text: "Bank Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_bank").focus();
                    },
                });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#keterangan").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
