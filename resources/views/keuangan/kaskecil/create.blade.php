<form action="{{ route('kaskecil.store') }}" method="POST" id="formKaskecil">
    <input type="hidden" id="cektutuplaporan">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-4">
            <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textuppercase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endhasanyrole
    <x-input-with-icon label="No Bukti" name="no_bukti" icon="ti ti-barcode" />
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
    <hr>
    <div class="row">
        <div class="col-lg-4 col-sm-12 col-md-12">
            <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" />
        </div>
        <div class="col-lg-3 col-sm-12 col-md-12">
            <div class="form-group mb-4">
                <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakun">
                    <option value="">Pilih Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ $d->nama_akun }} </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-3 col-sm-12 col-md-12">
            <x-input-with-icon label="Jumlah" name="jumlah" icon="ti ti-moneybag" align="right" money="true" />
        </div>
        <div class="col-lg-2 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <select name="debet_kredit" id="debet_kredit" class="form-select">
                    <option value="D" selected>DEBET</option>
                    <option value="K">KREDIT</option>
                </select>
            </div>
        </div>
    </div>




    @if (auth()->user()->kode_cabang == 'PST')
        <div class="row mb-3">
            <div class="col">
                <div class="form-check form-check-inline mt-3">
                    <input class="form-check-input" type="radio" name="kode_peruntukan" id="inlineRadio1" value="PC">
                    <label class="form-check-label" for="inlineRadio1">Pacific</label>
                </div>
                <div class="form-check form-check-inline mt-3">
                    <input class="form-check-input" type="radio" name="kode_peruntukan" id="inlineRadio2" value="MP">
                    <label class="form-check-label" for="inlineRadio2">Makmur Permata</label>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col">
            <a href="#" id="tambahitem" class="btn btn-primary w-100"><i class="ti ti-plus me-1"></i>Tambah Item</a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Keterangan</th>
                        <th>Akun</th>
                        <th>Penerimaan</th>
                        <th>Pengeluaran</th>
                        @if (auth()->user()->kode_cabang == 'PST')
                            <th>Peruntukan</th>
                        @endif
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="loaditem">

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
        const formKaskecil = $("#formKaskecil");
        const select2Kodecabang = $('.select2Kodecabang');
        formKaskecil.find("#jumlah").maskMoney();
        $(".flatpickr-date").flatpickr();
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodeakun = $('.select2Kodeakun');
        if (select2Kodeakun.length) {
            select2Kodeakun.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Akun',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
        let baris = 0;


        function resetform() {
            formKaskecil.find("#keterangan").val("");
            $('.select2Kodeakun').val('').trigger("change");
            // formKaskecil.find("#in_out").val("IN");
            formKaskecil.find("#jumlah").val("");
            // formKaskecil.find("#kode_peruntukan").val("PC");
        }



        function addItem() {

            const keterangan = formKaskecil.find("#keterangan").val();
            const dataCoa = formKaskecil.find("#kode_akun :selected").select2(this.data);
            const kode_akun = $(dataCoa).val();
            const nama_akun = $(dataCoa).text();
            const debet_kredit = formKaskecil.find("#debet_kredit").val();
            const jumlah = formKaskecil.find("#jumlah").val();
            const kode_peruntukan = formKaskecil.find("#kode_peruntukan").val() ?? '';
            let penerimaan = debet_kredit == 'K' ? jumlah : '';
            let pengeluaran = debet_kredit == 'D' ? jumlah : '';
            if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formKaskecil.find("#keterangan").focus();
                    },
                });
                return false;
            } else if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Akun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formKaskecil.find("#kode_akun").focus();
                    },
                });
                return false;
            } else if (jumlah == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formKaskecil.find("#jumlah").focus();
                    },
                })
            } else {
                baris = baris + 1;
                let item =
                    `<tr id="index_${baris}">
                <td>
                    <input type="hidden" name="keterangan_item[]" value="${keterangan}" />
                    <input type="hidden" name="jumlah_item[]" value="${jumlah}" />
                    <input type="hidden" name="debet_kredit_item[]" value="${debet_kredit}" />
                    <input type="hidden" name="kode_peruntukan_item[]" value="${kode_peruntukan}" />
                    ${keterangan}
                </td>
                <td>
                    <input type="hidden" name="kode_akun_item[]" value="${kode_akun}" />
                    ${nama_akun}
                </td>
                <td class='text-end'>${penerimaan}</td>
                <td class='text-end'>${pengeluaran}</td>
                <td class='text-center'>${kode_peruntukan}</td>
                <td>
                    <a href="#" id="index_${baris}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                </td>
            </tr>`;
                $('#loaditem').append(item);

                resetform();
            }
        }

        $("#tambahitem").click(function(e) {
            e.preventDefault();
            addItem();
        });


        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        formKaskecil.on('click', '.delete', function(e) {
            e.preventDefault();
            var id = $(this).attr("id");
            //event.preventDefault();
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

        formKaskecil.find("#saveButton").hide();

        formKaskecil.find('.agreement').change(function() {
            if (this.checked) {
                formKaskecil.find("#saveButton").show();
            } else {
                formKaskecil.find("#saveButton").hide();
            }
        });

        formKaskecil.submit(function(e) {
            const kode_cabang = formKaskecil.find("#kode_cabang").val();
            const no_bukti = formKaskecil.find("#no_bukti").val();
            const tanggal = formKaskecil.find("#tanggal").val();
            const cekData = $('#loaditem tr').length;
            if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $("#kode_cabang").focus();
                    },
                });
                return false;
            } else if (no_bukti == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Bukti harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $("#no_bukti").focus();
                    },
                });
                return false;
            } else if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $("#tanggal").focus();
                    },
                });
                return false;
            } else if (cekData == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $("#loaditem").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }


        })
    });
</script>
