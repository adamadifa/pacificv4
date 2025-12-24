<form action="#" id="formEditbarang">
    @php
        $total = toNumber($databarang['jumlah']) * toNumber($databarang['harga']);
    @endphp
    <x-input-with-icon label="Kode Barang" name="kode_barang" icon="ti ti-barcode" disabled="true"
        value="{{ $databarang['kode_barang'] }}" />
    <x-input-with-icon label="Nama Barang" name="nama_barang" icon="ti ti-box" disabled="true"
        value="{{ textCamelCase($barang->nama_barang) }}" />
    <x-input-with-icon label="Qty" id="jumlah" name="jumlah" icon="ti ti-box" value="{{ $databarang['jumlah'] }}"
        align="right" numberFormat="true" />
    <x-input-with-icon label="Harga" id="harga" name="harga" icon="ti ti-moneybag"
        value="{{ $databarang['harga'] }}" align="right" numberFormat="true" />
    <x-input-with-icon label="Total" id="total" name="total" icon="ti ti-moneybag" align="right"
        numberFormat="true" />
    <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnUpdatebarang"><i class="ti ti-send me-1"></i>Submit</button>
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


        function calculateTotal() {
            let qty = $("#formEditbarang").find("#jumlah").val();
            let harga = $("#formEditbarang").find("#harga").val();

            qty = convertNumber(qty);
            harga = convertNumber(harga);


            total = parseFloat(qty) * parseFloat(harga);
            return total;
        }

        $("#formEditbarang").find("#jumlah, #harga").on('keyup keydown', function(e) {
            const total = calculateTotal();
            console.log(total);
            $("#formEditbarang").find("#total").val(numberFormat(total, '2', ',', '.'));

            // alert('test');
        });

        calculateTotal();
    });
</script>
