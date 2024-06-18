<form action="{{ route('ajuanlimit.adjuststore', Crypt::encrypt($ajuanlimit->no_pengajuan)) }}" id="formAdjustlimit" method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <table class="table">
                <tr>
                    <th style="width:35%">No. Pengajuan</th>
                    <td>{{ $ajuanlimit->no_pengajuan }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($ajuanlimit->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Kode Pelanggan</th>
                    <td>{{ $ajuanlimit->kode_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Nama Pelanggan</th>
                    <td>{{ $ajuanlimit->nama_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Jumlah Ajuan</th>
                    <td style="font-weight: bold">{{ formatAngka($ajuanlimit->jumlah) }}</td>
                </tr>
                <tr>
                    <th>LJT Ajuan</th>
                    <td style="font-weight: bold">{{ $ajuanlimit->ljt }} Hari</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <x-input-with-icon label="Jumlah" name="jumlah_rekomendasi" icon="ti ti-adjusments" money="true" align="right"
                value="{{ formatAngka($ajuanlimit->jumlah_rekomendasi) }}" />
            <div class="form-group mb-3">
                <select name="ljt_rekomendasi" id="ljt_rekomendasi" class="form-select">
                    <option value="">LJT</option>
                    <option value="14" {{ $ajuanlimit->ljt == '14' ? 'selected' : '' }}>14 Hari</option>
                    <option value="30" {{ $ajuanlimit->ljt == '30' ? 'selected' : '' }}>30 Hari</option>
                    <option value="45" {{ $ajuanlimit->ljt == '45' ? 'selected' : '' }}>45 Hari</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100"><i class="ti ti-send me-1"></i>Update</button>
            </div>
        </div>
    </div>
</form>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/adjustlimit.js') }}"></script>
<script>
    $(".money").maskMoney();
</script>
