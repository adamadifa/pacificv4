<form action="{{ route('dpb.store') }}" method="POST" id="formDPB" autocomplete="off" aria-autocomplete="none">
    @csrf
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="row">
                <div class="col-lg-4">
                    <x-input-with-icon icon="ti ti-barcode" label="No. DPB" name="no_dpb_format" />
                </div>
                <div class="col-lg-8">
                    <x-input icon="ti ti-barcode" label="No. DPB" name="no_dpb" />
                </div>
            </div>
            <x-input-with-icon icon="ti ti-calendar" label="Tanggal Pengambilan" name="tanggal_ambil" datepicker="flatpickr-date" />
            @hasanyrole($roles_show_cabang)
                <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
                    select2="select2Kodecabang" />
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
            <x-input-with-icon icon="ti ti-map-pin" label="Tujuan" name="tujuan" />
        </div>
        <div class="col-lg-4 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <select name="kode_driver" id="kode_driver" class="form-select select2Kodedriver">
                    <option value="">Pilih Driver</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="kode_helper_1" id="kode_helper_1" class="form-select select2Kodehelper1">
                    <option value="">Pilih Helper</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="kode_helper_2" id="kode_helper_2" class="form-select select2Kodehelper2">
                    <option value="">Pilih Helper</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="kode_helper_3" id="kode_helper_3" class="form-select select2Kodehelper3">
                    <option value="">Pilih Helper</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2">Kode</th>
                        <th rowspan="2" style="width: 60%">Nama Produk</th>
                        <th colspan="3" class="text-center">Kuantitas</th>
                    </tr>
                    <tr>
                        <th>Dus/Ball</th>
                        <th>Pack</th>
                        <th>Pcs</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produk as $d)
                        @if (empty($d->isi_pcs_pack))
                            @php
                                $color = '#ebebebee';
                            @endphp
                        @else
                            @php
                                $color = '';
                            @endphp
                        @endif
                        <tr>
                            <td>
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                <input type="hidden" name="isi_pcs_dus[]" value="{{ $d->isi_pcs_dus }}">
                                <input type="hidden" name="isi_pcs_pack[]" value="{{ $d->isi_pcs_pack }}">
                                {{ $d->kode_produk }}
                            </td>
                            <td>{{ $d->nama_produk }}</td>
                            <td>
                                <input type="text" class="noborder-form text-end money" name="jml_dus[]">
                            </td>
                            <td style="background-color:{{ $color }} ">
                                <input type="text" class="noborder-form text-end money" style="background-color: {{ $color }}"
                                    name="jml_pack[]" {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }}>
                            </td>
                            <td>
                                <input type="text" class="noborder-form text-end money" name="jml_pcs[]">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
        </div>
    </div>
</form>
<script>
    $(function() {
        $(".money").maskMoney();
        const form = $("#formDPB");

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }


        $(".flatpickr-date").flatpickr();


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

        const select2Kodehelper1 = $('.select2Kodehelper1');
        if (select2Kodehelper1.length) {
            select2Kodehelper1.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Helper 1',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodehelper2 = $('.select2Kodehelper2');
        if (select2Kodehelper2.length) {
            select2Kodehelper2.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Helper 2',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodehelper3 = $('.select2Kodehelper3');
        if (select2Kodehelper3.length) {
            select2Kodehelper3.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Helper 3',
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

        function getkendaraanbyCabang() {
            var kode_cabang = form.find("#kode_cabang").val();
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: '/kendaraan/getkendaraanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
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
                    form.find("#kode_driver").html(respond);
                    form.find("#kode_helper_1").html(respond);
                    form.find("#kode_helper_2").html(respond);
                    form.find("#kode_helper_3").html(respond);
                }
            });
        }

        function generatenodpb() {
            const kode_cabang = form.find("#kode_cabang").val();
            const tanggal = form.find("#tanggal_ambil").val();
            $.ajax({
                type: 'POST',
                url: '/dpb/generatenodpb',
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    tanggal: tanggal
                },
                success: function(respond) {
                    form.find("#no_dpb_format").val(respond);
                }
            });
        }

        form.find("#tanggal_ambil").change(function() {
            generatenodpb();
        });
        getsalesmanbyCabang();
        getkendaraanbyCabang();
        getdriverhelperbyCabang();
        generatenodpb();

        form.find("#kode_cabang").change(function(e) {
            getsalesmanbyCabang();
            getkendaraanbyCabang();
            getdriverhelperbyCabang();
            generatenodpb();
        });




        form.find("#no_dpb").mask("00000");

        form.submit(function() {
            const no_dpb = form.find("#no_dpb").val();
            const no_dpb_format = form.find("#no_dpb_format").val();
            const kode_cabang = form.find("#kode_cabang").val();
            const kode_salesman = form.find("#kode_salesman").val();
            const kode_kendaraan = form.find("#kode_kendaraan").val();
            const tujuan = form.find("#tujuan").val();
            const kode_driver = form.find("#kode_driver").val();
            const tanggal_ambil = form.find("#tanggal_ambil").val();
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
            } else if (no_dpb_format == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Format DPB Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_dpb_format").focus();
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
            } else {
                buttonDisable();
            }
            // else if (kode_driver == "") {
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
            // }
        });
    });
</script>
