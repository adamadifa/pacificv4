<style>
    .deleteform {
        display: inline-block;
        margin: 0;
        padding: 0;
    }
</style>
<table class="table">
    <thead class="table-dark">
        <tr>
            <th>Akun</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($coaCabang as $d)
            <tr>
                <td>{{ $d->kode_akun }} - {{ $d->nama_akun }}</td>
                <td>
                    <div class="d-flex">
                        @can('coacabang.delete')
                            <div>
                                <form method="POST" name="deleteform" class="deleteform"
                                    action="{{ route('coacabang.delete', Crypt::encrypt($d->id)) }}">
                                    @csrf
                                    @method('DELETE')
                                    <a href="#" class="delete-confirm">
                                        <i class="ti ti-trash text-danger"></i>
                                    </a>
                                </form>
                            </div>
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>
