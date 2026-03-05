<form action="#" id="formItemservicekendaraan">
    <x-input-with-icon label="Auto" name="kode_item" icon="ti ti-barcode" disabled="true" hideLabel="true" />
    <x-input-with-icon label="Nama Item" name="nama_item" icon="ti ti-file-description" hideLabel="true" />
    <div class="form-group mb-3">
    @php
        $jenis_item_data = [
            (object) ['kode_jenis' => 'OLI', 'nama_jenis' => 'OLI'],
            (object) ['kode_jenis' => 'JASA', 'nama_jenis' => 'JASA'],
            (object) ['kode_jenis' => 'PART', 'nama_jenis' => 'PART'],
        ];
    @endphp
    <x-select label="Jenis Item" name="jenis_item" :data="$jenis_item_data" key="kode_jenis" textShow="nama_jenis" hideLabel="true" />
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button></button>
    </div>
</form>
