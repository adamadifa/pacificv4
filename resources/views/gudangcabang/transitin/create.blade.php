<form action="{{ route('transitin.store', Crypt::encrypt($surat_jalan->no_mutasi)) }}" id="formTransitin" method="POST">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>No. Surat Jalan</th>
                    <td>{{ $surat_jalan->no_mutasi }}</td>
                </tr>
                <tr>
                    <th>No. Dokumen</th>
                    <td>{{ $surat_jalan->no_dok }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($surat_jalan->tanggal) }}</td>
                </tr>
                <tr>
                    <th>No. Permintaan</th>
                    <td>{{ $surat_jalan->no_permintaan }}</td>
                </tr>
                <tr>
                    <th>Tanggal Permintaan</th>
                    <td>{{ DateToIndo($surat_jalan->tanggal_permintaan) }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td>{{ textUpperCase($surat_jalan->nama_cabang) }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $surat_jalan->keterangan }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if ($surat_jalan->status_surat_jalan == 0)
                            <span class="badge bg-danger">Belum Diterima Cabang</span>
                        @elseif($surat_jalan->status_surat_jalan == 1)
                            <span class="badge bg-success">Sudah Diterima Cabang</span>
                        @elseif($surat_jalan->status_surat_jalan == 2)
                            <span class="badge bg-info">Transit Out</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Kode</th>
                        <th style="width:50%">Nama Produk</th>
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
    <div class="row mt-2">
        <div class="col">
            <x-input-with-icon icon="ti ti-calendar" name="tanggal" label="Tanggal Transit IN" datepicker="flatpickr-date" />
            <button class="btn btn-primary w-100" type="submit" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
        </div>
    </div>
</form>
<script>
    $(function() {
        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }, ]
        });

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }
        const form = $("#formTransitin");
        form.submit(function(e) {
            const tanggal = $(this).find("#tanggal").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Transit IN Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });

                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
