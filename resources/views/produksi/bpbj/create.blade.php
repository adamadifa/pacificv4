<form action="{{ route('bpbj.store') }}" id="formcreateBpbj" method="POST">
    @csrf
    <input type="text" id="cektutuplaporan">
    <x-input-with-icon-label icon="ti ti-barcode" label="No. BPBJ" name="no_mutasi" readonly="true" />

    <x-input-with-icon-label icon="ti ti-calendar" label="Tanggal BPBJ" name="tanggal_mutasi"
        datepicker="flatpickr-date" />

    <hr>
    <x-select-label label="Produk" name="kode_produk" :data="$produk" key="kode_produk" textShow="nama_produk"
        upperCase="true" select2="select2Kodeproduk" />
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="form-group mb-3">
                <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Shift</label>
                <select name="shift" id="shift" class="form-select">
                    <option value="">Shift</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon-label icon="ti ti-box" label="Jumlah" name="jumlah" align="right" money="true" />
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <div class="form-group mb-3">
                <a href="#" class="btn btn-primary mt-4" id="tambahproduk"><i class="ti ti-plus"></i></a>
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
        <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox" value=""
            id="defaultCheck3">
        <label class="form-check-label" for="defaultCheck3"> Yakin Akan Disimpan ? </label>
    </div>
    <div class="form-group" id="saveButton">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/pages/bpbj/create.js') }}"></script> --}}
<script>
    $(".money").maskMoney();
    $(".flatpickr-date").flatpickr();
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


        function loaddetailtemp(kode_produk) {
            $("#loaddetailbpbjtemp").load("/bpbj/" + kode_produk + "/getdetailtemp");
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
            loaddetailtemp(kode_produk);
            generetenobpbj();
        });

        $("#tanggal_mutasi").change(function(e) {
            generetenobpbj();
            //console.log(cektutuplaporan('2024-01-01'));
            cektutuplaporan($(this).val(), "penjualan");

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

                })
            } else if (jumlah == "" || jumlah === 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Tidak Boleh Kosong!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#jumlah").focus();
                    },

                })
            } else {
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
                        if (respond === '0') {
                            Swal.fire("Saved!", "", "success");
                            loaddetailtemp(kode_produk);
                        } else if (respond === '1') {
                            Swal.fire("Oops!", "Data Sudah Ada", "warning");
                        } else {
                            Swal.fire("Error", respond, "error");
                        }
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
            var cektutuplaporan = $("#cektutuplaporan").val();
            if (cektutuplaporan === '1') {
                Swal.fire("Oops!", "Laporan Untuk Periode Ini Sudah Ditutup", "warning");
                return false;
            }

            return false;
        });
    });
</script>
