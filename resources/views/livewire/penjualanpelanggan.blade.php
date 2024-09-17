<div>
    <div class="form-group mb-3 mt-3">
        <div class="input-group input-group-merge">
            <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-barcode"></i></span>
            <input type="text" class="form-control" id="nofaktur_search" name="nofaktur_search" placeholder="Cari No. Faktur" autocomplete="off"
                aria-autocomplete="none" wire:model.live.debounce.200ms="nofaktur_search">
        </div>
    </div>
    @foreach ($datapenjualan as $d)
        @if ($d->status == '1')
            @php
                $bordercolor = 'border-primary';
            @endphp
        @else
            @php
                $bordercolor = 'border-danger';
            @endphp
        @endif
        <a href="{{ route('sfa.showpenjualan', Crypt::encrypt($d->no_faktur)) }}">
            <div class="card mb-2 shadow-none  border  {{ $bordercolor }} p-0 ">
                <div class="card-body d-flex justify-content-between p-2">
                    <div>
                        <h6 class="m-0">{{ $d->no_faktur }} {!! $d->status_batal == '1' ? '<span class="badge bg-danger"> (Batal)</span>' : '' !!}</h6>
                        <h7>{{ DateToIndo($d->tanggal) }}</h7>
                        <h6 class="font-weight-bold m-0">
                            @php
                                $totalnetto = $d->total_bruto - $d->potongan - $d->potongan_istimewa - $d->penyesuaian + $d->ppn;
                            @endphp
                            {{ formatAngka($totalnetto) }}
                        </h6>
                    </div>
                    <div>
                        <span class="badge bg-info">
                            {{ $d->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT' }}
                        </span>
                        <br>

                        @if ($d->status_batal == '1')
                            <span class="badge bg-danger mt-1">BATAL</span>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    @endforeach
    {{ $datapenjualan->links('pagination::bootstrap-5') }}
</div>
