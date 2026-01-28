<form
    action="{{ route('programikatan2026.updatefile', [Crypt::encrypt($detail->no_pengajuan), Crypt::encrypt($detail->kode_pelanggan)]) }}"
    method="POST" id="formUploadFile" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-input-file name="file_doc" label="Dokumen Kesepakatan" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpanFile"><i class="ti ti-send me-1"></i>Upload</button>
    </div>
</form>
<script>
    $(function() {
        $("#formUploadFile").submit(function(e) {
            let fileDoc = $(this).find("#file_doc")[0].files[0];
            if (fileDoc) {
                if (fileDoc.type !== 'application/pdf') {
                    Swal.fire({
                        title: "Oops!",
                        text: "Format file harus PDF !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => {
                            $(this).find("#file_doc").focus();
                        },
                    });
                    return false;
                } else if (fileDoc.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        title: "Oops!",
                        text: "Ukuran file maksimal 2 MB !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => {
                            $(this).find("#file_doc").focus();
                        },
                    });
                    return false;
                }
            } else {
                 Swal.fire({
                    title: "Oops!",
                    text: "File harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#file_doc").focus();
                    },
                });
                return false;
            }

            $(this).find("#btnSimpanFile").prop('disabled', true);
            $(this).find("#btnSimpanFile").html(` <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        });
    });
</script>
