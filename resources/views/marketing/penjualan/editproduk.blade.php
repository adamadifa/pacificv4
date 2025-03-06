<form action="#" id="formEditproduk">
    {{-- {{ $dataproduk['index_old'] }} --}}
    <input type="hidden" name="isi_pcs_dus" id="isi_pcs_dus">
    <input type="hidden" name="isi_pcs_pack" id="isi_pcs_pack">
    <input type="hidden" name="kode_kategori_diskon" id="kode_kategori_diskon">
    <input type="hidden" name="kode_produk" id="kode_produk">
    <input type="hidden" name="index_old" id="index_old" value="{{ $dataproduk['index_old'] }}">
    <div class="form-group mb-3">
        <select name="kode_harga" id="kode_harga" class="form-select" disabled>
            <option value="">Pilih Produk</option>
            @foreach ($harga as $d)
                <option data-isi_pcs_dus = "{{ $d->isi_pcs_dus }}" data-isi_pcs_pack = "{{ $d->isi_pcs_pack }}"
                    data-harga_dus = "{{ formatAngka($d->harga_dus) }}" data-harga_pack = "{{ formatAngka($d->harga_pack) }}"
                    data-harga_pcs = "{{ formatAngka($d->harga_pcs) }}" data-kode_kategori_diskon = "{{ $d->kode_kategori_diskon }}"
                    data-kode_produk="{{ $d->kode_produk }}" {{ $dataproduk['kode_harga'] == $d->kode_harga ? 'selected' : '' }}
                    value="{{ $d->kode_harga }}">
                    {{ $d->nama_produk }}</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-12.col-sm-12">
            <x-input-with-icon label="Dus" name="jml_dus" icon="ti ti-box" align="right" money="true" value="{{ $dataproduk['jml_dus'] }}" />
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12">
            <x-input-with-icon label="Harga / Dus" name="harga_dus" icon="ti ti-box" align="right" money="true"
                value="{{ $dataproduk['harga_dus'] }}" />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-12.col-sm-12">
            <x-input-with-icon label="Pack" name="jml_pack" icon="ti ti-box" align="right" money="true" value="{{ $dataproduk['jml_pack'] }}" />
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12">
            <x-input-with-icon label="Harga / Pack" name="harga_pack" icon="ti ti-box" align="right" money="true"
                value="{{ $dataproduk['harga_pack'] }}" />
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-lg-4 col-md-12.col-sm-12">
            <x-input-with-icon label="Pcs" name="jml_pcs" icon="ti ti-box" align="right" money="true" value="{{ $dataproduk['jml_pcs'] }}" />
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12">
            <x-input-with-icon label="Harga / Pcs" name="harga_pcs" icon="ti ti-box" align="right" money="true"
                value="{{ $dataproduk['harga_pcs'] }}" />
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-12">
            <div class="form-check mb-2">
                <input class="form-check-input status_promosi_edit" name="status_promosi_edit" value="1" type="checkbox" id="status_promosi_edit"
                    {{ $dataproduk['status_promosi'] == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="status_promosi_edit"> Promosi </label>
            </div>
        </div>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" type="submit" id="btnUpdateproduk"><i class="ti ti-send me-1"></i>Update</button>
        {{-- <a href="#" id="btnTest">Test</a> --}}
    </div>
</form>
<script>
    $(function() {
        $(".money").maskMoney();
        const form = $("#formEditproduk");
        form.find("#kode_harga").change(function() {
            let selectedOption = $(this).find(':selected');
            let isi_pcs_dus = selectedOption.data('isi_pcs_dus');
            let isi_pcs_pack = selectedOption.data('isi_pcs_pack');
            let harga_dus = selectedOption.data('harga_dus');
            let harga_pack = selectedOption.data('harga_pack');
            let harga_pcs = selectedOption.data('harga_pcs');
            form.find("#isi_pcs_dus").val(isi_pcs_dus);
            form.find("#isi_pcs_pack").val(isi_pcs_pack);
        });

        function getharga() {
            let selectedOption = form.find("#kode_harga").find(':selected');
            let isi_pcs_dus = selectedOption.data('isi_pcs_dus');
            let isi_pcs_pack = selectedOption.data('isi_pcs_pack');
            let kode_kategori_diskon = selectedOption.data('kode_kategori_diskon');

            let harga_dus;
            let harga_pack;
            let harga_pcs;
            if (form.find('#status_promosi_edit').is(':checked')) {
                harga_dus = 0;
                harga_pack = 0;
                harga_pcs = 0;
            } else {
                harga_dus = selectedOption.data('harga_dus');
                harga_pack = selectedOption.data('harga_pack');
                harga_pcs = selectedOption.data('harga_pcs');
            }

            form.find("#isi_pcs_dus").val(isi_pcs_dus);
            form.find("#isi_pcs_pack").val(isi_pcs_pack);
            form.find("#kode_kategori_diskon").val(kode_kategori_diskon);

            form.find("#harga_dus").val(harga_dus);
            form.find("#harga_pack").val(harga_pack);
            form.find("#harga_pcs").val(harga_pcs);

            if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                form.find("#jml_pack").prop('disabled', true);
                form.find("#harga_pack").prop('disabled', true);
            } else {
                form.find("#jml_pack").prop('disabled', false);
                form.find("#harga_pack").prop('disabled', false);
            }
            // form.find("#harga_dus").prop('disabled', true);
            // form.find("#harga_pack").prop('disabled', true);
            // form.find("#harga_pcs").prop('disabled', true);
        }


        form.find('.status_promosi_edit').change(function() {
            getharga();
        });

        function initHarga() {
            const nama_pelanggan = $("#nama_pelanggan").val();
            let selectedOption = form.find("#kode_harga").find(':selected');
            let isi_pcs_dus = selectedOption.data('isi_pcs_dus');
            let isi_pcs_pack = selectedOption.data('isi_pcs_pack');
            let kode_kategori_diskon = selectedOption.data('kode_kategori_diskon');
            let kode_produk = selectedOption.data('kode_produk');
            console.log(isi_pcs_pack);
            if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                form.find("#jml_pack").prop('disabled', true);
            } else {
                form.find("#jml_pack").prop('disabled', false);
            }

            form.find("#isi_pcs_dus").val(isi_pcs_dus);
            form.find("#isi_pcs_pack").val(isi_pcs_pack);
            form.find("#kode_kategori_diskon").val(kode_kategori_diskon);
            form.find("#kode_produk").val(kode_produk);

            if (nama_pelanggan.includes('KPBN') || nama_pelanggan.includes('RSB')) {
                form.find("#harga_dus").prop('disabled', false);

                if (isi_pcs_pack == "" || isi_pcs_pack === 0) {
                    form.find("#harga_pack").prop('disabled', true);
                    form.find("#harga_pack").prop('disabled', true);
                } else {
                    form.find("#harga_pack").prop('disabled', false);
                    form.find("#harga_pack").prop('disabled', false);
                }
                form.find("#harga_pcs").prop('disabled', false);
            } else {
                if (isi_pcs_pack == "" || isi_pcs_pack === 0) {
                    form.find("#harga_pack").prop('disabled', true);
                    form.find("#harga_pack").prop('disabled', true);
                } else {
                    form.find("#harga_pack").prop('disabled', false);
                    form.find("#harga_pack").prop('disabled', false);
                }
            }
        }

        initHarga();
        //   getharga();
    });
</script>
