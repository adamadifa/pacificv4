<form action="#" id="formPotongan">
    <x-input-with-icon label="Keterangan" name="keterangan_potongan" icon="ti ti-file-description" />
    <x-input-with-icon label="Qty" name="jumlah_potongan" icon="ti ti-box" numberFormat="true" />
    <x-input-with-icon label="Harga" name="harga_potongan" icon="ti ti-moneybag" align="right" numberFormat="true" />
    <x-input-with-icon label="Total" name="total_potongan" icon="ti ti-moneybag" align="right" numberFormat="true" disabled="true" />
    <div class="form-group mb-3">
        <select name="kode_akun_potongan" id="kode_akun_potongan" class="form-select select2Kodeakunpotongan">
            <option value="">Akun</option>
            @foreach ($coa as $d)
                <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-danger w-100" id="btnPotongan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
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


        function calculatePotongan() {
            let qty = $("#formPotongan").find("#jumlah_potongan").val();
            let harga = $("#formPotongan").find("#harga_potongan").val();


            qty = convertNumber(qty);
            harga = convertNumber(harga);


            subtotal_potongan = parseFloat(qty) * parseFloat(harga);
            return subtotal_potongan;
        }

        $("#formPotongan").find("#jumlah_potongan, #harga_potongan").on('keyup keydown', function(e) {
            const subtotalPotongan = calculatePotongan();
            console.log(subtotalPotongan);
            $("#total_potongan").val(numberFormat(subtotalPotongan, '2', ',', '.'));

            // alert('test');
        });

        const select2Kodeakunpotongan = $('.select2Kodeakunpotongan');
        if (select2Kodeakunpotongan.length) {
            select2Kodeakunpotongan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Akun',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>
