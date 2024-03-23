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
            <button class="btn btn-primary w-100" type="submit" name="submit"><i
                    class="ti ti-send me-1"></i>Submit</button>
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

        function getomancabang() {
            const bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
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

        getomancabang();

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

    });
</script>
