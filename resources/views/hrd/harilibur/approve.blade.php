<style>
    .table-modal {
        height: auto;
        max-height: 400px;
        overflow-y: scroll;

    }
</style>
<form action="{{ route('harilibur.storeapprove', Crypt::encrypt($harilibur->kode_libur)) }}" method="POST" id="formApprovehrd">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode Libur</th>
                    <td class="text-end">{{ $harilibur->kode_libur }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($harilibur->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td class="text-end">{{ $harilibur->nama_kategori }}</td>
                </tr>
                @if (!empty($harilibur->tanggal_diganti))
                    <tr>
                        <th>Pengganti Tanggal</th>
                        <td class="text-end">{{ DateToIndo($harilibur->tanggal_diganti) }}</td>
                    </tr>
                @endif
                @if (!empty($harilibur->tanggal_limajam))
                    <tr>
                        <th>Tanggal 5 Jam</th>
                        <td class="text-end">{{ DateToIndo($harilibur->tanggal_limajam) }}</td>
                    </tr>
                @endif
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ $harilibur->nama_cabang }}</td>
                </tr>
                @if ($harilibur->kode_cabang == 'PST')
                    <tr>
                        <th>Departemen</th>
                        <td class="text-end">{{ $harilibur->nama_dept }}</td>
                    </tr>
                @endif
                <tr>
                    <th>Keterangan</th>
                    <td class="text-end">{{ $harilibur->keterangan }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="table-modal">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>Nik</th>
                        <th>Nama Karyawan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->nik }}</td>
                            <td>{{ $d->nama_karyawan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="form-group mb-3 mt-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-thumb-up me-1"></i>Approve</button>
    </div>
</form>
<script>
    $(function() {
        const form = $('#formApprovehrd');
        $(".table-modal").freezeTable({
            'scrollable': true,
            'freezeColumn': false,
        });

        function buttonDisable() {
            $('#btnSimpan').prop('disabled', true);
            $('#btnSimpan').html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        form.submit(function(e) {
            buttonDisable();
        });
    });
</script>
