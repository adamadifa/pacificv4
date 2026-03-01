<div class="table-responsive">
    <table class="table table-hover table-striped mb-0">
        <thead>
            <tr style="background-color: #002e65;">
                <th class="py-3 text-white" style="padding-left: 15px;">KODE & NAMA AKUN</th>
                <th class="py-3 text-center text-white" style="width: 100px;">AKSI</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($coaCabang as $d)
                <tr>
                    <td class="fw-semibold py-1" style="padding-left: 15px; font-size: 13px;">
                        {{ $d->kode_akun }} - {{ $d->nama_akun }}
                    </td>
                    <td class="text-center py-1">
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            @can('coacabang.delete')
                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                    action="{{ route('coacabang.delete', Crypt::encrypt($d->id)) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                        data-bs-toggle="tooltip" title="Hapus">
                                        <i class="ti ti-trash fs-5"></i>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center py-4 text-muted">
                        <i class="ti ti-database-off d-block mb-1 fs-2"></i>
                        Tidak ada data ditemukan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
