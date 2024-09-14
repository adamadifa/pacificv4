<form action="{{ route('permintaankiriman.store') }}" method="POST" id="formcreatePermintaankiriman">
    @csrf
    <input type="hidden" id="cektutuplaporan">
    <input type="hidden" id="cekdetailtemp">
    <x-input-with-icon icon="ti ti-barcode" label="Auto" name="no_perminataan" readonly="true" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Permintaan" name="tanggal" datepicker="flatpickr-date" />
    <x-select label="Semua Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
        select2="select2Kodecabang" />
    <div class="form-group mb-3" id="salesman">
        <select name="kode_salesman" id="kode_salesman" class="select2Kodesalesman form-select">
        </select>
    </div>
    <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />
    <div class="divider text-start">
        <div class="divider-text">Detail Produk</div>
    </div>
    <div class="row">
        <div class="col-lg-7 col-md-12 col-sm-12">
            <x-select label="Pilih Produk" name="kode_produk" :data="$produk" key="kode_produk" textShow="nama_produk" upperCase="true"
                select2="select2Kodeproduk" />
        </div>
        <div class="col-lg-3 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-box" label="Jumlah" name="jumlah" align="right" money="true" />
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <a href="#" class="btn btn-primary" id="tambahproduk"><i class="ti ti-plus"></i></a>
        </div>
    </div>

    <table class="table table-hover table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Kode</th>
                <th style="width: 50%">Nama Produk</th>
                <th>Jumlah</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody id="loaddetailtemp"></tbody>
    </table>
    <div class="row">
        <div class="col-12">
            <div class="form-check mt-3 mb-3">
                <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox" value="" id="defaultCheck3">
                <label class="form-check-label" for="defaultCheck3"> Yakin Akan Disimpan ? </label>
            </div>
            <div class="form-group" id="saveButton">
                <button class="btn btn-primary w-100" type="submit" id="btnSimpan">
                    <ion-icon name="send-outline" class="me-1"></ion-icon>
                    Submit
                </button>
            </div>
        </div>
    </div>
</form>

<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script>
    $(function() {
        $(".money").maskMoney();
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
                    dropdownParent: $this.parent()
                });
            });
        }

        function initselect2Kodeproduk() {
            const select2Kodeproduk = $('.select2Kodeproduk');
            if (select2Kodeproduk.length) {
                select2Kodeproduk.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Produk',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }
        }

        initselect2Kodeproduk();

        const select2Kodesalesman = $('.select2Kodesalesman');
        if (select2Kodesalesman.length) {
            select2Kodesalesman.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Salesman',
                    dropdownParent: $this.parent()
                });
            });
        }

        $("#saveButton").hide();

        $('.agreement').change(function() {
            if (this.checked) {
                $("#saveButton").show();
            } else {
                $("#saveButton").hide();
            }
        });

        function cekdetailtemp() {
            $.ajax({
                type: 'POST',
                url: "{{ route('permintaankiriman.cekdetailtemp') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                cache: false,
                success: function(respond) {
                    $("#cekdetailtemp").val(respond);
                }
            });
        }

        function loaddetailtemp() {
            $("#loaddetailtemp").load("{{ route('permintaankiriman.getdetailtemp') }}");
            cekdetailtemp();
        }



        function cektutuplaporan(tanggal, jenis_laporan) {
            $.ajax({

                type: "POST",
                url: "/tutuplaporan/cektutuplaporan",
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    jenis_laporan: jenis_laporan
                },
                cache: false,
                success: function(respond) {
                    $("#cektutuplaporan").val(respond);
                }
            });
        }



        function getsalesmanbyCabang() {

            var kode_cabang = $("#kode_cabang").val();
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
                    $("#kode_salesman").html(respond);
                }
            });
        }

        function showhideSalesman() {
            const kode_cabang = $("#kode_cabang").val();
            if (kode_cabang == "TSM") {
                $("#salesman").show();
            } else {
                $("#salesman").hide();
                $('.select2Kodesalesman').val('').trigger("change");
            }
        }

        cekdetailtemp();
        loaddetailtemp();
        showhideSalesman();
        $("#tanggal").change(function(e) {
            cektutuplaporan($(this).val(), "gudangjadi");

        });

        $("#kode_cabang").change(function() {
            const kode_cabang = $(this).val();
            showhideSalesman();
            if (kode_cabang == "TSM") {
                getsalesmanbyCabang();
            }
        });
        $("#tambahproduk").click(function(e) {
            e.preventDefault();
            const kode_produk = $("#kode_produk").val();
            const jumlah = $("#jumlah").val();

            if (kode_produk == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Kode Produk !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kode_produk").focus();
                    },

                });

            } else if (jumlah == "" || jumlah === "0") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Tidak Boleh Kosong!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#jumlah").focus();
                    },

                });

            } else {
                $("#tambahproduk").prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('permintaankiriman.storedetailtemp') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_produk: kode_produk,
                        jumlah: jumlah
                    },
                    cache: false,
                    success: function(respond) {
                        console.log(respond);
                        if (respond.success == true) {
                            Swal.fire("Saved!", "", "success");
                            $("#jumlah").val("");
                            loaddetailtemp();
                            $('.select2Kodeproduk').val('').trigger("change");
                        } else {
                            Swal.fire("Oops!", respond.message, "error");
                        }
                        $("#tambahproduk").prop('disabled', false);
                    }
                });
            }
        });


        $('body').on('click', '.delete', function() {
            var id = $(this).attr('id');
            var kode_produk = $("#kode_produk").val();
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
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('permintaankiriman.deletetemp') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        cache: false,
                        success: function(respond) {
                            if (respond.success == true) {
                                Swal.fire({
                                    title: "Berhasil",
                                    text: "Data Berhasil Dihapus",
                                    icon: "success"
                                });
                                loaddetailtemp();
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: respond.message,
                                    icon: "error"
                                });
                            }
                        }
                    });
                }
            });
        });


        $("#formcreatePermintaankiriman").submit(function() {
            const tanggal = $("#tanggal").val();
            const kode_cabang = $("#kode_cabang").val();
            const keterangan = $("#keterangan").val();
            const cektutuplaporan = $("#cektutuplaporan").val();
            const cekdetailtemp = $("#cekdetailtemp").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#tanggal_mutasi").focus();
                    },
                });

                return false;
            } else if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kode_produk").focus();
                    },
                });

                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#keteranga").focus();
                    },
                });

                return false;
            } else if (cektutuplaporan === '1') {
                Swal.fire("Oops!", "Laporan Untuk Periode Ini Sudah Ditutup", "warning");
                return false;
            } else if (cekdetailtemp === '0' || cekdetailtemp === '') {
                Swal.fire("Oops!", "Data Masih Kosong", "warning");
                return false;
            } else {
                $("#btnSimpan").prop('disabled', true);
            }


        });
    });
</script>
