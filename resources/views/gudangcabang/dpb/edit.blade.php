<form action="{{ route('dpb.update', Crypt::encrypt($dpb->no_dpb)) }}" method="POST" id="formDPB" autocomplete="off" aria-autocomplete="none">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-input icon="ti ti-barcode" label="No. DPB" name="no_dpb" value="{{ $dpb->no_dpb }}" />
            @hasanyrole($roles_show_cabang)
                <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
                    select2="select2Kodecabang" selected="{{ $dpb->kode_cabang }}" />
            @endrole
            <div class="form-group mb-3">
                <select name="kode_salesman" id="kode_salesman" class="form-select select2Kodesalesman">
                    <option value="">Salesman</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="kode_kendaraan" id="kode_kendaraan" class="form-select select2Kodekendaraan">
                    <option value="">Pilih Kendaraan</option>
                </select>
            </div>
            <x-input-with-icon icon="ti ti-map-pin" label="Tujuan" name="tujuan" value="{{ $dpb->tujuan }}" />
            <button type="button" class="btn btn-primary text-nowrap mb-2 w-100" data-bs-toggle="popover" data-bs-placement="top"
                data-bs-content="Barang Kembali = Sisa Order, Retur / Reject Pasar &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Barang Keluar = Penjualan, Ganti Barang, Promosi, Pelunasan Hutang Kirim "
                title="Cara Pengisian DPB" data-bs-custom-class="popover-info">
                <i class="ti ti-info-square-rounded me-1"></i> Informasi Cara Pengisian
            </button>
        </div>

        <div class="col-lg-8 col-sm-12 col-md-12">
            <div class="row">
                <div class="col">
                    <div class="form-group mb-3">
                        <select name="kode_driver" id="kode_driver" class="form-select select2Kodedriver">
                            <option value="">Pili Driver</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 col-md-12.col-sm-12  ">
                    <div class="form-group mb-3">
                        <select name="helper" id="kode_helper" class="form-select select2Kodehelper">
                            <option value="">Pili Helper</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12">
                    <a href="#" class="btn btn-primary w-100" id="tambahhelper"><i class="ti ti-plus"></i></a>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <table class="table table-bordered" id="tabledetailhelper">
                        <thead class="table-dark">
                            <tr>
                                <th colspan="5">Detail Helper</th>
                            </tr>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Jumlah</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody id="loaddetailhelper">
                            @foreach ($driverhelper as $dh)
                                <tr id="index_{{ $dh->kode_driver_helper }}">
                                    <td>
                                        <input type="hidden" name="kodehelper[]" value="{{ $dh->kode_driver_helper }}">
                                        {{ $dh->kode_driver_helper }}
                                    </td>
                                    <td>{{ textUpperCase($dh->nama_driver_helper) }}</td>
                                    <td style="width: 20%">
                                        <input type="text" class="noborder-form text-end qtyhelper" name="qtyhelper[]"
                                            value="{{ formatAngkaDesimal3($dh->jumlah) }}">
                                    </td>
                                    <td>
                                        <a href="#" kode_helper="{{ $dh->kode_driver_helper }}" class="delete"><i
                                                class="ti ti-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="form-group mb-3 mt-2">
                        <select name="jenis_perhitungan" id="jenis_perhitungan" class="form-select">
                            <option value="">Jenis Perhitungan</option>
                            <option value="P" {{ $dpb->jenis_perhitungan == 'P' ? 'selected' : '' }}>Persentase
                            </option>
                            <option value="Q" {{ $dpb->jenis_perhitungan == 'Q' ? 'selected' : '' }}>Quantity
                            </option>
                            <option value="R" {{ $dpb->jenis_perhitungan == 'R' ? 'selected' : '' }}>Dibagi Rata
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            <table class="table table-bordered" id="tabledpb">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="3" class="align-middle">Kode</th>
                        <th rowspan="3" style="width: 60%" class="align-middle">Nama Produk</th>
                        <th colspan="3" class="text-center">Pengambilan</th>
                        <th colspan="3" class="text-center bg-success">Pengembalian</th>
                        <th colspan="4" class="text-center bg-danger align-middle" rowspan="2">Barang Keluar
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3">
                            <input type="text" class="noborder-form flatpickr-date text-center" name="tanggal_ambil" id="tanggal_ambil"
                                style="font-size: 14px; background-color:#002e65; color:white; border-bottom:1px solid white; padding:5px"
                                placeholder="Tanggal Pengambilan" value="{{ $dpb->tanggal_ambil }}">
                        </th>
                        <th colspan="3" class="bg-success">
                            <input type="text" class="noborder-form flatpickr-date text-center bg-success" name="tanggal_kembali"
                                id="tanggal_kembali" style="font-size: 14px; color:white; border-bottom:1px solid white; padding:5px"
                                placeholder="Tanggal Pengembalian" value="{{ !empty($dpb->tanggal_kembali) ? $dpb->tanggal_kembali : '' }}">
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 15%">Dus</th>
                        <th>Pack</th>
                        <th>Pcs</th>

                        <th class="bg-success" style="width: 15%">Dus</th>
                        <th class="bg-success">Pack</th>
                        <th class="bg-success">Pcs</th>

                        <th class="bg-danger" style="width: 15%">Dus</th>
                        <th class="bg-danger">Pack</th>
                        <th class="bg-danger">Pcs</th>
                        <th class="bg-danger" style="width: 10%">Desimal</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalbarangkeluar = 0;
                    @endphp
                    @foreach ($produk as $d)
                        @if (empty($d->isi_pcs_pack))
                            @php
                                $color = '#ebebebee';
                                $color_kembali = '#28c76f40';
                                $color_keluar = '#ea545451';
                            @endphp
                        @else
                            @php
                                $color = '';
                                $color_kembali = '#28c76f1a';
                                $color_keluar = '#ea54552e';
                            @endphp
                        @endif
                        @php
                            //Jml Pengambilan
                            $ambil = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_ambil));
                            $ambil_dus = $ambil[0];
                            $ambil_pack = $ambil[1];
                            $ambil_pcs = $ambil[2];

                            //Jml Pengembalian
                            $kembali = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_kembali));
                            $kembali_dus = $kembali[0];
                            $kembali_pack = $kembali[1];
                            $kembali_pcs = $kembali[2];

                            //Jml Barang Keluar
                            $keluar = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_penjualan));
                            $keluar_dus = $keluar[0];
                            $keluar_pack = $keluar[1];
                            $keluar_pcs = $keluar[2];

                            $jumlah_keluar = $keluar_dus * $d->isi_pcs_dus + $keluar_pack * $d->isi_pcs_pack + $keluar_pcs;
                            $barangkeluar_dus = ROUND($jumlah_keluar / $d->isi_pcs_dus, 3);
                            $totalbarangkeluar += $barangkeluar_dus;
                        @endphp
                        <tr>
                            <td>
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                <input type="hidden" id="isi_pcs_dus" name="isi_pcs_dus[]" value="{{ $d->isi_pcs_dus }}">
                                <input type="hidden" id="isi_pcs_pack" name="isi_pcs_pack[]" value="{{ $d->isi_pcs_pack }}">
                                {{ $d->kode_produk }}
                            </td>
                            <td>{{ $d->nama_produk }}</td>
                            <td>
                                <input type="text" class="noborder-form text-end money" name="jml_ambil_dus[]"
                                    value="{{ formatAngka($ambil_dus) }}">
                            </td>
                            <td style="background-color:{{ $color }}">
                                <input type="text" class="noborder-form text-end money" style="background-color:{{ $color }}"
                                    {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }} name="jml_ambil_pack[]"
                                    value="{{ formatAngka($ambil_pack) }}">
                            </td>
                            <td>
                                <input type="text" class="noborder-form text-end money" name="jml_ambil_pcs[]"
                                    value="{{ formatAngka($ambil_pcs) }}">
                            </td>

                            <td style="background-color:#28c76f1a">
                                <input type="text" style="background-color:#ffffff1a" class="noborder-form text-end money"
                                    name="jml_kembali_dus[]" value="{{ formatAngka($kembali_dus) }}">
                            </td>
                            <td style="background-color:{{ $color_kembali }}">
                                <input type="text" style="background-color:#ffffff1a" class="noborder-form text-end money"
                                    {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }} name="jml_kembali_pack[]"
                                    value="{{ formatAngka($kembali_pack) }}">
                            </td>
                            <td style="background-color:#28c76f1a">
                                <input type="text" style="background-color:#ffffff1a" class="noborder-form text-end money"
                                    name="jml_kembali_pcs[]" value="{{ formatAngka($kembali_pcs) }}">
                            </td>

                            <td style="background-color: #ea54552e">
                                <input type="text" style="background-color: #ffdcdc2e" class="noborder-form text-end money jml_keluar_dus"
                                    name="jml_keluar_dus[]" id="jml_keluar_dus" value="{{ formatAngka($keluar_dus) }}">
                            </td>
                            <td style="background-color: {{ $color_keluar }}">
                                <input type="text" style="background-color: #ffdcdc2e" class="noborder-form text-end money jml_keluar_pack"
                                    {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }} name="jml_keluar_pack[]" id="jml_keluar_pack"
                                    value="{{ formatAngka($keluar_pack) }}">
                            </td>
                            <td style="background-color: #ea54552e">
                                <input type="text" style="background-color: #ffdcdc2e" class="noborder-form text-end money jml_keluar_pcs"
                                    name="jml_keluar_pcs[]" id="jml_keluar_pcs" value="{{ formatAngka($keluar_pcs) }}">
                            </td>
                            <td class="text-end">
                                <input type="hidden" class="subtotal" value="{{ $barangkeluar_dus }}">
                                <span class="showDesimalbarangkeluar">{{ formatAngkaDesimal3($barangkeluar_dus) }}</span>
                            </td>
                        </tr>
                    @endforeach
                <tfoot>
                    <tr>
                        <th colspan="11">TOTAL</th>
                        <th class="text-end">
                            <input type="hidden" class="grandtotal" id="grandtotal" name="grandtotal" value="{{ $totalbarangkeluar }}">
                            <span id="showTotalbarangkeluar"
                                style="font-size: 16px !important; font-weight:bold">{{ formatAngkaDesimal3($totalbarangkeluar) }}</span>
                        </th>
                    </tr>
                </tfoot>
                </tbody>
            </table>
            {{-- {{ $totalbarangkeluar }} --}}
        </div>
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/ui-popover.js') }}"></script>
{{-- <script src="{{ asset('assets/js/pages/dpb/edit.js') }}"></script> --}}
<script>
    $(function() {

        $(".money").maskMoney();
        const form = $("#formDPB");

        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }, ]
        });

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



        var $tblrows = $("#tabledpb tbody tr");

        $tblrows.each(function(index) {

            var $tblrow = $(this);
            // console.log($tblrow.find('.jml_keluar_dus').val());
            $tblrow.find('.jml_keluar_dus,.jml_keluar_pack,.jml_keluar_pcs').on('keyup keydown',
                function() {
                    var jenis_perhitungan = form.find("#jenis_perhitungan").val();
                    var jml_keluar_dus = $tblrow.find("[id=jml_keluar_dus]").val();
                    var jml_keluar_pack = $tblrow.find("[id=jml_keluar_pack]").val();
                    var jml_keluar_pcs = $tblrow.find("[id=jml_keluar_pcs]").val();
                    var isi_pcs_dus = $tblrow.find("[id=isi_pcs_dus]").val();
                    var isi_pcs_pack = $tblrow.find("[id=isi_pcs_pack]").val();

                    if (jml_keluar_dus.length === 0) {
                        var jml_keluar_dus = 0;
                    } else {
                        var jml_keluar_dus = parseInt(jml_keluar_dus.replace(/\./g, ''));
                    }

                    if (jml_keluar_pack.length === 0) {
                        var jml_keluar_pack = 0;
                    } else {
                        var jml_keluar_pack = parseInt(jml_keluar_pack.replace(/\./g, ''));
                    }

                    if (jml_keluar_pcs.length === 0) {
                        var jml_keluar_pcs = 0;
                    } else {
                        var jml_keluar_pcs = parseInt(jml_keluar_pcs.replace(/\./g, ''));
                    }

                    if (isi_pcs_dus.length === 0) {
                        var isi_pcs_dus = 0;
                    } else {
                        var isi_pcs_dus = parseInt(isi_pcs_dus.replace(/\./g, ''));
                    }

                    if (isi_pcs_pack.length === 0) {
                        var isi_pcs_pack = 0;
                    } else {
                        var isi_pcs_pack = parseInt(isi_pcs_pack.replace(/\./g, ''));
                    }

                    var subTotal = ((parseInt(jml_keluar_dus) * parseInt(isi_pcs_dus)) + (parseInt(
                        jml_keluar_pack) * parseInt(isi_pcs_pack)) + parseInt(
                        jml_keluar_pcs)) / parseInt(isi_pcs_dus);
                    if (!isNaN(subTotal)) {
                        $tblrow.find('.subtotal').val(subTotal.toFixed(3));
                        $tblrow.find('.showDesimalbarangkeluar').text(numberFormat(subTotal, '3', ',', '.'));
                        var grandTotal = 0;
                        $(".subtotal").each(function() {
                            var stval = parseFloat($(this).val());
                            grandTotal += isNaN(stval) ? 0 : stval;
                        });
                        $('.grandtotal').val(grandTotal.toFixed(3));
                        $('#showTotalbarangkeluar').text(numberFormat(grandTotal, '3', ',', '.'));
                    }

                    console.log(jenis_perhitungan);
                    if (jenis_perhitungan == "R") {
                        generateqtyhelper();
                    }
                });
        });

        function generateqtyhelper() {
            const totalbarangkeluar = $("#grandtotal").val();
            const qtyhelper = form.find(".qtyhelper");
            const jenis_perhitungan = form.find("#jenis_perhitungan").val();

            if (jenis_perhitungan == "P") {
                qtyhelper.each(function() {
                    let value = $(this).val();
                    let persentase = totalbarangkeluar > 0 ? parseFloat(value) / parseFloat(
                        totalbarangkeluar) * 100 : 0;
                    $(this).val(Math.round(persentase));
                });
                $(".qtyhelper").prop('readonly', false);
            } else if (jenis_perhitungan == "R") {
                let jmlhelper = 0;
                qtyhelper.each(function() {
                    jmlhelper += 1;
                });

                qtyhelper.each(function() {
                    const qtyhelper = parseFloat(totalbarangkeluar) / parseFloat(jmlhelper);
                    $(this).val(numberFormat(qtyhelper, '3', ',', '.'));
                });

                $(".qtyhelper").prop('readonly', true);
            } else if (jenis_perhitungan == "Q") {
                //$(".qtyhelper").val(0);
                $(".qtyhelper").prop('readonly', false);
            }
        }

        form.find("#jenis_perhitungan").change(function(e) {
            const jenis_perhitungan = $("#jenis_perhitungan").val();
            if (jenis_perhitungan == "Q") {
                $(".qtyhelper").val(0);
            }
            generateqtyhelper();
        });
        generateqtyhelper();

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

        const select2Kodekendaraan = $('.select2Kodekendaraan');
        if (select2Kodekendaraan.length) {
            select2Kodekendaraan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Kendaraan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }


        const select2Kodehelper = $('.select2Kodehelper');
        if (select2Kodehelper.length) {
            select2Kodehelper.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Helper',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodedriver = $('.select2Kodedriver');
        if (select2Kodedriver.length) {
            select2Kodedriver.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Driver',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }



        function getsalesmanbyCabang() {
            var kode_cabang = form.find("#kode_cabang").val();
            var kode_salesman = "{{ $dpb->kode_salesman }}";
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    //console.log(respond);
                    form.find("#kode_salesman").html(respond);
                }
            });
        }

        function getkendaraanbyCabang() {
            var kode_cabang = form.find("#kode_cabang").val();
            var kode_kendaraan = "{{ $dpb->kode_kendaraan }}";
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: '/kendaraan/getkendaraanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_kendaraan: kode_kendaraan
                },
                cache: false,
                success: function(respond) {
                    //console.log(respond);
                    form.find("#kode_kendaraan").html(respond);
                }
            });
        }


        function getdriverhelperbyCabang() {
            var kode_cabang = form.find("#kode_cabang").val();
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: '/driverhelper/getdriverhelperbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(respond) {
                    //console.log(respond);
                    form.find("#kode_helper").html(respond);
                }
            });
        }


        function getdriverbyCabang() {
            var kode_cabang = form.find("#kode_cabang").val();
            var kode_driver = "{{ $driver != null ? $driver->kode_driver_helper : '' }}";
            $.ajax({
                type: 'POST',
                url: '/driverhelper/getdriverhelperbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_driver_helper: kode_driver
                },
                cache: false,
                success: function(respond) {
                    //console.log(respond);
                    form.find("#kode_driver").html(respond);
                }
            });
        }

        //   function generatenodpb() {
        //      const kode_cabang = form.find("#kode_cabang").val();
        //      const tanggal = form.find("#tanggal_ambil").val();
        //      $.ajax({
        //         type: 'POST',
        //         url: '/dpb/generatenodpb',
        //         cache: false,
        //         data: {
        //            _token: "{{ csrf_token() }}",
        //            kode_cabang: kode_cabang,
        //            tanggal: tanggal
        //         },
        //         success: function(respond) {
        //            form.find("#no_dpb_format").val(respond);
        //         }
        //      });
        //   }

        //   form.find("#tanggal_ambil").change(function() {
        //      generatenodpb();
        //   });
        getsalesmanbyCabang();
        getkendaraanbyCabang();
        getdriverhelperbyCabang();
        getdriverbyCabang();
        //   generatenodpb();

        form.find("#kode_cabang").change(function(e) {
            getsalesmanbyCabang();
            getkendaraanbyCabang();
            getdriverhelperbyCabang();
            getdriverbyCabang();
            //  generatenodpb();
        });


        form.on('click', '.delete', function(e) {
            e.preventDefault();
            var kode_helper = $(this).attr("kode_helper");
            event.preventDefault();
            Swal.fire({
                title: `Apakah Anda Yakin Ingin Menghapus Data Ini ?`,
                text: "Jika dihapus maka data akan hilang permanent.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#554bbb",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Hapus Saja!"
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $(`#index_${kode_helper}`).remove();
                    if (jenis_perhitungan == "R") {
                        generateqtyhelper();
                    }
                }
            });
        });

        //   form.find("#no_dpb").mask("00000");
        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }
        form.submit(function() {
            const no_dpb = form.find("#no_dpb").val();
            const tanggal_ambil = form.find("#tanggal_ambil").val();
            const tanggal_kembali = form.find("#tanggal_kembali").val();
            const kode_cabang = form.find("#kode_cabang").val();
            const kode_salesman = form.find("#kode_salesman").val();
            const kode_kendaraan = form.find("#kode_kendaraan").val();
            const tujuan = form.find("#tujuan").val();
            const kode_driver = form.find("#kode_driver").val();
            const jenis_perhitungan = form.find("#jenis_perhitungan").val();
            const qtyhelper = form.find(".qtyhelper");
            const totalbarangkeluar = $("#grandtotal").val();


            let cekvalqtyhelper = 0;
            let totalqtyhelper = 0;
            qtyhelper.each(function() {
                var val = $(this).val() == "" ? 0 : $(this).val();
                var value = val
                    .replaceAll('.', '')
                    .replaceAll(',', '.');
                if (value == "" || value == "0") {
                    cekvalqtyhelper += 1;
                }
                totalqtyhelper += parseFloat(value);
                //alert(totalqtyhelper.toFixed(3));
            });


            //return false;
            if (no_dpb == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. DPB Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_dpb").focus();
                    },
                });

                return false;
            } else if (tanggal_ambil == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Pengambilan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal_ambil").focus();
                    },
                });

                return false;
            } else if (tanggal_kembali == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Pengembalian Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal_kembali").focus();
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
            } else if (kode_kendaraan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kendaraan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_kendaraan").focus();
                    },
                });

                return false;
            } else if (tujuan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tujuan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tujuan").focus();
                    },
                });
                return false;
                // }
                //  else if (kode_driver == "") {
                //     Swal.fire({
                //         title: "Oops!",
                //         text: "Driver Harus Diisi !",
                //         icon: "warning",
                //         showConfirmButton: true,
                //         didClose: (e) => {
                //             form.find("#kode_driver").focus();
                //         },
                //     });

                //     return false;
            } else if (jenis_perhitungan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Perhitungan Harus Diisi",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jenis_perhitungan").focus();
                    },
                });

                return false;
            } else if (jenis_perhitungan == "P" && totalqtyhelper != "100") {
                Swal.fire({
                    title: "Oops!",
                    text: "Total Jumlah Harus 100%",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#qty_helper").focus();
                    },
                });
                return false;
            } else if (jenis_perhitungan == "Q" && totalqtyhelper > totalbarangkeluar) {
                // alert(totalqtyhelper);
                // alert(totalbarangkeluar);
                Swal.fire({
                    title: "Oops!",
                    text: "Total Qty Helper Melebihi Total Barang Keluar",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#qty_helper").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });

        function addHelper() {
            const dataHelper = form.find("#kode_helper :selected").select2(this.data);
            const kode_helper = $(dataHelper).val();
            const nama_helper = $(dataHelper).text();
            const qty_helper = form.find("#qty_helper").val();

            let helper = `
            <tr id="index_${kode_helper}">
                <td>
                    <input type="hidden" name="kodehelper[]" value="${kode_helper}"/>
                    ${kode_helper}
                </td>
                <td>${nama_helper}</td>
                <td>
                    <input type="text" name="qtyhelper[]" value="0"  class="noborder-form text-end qtyhelper"/>
                </td>
                <td>
                    <a href="#" kode_helper="${kode_helper}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                </td>
            </tr>
            `;

            $('#loaddetailhelper').prepend(helper);
            $('.select2Kodehelper').val("").trigger("change");
            $("#qty_helper").val("");
        }

        form.find("#tambahhelper").click(function() {
            const kode_helper = form.find("#kode_helper").val();
            const qty_helper = form.find("#qty_helper").val();
            const cekdetail = form.find('#tabledetailhelper').find('#index_' + kode_helper).length;
            const jenis_perhitungan = form.find("#jenis_perhitungan").val();
            if (kode_helper == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Helper Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_helper").focus();
                    },
                });
            } else if (cekdetail > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Helper Suda Ada",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_helper").focus();
                    },
                });
            } else {
                addHelper();
                if (jenis_perhitungan == "R") {
                    generateqtyhelper();
                }
            }
        });
    });
</script>
