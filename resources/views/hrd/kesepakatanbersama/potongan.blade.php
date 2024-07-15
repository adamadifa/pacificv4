<form action="{{ route('kesepakatanbersama.storepotongan', Crypt::encrypt($no_kb)) }}" method="POST" id="formPotongan">
    @csrf
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-moneybag" label="Jumlah" name="jumlah" money="true" align="right" />
        </div>
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary w-100" id="btnTambahpotongan"><i class="ti ti-plus me-1"></i>Tambah Potongan</button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="loadpotongan">
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($potongan as $d)
                        <tr>
                            <input type="hidden" name="keterangan_item[]" value="{{ $d->keterangan }}">
                            <input type="hidden" name="jumlah_item[]" value="{{ formatAngka($d->jumlah) }}">
                            <td>{{ $d->keterangan }}</td>
                            <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                            <td>
                                <a href="#" id="{{ $no }}" class="delete"><i class="ti ti-trash text-danger"></i></a>
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
    $(document).ready(function() {
        const form = $("#formPotongan");
        $(".money").maskMoney();
        let baris = "{{ $no }}";

        function resetForm() {
            form.find("#keterangan").val("");
            form.find("#jumlah").val("");
        }
        $("#btnTambahpotongan").click(function(e) {
            e.preventDefault();
            const keterangan = form.find("#keterangan").val();
            const jumlah = form.find("#jumlah").val();
            if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#keterangan").focus();
                    },
                });
                return false;
            } else if (jumlah == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#jumlah").focus();
                    },
                });
            } else {
                let newItem = `<tr id="${baris}">
                    <input type="hidden" name="keterangan_item[]" value="${keterangan}"/>
                    <input type="hidden" name="jumlah_item[]" value="${jumlah}"/>
                    <td>${keterangan}</td>
                    <td class="text-end">${jumlah}</td>
                    <td>
                        <a href="#" id="${baris}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                    </td>
                </tr>`;
                form.find("#loadpotongan").append(newItem);
                resetForm();
                baris += 1;
            }
        });

        $(document).on("click", ".delete", function() {
            Swal.fire({
                title: "Apakah Anda Yakin Ingin Menghapus Data Ini ?",
                text: "Jika dihapus maka data akan hilang permanent.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#554bbb",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Hapus Saja!",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $(this).closest("tr").remove();
                }
            })
        });

        $("#saveButton").hide();
        $(".agreement").change(function() {
            if (this.checked) {
                $("#saveButton").show();
            } else {
                $("#saveButton").hide();
            }
        });

        function buttonDisable() {
            $("#btnSimpan").prop("disabled", true);
            $("#btnSimpan").html(`
                <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Loading..`);
        }

        form.submit(function(e) {
            if (form.find("#loadpotongan").children().length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Potongan Masih Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#keterangan").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        })
    });
</script>
