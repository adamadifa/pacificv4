<form action="{{ route('bpb.store') }}" method="post" id="formcreatebpb">
    @csrf
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" value="{{ Date('Y-m-d') }}"
        datepicker="flatpickr-date" />
    {{-- <select class="form-select select2KodeDept" name="tujuan" id="tujuan">
        <option value="">Pilih Tujuan</option>
        <option value="GDL" {{ Request('tujuan') == 'GDL' ? 'selected' : '' }}>Gudang Logistik</option>
        <option value="GDB" {{ Request('tujuan') == 'GDB' ? 'selected' : '' }}>Gudang Bahan</option>
        <option value="GAF" {{ Request('tujuan') == 'GAF' ? 'selected' : '' }}>General Afair</option>
    </select> --}}
    <div class="divider text-start">
        <div class="divider-text">Detail Barang</div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <select class="form-select select2Kodebarang" name="kode_barang" id="kode_barang">
                <option value="">Pilih Barang</option>
                @foreach ($barang as $b)
                    <option value="{{ $b->kode_barang }}">
                        {{ $b->kode_barang }}
                        | {{ strtoupper($b->nama_barang) }}
                        ({{ strtoupper($b->satuan) }})
                    </option>
                @endforeach
            </select>

        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-box" label="Jumlah" name="jumlah" align="right" numberFormat="true" />
        </div>
    </div>
    <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />
    <a href="#" class="btn btn-primary w-100" id="tambahproduk"><i class="ti ti-plus me-1"></i>Tambah Produk</a>
    <div class="row mt-2">
        <div class="col">
            <table class="table table-bordered" id="tabledetail">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 10%">Kode</th>
                        <th style="width: 25%">Nama Barang</th>
                        <th style="width: 5%">Jumlah</th>
                        <th>Keterangan</th>
                        <th style="width: 5%">#</th>
                    </tr>
                </thead>
                <tbody id="loaddetail">
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
        const formCreate = $("#formcreatebpb");
        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }, ]
        });

        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });

        function convertToRupiah(number) {
            if (number) {
                var rupiah = "";
                var numberrev = number
                    .toString()
                    .split("")
                    .reverse()
                    .join("");
                for (var i = 0; i < numberrev.length; i++)
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
                return (
                    rupiah
                    .split("", rupiah.length - 1)
                    .reverse()
                    .join("")
                );
            } else {
                return number;
            }
        }

        const select2Kodebarang = formCreate.find('.select2Kodebarang');
        if (select2Kodebarang.length) {
            select2Kodebarang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Barang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function addProduk() {
            const dataBarang = formCreate.find("#kode_barang :selected").select2(this.data);
            const kode_barang = $(dataBarang).val();
            const nama_barang = $(dataBarang).text().split("|");
            const jumlah = formCreate.find("#jumlah").val();
            const jml = jumlah.replaceAll(".", "").replaceAll(",", ".");
            const keterangan = formCreate.find("#keterangan").val();

            let produk = `
                <tr id="index_${kode_barang}">
                    <td>
                        <input type="hidden" name="kode_barang[]" value="${kode_barang}"/>
                        ${kode_barang}
                    </td>
                    <td>${nama_barang[1]}</td>
                    <td class="text-end">
                        <input type="hidden" name="jml[]" value="${jumlah}" class="noborder-form text-end jumlah" />
                        ${jumlah}
                    </td>
                    <td>
                        <input type="hidden" name="ket[]" value="${keterangan}" class="noborder-form" />
                        ${keterangan}
                    </td>
                    <td class="text-center">
                        <a href="#" kode_barang="${kode_barang}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                    </td>
                </tr>
            `;

            //append to table
            $('#loaddetail').prepend(produk);
            $('.select2Kodebarang').val('').trigger("change");
            $("#jumlah").val("");
            $("#harga").val("");
            $("#keterangan").val("");
            $("#kode_barang").focus();
        }

        formCreate.find("#tambahproduk").click(function(e) {
            e.preventDefault();
            const kode_barang = formCreate.find("#kode_barang").val();
            const jumlah = formCreate.find("#jumlah").val();
            const harga = formCreate.find("#harga").val();
            if (kode_barang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Barang !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#kode_barang").focus();
                    },

                });

            } else if (jumlah == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Harus Diisi  !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#jumlah").focus();
                    },

                });

            } else {
                formCreate.find("#tambahproduk").prop('disabled', true);
                addProduk();
                // if (formCreate.find('#tabledetail').find('#index_' + kode_barang).length > 0) {
                //     Swal.fire({
                //         title: "Oops!",
                //         text: "Data Sudah Ada!",
                //         icon: "warning",
                //         showConfirmButton: true,
                //         didClose: (e) => {
                //             formCreate.find("#kode_produk").focus();
                //         },

                //     });
                // } else {
                //     addProduk();
                // }
            }
        });

        formCreate.on('click', '.delete', function(e) {
            e.preventDefault();
            var kode_barang = $(this).attr("kode_barang");
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
                    $(`#index_${kode_barang}`).remove();
                }
            });
        });
        formCreate.find("#saveButton").hide();

        formCreate.find('.agreement').change(function() {
            if (this.checked) {
                formCreate.find("#saveButton").show();
            } else {
                formCreate.find("#saveButton").hide();
            }
        });

        formCreate.submit(function() {
            const no_bpb = formCreate.find("#no_bpb").val();
            const tanggal = formCreate.find("#tanggal").val();
            if (formCreate.find('#loaddetail tr').length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Barang Masih Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#kode_barang").focus();
                    },
                });

                return false;
            } else if (no_bpb == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Bukti Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#no_bpb").focus();
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
                        formCreate.find("#tanggal").focus();
                    },
                });

                return false;
            }
        });
    });
</script>
