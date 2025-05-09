<form action="{{ route('monitoringprogram.storepencairansimpanan', Crypt::encrypt($simpanan->kode_pelanggan)) }}" method="POST"
    id="formPencairansimpanan">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode Pelanggan</th>
                    <td class="text-end">{{ $simpanan->kode_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Nama Pelanggan</th>
                    <td class="text-end">{{ $simpanan->nama_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Salesman</th>
                    <td class="text-end">{{ $simpanan->nama_salesman }}</td>
                </tr>
                <tr>
                    <th>Saldo</th>
                    <td class="text-end">
                        @php
                            $saldo = $simpanan->total_reward - $simpanan->total_pencairan;
                        @endphp
                        {{ formatAngka($saldo) }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <x-input-with-icon icon="ti ti-moneybag" label="Jumlah Pencairan" align="right" name="jumlah" money="true" />
            <div class="form-group">
                <select name="metode_pembayaran" id="metode_pembayaran" class="form-select">
                    <option value="">Metode Pembayaran</option>
                    <option value="TN">Tunai</option>
                    <option value="TF">Transfer</option>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        $(".money").maskMoney();

        $("#formPencairansimpanan").submit(function(e) {
            const jumlah = $(this).find("#jumlah").val();
            const jml = jumlah.replace(/\./g, '');
            const metode_pembayaran = $(this).find("#metode_pembayaran").val();
            const saldo = "{{ $saldo }}";
            if (jumlah == "" || metode_pembayaran == "") {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Data belum lengkap',
                })
            } else if (jml < 100000) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Pencairan minimal Rp. 100.000',
                })
            } else if (jml > saldo) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Jumlah pencairan melebihi saldo',
                })
            } else {
                $("#btnSimpan").attr("disabled", true);
                $("#btnSimpan").html(`
                <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                `);
            }
        })
    });
</script>
