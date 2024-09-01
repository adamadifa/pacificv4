<form action="{{ route('harga.update', Crypt::encrypt($harga->kode_harga)) }}" id="formeditHarga" method="POST">
    @csrf
    @method('PUT')
    {{-- {{ $harga->kode_produk }} --}}
    <x-input-with-icon icon="ti ti-barcode" label="Kode Harga" name="kode_harga" value="{{ $harga->kode_harga }}" disabled="true" />
    <x-select label="Produk" name="kode_produk" :data="$produk" key="kode_produk" textShow="nama_produk" selected="{{ $harga->kode_produk }}"
        upperCase="true" disabled="{{ !auth()->user()->hasRole('super admin') ? 'disabled' : '' }}" />
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-input-with-icon label="Harga Dus" icon="ti ti-file" name="harga_dus" align="right" money="true"
                value="{{ formatRupiah($harga->harga_dus) }}" />
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-input-with-icon label="Harga Pack" icon="ti ti-file" name="harga_pack" align="right" money="true"
                value="{{ formatRupiah($harga->harga_pack) }}" />
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-input-with-icon label="Harga Pcs" icon="ti ti-file" name="harga_pcs" align="right" money="true"
                value="{{ formatRupiah($harga->harga_pcs) }}" />
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-input-with-icon label="Harga Retur Dus" icon="ti ti-file" name="harga_retur_dus" align="right" money="true"
                value="{{ formatRupiah($harga->harga_retur_dus) }}" />
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-input-with-icon label="Harga Retur Pack" icon="ti ti-file" name="harga_retur_pack" align="right" money="true"
                value="{{ formatRupiah($harga->harga_retur_pack) }}" />
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-input-with-icon label="Harga Retur Pcs" icon="ti ti-file" name="harga_retur_pcs" align="right" money="true"
                value="{{ formatRupiah($harga->harga_retur_pcs) }}" />
        </div>
    </div>
    @hasanyrole('super admin')
        <div class="form-group mb-3">
            <select name="status_aktif_harga" id="status_aktif_harga" class="form-select">
                <option value="">Status</option>
                <option value="1" {{ $harga->status_aktif_harga === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $harga->status_aktif_harga === '0' ? 'selected' : '' }}>Non Aktif</option>
            </select>
        </div>
    @endhasanyrole
    @hasanyrole('super admin')
        <div class="form-group mb-3">
            <select name="status_ppn" id="status_ppn" class="form-select">
                <option value="">Status PPN</option>
                <option value="IN" {{ $harga->status_ppn === 'IN' ? 'selected' : '' }}>INCLUDE</option>
                <option value="EX" {{ $harga->status_ppn === 'EX' ? 'selected' : '' }}>EXCLUDE</option>
            </select>
        </div>
    @endhasanyrole
    @hasanyrole('super admin')
        <div class="form-group mb-3">
            <select name="status_promo" id="status_promo" class="form-select">
                <option value="">Status Promo</option>
                <option value="1" {{ $harga->status_promo === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $harga->status_promo === '0' ? 'selected' : '' }}>Non Aktif</option>
            </select>
        </div>
    @endhasanyrole
    <x-select label="Kategori" name="kode_kategori_salesman" :data="$kategori_salesman" key="kode_kategori_salesman" textShow="nama_kategori_salesman"
        selected="{{ $harga->kode_kategori_salesman }}" upperCase="true"
        disabled="{{ !auth()->user()->hasRole('super admin') ? 'disabled' : '' }}" />
    @hasanyrole('super admin')
        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" selected="{{ $harga->kode_cabang }}"
            select2="select2Kodecabang" />
    @endhasanyrole
    @hasanyrole('super admin')
        <div class="form-group mb-3">
            <select name="kode_pelanggan" id="kode_pelanggan" class="form-select">
                <option value="">Kode Pelanggan</option>
            </select>
        </div>
    @endhasanyrole
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
<script src="{{ asset('assets/js/pages/harga/edit.js') }}"></script>
<script>
    $(".money").maskMoney();
</script>
<script>
    $(function() {
        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
    })
</script>
