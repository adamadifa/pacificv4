<form action="{{ route('badstokga.store') }}" method="POST" id="formBadStok">
    @csrf
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
    <div class="form-group mb-3">
        <select name="kode_asal_bs" id="kode_asal_bs" class="form-select select2Kodeasalbs">
            <option value="">Asal Bad Stok</option>
            <option value="GDG">GUDANG</option>
            @foreach ($asalbadstok as $d)
                <option value="{{ $d->kode_cabang }}"> {{ textUpperCase($d->nama_cabang) }}</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Kode</th>
                        <th>Produk</th>
                        <th style="width: 15%">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produk as $d)
                        <tr>
                            <td>
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                {{ $d->kode_produk }}
                            </td>
                            <td>{{ $d->nama_produk }}</td>
                            <td>
                                <input type="text" class="noborder-form text-end money" name="jumlah[]">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        const form = $('#formBadStok');
        const select2Kodeasalbs = $('.select2Kodeasalbs');
        if (select2Kodeasalbs.length) {
            select2Kodeasalbs.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Asal Bad Stok',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $(".money").maskMoney();
        $(".flatpickr-date").flatpickr();
    });
</script>
