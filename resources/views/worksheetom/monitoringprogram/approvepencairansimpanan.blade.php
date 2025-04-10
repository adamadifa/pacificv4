<form action="{{ route('monitoringprogram.storeapprovepencairansimpanan', Crypt::encrypt($pencairansimpanan->kode_pencairan)) }}" method="POST"
    id="formPencairanpencairansimpanan">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode Pelanggan</th>
                    <td class="text-end">{{ $pencairansimpanan->kode_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Nama Pelanggan</th>
                    <td class="text-end">{{ $pencairansimpanan->nama_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Salesman</th>
                    <td class="text-end">{{ $pencairansimpanan->nama_salesman }}</td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td class="text-end">
                        @php
                            $jumlah = $pencairansimpanan->jumlah;
                        @endphp
                        {{ formatAngka($jumlah) }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-thumb-up me-1"></i>Approve</button></button>
        </div>
        <div class="col">
            <div class="col">
                <button class="btn btn-danger w-100" id="btnSimpan" name="cancel" value="1"><i
                        class="ti ti-thumb-down me-1"></i>Batalkan</button></button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        $(".money").maskMoney();
    });
</script>
