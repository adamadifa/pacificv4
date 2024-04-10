<form action="" method="POST">
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>No. Permintaan</th>
                    <td>{{ $pk->no_permintaan }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($pk->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td>{{ textUpperCase($pk->nama_cabang) }}</td>
                </tr>
                @if (!empty($pk->kode_salesman))
                    <tr>
                        <th>Salesman</th>
                        <td>{{ $pk->nama_salesman }}</td>
                    </tr>
                @endif
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $pk->keterangan }}</td>
                </tr>
            </table>

        </div>
    </div>
    <div class="row mt-2">
        <div class="col">

            @csrf
            <input type="hidden" id="cektutuplaporan">
            <x-input-with-icon icon="ti ti-barcode" label="Auto" name="no_mutasi" readonly="true" />
            <x-input-with-icon icon="ti ti-barcode" label="No. Dokumen" name="no_dokumen" readonly="true" />
            <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" />
            <x-select label="Tujuan" name="kode_tujuan" :data="$tujuan_angkutan" key="kode_tujuan" textShow="tujuan"
                select2="Select2Kodetujuan" upperCase="true" />
            <x-input-with-icon icon="ti ti-barcode" label="No. Polisi" name="no_polisi" />
            <x-input-with-icon icon="ti ti-file" label="Tarif" name="tarif" money="true" align="right" />
            <x-input-with-icon icon="ti ti-file" label="Tepung" name="tepung" money="true" align="right" />
            <x-input-with-icon icon="ti ti-file" label="BS" name="bs" money="true" align="right" />
            <x-select label="Angkutan" name="kode_angkutan" :data="$angkutan" key="kode_angkutan"
                textShow="nama_angkutan" select2="select2Kodeangkutan" upperCase="true" />

            <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail as $d)
                        <tr>
                            <td>{{ $d->kode_produk }}</td>
                            <td>{{ $d->nama_produk }}</td>
                            <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>
<script>
    $(function() {
        $(".money").maskMoney();
        const Select2Kodetujuan = $('.Select2Kodetujuan');
        if (Select2Kodetujuan.length) {
            Select2Kodetujuan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Tujuan',
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodeangkutan = $('.select2Kodeangkutan');
        if (select2Kodeangkutan.length) {
            select2Kodeangkutan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Angkutan',
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>
