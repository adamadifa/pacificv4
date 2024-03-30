@foreach ($detail as $d)
    @php
        $subtotal = $d->total_oman - $d->saldo_akhir_gudang;
    @endphp
    <tr>
        <td>
            {{ $d->kode_produk }}
            <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
        </td>
        <td>{{ $d->nama_produk }}</td>
        <td class="text-end">
            <input type="hidden" id="oman_marketing" class="oman_marketing" name="oman_marketing[]"
                value="{{ $d->total_oman }}">
            {{ formatAngka($d->total_oman) }}
        </td>
        <td class="text-end">
            <input type="hidden" id="stok_gudang" class="stok_gudang" name="stok_gudang[]"
                value="{{ $d->saldo_akhir_gudang }}">
            {{ formatAngka($d->saldo_akhir_gudang) }}
        </td>
        <td>
            <input type="text" id="buffer_stok" name="buffer_stok[]" placeholder="0"
                class="form-table text-end buffer_stok number-separator">
        </td>
        <td>
            <input type="text" id="subtotal" name="subtotal[]" placeholder="0" value="{{ formatAngka($subtotal) }}"
                class="form-table text-end subtotal" readonly>
        </td>
    </tr>
@endforeach

<script>
    function convertToRupiah(number) {
        if (number) {
            var rupiah = "";
            var numberrev = number
                .toString()
                .split("")
                .reverse()
                .join("");
            for (var i = 0; i < numberrev.length; i++)
                if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
            return (
                rupiah
                .split("", rupiah.length - 1)
                .reverse()
                .join("")
            );
        } else {
            return number;
        }
    }

    easyNumberSeparator({
        selector: '.number-separator',
        separator: '.',
        decimalSeparator: ',',
    });
    var $tblrows = $("#mytable tbody tr");
    $tblrows.each(function(index) {
        var $tblrow = $(this);
        $tblrow.find('.oman_marketing,.stok_gudang,.buffer_stok').on('input', function() {
            var oman_marketing = $tblrow.find("[id=oman_marketing]").val();
            var stok_gudang = $tblrow.find("[id=stok_gudang]").val();
            var buffer_stok = $tblrow.find("[id=buffer_stok]").val();

            if (oman_marketing.length === 0) {
                var om = 0;
            } else {
                var om = parseInt(oman_marketing.replace(/\./g, ''));
            }
            if (stok_gudang.length === 0) {
                var sg = 0;
            } else {
                var sg = parseInt(stok_gudang.replace(/\./g, ''));
            }
            if (buffer_stok.length === 0) {
                var bs = 0;
            } else {
                var bs = parseInt(buffer_stok.replace(/\./g, ''));
            }


            var subTotal = parseInt(om) - parseInt(sg) + parseInt(bs);

            if (!isNaN(subTotal)) {
                $tblrow.find('.subtotal').val(convertToRupiah(subTotal));
                // var grandTotal = 0;
                // $(".subtotal").each(function() {
                //     var stval = parseInt($(this).val());
                //     grandTotal += isNaN(stval) ? 0 : stval;
                // });
                //$('.grdtot').val(grandTotal.toFixed(2));
            }

        });
    });
</script>
