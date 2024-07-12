<form action="{{ route('barangkeluarmtc.store') }}" method="POST" id="formBarangKeluar">
    @csrf
    <div class="row">
        <div class="col">
            <x-input-with-icon label="No. Bukti" name="no_bukti" icon="ti ti-barcode" />
            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
            <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept" upperCase="true" />
        </div>
    </div>
    <div class="divider text-start">
        <div class="divider-text">Detail Barang</div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <x-select label="Pilih Barang" name="kode_barang" :data="$barang" key="kode_barang" textShow="nama_barang" upperCase="true"
                select2="select2Kodebarang" showKey="true" />
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-box" label="Jumlah" name="jumlah" align="right" numberFormat="true" />
        </div>
    </div>
    <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />
    <div class="form-group.mb-3">
        <a href="#" class="btn btn-primary w-100" id="tambahbarang"><i class="ti ti-plus me-1"></i>Tambah Barang</a>
    </div>
    <div class="row mt-3">
        <div class="col">
            <table class="table table-bordred">
                <thead class="table-dark">
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="loadbarangkeluar"></tbody>
            </table>
        </div>
    </div>
    <div class="row mt-2">
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
<script>
    $(function() {
        const form = $('#formBarangKeluar');
        let baris = 0;
        $(".flatpickr-date").flatpickr();
        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });

        function removeSpecialCharacters(str) {
            // Ekspresi reguler untuk mencari karakter yang bukan huruf atau angka
            const regex = /[^a-zA-Z0-9]/g;
            // Mengganti karakter yang cocok dengan ekspresi reguler dengan string kosong
            return str.replace(regex, '');
        }

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
                <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Loading..
            `);
        }

        function resetForm() {
            form.find("#kode_barang").val("");
            form.find("#jumlah").val("");
            form.find("#keterangan").val("");
        }
        $("#tambahbarang").on('click', function() {
            const kode_barang = form.find("#kode_barang").val();
            const kode_barang_index = removeSpecialCharacters(kode_barang);
            const nbarang = form.find("#kode_barang :selected").text().split("|");
            const nama_barang = nbarang[1];
            const jumlah = form.find("#jumlah").val();
            const keterangan = form.find("#keterangan").val();

            if (kode_barang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Barang Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_barang").focus();
                    },
                });
            } else if (jumlah == "" || jumlah === 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Qty Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });
            } else if ($("#loadbarangkeluar").find(`#${kode_barang_index}`).length > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Sudah Ada!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_barang").focus();
                    },
                })
            } else {
                baris++;
                let newRow = `<tr id="${kode_barang_index}">
                    <input type="hidden" name="kode_barang_item[]" value="${kode_barang}"/>
                    <input type="hidden" name="jumlah_item[]" value="${jumlah}"/>
                    <input type="hidden" name="keterangan_item[]" value="${keterangan}"/>
                    <td>${kode_barang}</td>
                    <td>${nama_barang}</td>
                    <td>${keterangan}</td>
                    <td class="text-end">${jumlah}</td>
                    <td><a href="#" id="${kode_barang_index}" class="delete"><i class="ti ti-trash text-danger"></i></a></td>
                </tr>`;
                $('#loadbarangkeluar').append(newRow);
                resetForm();
            }
        });

        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            let id = $(this).attr("id");
            //alert(id);
            // event.preventDefault();
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
                    $(`#${id}`).remove();
                }
            });
        });
        $("#saveButton").hide();
        $(".agreement").change(function() {
            if (this.checked) {
                $("#saveButton").show();
            } else {
                $("#saveButton").hide();
            }
        });



        form.submit(function(e) {
            const no_bukti = form.find("#no_bukti").val();
            const tanggal = form.find("#tanggal").val();
            const kode_dept = form.find("#kode_dept").val();
            if (no_bukti == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Bukti Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_bukti").focus();
                    },
                });
                return false;
            } else if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tgl. Pengeluaran Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_dept == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Departemen Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_dept").focus();
                    },
                });
                return false;
            } else if (form.find('#loadbarangkeluar tr').length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Barang Masih Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_barang").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });

    });
</script>
