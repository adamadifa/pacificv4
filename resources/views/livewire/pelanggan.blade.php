<div>
    <a class="btn btn-primary btn-sm w-100 mb-3" href="{{ route('sfa.createpelanggan') }}"><i
            class="ti ti-user-plus me-1"></i>Register New Outlet</a>
    <div class="form-group mb-3">
        <div class="input-group input-group-merge">
            <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-user"></i></span>
            <input type="text" class="form-control" id="namapelanggan_search" name="namapelanggan_search"
                placeholder="Cari Pelanggan" autocomplete="off" aria-autocomplete="none"
                wire:model.live.debounce.300ms="namapelanggan_search">
        </div>
    </div>
    @foreach ($datapelanggan as $key => $d)
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($d->kode_pelanggan)) }}">
            <div class="card mb-2"
                @if ($d->status_aktif_pelanggan == 0) style="background: linear-gradient(90deg, #ff0000, #ff6666); color:white;" @endif>
                <div class="card-body d-flex" style="padding: 10px !important;">
                    <div>
                        <img class="card-img" src="../../assets/img/elements/9.jpg" alt="Card image"
                            style="width: 60px; height: auto;">
                    </div>
                    <div class="ms-2">
                        <h6 class="m-0">{{ $d->kode_pelanggan }} </h6>
                        <h7>{{ textUpperCase($d->nama_pelanggan) }}</h7>
                        {{-- <br>
                        <span>Limit : {{ formatAngka($d->limit_pelanggan) }}</span> --}}
                        <br>
                        <span class="badge bg-primary">{{ $d->nama_wilayah }}</span>
                    </div>
                </div>
            </div>
        </a>
    @endforeach

</div>
