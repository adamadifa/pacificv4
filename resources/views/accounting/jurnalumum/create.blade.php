<form action="{{ route('jurnalumum.store') }}" method="POST" id="formJurnalumum">
    @csrf
    <div class="row mb-3">
        <div class="col">
            <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" />
            <div class="row">
                <div class="col-lg-6 col-sm-12 col-md-12">
                    <div class="form-group mb-3">
                        <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakun">
                            <option value="">Akun</option>
                            @foreach ($coa as $d)
                                <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 col-md-12">
                    <x-input-with-icon label="Jumlah" name="jumlah" align="right" numberFormat="true" icon="ti ti-moneybag" />
                </div>
            </div>
            <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />
            <div class="form-group mb-3">
                <select name="debet_kredit" id="debet_kredit" class="form-select">
                    <option value="">Debet / Kredit</option>
                    <option value="D">Debet</option>
                    <option value="K">Kredit</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="kode_peruntukan" id="kode_peruntukan" class="form-select">
                    <option value="">Peruntukan</option>
                    <option value="MP">MP</option>
                    <option value="PC">PACIFIC</option>
                </select>
            </div>
            <div class="form-group mb-3" id="cabang">
                <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="btnTambahItem"><i class="ti ti-plus me-1"></i>Tambah
                    Item</button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Akun</th>
                        <th>Keteranngan</th>
                        <th>Debet</th>
                        <th>Kredit</th>
                        <th>Peruntukan</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="loadjurnalumum">
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="3" class="text-end">TOTAL</td>
                        <td class="text-end" id="total_debet"></td>
                        <td class="text-end" id="total_kredit"></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
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
        let total_debet_set = 0;
        let total_kredit_set = 0;
        let baris = 0;
        const form = $('#formJurnalumum');
        $(".flatpickr-date").flatpickr();

        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
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
                })
            })
        }

        form.find("#cabang").hide();

        function loadkodecabang() {
            const kode_peruntukan = $("#kode_peruntukan").val();
            if (kode_peruntukan == "PC") {
                $("#cabang").show();
            } else {
                $("#cabang").hide();
            }
        }

        $("#kode_peruntukan").change(function() {
            loadkodecabang();
        });

        form.find("#saveButton").hide();

        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").show();
            } else {
                form.find("#saveButton").hide();
            }
        });

        function numberFormat(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = typeof thousands_sep === 'undefined' ? '.' : thousands_sep,
                dec = typeof dec_point === 'undefined' ? ',' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        function calculateTotal() {
            let total_debet = 0;
            let total_kredit = 0;
            form.find("tbody tr").each(function() {
                const debet = $(this).find(".jmldebet").text().replace(/\./g, '') || 0;
                const kredit = $(this).find(".jmlkredit").text().replace(/\./g, '') || 0;
                // total_debet += parseFloat(debet);
                // total_kredit += parseFloat(kredit);
                total_debet += parseInt(debet);
                total_kredit += parseInt(kredit);
                console.log(total_debet);
                console.log(total_kredit);
            });
            total_debet_set = total_debet;
            total_kredit_set = total_kredit;
            form.find("#total_debet").text(numberFormat(total_debet_set, 0, ',', '.'));
            form.find("#total_kredit").text(numberFormat(total_kredit_set, 0, ',', '.'));
        }

        $("#btnTambahItem").click(function(e) {
            e.preventDefault();
            const tanggal = form.find("#tanggal").val();
            const kode_akun = form.find("#kode_akun").val();
            const jumlah = form.find("#jumlah").val();
            const keterangan = form.find("#keterangan").val();
            const debet_kredit = form.find("#debet_kredit").val();
            const kode_peruntukan = form.find("#kode_peruntukan").val();
            const kode_cabang = kode_peruntukan == 'MP' ? '' : form.find("#kode_cabang").val();
            const bgperuntukan = "";
            const kredit = debet_kredit == 'K' ? jumlah : '';
            const debet = debet_kredit == 'D' ? jumlah : '';
            const dataCoa = form.find("#kode_akun :selected").select2(this.data);
            const nama_akun = $(dataCoa).text();

            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Akun harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_akun").focus();
                    },
                })
            } else if (jumlah == "" || jumlah == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#jumlah").focus();
                    },
                })
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#keterangan").focus();
                    },
                })
            } else if (debet_kredit == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Debet/Kredit harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#debet_kredit").focus();
                    },
                })
            } else if (kode_peruntukan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Peruntukan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_peruntukan").focus();
                    },
                })
            } else if (kode_cabang == "" && kode_peruntukan == "PC") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Cabang harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_cabang").focus();
                    },
                })
            } else {
                // Cek apakah sedang dalam mode edit
                const editId = form.find("#btnTambahItem").data('edit-id');

                if (editId) {
                    // Update row yang sudah ada
                    const row = form.find(`#${editId}`);
                    row.find('input[name="tanggal_item[]"]').val(tanggal);
                    row.find('input[name="kode_akun_item[]"]').val(kode_akun);
                    row.find('input[name="debet_kredit_item[]"]').val(debet_kredit);
                    row.find('input[name="jumlah_item[]"]').val(jumlah);
                    row.find('input[name="keterangan_item[]"]').val(keterangan);
                    row.find('input[name="kode_peruntukan_item[]"]').val(kode_peruntukan);
                    row.find('input[name="kode_cabang_item[]"]').val(kode_cabang);

                    // Update tampilan
                    row.find('td:eq(0)').text(tanggal);
                    row.find('td:eq(1)').text(nama_akun);
                    row.find('td:eq(2)').text(keterangan);
                    row.find('td:eq(3)').removeClass('jmldebet').addClass('text-end jmldebet').text(debet);
                    row.find('td:eq(4)').removeClass('jmlkredit').addClass('text-end jmlkredit').text(kredit);
                    row.find('td:eq(5)').text(kode_peruntukan + (kode_cabang ? ' (' + kode_cabang + ')' : ''));

                    // Reset mode edit
                    form.find("#btnTambahItem").removeData('edit-id');
                    form.find("#btnTambahItem").html('<i class="ti ti-plus me-1"></i>Tambah Item');
                } else {
                    // Tambah row baru
                    baris += 1;
                    let newRow = `<tr id="${baris}">
                        <input type="hidden" name="tanggal_item[]" value="${tanggal}"/>
                        <input type="hidden" name="kode_akun_item[]" value="${kode_akun}"/>
                        <input type="hidden" name="debet_kredit_item[]" value="${debet_kredit}"/>
                        <input type="hidden" name="jumlah_item[]" value="${jumlah}"/>
                        <input type="hidden" name="keterangan_item[]" value="${keterangan}"/>
                        <input type="hidden" name="kode_peruntukan_item[]" value="${kode_peruntukan}"/>
                        <input type="hidden" name="kode_cabang_item[]" value="${kode_cabang}"/>
                        <td>${tanggal}</td>
                        <td>${nama_akun}</td>
                        <td>${keterangan}</td>
                        <td class="text-end jmldebet">${debet}</td>
                        <td class="text-end jmlkredit">${kredit}</td>
                        <td>${kode_peruntukan} ${kode_cabang ? '(' + kode_cabang + ')' : ''}</td>
                        <td>
                            <a href="#" id="${baris}" class="edit me-2"><i class="ti ti-edit text-info"></i></a>
                            <a href="#" id="${baris}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                        </td>
                    </tr>`;
                    form.find("#loadjurnalumum").append(newRow);
                }
                resetForm();
                calculateTotal();
            }
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
                    // Reset mode edit jika row yang dihapus sedang dalam mode edit
                    const editId = form.find("#btnTambahItem").data('edit-id');
                    if (editId == id) {
                        form.find("#btnTambahItem").removeData('edit-id');
                        form.find("#btnTambahItem").html('<i class="ti ti-plus me-1"></i>Tambah Item');
                        resetForm();
                    }
                    $(`#${id}`).remove();
                    calculateTotal();
                }
            });
        });

        form.on('click', '.edit', function(e) {
            e.preventDefault();
            const id = $(this).attr("id");
            const row = form.find(`#${id}`);

            // Reset form terlebih dahulu jika ada mode edit sebelumnya
            const currentEditId = form.find("#btnTambahItem").data('edit-id');
            if (currentEditId && currentEditId != id) {
                resetForm();
            }

            // Ambil data dari row
            const tanggal = row.find('input[name="tanggal_item[]"]').val();
            const kode_akun = row.find('input[name="kode_akun_item[]"]').val();
            const jumlah = row.find('input[name="jumlah_item[]"]').val();
            const keterangan = row.find('input[name="keterangan_item[]"]').val();
            const debet_kredit = row.find('input[name="debet_kredit_item[]"]').val();
            const kode_peruntukan = row.find('input[name="kode_peruntukan_item[]"]').val();
            const kode_cabang = row.find('input[name="kode_cabang_item[]"]').val();

            // Isi form dengan data yang akan diedit
            form.find("#tanggal").val(tanggal);
            form.find('.select2Kodeakun').val(kode_akun).trigger('change');
            form.find("#jumlah").val(jumlah);
            form.find("#keterangan").val(keterangan);
            form.find("#debet_kredit").val(debet_kredit);
            form.find("#kode_peruntukan").val(kode_peruntukan).trigger('change');

            // Tunggu sebentar untuk memastikan select2 sudah ter-update
            setTimeout(function() {
                if (kode_peruntukan == 'PC' && kode_cabang) {
                    form.find('.select2Kodecabang').val(kode_cabang).trigger('change');
                }
            }, 100);

            // Set mode edit
            form.find("#btnTambahItem").data('edit-id', id);
            form.find("#btnTambahItem").html('<i class="ti ti-edit me-1"></i>Update Item');

            // Scroll ke form
            $('html, body').animate({
                scrollTop: form.find("#tanggal").offset().top - 100
            }, 500);
        });


        function resetForm() {
            //form.find("#tanggal").val("");
            form.find('.select2Kodeakun').val('').trigger("change");
            form.find("#debet_kredit").val("");
            form.find("#jumlah").val("");
            //form.find("#keterangan").val("");
            //form.find("#kode_peruntukan").val("");
            form.find('.select2Kodecabang').val('').trigger("change");

            // Reset mode edit jika ada
            if (form.find("#btnTambahItem").data('edit-id')) {
                form.find("#btnTambahItem").removeData('edit-id');
                form.find("#btnTambahItem").html('<i class="ti ti-plus me-1"></i>Tambah Item');
            }
        }


        form.submit(function() {

            if (form.find("#loadjurnalumum").children().length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jurnal Umum Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#loadjurnalumum").focus();
                    },
                });
                return false;
            } else if (total_debet_set != total_kredit_set) {
                Swal.fire({
                    title: "Oops!",
                    text: "Total Debet Tidak Sama Dengan Total Kredit !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#loadjurnalumum").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });

    });
</script>
