<form action="{{ route('fsthp.store') }}" id="formcreateFsthp" method="POST">
    @csrf
    <input type="hidden" id="cektutuplaporan">
    <input type="hidden" id="cekdetailtemp">
    <x-input-with-icon-label icon="ti ti-barcode" label="No. FSTHP" name="no_mutasi" readonly="true" />
    <x-input-with-icon-label icon="ti ti-calendar" label="Tanggal FSTHP" name="tanggal_mutasi"
        datepicker="flatpickr-date" />
    <hr>
    <div class="form-group mb-3">
        <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Unit</label>
        <select name="unit" id="unit" class="form-select">
            <option value="">Unit</option>
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
    </div>
    <x-select-label label="Produk" name="kode_produk" :data="$produk" key="kode_produk" textShow="nama_produk"
        upperCase="true" select2="select2Kodeproduk" />
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="form-group mb-3">
                <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Shift</label>
                <select name="shift" id="shift" class="form-select">
                    <option value="">Shift</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon-label icon="ti ti-box" label="Jumlah" name="jumlah" align="right" money="true" />
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <div class="form-group mb-3">
                <a href="#" class="btn btn-primary mt-4" id="tambahproduk"><i class="ti ti-plus"></i></a>
            </div>
        </div>
    </div>
    <table class="table table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Shift</th>
                <th>Jumlah</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody id="loaddetailfsthptemp"></tbody>
    </table>
    <div class="form-check mt-3 mb-3">
        <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox" value=""
            id="defaultCheck3">
        <label class="form-check-label" for="defaultCheck3"> Yakin Akan Disimpan ? </label>
    </div>
    <div class="form-group" id="saveButton">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>
