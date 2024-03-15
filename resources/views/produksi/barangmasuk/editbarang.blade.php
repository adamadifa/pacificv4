<x-input-with-icon icon="ti ti-barcode" value="{{ $detail->kode_barang_produksi }}" label="Auto" name="no_bukti_edit"
    disabled="true" />
<x-input-with-icon icon="ti ti-box" value="{{ $detail->nama_barang }}" label="Nama Barang" name="nama_barang_edit"
    disabled="true" />
<x-input-with-icon icon="ti ti-file-description" value="{{ $detail->keterangan }}" label="Keterangan"
    name="keterangan_edit" />
<x-input-with-icon icon="ti ti-file" value="{{ formatAngkaDesimal($detail->jumlah) }}" label="Jumlah" name="jumlah_edit"
    align="right" />
<div class="form-group mb-3">
    <button class="btn btn-primary w-100" id_edit="{{ $detail->id }}" id="updatebarang"><i
            class="ti ti-send me-1"></i>Update</button>
</div>
