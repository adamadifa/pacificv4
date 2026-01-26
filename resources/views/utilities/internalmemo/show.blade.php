@php
    $isExpired = false;

    if (!empty($memo->berlaku_sampai) && $memo->berlaku_sampai < date('Y-m-d')) {
        $isExpired = true;
    }

    $isAktif = $memo->status === 'aktif' && !$isExpired;
@endphp

<div class="row">
    {{-- INFO MEMO --}}
    <div class="col-md-6">
        <table class="table table-sm table-bordered">
            <tr>
                <th width="35%">No Internal Memo</th>
                <td><strong>{{ $memo->no_im }}</strong></td>
            </tr>

            <tr>
                <th>Judul Memo</th>
                <td>{{ $memo->judul }}</td>
            </tr>

            <tr>
                <th>Tanggal Upload</th>
                <td>{{ formatIndo($memo->tanggal_im) }}</td>
            </tr>

            <tr>
                <th>Berlaku Dari</th>
                <td>
                    <button class="btn btn-sm {{ $isAktif ? 'btn-primary' : 'btn-danger' }}">
                        {{ $memo->berlaku_dari ? formatIndo($memo->berlaku_dari) : formatIndo($memo->tanggal_im) }}
                    </button>
                </td>
            </tr>

            <tr>
                <th>Berlaku Sampai</th>
                <td>
                    <button class="btn btn-sm {{ $isAktif ? 'btn-primary' : 'btn-danger' }}">
                        {{ $memo->berlaku_sampai ? formatIndo($memo->berlaku_sampai) : 'Tidak Ditentukan' }}
                    </button>
                </td>
            </tr>

            <tr>
                <th>Status</th>
                <td>
                    @if ($isAktif)
                        <span class="badge bg-success">
                            <i class="ti ti-thumb-up me-1"></i> Aktif
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            <i class="ti ti-thumb-down me-1"></i> Non Aktif
                        </span>
                    @endif

                    @if ($isExpired)
                        <span class="badge bg-danger ms-1">
                            <i class="ti ti-alert-triangle me-1"></i> Expired
                        </span>
                    @endif
                </td>
            </tr>

            <tr>
                <th>Keterangan</th>
                <td>{{ $memo->keterangan ?? '-' }}</td>
            </tr>
        </table>
    </div>

    {{-- FILE MEMO --}}
    <div class="col-md-6">
        @if ($memo->file_im)
            <iframe src="{{ Storage::url('internal_memo/' . $memo->file_im) }}" width="100%" height="420px"
                style="border:1px solid #ddd;border-radius:6px;">
            </iframe>
        @else
            <div class="alert alert-warning">
                <i class="ti ti-file-off me-1"></i>
                File memo tidak tersedia
            </div>
        @endif
    </div>
</div>
