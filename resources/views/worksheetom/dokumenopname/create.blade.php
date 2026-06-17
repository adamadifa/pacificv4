<form action="{{ route('worksheetom.dokumenopname.store') }}" method="POST" id="formDokumenopname" enctype="multipart/form-data">
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
    <x-input-with-icon label="Tanggal Dokumen" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" value="{{ date('Y-m-d') }}" />
    <x-input-file name="file_persediaan" label="Opname Persediaan (PDF, PNG, JPG, JPEG - Max 5MB)" />
    <x-input-file name="file_kas_kecil" label="Opname Kas Kecil (PDF, PNG, JPG, JPEG - Max 5MB)" />
    <x-input-file name="file_kas_besar" label="Opname Kas Besar (PDF, PNG, JPG, JPEG - Max 5MB)" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Upload Dokumen</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        const formDokumenopname = $("#formDokumenopname");
        const select2Kodecabang = $('.select2Kodecabang');
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

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        formDokumenopname.submit(function(e) {
            const has_cabang = formDokumenopname.find("#kode_cabang").length > 0;
            if (has_cabang) {
                const kode_cabang = formDokumenopname.find("#kode_cabang").val();
                if (kode_cabang == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Cabang Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => {
                            formDokumenopname.find("#kode_cabang").focus();
                        },
                    });
                    return false;
                }
            }
            const tanggal = formDokumenopname.find("#tanggal").val();
            const file_persediaan = formDokumenopname.find("#file_persediaan").val();
            const file_kas_kecil = formDokumenopname.find("#file_kas_kecil").val();
            const file_kas_besar = formDokumenopname.find("#file_kas_besar").val();

            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formDokumenopname.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (file_persediaan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "File Opname Persediaan Harus Dipilih !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formDokumenopname.find("#file_persediaan").focus();
                    },
                });
                return false;
            } else if (file_kas_kecil == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "File Opname Kas Kecil Harus Dipilih !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formDokumenopname.find("#file_kas_kecil").focus();
                    },
                });
                return false;
            } else if (file_kas_besar == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "File Opname Kas Besar Harus Dipilih !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formDokumenopname.find("#file_kas_besar").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
