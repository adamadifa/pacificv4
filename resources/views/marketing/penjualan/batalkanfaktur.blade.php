<form action="{{ route('penjualan.updatefakturbatal', Crypt::encrypt($penjualan->no_faktur)) }}" method="POST" id="formBatalkanfaktur">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>No. Faktur</th>
                    <td>{{ $penjualan->no_faktur }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($penjualan->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Kode Pelanggan</th>
                    <td>{{ $penjualan->kode_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Nama Pelanggan</th>
                    <td>{{ $penjualan->nama_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Jenis Transaksi</th>
                    <td>{{ $penjualan->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT' }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <x-textarea label="Alasan Faktur Dibatalkan" name="keterangan" value="{{ $penjualan->keterangan }}" />
            <button class="btn btn-danger w-100"><i class="ti ti-send me-1"></i>Ubah Ke Faktur Batal</button>
        </div>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formBatalkanfaktur");
        form.submit(function() {
            const keterangan = $(this).find("#keterangan").val();
            if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Alasan Faktur Dibatalkan Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#keterangan").focus();
                    },
                });

                return false;
            }
        });
    });
</script>
