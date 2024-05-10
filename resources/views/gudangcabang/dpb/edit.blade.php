<form action="{{ route('dpb.update', Crypt::encrypt($dpb->no_dpb)) }}" method="POST" id="formDPB" autocomplete="off"
    aria-autocomplete="none">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-input icon="ti ti-barcode" label="No. DPB" name="no_dpb" value="{{ $dpb->no_dpb }}" />
            @hasanyrole($roles_show_cabang)
                <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                    upperCase="true" select2="select2Kodecabang" selected="{{ $dpb->kode_cabang }}" />
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
            <button type="button" class="btn btn-primary text-nowrap mb-2 w-100" data-bs-toggle="popover"
                data-bs-placement="top"
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
                <div class="col-lg-7 col-md-12.col-sm-12  ">
                    <div class="form-group mb-3">
                        <select name="helper" id="kode_helper" class="form-select select2Kodehelper">
                            <option value="">Pili Helper</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <x-input-with-icon label="Jumlah" name="qty_helper" icon="ti ti-box" align="right" />
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
                                    <td>{{ $dh->kode_driver_helper }}</td>
                                    <td>{{ textUpperCase($dh->nama_driver_helper) }}</td>
                                    <td style="width: 20%">
                                        <input type="text" class="noborder-form text-end qtyhelper"
                                            name="qtyhelper[]">
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
                            <option value="P">Persentase</option>
                            <option value="Q">Quantity</option>
                            <option value="R">Dibagi Rata</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="3" class="align-middle">Kode</th>
                        <th rowspan="3" style="width: 60%" class="align-middle">Nama Produk</th>
                        <th colspan="3" class="text-center">Pengambilan</th>
                        <th colspan="3" class="text-center bg-success">Pengembalian</th>
                        <th colspan="3" class="text-center bg-danger align-middle" rowspan="2">Barang Keluar
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3">
                            <input type="text" class="noborder-form flatpickr-date text-center"
                                name="tanggal_ambil" id="tanggal_ambil"
                                style="font-size: 14px; background-color:#002e65; color:white; border-bottom:1px solid white; padding:5px"
                                placeholder="Tanggal Pengambilan" value="{{ $dpb->tanggal_ambil }}">
                        </th>
                        <th colspan="3" class="bg-success">
                            <input type="text" class="noborder-form flatpickr-date text-center bg-success"
                                name="tanggal_kembali" id="tanggal_kembali"
                                style="font-size: 14px; color:white; border-bottom:1px solid white; padding:5px"
                                placeholder="Tanggal Pengembalian"
                                value="{{ !empty($dpb->tanggal_kembali) ? $dpb->tanggal_kembali : '' }}">
                        </th>
                    </tr>
                    <tr>
                        <th>Dus/Ball</th>
                        <th>Pack</th>
                        <th>Pcs</th>

                        <th class="bg-success">Dus/Ball</th>
                        <th class="bg-success">Pack</th>
                        <th class="bg-success">Pcs</th>

                        <th class="bg-danger">Dus/Ball</th>
                        <th class="bg-danger">Pack</th>
                        <th class="bg-danger">Pcs</th>
                    </tr>
                </thead>
                <tbody>
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
                        @endphp
                        <tr>
                            <td>
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                <input type="hidden" name="isi_pcs_dus[]" value="{{ $d->isi_pcs_dus }}">
                                <input type="hidden" name="isi_pcs_pack[]" value="{{ $d->isi_pcs_pack }}">
                                {{ $d->kode_produk }}
                            </td>
                            <td>{{ $d->nama_produk }}</td>
                            <td>
                                <input type="text" class="noborder-form text-end money" name="jml_ambil_dus[]"
                                    value="{{ formatAngka($ambil_dus) }}">
                            </td>
                            <td style="background-color:{{ $color }}">
                                <input type="text" class="noborder-form text-end money"
                                    style="background-color:{{ $color }}"
                                    {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }} name="jml_ambil_pack[]"
                                    value="{{ formatAngka($ambil_pack) }}">
                            </td>
                            <td>
                                <input type="text" class="noborder-form text-end money" name="jml_ambil_pcs[]"
                                    value="{{ formatAngka($ambil_pcs) }}">
                            </td>

                            <td style="background-color:#28c76f1a">
                                <input type="text" style="background-color:#ffffff1a"
                                    class="noborder-form text-end money" name="jml_kembali_dus[]"
                                    value="{{ formatAngka($kembali_dus) }}">
                            </td>
                            <td style="background-color:{{ $color_kembali }}">
                                <input type="text" style="background-color:#ffffff1a"
                                    class="noborder-form text-end money"
                                    {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }} name="jml_kembali_pack[]"
                                    value="{{ formatAngka($kembali_pack) }}">
                            </td>
                            <td style="background-color:#28c76f1a">
                                <input type="text" style="background-color:#ffffff1a"
                                    class="noborder-form text-end money" name="jml_kembali_pcs[]"
                                    value="{{ formatAngka($kembali_pcs) }}">
                            </td>

                            <td style="background-color: #ea54552e">
                                <input type="text" style="background-color: #ffdcdc2e"
                                    class="noborder-form text-end money jml_keluar_dus" name="jml_keluar_dus[]"
                                    value="{{ formatAngka($keluar_dus) }}">
                            </td>
                            <td style="background-color: {{ $color_keluar }}">
                                <input type="text" style="background-color: #ffdcdc2e"
                                    class="noborder-form text-end money jml_keluar_pack"
                                    {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }} name="jml_keluar_pack[]"
                                    value="{{ formatAngka($keluar_pack) }}">
                            </td>
                            <td style="background-color: #ea54552e">
                                <input type="text" style="background-color: #ffdcdc2e"
                                    class="noborder-form text-end money jml_keluar_pcs" name="jml_keluar_pcs[]"
                                    value="{{ formatAngka($keluar_pcs) }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary w-100"><i class="ti ti-send me-1"></i>Submit</button>
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
                    console.log(respond);
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
                    console.log(respond);
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
                    console.log(respond);
                    form.find("#kode_helper").html(respond);
                }
            });
        }


        function getdriverbyCabang() {
            var kode_cabang = form.find("#kode_cabang").val();
            var kode_driver = "{{ $driver->kode_driver_helper }}";
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
                    console.log(respond);
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
                }
            });
        });

        //   form.find("#no_dpb").mask("00000");

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

            let cekvalqtyhelper = 0;
            let totalqtyhelper = 0;
            qtyhelper.each(function() {
                var val = $(this).val() == "" ? 0 : $(this).val();
                if (val == "" || val == "0") {
                    cekvalqtyhelper += 1;
                }

                totalqtyhelper += parseInt(val);
            });


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
            } else if (kode_driver == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Driver Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_driver").focus();
                    },
                });

                return false;
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
                    <input type="text" name="qtyhelper[]" value="${qty_helper}"  class="noborder-form text-end qtyhelper"/>
                </td>
                <td>
                    <a href="#" kode_helper="${kode_helper}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                </td>
            </tr>
            `;

            $('#loaddetailhelper').prepend(helper);
            $("#kode_helper").val("");
            $("#jenis_perhitungan").val("");
            $("#qty_helper").val();
        }

        form.find("#tambahhelper").click(function() {
            const kode_helper = form.find("#kode_helper").val();
            const qty_helper = form.find("#qty_helper").val();
            const cekdetail = form.find('#tabledetailhelper').find('#index_' + kode_helper).length;
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
            } else if (qty_helper == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Persentase / Qty Helper Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#qty_helper").focus();
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
            }
        });
    });
</script>
