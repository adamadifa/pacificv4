<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="text-white" style="background-color: #002e65;">
            <tr>
                <th class="text-center text-white" style="width: 5%">No</th>
                <th class="text-white">Keterangan</th>
                <th class="text-white">Kategori</th>
                <th class="text-end text-white">Jumlah</th>
                @if (auth()->user()->hasRole(['super admin', 'manager keuangan']))
                    <th class="text-center text-white" style="width: 10%">#</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse ($details as $d)
                @php $total += $d->jumlah; @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $d->keterangan }}</td>
                    <td>{{ $d->nama_kategori }}</td>
                    <td class="text-end fw-bold {{ $jenis == 'K' ? 'text-success' : 'text-danger' }}">
                        {{ formatAngka($d->jumlah) }}
                    </td>
                    @if (auth()->user()->hasRole(['super admin', 'manager keuangan']))
                        <td class="text-center">
                            @if ($d->kode_kategori == 'MK007' || stripos($d->nama_kategori, 'tunai setoran') !== false)
                                <form method="POST" class="deleteform-mutasi d-inline"
                                    action="{{ route('mutasikeuangan.delete', Crypt::encrypt($d->id)) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-label-danger delete-confirm-mutasi" data-bs-toggle="tooltip" title="Hapus">
                                        <i class="ti ti-trash fs-5"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ auth()->user()->hasRole(['super admin', 'manager keuangan']) ? '5' : '4' }}" class="text-center text-muted">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot class="table-light">
            <tr>
                <th colspan="3" class="text-center">TOTAL</th>
                <th class="text-end fw-bold {{ $jenis == 'K' ? 'text-success' : 'text-danger' }}">
                    {{ formatAngka($total) }}
                </th>
                @if (auth()->user()->hasRole(['super admin', 'manager keuangan']))
                    <th></th>
                @endif
            </tr>
        </tfoot>
    </table>
</div>

@if (auth()->user()->hasRole(['super admin', 'manager keuangan']))
<script>
    $(function() {
        $('.delete-confirm-mutasi').click(function(event) {
            var form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: `Apakah Anda Yakin Ingin Menghapus Data Ini ?`,
                text: "Jika dihapus maka data akan hilang permanent.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#554bbb",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Hapus Saja!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endif

