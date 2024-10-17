<form action="{{ route('bpbj.store') }}" id="formcreateBpbj" method="POST">
    @csrf
    <input type="hidden" id="cektutuplaporan">
    <input type="hidden" id="cekdetailtemp">
    <x-input-with-icon icon="ti ti-barcode" label="No. BPBJ" name="no_mutasi" readonly="true" />

    <x-input-with-icon icon="ti ti-calendar" label="Tanggal BPBJ" name="tanggal_mutasi" datepicker="flatpickr-date" />

    <hr>
    <x-select label="Produk" name="kode_produk" :data="$produk" key="kode_produk" textShow="nama_produk" upperCase="true"
        select2="select2Kodeproduk" />
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="form-group mb-3">

                <select name="shift" id="shift" class="form-select">
                    <option value="">Shift</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-box" label="Jumlah" name="jumlah" align="right" money="true" />
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <div class="form-group mb-3">
                <button class="btn btn-primary mt-4" id="tambahproduk"><i class="ti ti-plus"></i></button>
            </div>
        </div>
    </div>
    <table class="table table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Shift</th>
                <th>Jumlah</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody id="loaddetailbpbjtemp"></tbody>
    </table>
    <div class="form-check mt-3 mb-3">
        <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox" value="" id="defaultCheck3">
        <label class="form-check" for="defaultCheck3"> Yakin Akan Disimpan ? </label>
    </div>
    <div class="form-group" id="saveButton">
        <button class="btn btn-primary w-100" type="submit" id="btnSimpan">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>

<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/pages/bpbj/create.js') }}"></script> --}}
<script>
    $(".money").maskMoney();
    $(".flatpickr-date").flatpickr({
        enable: [{
            from: "{{ $start_periode }}",
            to: "{{ $end_periode }}"
        }, ]
    });
</script>
<script>
    $(function() {


        const select2Kodeproduk = $('.select2Kodeproduk');
        if (select2Kodeproduk.length) {
            select2Kodeproduk.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Produk',
                    dropdownParent: $this.parent()
                });
            });
        }

        function cekdetailtemp() {
            var kode_produk = $("#kode_produk").val();
            $.ajax({
                type: 'POST',
                url: '/bpbj/cekdetailtemp',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_produk: kode_produk
                },
                cache: false,
                success: function(respond) {
                    $("#cekdetailtemp").val(respond);
                }
            });
        }


        function loaddetailtemp() {
            const kode_produk = $("#kode_produk").val();
            $("#loaddetailbpbjtemp").load("/bpbj/" + kode_produk + "/getdetailtemp");
            cekdetailtemp();
        }



        function generetenobpbj() {
            var tanggal_mutasi = $("#tanggal_mutasi").val();
            var kode_produk = $("#kode_produk").val();
            $.ajax({
                type: 'POST',
                url: '/bpbj/generatenobpbj',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal_mutasi: tanggal_mutasi,
                    kode_produk: kode_produk
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    $("#no_mutasi").val(respond);
                }

            });
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




        $("#kode_produk").change(function(e) {
            const kode_produk = $(this).val();
            loaddetailtemp();
            generetenobpbj();
        });

        $("#tanggal_mutasi").change(function(e) {
            generetenobpbj();
            //console.log(cektutuplaporan('2024-01-01'));
            cektutuplaporan($(this).val(), "produksi");

        });

        $("#tambahproduk").click(function(e) {
            e.preventDefault();
            const kode_produk = $("#kode_produk").val();
            const shift = $("#shift").val();
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

            } else if (shift == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Shift !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#shift").focus();
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
                    url: "{{ route('bpbj.storedetailtemp') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_produk: kode_produk,
                        shift: shift,
                        jumlah: jumlah
                    },
                    cache: false,
                    success: function(respond) {
                        console.log('tes');
                        Swal.fire("Saved!", "", "success");
                        $("#jumlah").val(0);
                        $("#shift").val("");
                        loaddetailtemp();
                        $("#tambahproduk").prop('disabled', false);
                    },
                    error: function(xhr) {
                        Swal.fire("Error", xhr.responseJSON.message, "error");
                    }
                });
            }
        });

        $("#saveButton").hide();

        $('.agreement').change(function() {
            if (this.checked) {
                $("#saveButton").show();
            } else {
                $("#saveButton").hide();
            }
        });

        $("#formcreateBpbj").submit(function() {
            const no_mutasi = $("#no_mutasi").val();
            const tanggal_mutasi = $("#tanggal_mutasi").val();
            const kode_produk = $("#kode_produk").val();
            const cektutuplaporan = $("#cektutuplaporan").val();
            const cekdetailtemp = $("#cekdetailtemp").val();
            if (no_mutasi == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Mutasi Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#no_mutasi").focus();
                    },
                });

                return false;
            } else if (tanggal_mutasi == "") {
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
            } else if (kode_produk == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Produk Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kode_produk").focus();
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
                        url: '/bpbj/deletetemp',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        cache: false,
                        success: function(respond) {
                            Swal.fire({
                                title: "Berhasil",
                                text: "Data Berhasil Dihapus",
                                icon: "success"
                            });
                            loaddetailtemp();
                        },
                        error: function(xhr) {
                            Swal.fire("Error", xhr.responseJSON.message, "error");
                        }
                    });
                }
            });
        });
    });
</script>
