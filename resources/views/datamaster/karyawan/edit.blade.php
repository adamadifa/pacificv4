<form action="{{ route('karyawan.update', Crypt::encrypt($karyawan->nik)) }}" id="formeditKaryawan" method="POST"
    enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <x-input-with-icon-label icon="ti ti-barcode" label="NIK" name="nik" value="{{ $karyawan->nik }}" />
    <x-input-with-icon-label icon="ti ti-credit-card" label="No. KTP" name="no_ktp" value="{{ $karyawan->no_ktp }}" />
    <x-input-with-icon-label icon="ti ti-user" label="Nama Karyawan" name="nama_karyawan"
        value="{{ $karyawan->nama_karyawan }}" />
    <div class="row">
        <div class="col-6">
            <x-input-with-icon-label icon="ti ti-map-pin" label="Tempat Lahir" name="tempat_lahir"
                value="{{ $karyawan->tempat_lahir }}" />
        </div>
        <div class="col-6">
            <x-input-with-icon-label icon="ti ti-calendar" label="Tanggal Lahir" datepicker="flatpickr-date"
                name="tanggal_lahir" value="{{ $karyawan->tanggal_lahir }}" />
        </div>
    </div>
    <x-textarea-label label="Alamat" name="alamat" value="{{ $karyawan->alamat }}" />
    <div class="form-group mb-3">
        <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Jenis Kelamin</label>
        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
            <option value="">Jenis Kelamin</option>
            <option value="L" {{ $karyawan->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki - Laki</option>
            <option value="P" {{ $karyawan->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
    </div>
    <x-input-with-icon-label icon="ti ti-phone" label="No. HP" name="no_hp" value="{{ $karyawan->no_hp }}" />
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-select-label label="Status Perkawinan" name="kode_status_kawin" :data="$status_perkawinan" key="kode_status_kawin"
                textShow="keterangan_status_kawin" kode="true" selected="{{ $karyawan->kode_status_kawin }}" />
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Pendidikan
                    Terakhir</label>
                <select name="pendidikan_terakhir" id="pendidikan_terakhir" class="form-select">
                    <option value="">Pendidikan Terakhir</option>
                    <option value="SD" {{ $karyawan->pendidikan_terakhir == 'SD' ? 'selected' : '' }}>SD</option>
                    <option value="SMP" {{ $karyawan->pendidikan_terakhir == 'SMP' ? 'selected' : '' }}>SMP</option>
                    <option value="SMA" {{ $karyawan->pendidikan_terakhir == 'SMA' ? 'selected' : '' }}>SMP</option>
                    <option value="SMK" {{ $karyawan->pendidikan_terakhir == 'SMK' ? 'selected' : '' }}>SMK</option>
                    <option value="D1" {{ $karyawan->pendidikan_terakhir == 'D1' ? 'selected' : '' }}>D1</option>
                    <option value="D2" {{ $karyawan->pendidikan_terakhir == 'D2' ? 'selected' : '' }}>D2</option>
                    <option value="D3" {{ $karyawan->pendidikan_terakhir == 'D3' ? 'selected' : '' }}>D3</option>
                    <option value="D4" {{ $karyawan->pendidikan_terakhir == 'D4' ? 'selected' : '' }}>D4</option>
                    <option value="S1" {{ $karyawan->pendidikan_terakhir == 'S1' ? 'selected' : '' }}>S1</option>
                    <option value="S2" {{ $karyawan->pendidikan_terakhir == 'S2' ? 'selected' : '' }}>S2</option>
                    <option value="S3" {{ $karyawan->pendidikan_terakhir == 'S3' ? 'selected' : '' }}>S3</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Perusahaan</label>
                <select name="kode_perusahaan" id="pendidikan_terakhir" class="form-select">
                    <option value="">Perusahaan</option>
                    <option value="MP" {{ $karyawan->kode_perusahaan == 'MP' ? 'selected' : '' }}>Makmur Permata
                    </option>
                    <option value="PC" {{ $karyawan->kode_perusahaan == 'PC' ? 'selected' : '' }}>Pacific</option>
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-select-label label="Kantor Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang"
                textShow="nama_cabang" selected="{{ $karyawan->kode_cabang }}" />
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-select-label label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept"
                upperCase="true" selected="{{ $karyawan->kode_dept }}" />
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-select-label label="Group" name="kode_group" :data="$group" key="kode_group"
                textShow="nama_group" upperCase="true" selected="{{ $karyawan->kode_group }}" />
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-select-label label="Jabatan" name="kode_jabatan" :data="$jabatan" key="kode_jabatan"
                textShow="nama_jabatan" upperCase="true" selected="{{ $karyawan->kode_jabatan }}" />
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-select-label label="Klasifikasi" name="kode_klasifikasi" :data="$klasifikasi" key="kode_klasifikasi"
                textShow="klasifikasi" upperCase="true" selected="{{ $karyawan->kode_klasifikasi }}" />
        </div>
    </div>
    <x-input-with-icon-label icon="ti ti-calendar" datepicker="flatpickr-date" label="Tanggal Masuk"
        name="tanggal_masuk" value="{{ $karyawan->tanggal_masuk }}" />
    <div class="form-group mb-3">
        <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Status Karyawan</label>
        <select name="status_karyawan" id="pendidikan_terakhir" class="form-select">
            <option value="">Status Karyawan</option>
            <option value="K" {{ $karyawan->status_karyawan == 'K' ? 'selected' : '' }}>Kontrak</option>
            <option value="T" {{ $karyawan->status_karyawan == 'T' ? 'selected' : '' }}>Tetap</option>
            <option value="T" {{ $karyawan->status_karyawan == 'O' ? 'selected' : '' }}>Outsourcing</option>
        </select>
    </div>
    <x-input-with-icon-label icon="ti ti-barcode" label="PIN" name="pin" value="{{ $karyawan->pin }}" />
    <div class="form-group mb-3">
        <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Status Aktif /
            Nonaktif</label>
        <select name="status_aktif_karyawan" id="status_aktif_karyawan" class="form-select">
            <option value="">Aktif / Nonaktif</option>
            <option value="1" {{ $karyawan->status_aktif_karyawan === '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ $karyawan->status_aktif_karyawan === '0' ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>

    <div class="row" id="nonaktif">
        <x-input-with-icon-label icon="ti ti-calendar" datepicker="flatpickr-date" label="Tanggal Nonaktif"
            name="tanggal_nonaktif" value="{{ $karyawan->tanggal_nonaktif }}" />
        <x-input-with-icon-label icon="ti ti-calendar" datepicker="flatpickr-date" label="Tanggal Off Gaji"
            name="tanggal_off_gaji" value="{{ $karyawan->tanggal_off_gaji }}" />
    </div>

    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/karyawan/edit.js') }}"></script>


<script>
    $(function() {
        function loadnonaktif() {
            var status_aktif_karyawan = $("#status_aktif_karyawan").val();
            if (status_aktif_karyawan === '1') {
                $("#nonaktif").hide();
            } else {
                $("#nonaktif").show();
            }
        }

        loadnonaktif();

        $("#status_aktif_karyawan").change(function(e) {
            loadnonaktif();
        });


        $(".flatpickr-date").flatpickr();
        $('#nik').mask('00.00.000');

    });
</script>
