<form action="{{ route('jurnalkoreksi.store') }}" id="formJurnalkoreksi" method="POST">
    @csrf
    <x-input-with-icon label="Tanggal" icon="ti ti-calendar" name="tanggal" datepicker="flatpickr-date" />
    <x-select label="Supplier" name="kode_supplier" :data="$supplier" key="kode_supplier" textShow="nama_supplier" upperCase="true"
        select2="select2Kodesupplier" />
    <div class="form-group mb-3">
        <select name="no_bukti" id="no_bukti" class="form-select select2Nobukti">
            <option value="">No. Bukti Pembelian</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="kode_barang" id="kode_barang" class="form-select select2Kodebarang">
            <option value="">Pilih Barang</option>
        </select>
    </div>
    <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" />
    <x-input-with-icon label="Qty" name="jumlah" icon="ti ti-box" numberFormat="true" />
    <x-input-with-icon label="Harga" name="harga" icon="ti ti-moneybag" align="right" numberFormat="true" />
    <x-input-with-icon label="Total" name="total" icon="ti ti-moneybag" align="right" numberFormat="true" disabled="true" />
    <div class="form-group mb-3">
        <select name="kode_akun_debet" id="kode_akun_debet" class="form-select select2Kodeakundebet">
            <option value="">Akun Debet</option>
            @foreach ($coa as $d)
                <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="kode_akun_kredit" id="kode_akun_kredit" class="form-select select2Kodeakunkredit">
            <option value="">Akun Kredit</option>
            @foreach ($coa as $d)
                <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formJurnalkoreksi");
        $(".flatpickr-date").flatpickr();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        const select2Kodesupplier = $('.select2Kodesupplier');
        if (select2Kodesupplier.length) {
            select2Kodesupplier.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Supplier',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Nobukti = $('.select2Nobukti');
        if (select2Nobukti.length) {
            select2Nobukti.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'No. Bukti Pembelian',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodebarang = $('.select2Kodebarang');
        if (select2Kodebarang.length) {
            select2Kodebarang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Barang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodeakundebet = $('.select2Kodeakundebet');
        if (select2Kodeakundebet.length) {
            select2Kodeakundebet.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Akun Debet',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }


        const select2Kodeakunkredit = $('.select2Kodeakunkredit');
        if (select2Kodeakunkredit.length) {
            select2Kodeakunkredit.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Akun Kredit',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        form.find("#kode_supplier").change(function() {
            const kode_supplier = $(this).val();
            $("#no_bukti").load(`/pembelian/${kode_supplier}/getpembelianbysupplier`);
        });


        form.find("#no_bukti").change(function() {
            const no_bukti = $(this).val();
            $.ajax({
                type: "POST",
                url: "{{ route('pembelian.getbarangpembelian') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    no_bukti: no_bukti
                },
                cache: false,
                success: function(respond) {
                    $("#kode_barang").html(respond);
                }
            });
        });

        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });


        function convertNumber(number) {
            // Hilangkan semua titik
            let formatted = number.replace(/\./g, '');
            // Ganti semua koma dengan titik
            formatted = formatted.replace(/,/g, '.');
            return formatted || 0;
        }


        function numberFormat(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep,
                dec = typeof dec_point === 'undefined' ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        };


        function calculateTotal() {
            let qty = form.find("#jumlah").val();
            let harga = form.find("#harga").val();


            qty = convertNumber(qty);
            harga = convertNumber(harga);


            total = parseFloat(qty) * parseFloat(harga);
            return total;
        }

        form.find("#jumlah, #harga").on('keyup keydown', function(e) {
            const total = calculateTotal();
            form.find("#total").val(numberFormat(total, '2', ',', '.'));
            // alert('test');
        });

        form.submit(function(e) {
            const tanggal = form.find("#tanggal").val();
            const kode_supplier = form.find("#kode_supplier").val();
            const no_bukti = form.find("#no_bukti").val();
            const kode_barang = form.find("#kode_barang").val();
            const keterangan = form.find("#keterangan").val();
            const jumlah = form.find("#jumlah").val();
            const harga = form.find("#harga").val();
            const kode_akun_debet = form.find("#kode_akun_debet").val();
            const kode_akun_kredit = form.find("#kode_akun_kredit").val();

            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_supplier == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Supplier harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_supplier").focus();
                    },
                });
                return false;
            } else if (no_bukti == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Bukti harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_bukti").focus();
                    },
                });
                return false;
            } else if (kode_barang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Barang harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_barang").focus();
                    },
                });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#keterangan").focus();
                    },
                });
                return false;
            } else if (jumlah == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });
                return false;
            } else if (harga == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Harga harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#harga").focus();
                    },
                });
                return false;
            } else if (kode_akun_debet == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Akun Debet harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_akun_debet").focus();
                    },
                });
                return false;
            } else if (kode_akun_kredit == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Akun Kredit harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_akun_kredit").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }


        });
    });
</script>
