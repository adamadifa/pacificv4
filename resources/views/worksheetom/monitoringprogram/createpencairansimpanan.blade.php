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
                            $saldo = $simpanan->total_reward;
                        @endphp
                        {{ formatAngka($saldo) }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <x-input-with-icon icon="ti ti-moneybag" label="Jumlah Pencairan" align="right" name="jml_pencairan" money="true" />
            <div class="form-group">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        $(".money").maskMoney();
    });
</script>
