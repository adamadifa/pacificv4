<form action="#" id="formSplitbarang">
    @php
        $total = toNumber($databarang['jumlah']) * toNumber($databarang['harga']) + toNumber($databarang['penyesuaian']);
    @endphp
    <div class="row mb-3">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode Barang</th>
                    <td class="text-end">{{ $databarang['kode_barang'] }}</td>
                </tr>
                <tr>
                    <th>Nama Barang</th>
                    <td class="text-end">{{ $databarang['nama_barang'] }}</td>
                </tr>
                <tr>
                    <th>Qty</th>
                    <td class="text-end">{{ $databarang['jumlah'] }}</td>
                </tr>
                <tr>
                    <th>Harga</th>
                    <td class="text-end">{{ $databarang['harga'] }}</td>
                </tr>
                <tr>
                    <th>Penyesuaian</th>
                    <td class="text-end">{{ $databarang['penyesuaian'] }}</td>
                </tr>
                <tr>
                    <th>Total</th>
                    <td class="text-end" id="totalSplit">{{ formatAngkaDesimal($total) }}</td>
                </tr>
                <tr>
                    <th>Penyesuaian</th>
                    <td class="text-end">{{ $databarang['keterangan'] }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ $databarang['kode_cabang'] }}</td>
                </tr>
                <tr>
                    <th>Akun</th>
                    <td class="text-end">{{ $databarang['kode_akun'] }} - {{ $akun->nama_akun }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12">
            <x-input-with-icon label="Nama Barang" name="nama_barang_split" icon="ti ti-barcode" readonly="true" />
            <input type="hidden" id="kode_barang" name="kode_barang">
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <x-input-with-icon label="Qty" name="jumlah" icon="ti ti-box" align="right" numberFormat="true" />
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <x-input-with-icon label="Harga" name="harga" icon="ti ti-moneybag" align="right" numberFormat="true" />
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <x-input-with-icon label="Penyesuaian" name="penyesuaian" icon="ti ti-moneybag" align="right" numberFormat="true" />
        </div>
        <div class="col-lg-3 col-md-12 col-sm-12">
            <div class="form-group mb-3">
                <select name="kode_akun" id="kode_akun_split" class="form-select select2Kodeakunsplit">
                    <option value="">Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" />
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-select label="Cabang" name="kode_cabang_split" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
                select2="select2Kodecabangsplit" />
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="btnSplitbarang">
                    <i class="ti ti-plus me-1"></i>Split Barang
                </button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 10%">Kode</th>
                        <th style="width: 20%">Nama Barang</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        {{-- <th>Subotal</th> --}}
                        <th>Peny</th>
                        <th>Total</th>
                        <th style="width: 20%">kode Akun</th>
                        <th style="width: 3%">Cabang</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="loadsplitbarang">
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="5">TOTAL</td>
                        <td id="grandtotal" class="text-end"></td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100"><i class="ti ti-send me-1"></i>Submit</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {

        const form = $("#formSplitbarang");

        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });


        const select2Kodeakunsplit = $('.select2Kodeakunsplit');
        if (select2Kodeakunsplit.length) {
            select2Kodeakunsplit.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Akun',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }


        const select2Kodecabangsplit = $('.select2Kodecabangsplit');
        if (select2Kodecabangsplit.length) {
            select2Kodecabangsplit.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }



    });
</script>
