<form action="{{ route('ajuantransfer.prosesstore', Crypt::encrypt($ajuantransfer->no_pengajuan)) }}" id="formProsesajuantransferdana" method="POST">
    @csrf
    <div class="row mb-3">
        <div class="col">
            <table class="table">
                <tr>
                    <th>No. Pengajuan</th>
                    <td>{{ $ajuantransfer->no_pengajuan }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ $ajuantransfer->tanggal }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $ajuantransfer->nama }}</td>
                </tr>
                <tr>
                    <th>Nama bank</th>
                    <td>{{ $ajuantransfer->nama_bank }}</td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td>{{ formatAngka($ajuantransfer->jumlah) }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $ajuantransfer->keterangan }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td>{{ textUpperCase($ajuantransfer->nama_cabang) }}</td>
                </tr>
            </table>
        </div>
    </div>
    <x-input-with-icon label="Link Bukti" name="bukti" icon="ti ti-link" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/prosesajuantransfer.js') }}"></script>
