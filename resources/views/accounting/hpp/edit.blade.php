<form action="{{ route('hpp.update', Crypt::encrypt($hpp->kode_hpp)) }}" id="formHpp" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode HPP</th>
                    <td class="text-end">{{ $hpp->kode_hpp }}</td>
                </tr>
                <tr>
                    <th>Bulan</th>
                    <td class="text-end">{{ $namabulan[$hpp->bulan] }}</td>
                </tr>
                <tr>
                    <th>Tahun</th>
                    <td class="text-end">{{ $hpp->tahun }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Kode</th>
                        <th style="width:50%">Nama Produk</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail as $d)
                        <tr>
                            <td>
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                {{ $d->kode_produk }}
                            </td>
                            <td>{{ $d->nama_produk }}</td>
                            <td>
                                <input type="text" class="noborder-form text-end number-separator" name="harga_hpp[]"
                                    value="{{ formatAngkaDesimal($d->harga_hpp) }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $('#formHpp');

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });

        form.on('submit', function(e) {
            // e.preventDefault();
            const bulan = form.find('#bulan').val();
            const tahun = form.find('#tahun').val();
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find('#bulan').focus();
                    },
                })
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find('#tahun').focus();
                    },
                })
            } else {
                buttonDisable();
                // form.submit();
            }
        })
    });
</script>
