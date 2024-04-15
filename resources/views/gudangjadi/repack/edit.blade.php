<form action="{{ route('repackgudangjadi.update', Crypt::encrypt($repack->no_mutasi)) }}" method="post"
    id="formeditRepack">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Auto" name="no_mutasi" readonly="true"
        value="{{ $repack->no_mutasi }}" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date"
        value="{{ $repack->tanggal }}" />
    <div class="divider text-start">
        <div class="divider-text">Detail Produk</div>
    </div>
    <div class="row">
        <div class="col-lg-7 col-md-12 col-sm-12">
            <x-select label="Pilih Produk" name="kode_produk" :data="$produk" key="kode_produk" textShow="nama_produk"
                upperCase="true" select2="select2Kodeproduk" />
        </div>
        <div class="col-lg-3 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-box" label="Jumlah" name="jumlah" align="right" money="true" />
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <a href="#" class="btn btn-primary" id="tambahproduk"><i class="ti ti-plus"></i></a>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <table class="table table-bordered table-hover table-striped" id="tabledetailProduk">
                <thead class="table-dark">
                    <tr>
                        <th>Kode</th>
                        <th style="width:50%">Nama Produk</th>
                        <th>Jumlah</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="loaddetail">
                    @foreach ($detail as $d)
                        <tr id={{ 'index_' . $d->kode_produk }}>
                            <td>
                                {{ $d->kode_produk }}
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                            </td>
                            <td>{{ $d->nama_produk }}</td>
                            <td class="text-end">
                                <input type="text" name="jml[]" class="noborder-form text-end jml money"
                                    value="{{ formatAngka($d->jumlah) }}">
                            </td>
                            <td>
                                <a href="#" kode_produk="{{ $d->kode_produk }}" class="delete"><i
                                        class="ti ti-trash text-danger"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="form-check mt-3 mb-3">
                <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox"
                    value="" id="defaultCheck3">
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

<script>
    $(function() {
        const form = $("#formeditRepack");
        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }, ]
        });

        $(".money").maskMoney();

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

        $("#tanggal").change(function(e) {
            cektutuplaporan($(this).val(), "gudangjadi");
        });

        const select2Kodeproduk = form.find('.select2Kodeproduk');
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

        function addProduk() {
            const dataProduk = form.find("#kode_produk :selected").select2(this.data);
            const kode_produk = $(dataProduk).val();
            const nama_produk = $(dataProduk).text();
            const jumlah = form.find("#jumlah").val();

            let produk = `
                    <tr id="index_${kode_produk}">
                        <td>
                            <input type="hidden" name="kode_produk[]" value="${kode_produk}"/>
                            ${kode_produk}
                        </td>
                        <td>${nama_produk}</td>
                        <td>
                            <input type="text" name="jml[]" value="${jumlah}" class="noborder-form text-end jml money" />
                        </td>
                        <td class="text-center">
                            <a href="#" kode_produk="${kode_produk}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                        </td>
                    </tr>
                `;

            //append to table
            $('#loaddetail').prepend(produk);
            $(".money").maskMoney();
            $('.select2Kodeproduk').val('').trigger("change");
            $("#jumlah").val("");
            $("#kode_produk").focus();
        }

        form.find("#tambahproduk").click(function(e) {
            e.preventDefault();
            const kode_produk = form.find("#kode_produk").val();
            const jumlah = form.find("#jumlah").val();
            if (kode_produk == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Kode Produk !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_produk").focus();
                    },

                });

            } else if (jumlah == "" || jumlah === "0") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Tidak Boleh Kosong!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },

                });

            } else {
                form.find("#tambahproduk").prop('disabled', true);
                if (form.find('#tabledetailProduk').find('#index_' + kode_produk).length > 0) {
                    Swal.fire({
                        title: "Oops!",
                        text: "Data Sudah Ada!",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            form.find("#kode_produk").focus();
                        },

                    });
                } else {
                    addProduk();
                }
            }
        });

        form.on('click', '.delete', function(e) {
            e.preventDefault();
            var kode_produk = $(this).attr("kode_produk");
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
                    $(`#index_${kode_produk}`).remove();
                }
            });
        });
        form.find("#saveButton").hide();

        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").show();
            } else {
                form.find("#saveButton").hide();
            }
        });

        form.submit(function() {
            const tanggal = form.find("#tanggal").val();
            if (form.find('#loaddetail tr').length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Produk Masih Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_produk").focus();
                    },
                });

                return false;
            } else if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });

                return false;
            }
        });
    });
</script>
