<form action="{{ route('fsthp.store') }}" id="formcreateFsthp" method="POST">
    @csrf
    <input type="hidden" id="cektutuplaporan">
    <input type="hidden" id="cekdetailtemp">
    <x-input-with-icon icon="ti ti-barcode" label="No. FSTHP" name="no_mutasi" readonly="true" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal FSTHP" name="tanggal_mutasi" datepicker="flatpickr-date" />
    <div class="form-group mb-3">
        {{-- <label for="exampleFormControlInput1" style="font-weight: 600" class="form">Unit</label> --}}
        <select name="unit" id="unit" class="form-select">
            <option value="">Unit</option>
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
    </div>
    <x-select label="Produk" name="kode_produk" :data="$produk" key="kode_produk" textShow="nama_produk"
        upperCase="true" select2="select2Kodeproduk" />
    <div class="form-group mb-3">
        {{-- <label for="exampleFormControlInput1" style="font-weight: 600" class="form">Shift</label> --}}
        <select name="shift" id="shift" class="form-select">
            <option value="">Shift</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select>
    </div>
    <x-input-with-icon icon="ti ti-box" label="Jumlah" name="jumlah" align="right" money="true" />

    <div class="form-group" id="saveButton">
        <button class="btn btn-primary w-100" id="btnSimpan" type="submit">
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


        function generatenofsthp() {
            const tanggal_mutasi = $("#tanggal_mutasi").val();
            const kode_produk = $("#kode_produk").val();
            const shift = $("#shift").val();
            $.ajax({
                type: 'POST',
                url: '/fsthp/generatenofsthp',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal_mutasi: tanggal_mutasi,
                    kode_produk: kode_produk,
                    shift: shift
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
            generatenofsthp();
        });

        $("#tanggal_mutasi").change(function(e) {
            generatenofsthp();
            //console.log(cektutuplaporan('2024-01-01'));
            cektutuplaporan($(this).val(), "produksi");

        });

        $("#shift").change(function(e) {
            generatenofsthp();
        });




        $("#formcreateFsthp").submit(function() {
            const no_mutasi = $("#no_mutasi").val();
            const tanggal_mutasi = $("#tanggal_mutasi").val();
            const unit = $("#unit").val();
            const kode_produk = $("#kode_produk").val();
            const cektutuplaporan = $("#cektutuplaporan").val();

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
            } else if (unit == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Unit Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#unit").focus();
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
            } else {
                $("#btnSimpan").prop('disabled', true);
            }


        });



    });
</script>
