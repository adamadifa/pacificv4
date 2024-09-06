<form action="{{ route('kontrabonpmb.storeproses', Crypt::encrypt($kontrabon->no_kontrabon)) }}" method="POST" id="formProseskontrabon">
    @csrf
    <div class="row mb-3">
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
                    <th>Terima Dari</th>
                    <td class="text-end">{{ $kontrabon->nama_supplier }}</td>
                </tr>
            </table>

        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>No. Bukti</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($detail as $d)
                        @php
                            $total += $d->jumlah;
                        @endphp
                        <tr class="cursor-pointer btnShowpembelian" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ DateToIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_bukti }}</td>
                            <td class="text-end">{{ formatAngkaDesimal($d->jumlah) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="3">TOTAL</td>
                        <td class="text-end">{{ formatAngkaDesimal($total) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <x-input-with-icon label="Tanggal" name="tanggal" datepicker="flatpickr-date" icon="ti ti-calendar" />
            <div class="form-group mb-3">
                <select name="kode_bank" id="kode_bank" class="form-select select2Kodebank">
                    <option value="">Bank</option>
                    @foreach ($bank as $d)
                        <option value="{{ $d->kode_bank }}">{{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakun">
                    <option value="">Akun</option>
                    <option value="2-1300">2-1300 - Hutang Lainnya</option>
                    <option value="2-1200">2-1200 - Hutang Dagang</option>
                </select>
            </div>
            <div class="row" id="kaskecil">
                <x-input-with-icon label="No. BKK" name="no_bkk" icon="ti ti-file-barcode" />
            </div>
            <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" />
            <div class="row form-group mb-3">
                <div class="col-12">
                    <div class="form-check mt-3 mb-2">
                        <input class="form-check-input dibayarcabang" name="dibayarcabang" value="1" type="checkbox" id="dibayarcabang">
                        <label class="form-check-label" for="dibayarcabang"> Dibayar Oleh Cabang ? </label>
                    </div>
                </div>
            </div>
            <div class="row" id="cabang">
                <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
                    select2="select2Kodecabang" />
            </div>

            {{-- BK071 --}}
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formProseskontrabon");


        function showkaskecil() {
            const kode_bank = form.find("#kode_bank").val();
            if (kode_bank == "BK071") {
                $("#kaskecil").show();
            } else {
                $("#kaskecil").hide();
            }
        }

        showkaskecil();

        form.find("#kode_bank").on("change", function() {
            showkaskecil();
        });
        $(".flatpickr-date").flatpickr();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
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

        form.find("#cabang").hide();
        form.find('.dibayarcabang').change(function() {
            if (this.checked) {
                form.find("#cabang").show();
            } else {
                form.find("#cabang").hide();
            }
        });

        form.submit(function(e) {
            const tanggal = form.find("#tanggal").val();
            const kode_akun = form.find("#kode_akun").val();
            const keterangan = form.find("#keterangan").val();
            const kode_cabang = form.find("#kode_cabang").val();
            const kode_bank = form.find("#kode_bank").val();
            const no_bkk = form.find("#no_bkk").val();
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
            } else if (kode_bank == "BK071" && no_bkk == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No BKK Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_bkk").focus();
                    }
                })
            } else if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Akun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_akun").focus();
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
            } else if ($(".dibayarcabang").is(':checked') && kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_cabang").focus();
                    },
                });

                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
