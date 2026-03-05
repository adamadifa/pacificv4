<form action="{{ route('ajuanlimit.update', Crypt::encrypt($ajuanlimit->no_pengajuan)) }}" method="POST" id="formEditAjuanLimit">
    @method('PUT')
    @csrf

    <x-input-with-icon icon="ti ti-barcode" label="No. Pengajuan" disabled="true" name="no_pengajuan" :value="$ajuanlimit->no_pengajuan" hideLabel="true" />
    
    @if ($level_user == 'super admin')
        <div class="form-group mb-3">
            <label for="posisi_ajuan" class="form-label">Posisi Ajuan</label>
            <select name="posisi_ajuan" id="posisi_ajuan" class="form-select">
                <option value="">Pilih Posisi Ajuan</option>
                @foreach ($roles_approve as $role)
                    <option value="{{ $role }}" @if ($ajuanlimit->posisi_ajuan == $role) selected @endif>
                        {{ textUpperCase($role) }}</option>
                @endforeach
                <option value="" @if ($ajuanlimit->posisi_ajuan == null && $ajuanlimit->status == '1') selected @endif>SUDAH DISETUJUI (SELESAI)</option>
            </select>
        </div>
    @else
        <div class="alert alert-warning">
            Anda tidak memiliki akses untuk mengubah posisi ajuan.
        </div>
    @endif

    <div class="form-group">
        <button class="btn btn-primary w-100" id="btnSimpan" type="submit">
            <i class="ti ti-device-floppy me-1"></i>
            Update Posisi
        </button>
    </div>
</form>
<script>
    $(document).ready(function() {
        const form = $("#formEditAjuanLimit");
        form.submit(function() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`<div class="spinner-border spinner-border-sm text-white me-2" role="status"><span class="visually-hidden">Loading...</span></div> Loading..`);
        });
    });
</script>
