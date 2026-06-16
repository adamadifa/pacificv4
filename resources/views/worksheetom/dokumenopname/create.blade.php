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
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <x-input-with-icon label="Tanggal Dokumen" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" value="{{ date('Y-m-d') }}" />
    <x-input-file name="file_dokumen" label="File Dokumen (PDF, PNG, JPG, JPEG - Max 5MB)" />
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
            const bulan = formDokumenopname.find("#bulan").val();
            const tahun = formDokumenopname.find("#tahun").val();
            const tanggal = formDokumenopname.find("#tanggal").val();
            const file_dokumen = formDokumenopname.find("#file_dokumen").val();

            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formDokumenopname.find("#bulan").focus();
                    },
                });
                return false;
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formDokumenopname.find("#tahun").focus();
                    },
                });
                return false;
            } else if (tanggal == "") {
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
            } else if (file_dokumen == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "File Dokumen Harus Dipilih !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formDokumenopname.find("#file_dokumen").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
