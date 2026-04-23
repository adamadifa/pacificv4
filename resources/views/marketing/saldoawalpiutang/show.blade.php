<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border mb-3">
            <div class="card-header border-bottom py-2" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white" style="font-size: 0.85rem;"><i class="ti ti-info-circle me-2"></i>Informasi Saldo Awal</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <tr>
                            <th class="bg-light" style="width: 20%">Kode</th>
                            <td style="width: 30%">{{ $saldo_awal->kode_saldo_awal }}</td>
                            <th class="bg-light" style="width: 20%">Tanggal</th>
                            <td style="width: 30%">{{ date('d-m-Y', strtotime($saldo_awal->tanggal)) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Bulan</th>
                            <td>{{ $nama_bulan[$saldo_awal->bulan] }}</td>
                            <th class="bg-light">Tahun</th>
                            <td>{{ $saldo_awal->tahun }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-2" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white" style="font-size: 0.85rem;"><i class="ti ti-list me-2"></i>Rincian Piutang</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 45vh; overflow-y: auto;">
                    <table class="table table-bordered table-striped table-sm mb-0">
                        <thead class="sticky-top">
                            <tr>
                                <th style="background-color: #002e65; color: white;">No. Faktur</th>
                                <th style="background-color: #002e65; color: white;">Tanggal</th>
                                <th style="background-color: #002e65; color: white;">Pelanggan</th>
                                <th class="text-end" style="background-color: #002e65; color: white;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($detail as $d)
                                @php
                                    $total += $d->jumlah;
                                @endphp
                                <tr>
                                    <td>{{ $d->no_faktur }}</td>
                                    <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                    <td>{{ $d->nama_pelanggan }}</td>
                                    <td class="text-end fw-semibold">{{ number_format($d->jumlah, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer py-3 border-top" style="background-color: #e8f2ff;">
                <div class="d-flex justify-content-between align-items-center px-2">
                    <h5 class="mb-0 fw-bold text-primary"><i class="ti ti-calculator me-2"></i>TOTAL PIUTANG</h5>
                    <h5 class="mb-0 fw-bold text-primary" style="font-size: 1.5rem;">{{ number_format($total, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>
