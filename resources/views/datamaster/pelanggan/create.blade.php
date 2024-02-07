<form action="{{ route('pelanggan.store') }}" id="formcreatePelanggan" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-barcode" label="Kode Pelanggan" name="kode_pelanggan" />
            <x-input-with-icon icon="ti ti-barcode" label="Kode Pelanggan" name="kode_pelanggan" />
            <x-input-with-icon icon="ti ti-barcode" label="Kode Pelanggan" name="kode_pelanggan" />
        </div>
        <div class="col-lg-1 col-md-12 col-sm-12">
            <div class="divider divider-vertical">
                <div class="divider-text">
                    <i class="ti ti-crown"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-barcode" label="Kode Pelanggan" name="kode_pelanggan" />
            <x-input-with-icon icon="ti ti-barcode" label="Kode Pelanggan" name="kode_pelanggan" />
            <x-input-with-icon icon="ti ti-barcode" label="Kode Pelanggan" name="kode_pelanggan" />
        </div>
    </div>

</form>
