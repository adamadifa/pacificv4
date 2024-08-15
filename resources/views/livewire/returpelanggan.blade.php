<div>
    <div class="form-group mb-3 mt-3">
        <div class="input-group input-group-merge">
            <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-barcode"></i></span>
            <input type="text" class="form-control" id="nofaktur_search" name="nofaktur_search" placeholder="Cari No. Faktur" autocomplete="off"
                aria-autocomplete="none" wire:model.live.debounce.200ms="nofaktur_search">
        </div>
    </div>
    @foreach ($dataretur as $d)
        <a href="{{ route('sfa.showretur', Crypt::encrypt($d->no_retur)) }}">
            <div class="card mb-2 shadow-none  border  border-primary p-0">
                <div class="card-body d-flex justify-content-between p-2">
                    <div>
                        <h6 class="m-0">{{ $d->no_retur }}</h6>
                        <h6 class="m-0">{{ $d->no_faktur }}</h6>
                        <h7>{{ DateToIndo($d->tanggal) }}</h7>
                        <h6 class="font-weight-bold m-0">
                            {{ formatAngka($d->total_retur) }}
                        </h6>
                    </div>
                    <div>
                        <span class="badge {{ $d->jenis_retur == 'PF' ? 'bg-danger' : 'bg-success' }}">
                            {{ $d->jenis_retur == 'PF' ? 'POTONG FAKTUR' : 'GANTI BARANG' }}
                        </span>
                        <br>
                    </div>
                </div>
            </div>
        </a>
    @endforeach

</div>
