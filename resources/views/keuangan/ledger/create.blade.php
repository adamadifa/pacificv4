<form action="#" method="POST" id="formLedger">
    <x-select label="Bank" name="kode_bank" :data="$bank" key="kode_bank" textShow="nama_bank" upperCase="true" select2="select2Kodebank" />
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
    <x-input-with-icon label="Pelanggan" name="pelanggan" icon="ti ti-user" />
    <x-textarea label="Keterangan" name="keterangan" />
    <x-input-with-icon label="Jumlah" name="jumlah" icon="ti ti-moneybag" align="right" money="true" />
    <div class="form-group mb-3">
        <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakun">
            <option value="">Pilih Kode Akun</option>
            @foreach ($coa as $d)
                <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ $d->nama_akun }}</option>
            @endforeach
        </select>
    </div>
    <div class="row mb-3">
        <div class="col-6">
            <select name="debet_kredit" id="debet_kredit" class="form-select">
                <option value="">Debet / Kredit</option>
                <option value="D">Debet</option>
                <option value="K">Kredit</option>
            </select>
        </div>
        <div class="col-6">
            <select name="peruntukan" id="peruntukan" class="form-select">
                <option value="">Peruntukan</option>
                <option value="MP">MP</option>
                <option value="PC">PACIFIC</option>
            </select>
        </div>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100"><i class="ti ti-plus me-1"></i>Tambah</button>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Keterangan</th>
                        <th>Kode Akun</th>
                        <th>Debet</th>
                        <th>Kredit</th>
                        <th>#</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</form>
<script>
    $(function() {
        const select2Kodebank = $('.select2Kodebank');
        if (select2Kodebank.length) {
            select2Kodebank.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih  Bank',
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
                    placeholder: 'Pilih  Kode Akun',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

    });
</script>
