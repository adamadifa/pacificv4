<form action="{{ route('pencairanprogram.storeupload', Crypt::encrypt($kode_pencairan)) }}" method="POST" id="formUpload" enctype="multipart/form-data">
    @csrf
    <x-textarea label="Link Bukti Transfer" name="bukti_transfer" />
    <div class="form-group">
        <div class="col">
            <button class="btn btn-primary w-100" type="submit" id="btnSimpan"><i class="ti ti-upload me-1"></i> Upload</button>
        </div>
    </div>
</form>
