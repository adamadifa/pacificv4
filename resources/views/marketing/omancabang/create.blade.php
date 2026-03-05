<style>
    .form-oman {
        width: 100%;
        border: 0px;
    }

    .form-oman:focus {
        outline: none;
    }
</style>
<form action="{{ route('omancabang.store') }}" method="POST" id="frmCreateomancabang">
    <div class="row">
        <div class="co-12">
            @csrf
            <div class="row">
                @hasanyrole($roles_show_cabang)
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                            select2="select2Kodecabang" showKey="true" upperCase="true" hideLabel="true" />
                    </div>

                    <div class="col-lg-3 col-sm-12 col-md-12">
                        @php
                            $bulan_data = collect($list_bulan)->map(function ($item) {
                                return (object) $item;
                            });
                        @endphp
                        <x-select label="Bulan" name="bulan" id="bulan" :data="$bulan_data" key="kode_bulan"
                            textShow="nama_bulan" hideLabel="true" />
                    </div>
                    <div class="col-lg-3 col-sm-12 col-md-12">
                        @php
                            $tahun_data = [];
                            for ($t = $start_year; $t <= date('Y') + 1; $t++) {
                                $tahun_data[] = (object) ['tahun' => $t];
                            }
                        @endphp
                        <x-select label="Tahun" name="tahun" id="tahun" :data="$tahun_data" key="tahun" textShow="tahun"
                            hideLabel="true" />
                    </div>
                @else
                    <div class="col-lg-6 col-sm-12 col-md-12">
                        <x-select label="Bulan" name="bulan" id="bulan" :data="$bulan_data" key="kode_bulan"
                            textShow="nama_bulan" hideLabel="true" />
                    </div>
                    <div class="col-lg-6 col-sm-12 col-md-12">
                        <x-select label="Tahun" name="tahun" id="tahun" :data="$tahun_data" key="tahun" textShow="tahun"
                            hideLabel="true" />
                    </div>
                @endhasanyrole
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table table-hover table-bordered" id="tableomanCabang">
                <thead class="table-dark">
                    <tr>

                        <th rowspan="3" class="align-middle text-center">Kode Produk</th>
                        <th rowspan="3" class="align-middle" style="width: 25%">Nama Produk</th>
                        <th colspan="4" class="text-center">Jumlah Permintaan</th>
                        <th rowspan="3" class="align-middle">Total</th>
                    </tr>
                    <tr>
                        <th class="text-center">Minggu ke 1</th>
                        <th class="text-center">Minggu ke 2</th>
                        <th class="text-center">Minggu ke 3</th>
                        <th class="text-center">Minggu ke 4</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produk as $d)
                        <tr>

                            <td class="text-center">
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                {{ $d->kode_produk }}
                            </td>
                            <td>{{ $d->nama_produk }}</td>
                            <td>
                                <input type="text" id="jmlm1" name="jmlm1[]" class="jmlm1 text-end form-oman number-separator" placeholder="0"
                                    autocomplete="false" aria-autocomplete="list">
                            </td>
                            <td>
                                <input type="text" id="jmlm2" name="jmlm2[]" class="jmlm2 text-end form-oman number-separator" placeholder="0"
                                    autocomplete="false" aria-autocomplete="list" />
                            </td>
                            <td>
                                <input type="text" id="jmlm3" name="jmlm3[]" class="jmlm3 text-end form-oman number-separator" placeholder="0"
                                    autocomplete="false" aria-autocomplete="list" />
                            </td>
                            <td>
                                <input type="text" id="jmlm4" name="jmlm4[]" class="jmlm4 text-end form-oman number-separator" placeholder="0"
                                    autocomplete="false" aria-autocomplete="list" />
                            </td>
                            <td>
                                <input type="text" id="subtotal" name="subtotal[]" class="subtotal text-end form-oman" placeholder="0" readonly />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <button class="btn btn-primary w-100" type="submit" name="submit"><i class="ti ti-send me-1"></i>Submit</button>
        </div>
    </div>
</form>
<script>
    $(function() {
        const select2Kodecabang = $('.select2Kodecabang');

        function initselect2Kodecabang() {
            if (select2Kodecabang.length) {
                select2Kodecabang.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Cabang',
                        dropdownParent: $this.parent(),

                    });
                });
            }
        }

        initselect2Kodecabang();

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
        var $tblrows = $("#tableomanCabang tbody tr");
        $tblrows.each(function(index) {
            var $tblrow = $(this);
            $tblrow.find('.jmlm1,.jmlm2,.jmlm3,.jmlm4').on('input', function() {
                var jmlm1 = $tblrow.find("[id=jmlm1]").val();
                var jmlm2 = $tblrow.find("[id=jmlm2]").val();
                var jmlm3 = $tblrow.find("[id=jmlm3]").val();
                var jmlm4 = $tblrow.find("[id=jmlm4]").val();



                if (jmlm1.length === 0) {
                    var jml1 = 0;
                } else {
                    var jml1 = parseInt(jmlm1.replace(/\./g, ''));
                }
                if (jmlm2.length === 0) {
                    var jml2 = 0;
                } else {
                    var jml2 = parseInt(jmlm2.replace(/\./g, ''));
                }
                if (jmlm3.length === 0) {
                    var jml3 = 0;
                } else {
                    var jml3 = parseInt(jmlm3.replace(/\./g, ''));
                }

                if (jmlm4.length === 0) {
                    var jml4 = 0;
                } else {
                    var jml4 = parseInt(jmlm4.replace(/\./g, ''));
                }
                var subTotal = parseInt(jml1) + parseInt(jml2) + parseInt(jml3) + parseInt(
                    jml4);

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


        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });

        $("#frmCreateomancabang").submit(function() {
            const kode_cabang = $("#kode_cabang").val();
            const bulan = $("#bulan").val();
            const tahun = $("#tahun").val();

            if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kode_cabang").focus();
                    },
                });

                return false;
            } else if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#bulan").focus();
                    },
                });

                return false;
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#tahun").focus();
                    },
                });
                return false;
            }
        });

    });
</script>
