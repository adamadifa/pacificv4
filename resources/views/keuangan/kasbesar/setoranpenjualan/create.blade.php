<form action="{{ route('setoranpenjualan.store') }}" method="POST" id="formCreatesetoran">
    @csrf
    <input type="hidden" name="lhp_tunai" id="lhp_tunai">
    <input type="hidden" name="lhp_tagihan" id="lhp_tagihan">
    <input type="hidden" name="lhp_total" id="lhp_total">
    <input type="hidden" name="setoran_giro" id="setoran_giro">
    <input type="hidden" name="setoran_transfer" id="setoran_transfer">
    <input type="hidden" name="giro_to_cash" id="giro_to_cash">
    <input type="hidden" name="giro_to_transfer" id="giro_to_transfer">

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
            <td id="lhp_tunai_text" class="text-end text-success"></td>
        </tr>
        <tr>
            <th>TAGIHAN</th>
            <td id="lhp_tagihan_text" class="text-end text-success"></td>
        </tr>
        <tr>
            <th>TOTAL LHP</th>
            <td id="lhp_total_text" class="text-end text-success"></td>
        </tr>
    </table>
    <div class="divider text-start">
        <div class="divider-text">
            <i class="ti ti-file-description me-2"></i> SETORAN
        </div>
    </div>
    <x-input-with-icon label="Setoran Kertas" name="setoran_kertas" money="true" align="right" icon="ti ti-moneybag" />
    <x-input-with-icon label="Setoran Logam" name="setoran_logam" money="true" align="right" icon="ti ti-moneybag" />
    <x-input-with-icon label="Setoran Lainnya" name="setoran_lainnya" money="true" align="right" icon="ti ti-moneybag" />
    <table class="table mb-3">
        <tr>
            <th>Setoran Giro</th>
            <td id="setoran_giro_text" class="text-end text-danger"></td>
        </tr>
        <tr>
            <th>Setoran Transfer</th>
            <td id="setoran_transfer_text" class="text-end text-danger"></td>
        </tr>
        <tr>
            <th>Total Setoran</th>
            <td id="setoran_total_text" class="text-end text-danger"></td>
        </tr>
        <tr>
            <th>Selisih</th>
            <td id="selisih_text" class="text-end text-danger"></td>
        </tr>
        <tr>
            <th>Ganti Giro ke Cash</th>
            <td id="giro_to_cash_text" class="text-end text-warning"></td>
        </tr>
        <tr>
            <th>Ganti Giro ke Transfer</th>
            <td id="giro_to_transfer_text" class="text-end text-warning"></td>
        </tr>

    </table>
    <x-textarea label="Keterangan" name="keterangan" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formCreatesetoran");
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney();

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

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }
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
            let total_lhp;
            $.ajax({
                type: 'POST',
                url: '/setoranpenjualan/getlhp',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(response) {
                    console.log(response);
                    total_lhp = parseInt(response.data.lhp_tunai) + parseInt(response.data.lhp_tagihan);
                    form.find("#lhp_tunai_text").text(convertToRupiah(response.data.lhp_tunai));
                    form.find("#lhp_tunai").val(response.data.lhp_tunai);
                    form.find("#lhp_tagihan_text").text(convertToRupiah(response.data.lhp_tagihan));
                    form.find("#lhp_tagihan").val(response.data.lhp_tagihan);
                    form.find("#lhp_total_text").text(convertToRupiah(total_lhp));
                    form.find("#lhp_total").val(total_lhp);
                    form.find("#setoran_giro_text").text(convertToRupiah(response.data.setoran_giro));
                    form.find("#setoran_giro").val(response.data.setoran_giro);
                    form.find("#setoran_transfer_text").text(convertToRupiah(response.data.setoran_transfer));
                    form.find("#setoran_transfer").val(response.data.setoran_transfer);
                    loadtotalsetoran();
                    form.find("#giro_to_cash_text").text(convertToRupiah(response.data.giro_to_cash));
                    form.find("#giro_to_cash").val(response.data.giro_to_cash);
                    form.find("#giro_to_transfer_text").text(convertToRupiah(response.data.giro_to_transfer));
                    form.find("#giro_to_transfer").val(response.data.giro_to_transfer);
                }
            });
        }

        form.find("#tanggal,#kode_cabang,#kode_salesman").change(function(e) {
            getlhp();
        });


        function loadtotalsetoran() {
            let total_setoran;
            let selisih;
            let sk = form.find("#setoran_kertas").val();
            let setoran_kertas = sk != "" ? parseInt(sk.replace(/\./g, '')) : 0;

            let sg = form.find("#setoran_logam").val();
            let setoran_logam = sg != "" ? parseInt(sg.replace(/\./g, '')) : 0;

            let sl = form.find("#setoran_lainnya").val();
            let setoran_lainnya = sl != "" ? parseInt(sl.replace(/\./g, '')) : 0;


            let st = form.find("#setoran_transfer_text").text();
            let setoran_transfer = st != "" ? parseInt(st.replace(/\./g, '')) : 0;

            let sb = form.find("#setoran_giro_text").text();
            let setoran_giro = sb != "" ? parseInt(sb.replace(/\./g, '')) : 0;

            let lhp = form.find("#lhp_total_text").text();
            let total_lhp = lhp != "" ? parseInt(lhp.replace(/\./g, '')) : 0;


            total_setoran = parseInt(setoran_kertas) + parseInt(setoran_logam) + parseInt(setoran_lainnya) + parseInt(setoran_transfer) +
                parseInt(setoran_giro);
            selisih = parseInt(total_setoran) - parseInt(total_lhp);
            $("#setoran_total_text").text(convertToRupiah(total_setoran));
            $("#selisih_text").text(convertToRupiah(selisih));

        }

        form.find("#setoran_logam,#setoran_kertas,#setoran_lainnya").on('keyup keydown', function(e) {
            loadtotalsetoran();
        });

        form.submit(function() {
            const tanggal = form.find("#tanggal").val();
            const kode_cabang = form.find("#kode_cabang").val();
            const kode_salesman = form.find("#kode_salesman").val();
            const lhp_total = form.find("#lhp_total").val();
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

            } else if (kode_cabang == "") {
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
            } else if (kode_salesman == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Salesman Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_salesman").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
                return true;
            }

            // else if (lhp_total == "" || lhp_total === '0') {
            //     Swal.fire({
            //         title: "Oops!",
            //         text: "Tidak Ada Transaksi Pada Tanggal Tersebut !",
            //         icon: "warning",
            //         showConfirmButton: true,
            //         didClose: (e) => {
            //             form.find("#tanggal").focus();
            //         },
            //     });
            //     return false;
            // }
        });
    });
</script>
