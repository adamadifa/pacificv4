@foreach ($aktifitas as $d)
    <div class="card mb-1 border border-primary">
        <div class="card-body">
            <div class="d-flex">
                <div class="img-thumbnail">
                    <img src="{{ asset('assets/img/avatars/1.png') }}" alt="" class="w-px-50 h-auto rounded-circle">
                </div>
                <div class="detail ms-2">
                    <h6 class="badge bg-primary m-0"><i class="ti ti-calendar me-2"></i>{{ DateToIndo($d->tanggal) }}
                        {{ date('H:i', strtotime($d->created_at)) }}</h6>
                    <p>{{ $d->keterangan }}</p>
                </div>
            </div>
        </div>
    </div>
@endforeach
