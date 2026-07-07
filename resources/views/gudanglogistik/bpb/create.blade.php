<form action="{{ route('bpb.store') }}" method="post" id="formcreatebpb">
    @csrf

    <div class="row mb-3">
        <div class="col-md-6 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" value="{{ Date('Y-m-d') }}"
                datepicker="flatpickr-date" />
        </div>
    </div>

    <div class="divider text-start mt-4 mb-4">
        <div class="divider-text fw-bold text-dark fs-6"><i class="ti ti-box me-1 text-primary"></i> Detail Barang</div>
    </div>

    <div class="row g-3 align-items-end mb-4">
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label class="form-label fw-semibold text-dark mb-1">Pilih Barang</label>
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
        </div>
        <div class="col-lg-2 col-md-2 col-sm-6">
            <x-input-with-icon icon="ti ti-box" label="Jumlah" name="jumlah" align="right" numberFormat="true" />
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12">
            <button type="button" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-1"
                id="tambahproduk" style="height: 38px;">
                <i class="ti ti-plus"></i> <span>Tambah</span>
            </button>
        </div>
    </div>

    <div class="card shadow-sm border mb-4">
        <div class="card-header py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
            <h6 class="m-0 fw-bold text-white d-flex align-items-center gap-2">
                <i class="ti ti-list fs-4"></i>
                <span>Daftar Barang yang Diajukan</span>
            </h6>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-sm table-hover table-bordered align-middle mb-0" id="tabledetail">
                <thead style="background-color: #002e65;">
                    <tr>
                        <th class="text-white" style="width: 15%">Kode</th>
                        <th class="text-white" style="width: 35%">Nama Barang</th>
                        <th class="text-white text-end" style="width: 15%">Jumlah</th>
                        <th class="text-white">Keterangan</th>
                        <th class="text-white text-center" style="width: 10%">#</th>
                    </tr>
                </thead>
                <tbody id="loaddetail" class="table-border-bottom-0">
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="form-check mt-3 mb-3">
                <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox"
                    id="defaultCheck3">
                <label class="form-check-label fw-semibold text-dark" for="defaultCheck3"> Yakin Akan Disimpan ?
                </label>
            </div>
            <div class="form-group" id="saveButton">
                <button class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-1"
                    type="submit" id="btnSimpan">
                    <i class="ti ti-send"></i> <span>Submit</span>
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
                        <span class="badge bg-secondary text-white font-monospace shadow-xs">${kode_barang}</span>
                    </td>
                    <td class="fw-semibold text-dark">${nama_barang[1]}</td>
                    <td class="text-end fw-bold text-dark">
                        <input type="hidden" name="jml[]" value="${jumlah}" class="noborder-form text-end jumlah" />
                        ${jumlah}
                    </td>
                    <td>
                        <input type="hidden" name="ket[]" value="${keterangan}" class="noborder-form" />
                        <span class="text-muted small">${keterangan || '-'}</span>
                    </td>
                    <td class="text-center">
                        <a href="#" kode_barang="${kode_barang}" class="delete text-danger d-inline-block p-1" title="Hapus"><i class="ti ti-trash fs-5"></i></a>
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
                addProduk();
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
