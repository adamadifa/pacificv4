<table class="table">
    <thead>
        <tr>
            <th>No.</th>
            <th>Waktu</th>
            <th>Keterangan</th>
            <th>Foto</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($aktifitas as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ !empty($d->created_at) ? date('H:i', strtotime($d->created_at)) : '' }}</td>
                <td>{{ $d->keterangan }}</td>
                <td>
                    @if (!empty($d->foto))
                        @if (Storage::disk('public')->exists('/aktifitas_smm/' . $d->foto))
                            <div class="avatar avatar-xs me-2">
                                <img src="{{ getfotoPelanggan($d->foto) }}" alt="" class="rounded-circle">
                            </div>
                        @else
                            <div class="avatar avatar-xs me-2">
                                <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="" class="rounded-circle">
                            </div>
                        @endif
                    @else
                        <div class="avatar avatar-xs me-2">
                            <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="" class="rounded-circle">
                        </div>
                    @endif

                </td>
            </tr>
        @endforeach
    </tbody>
</table>
