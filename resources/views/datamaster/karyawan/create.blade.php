<form action="{{ route('salesman.store') }}" id="formcreateSalesman" method="POST" enctype="multipart/form-data">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="NIK" name="nik" />
    <x-input-with-icon icon="ti ti-credit-card" label="No. KTP" name="no_ktp" />
    <x-input-with-icon icon="ti ti-user" label="Nama Karyawan" name="nama_karyawan" />
    <div class="row">
        <div class="col-6">
            <x-input-with-icon icon="ti ti-map-pin" label="Tempat Lahir" name="tempat_lahir" />
        </div>
        <div class="col-6">
            <x-input-with-icon icon="ti ti-calendar" label="Tanggal Lahir" name="tanggal_lahir" />
        </div>
    </div>
    <x-textarea label="Alamat" name="alamat" />
    <div class="form-group mb-3">
        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
            <option value="">Jenis Kelamin</option>
            <option value="L">Laki - Laki</option>
            <option value="P">Perempuan</option>
        </select>
    </div>
</form>
