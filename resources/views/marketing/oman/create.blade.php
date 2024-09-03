<style>
    .form-oman {
        width: 100%;
        border: 0px;
    }

    .form-oman:focus {
        outline: none;
    }
</style>
<form action="{{ route('oman.store') }}" method="POST" id="frmCreateOman">
    <input type="hidden" id="cektutuplaporan">
    <div class="row">
        <div class="co-12">
            @csrf
            <div class="row">
                <div class="col-lg-6 col-sm-12 col-md-12">
                    <div class="form-group mb-3">
                        <select name="bulan" id="bulan" class="form-select">
                            <option value="">Bulan</option>
                            @foreach ($list_bulan as $d)
                                <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 col-md-12">
                    <div class="form-group mb-3">
                        <select name="tahun" id="tahun" class="form-select">
                            <option value="">Tahun</option>
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="table table-responsive">
                <table class="table table-hover table-bordered" id="mytable">
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
                    <tbody id="loadomancabang">

                    </tbody>
                </table>
            </div>
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

        function getomancabang() {
            const bulan = $("#bulan").val();
            const tahun = $("#tahun").val();
            const tanggal = tahun + "-" + bulan + "-01";
            // alert(tanggal);
            cektutuplaporan(tanggal, "penjualan");
            $.ajax({
                type: "POST",
                url: "/omancabang/getomancabang",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success: function(respond) {
                    $("#loadomancabang").html(respond);
                }
            });
        }

        //getomancabang();



        $("#bulan,#tahun").change(function() {
            getomancabang();
        });


        $("#frmCreateOman").submit(function() {
            const bulan = $("#bulan").val();
            const tahun = $("#tahun").val();

            if (bulan == "") {
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




        $(document).on('click', '.editOmancabang', function() {
            var kode_produk = $(this).attr('kode_produk');
            var bulan = $(this).attr('bulan');
            var tahun = $(this).attr('tahun');
            var minggu_ke = $(this).attr('minggu_ke');

            $.ajax({
                type: "POST",
                url: "/omancabang/editprodukomancabang",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_produk: kode_produk,
                    bulan: bulan,
                    tahun: tahun,
                    minggu_ke: minggu_ke
                },
                cache: false,
                success: function(response) {

                    if (response.status == "warning") {
                        Swal.fire({
                            title: 'Oops!',
                            text: response.message,
                            icon: 'warning',
                        });
                    } else {
                        $('#mdleditOmancabang').modal("show");
                        $("#loadeditOmancabang").html(response);
                    }
                }
            });
        });

        $(document).on('click', '#updateomanCabang', function() {
            let kode_oman = [];
            let kode_produk = [];
            let minggu_ke = $('input[name=minggu_ke_edit_omancabang]').val();
            let jumlah = [];
            $('input[name=kode_oman_edit_omancabang]').each(function() {
                kode_oman.push($(this).val());
            });

            $('input[name=kode_produk_edit_omancabang]').each(function() {
                kode_produk.push($(this).val());
            });



            $('input[name=jumlah_edit_omancabang]').each(function() {
                jumlah.push($(this).val());
            });



            $.ajax({
                type: "POST",
                url: "/omancabang/updateprodukomancabang",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_oman: kode_oman,
                    kode_produk: kode_produk,
                    minggu_ke: minggu_ke,
                    jumlah: jumlah
                },
                cache: false,
                success: function(respond) {
                    var res = respond.split("|");

                    if (res[0] == "success") {
                        Swal.fire({
                            title: res[1],
                            text: res[2],
                            icon: res[0],
                            showConfirmButton: true,
                            didClose: (e) => {
                                $('#mdleditOmancabang').modal("hide");
                                getomancabang();
                            },
                        });
                    } else {
                        Swal.fire({
                            title: res[1],
                            text: res[2],
                            icon: res[0]
                        });
                    }
                }
            });
        });

        $("#frmCreateOman").submit(function(e) {
            const cektutuplaporan = $("#cektutuplaporan").val();
            const bulan = $("#bulan").val();
            const tahun = $("#tahun").val();
            if (bulan == "") {
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
            } else if (cektutuplaporan > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Laporan Periode Ini Sudah Dituutp !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#bulan").focus();
                    },
                });
                return false;
            }
        });

    });
</script>
