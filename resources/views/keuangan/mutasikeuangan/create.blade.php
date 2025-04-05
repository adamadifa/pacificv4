<form action="{{ route('ledger.store') }}" method="POST" id="formLedger">
    <input type="hidden" id="cektutuplaporan">
    @csrf
    <div class="form-group mb-4">
        <select name="kode_bank" id="kode_bank" class="form-select select2Kodebank">
            <option value="">Pilih Bank</option>
            @foreach ($bank as $d)
                <option value="{{ $d->kode_bank }}">{{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}</option>
            @endforeach
        </select>
    </div>
    <div class="divider text-start">
        <div class="divider-text">
            <i class="ti ti-file-description me-2"></i>
        </div>
    </div>
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
    <x-input-with-icon label="Pelanggan" name="pelanggan" icon="ti ti-user" />
    <x-textarea label="Keterangan" name="keterangan" />
    <x-input-with-icon label="Jumlah" name="jumlah" icon="ti ti-moneybag" align="right" money="true" />
    <div class="form-group mb-3">
        <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakun">
            <option value="">Pilih Kode Akun</option>
            @foreach ($coa as $d)
                <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ $d->nama_akun }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group row mb-3">
        <div class="col-6">
            <select name="debet_kredit" id="debet_kredit" class="form-select">
                <option value="">Debet / Kredit</option>
                <option value="D">Debet</option>
                <option value="K">Kredit</option>
            </select>
        </div>
        <div class="col-6">
            <select name="kode_peruntukan" id="kode_peruntukan" class="form-select">
                <option value="">Peruntukan</option>
                <option value="MP">MP</option>
                <option value="PC">PACIFIC</option>
            </select>
        </div>
    </div>
    <div class="form-group mb-3" id="ket_peruntukan">
        <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
            <option value="">Pilih Cabang</option>
            @foreach ($cabang as $d)
                <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-success w-100" id="tambahitem"><i class="ti ti-plus me-1"></i>Tambah</button>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 10%">Tanggal</th>
                        <th style="width: 15%">Pelanggan</th>
                        <th style="width: 20%">Keterangan</th>
                        <th style="width: 20%">Kode Akun</th>
                        <th style="width: 15%">Debet</th>
                        <th style="width: 15%">Kredit</th>
                        <th style="width: 5%">#</th>
                    </tr>
                </thead>
                <tbody id="loaditem"></tbody>
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
        const form = $("#formLedger");
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        function loadketperuntukan() {
            const kode_peruntukan = form.find("#kode_peruntukan").val();
            if (kode_peruntukan == "PC") {
                form.find("#ket_peruntukan").show();
            } else {
                form.find("#ket_peruntukan").hide();
            }
        }


        function cektutuplaporan() {
            const tanggal = form.find("#tanggal").val();
            $.ajax({
                type: 'POST',
                url: '/tutuplaporan/cektutuplaporan',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    jenis_laporan: "ledger"
                },
                cache: false,
                success: function(response) {
                    form.find("#cektutuplaporan").val(response);
                }
            });
        }

        form.find("#tanggal").change(function() {
            cektutuplaporan();
        });
        loadketperuntukan();
        $("#kode_peruntukan").change(function() {
            loadketperuntukan();
        });
        const select2Kodebank = $('.select2Kodebank');
        if (select2Kodebank.length) {
            select2Kodebank.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih  Bank',
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
                    placeholder: 'Pilih  Kode Akun',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih  Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function resetForm() {
            form.find("#tanggal").val("");
            form.find("#pelanggan").val("");
            form.find("#keterangan").val("");
            $('.select2Kodeakun').val('').trigger("change");
            form.find("#jumlah").val("");
            form.find("#debet_kredit").val("");
            form.find("#kode_peruntukan").val("");
        }
        let baris = 0;

        function addItem() {
            const kode_bank = form.find("#kode_bank").val();
            const tanggal = form.find("#tanggal").val();
            const pelanggan = form.find("#pelanggan").val();
            const keterangan = form.find("#keterangan").val();
            const dataCoa = form.find("#kode_akun :selected").select2(this.data);
            const kode_akun = $(dataCoa).val();
            const nama_akun = $(dataCoa).text();
            const jumlah = form.find("#jumlah").val();
            const debet_kredit = form.find("#debet_kredit").val();
            const kode_peruntukan = form.find("#kode_peruntukan").val();
            const kredit = debet_kredit == 'K' ? jumlah : '';
            const debet = debet_kredit == 'D' ? jumlah : '';
            const kode_cabang = form.find("#kode_cabang").val();
            const cektutuplaporan = form.find("#cektutuplaporan").val();
            let bgperuntukan = "";
            if (kode_peruntukan == "MP") {
                bgperuntukan = "bg-success text-white";
            } else if (kode_peruntukan == "PC") {
                bgperuntukan = "bg-info text-white";
            } else {
                bgperuntukan = "";
            }

            // Pisahkan tanggal menjadi tahun, bulan, dan hari
            let bagianTanggal = tanggal.split("-");
            let bagianTahun = bagianTanggal[0].substr(-2);
            // Susun kembali bagian-bagian tanggal dalam format d-m-y
            let tanggalledger = `${parseInt(bagianTanggal[2])}-${parseInt(bagianTanggal[1])}-${bagianTahun}`;
            if (kode_bank == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih Bank Terlebih Dahulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_bank").focus();
                    },
                });
            } else if (cektutuplaporan > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Laporan Periode Ini Sudah Ditutup !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },

                });
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
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#keterangan").focus();
                    },

                });
            } else if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Akun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_akun").focus();
                    },

                });
            } else if (debet_kredit == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Debet / Kredit Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#debet_kredit").focus();
                    },

                });
            } else if (jumlah == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },

                });
            } else if (kode_peruntukan == "PC" && kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan Peruntukan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_cabang").focus();
                    },

                });
            } else {
                baris = baris + 1;
                let item =
                    `<tr id="index_${baris}" class="${bgperuntukan}">
                <td>
                    <input type="hidden" name="debet_kredit_item[]" value="${debet_kredit}" />
                    <input type="hidden" name="jumlah_item[]" value="${jumlah}" />
                    <input type="hidden" name="kode_peruntukan_item[]" value="${kode_peruntukan}" />
                    <input type="hidden" name="kode_cabang_item[]" value="${kode_cabang}" />
                    <input type="hidden" name="tanggal_item[]" value="${tanggal}" />
                    ${tanggalledger}
                </td>
                <td>
                    <input type="hidden" name="pelanggan_item[]" value="${pelanggan}" />
                    ${pelanggan}
                </td>
                <td>
                    <input type="hidden" name="keterangan_item[]" value="${keterangan}" />
                    ${keterangan}
                </td>
                <td>
                    <input type="hidden" name="kode_akun_item[]" value="${kode_akun}" />
                    ${nama_akun}
                </td>
                <td class='text-end'>${debet}</td>
                <td class='text-end'>${kredit}</td>
                <td>
                    <a href="#" id="index_${baris}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                </td>
            </tr>`;
                $('#loaditem').append(item);
                resetForm();
                loadketperuntukan();
            }
        }

        $("#tambahitem").click(function(e) {
            e.preventDefault();
            addItem();
        });

        form.on('click', '.delete', function(e) {
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

        $("#kode_bank").change(function() {
            const dataBank = form.find("#kode_bank :selected").select2(this.data);
            const kode_bank = $(dataBank).val();
            const nama_bank = $(dataBank).text();
            const cekData = $('#loaditem tr').length;
            if (cekData > 0) {
                Swal.fire({
                    title: `Apakah Anda Yakin Ingin Mengganti Bank ?`,
                    text: "Jika Anda Mengganti Bank, Maka Data Akan Di Reset Kembali.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    showCancelButton: true,
                    confirmButtonColor: "#554bbb",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Ganti Saja!"
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $("#loaditem").html('');
                        $("#modal").find(".modal-title").text('Input Ledger ' + nama_bank);
                    }
                });
            }

        });

        form.find("#saveButton").hide();

        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").show();
            } else {
                form.find("#saveButton").hide();
            }
        });

        form.submit(function(e) {
            const cekData = $('#loaditem tr').length;
            if (cekData === 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Masih Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_bank").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
