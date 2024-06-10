<form action="#" method="POST" id="formCreatesetoran">
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal LHP" name="tanggal" datepicker="flatpickr-date" />
    @hasanyrole($roles_show_cabang)
        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
            select2="select2Kodecabang" />
    @endhasanyrole
    <div class="form-group mb-3">
        <select name="kode_salesman" id="kode_salesman" class="select2Kodesalesman form-select">
        </select>
    </div>
    <div class="divider text-start">
        <div class="divider-text">
            <i class="ti ti-file-description me-2"></i> LHP
        </div>
    </div>
    <table class="table">
        <tr>
            <th>TUNAI</th>
            <td id="lhp_tunai_text"></td>
        </tr>
        <tr>
            <th>TAGIHAN</th>
            <td id="lhp_tagihan_text"></td>
        </tr>
        <tr>
            <th>TOTAL LHP</th>
            <td id="lhp_total"></td>
        </tr>
    </table>
    <div class="divider text-start">
        <div class="divider-text">
            <i class="ti ti-file-description me-2"></i> SETORAN
        </div>
    </div>
    <x-input-with-icon label="Setoran Kertas" name="setoran_kertas" money="true" align="right"
        icon="ti ti-moneybag" />
    <x-input-with-icon label="Setoran Logam" name="setoran_logam" money="true" align="right" icon="ti ti-moneybag" />
    <x-input-with-icon label="Setoran Lainnya" name="setoran_lainnya" money="true" align="right"
        icon="ti ti-moneybag" />
    <table class="table mb-3">
        <tr>
            <th>Setoran Giro</th>
            <td id="setoran_giro_text"></td>
        </tr>
        <tr>
            <th>Setoran Transfer</th>
            <td id="setoran_transfer_text"></td>
        </tr>
        <tr>
            <th>Total</th>
            <td id="setoran_total_text"></td>
        </tr>
        <tr>
            <th>Selisih</th>
            <td id="selisih_text"></td>
        </tr>
        <tr>
            <th>Ganti Giro ke Cash</th>
            <td id="giro_to_cash_text"></td>
        </tr>
        <tr>
            <th>Ganti Giro ke Transfer</th>
            <td id="giro_to_transfer_text"></td>
        </tr>

    </table>
    <x-textarea label="Keterangan" name="keterangan" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formCreatesetoran");
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney();
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

        const select2Kodesalesman = $('.select2Kodesalesman');
        if (select2Kodesalesman.length) {
            select2Kodesalesman.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Salesman',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function getsalesmanbyCabang() {

            var kode_cabang = form.find("#kode_cabang").val();
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    form.find("#kode_salesman").html(respond);
                }
            });
        }

        getsalesmanbyCabang();

        form.find("#kode_cabang").change(function(e) {
            getsalesmanbyCabang();
        });

        function getlhp() {
            const tanggal = form.find("#tanggal").val();
            const kode_salesman = form.find("#kode_salesman").val();
            $.ajax({
                type: 'POST',
                url: '/setoranpenjualan/getlhp',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                }
            });
        }

        form.find("#tanggal,#kode_cabang,#kode_salesman").change(function(e) {
            getlhp();
        });
    });
</script>
