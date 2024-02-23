<form action="{{ route('regional.store') }}" id="formcreateRegional" method="POST">
    @csrf
    <x-input-with-icon-label icon="ti ti-barcode" label="No. BPBJ" name="no_mutasi" />
    <x-input-with-icon-label icon="ti ti-calendar" label="Tanggal BPBJ" name="tanggal_mutasi" />
    <hr>
    <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12">
            <x-select-label label="Produk" name="kode_produk" :data="$produk" key="kode_produk" textShow="nama_produk"
                upperCase="true" select2="select2Kodeproduk" />
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
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
        <div class="col-lg-3 col-md-12 col-sm-12">
            <x-input-with-icon-label icon="ti ti-box" label="Jumlah" name="jumlah" align="right" />
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
        <tbody></tbody>
    </table>

</form>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/pelanggan/create.js') }}"></script>
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


        $("#tambahproduk").click(function(e) {
            e.preventDefault();
            var kode_produk = $("#kode_produk").val();
            var shift = $("#shift").val();
            var jumlah = $("#jumlah").val();

            if (kode_produk == "") {
                Swal.fire({

                    title: "Oops!",
                    text: "Silahkan Pilih dulu Kode Produk !",
                    icon: "warning",
                    showConfirmButton: true,
                    returnFocus: true
                }).then((result) => {

                });

            } else if (shift == "") {

            } else if (jumlah == "") {

            } else {

            }
        });
    });
</script>
