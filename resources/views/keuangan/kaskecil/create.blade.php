<form action="{{ route('kaskecil.store') }}" method="POST" id="formKaskecil">
    <input type="hidden" id="cektutuplaporan">
    @csrf
    <div class="form-group mb-4">
        <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
            <option value="">Pilih Cabang</option>
            @foreach ($cabang as $d)
                <option value="{{ $d->kode_cabang }}">{{ textuppercase($d->nama_cabang) }}</option>
            @endforeach
        </select>
    </div>
    <x-input-with-icon label="No Bukti" name="no_bukti" icon="ti ti-barcode" />
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
    <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" />
    <x-input-with-icon label="Jumlah" name="jumlah" icon="ti ti-moneybag" align="right" money="true" />
    <div class="form-group mb-4">
        <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakun">
            <option value="">Pilih Akun</option>
            @foreach ($coa as $d)
                <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ $d->nama_akun }} </option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-check form-check-inline mt-3">
                <input class="form-check-input" type="radio" name="in_out" id="inlineRadio1" value="K">
                <label class="form-check-label" for="inlineRadio1">IN</label>
            </div>
            <div class="form-check form-check-inline mt-3">
                <input class="form-check-input" type="radio" name="in_out" id="inlineRadio2" value="D" checked>
                <label class="form-check-label" for="inlineRadio2">OUT</label>
            </div>
        </div>
    </div>
    @if (auth()->user()->kode_cabang == 'PST')
        <div class="row">
            <div class="col">
                <div class="form-check form-check-inline mt-3">
                    <input class="form-check-input" type="radio" name="kode_peruntukan" id="inlineRadio1" value="PC">
                    <label class="form-check-label" for="inlineRadio1">Pacific</label>
                </div>
                <div class="form-check form-check-inline mt-3">
                    <input class="form-check-input" type="radio" name="in_out" id="inlineRadio2" value="MP">
                    <label class="form-check-label" for="inlineRadio2">Makmur Permata</label>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col">
            <a href="#" id="tambahitem" class="btn btn-primary w-100"><i class="ti ti-plus me-1"></i>Tambah Item</a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>Akun</th>
                        <th>IN/OUT</th>
                        @if (auth()->user()->kode_cabang == 'PST')
                            <th>Peruntukan</th>
                        @endif
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="loadkaskecil">

                </tbody>
            </table>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        const formKaskecil = $("#formKaskecil");
        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodeakun = $('.select2Kodeakun');
        if (select2Kodeakun.length) {
            select2Kodeakun.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Akun',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function addItem() {
            const kode_cabang = formKaskecil.find("#kode_cabang").val();
            const no_bukti = formKaskecil.find("#no_bukti").val();
            const tanggal = formKaskecil.find("#tanggal").val();
            const keterangan = formKaskecil.find("#keterangan").val();
            const kode_akun = formKaskecil.find("#kode_akun").val();
            if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formKaskecil.find("#kode_cabang").focus();
                    },
                });
                return false;
            } else if (no_bukti == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Bukti Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formKaskecil.find("#no_bukti").focus();
                    },
                });
                return false;
            } else if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formKaskecil.find("#tanggal").focus();
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
                        formKaskecil.find("#keterangan").focus();
                    },
                });
                return false;
            } else if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Akun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formKaskecil.find("#kode_akun").focus();
                    },
                });
                return false;
            }
        }

        $("#tambahitem").click(function(e) {
            e.preventDefault();
            addItem();
        });
    });
</script>
