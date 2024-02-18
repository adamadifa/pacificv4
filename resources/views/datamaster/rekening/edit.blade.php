<form action="{{ route('rekening.update', Crypt::encrypt($karyawan->nik)) }}" id="formcreateRekening" method="POST">
    @csrf
    @method('PUT')
    <table class="table mb-3">
        <tr>
            <td class="fw-bold">NIK</td>
            <td>{{ $karyawan->nik }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Nama Karyawan</td>
            <td>{{ $karyawan->nama_karyawan }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Jabatan</td>
            <td>{{ $karyawan->nama_jabatan }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Departemen</td>
            <td>{{ $karyawan->nama_dept }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Cabang</td>
            <td>{{ $karyawan->kode_cabang }}</td>
        </tr>
    </table>
    <x-input-with-icon icon="ti ti-credit-card" label="No. Rekening" name="no_rekening" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/rekening/create.js') }}"></script>
