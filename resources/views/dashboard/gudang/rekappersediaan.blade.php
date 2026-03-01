<style>
    .inventory-table-container {
        border-radius: 12px;
        overflow-x: auto;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .inventory-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    /* Sticky Header - Stacked Implementation */
    .inventory-table thead tr:nth-child(1) th {
        position: sticky !important;
        top: 0 !important;
        background-color: #002e65 !important;
        z-index: 25 !important; /* Above scrolling data */
    }

    .inventory-table thead tr:nth-child(2) th {
        position: sticky !important;
        top: 45px !important; /* Offset for the first row's height */
        background-color: #002e65 !important;
        z-index: 24 !important; /* Slightly below the first row but above data */
    }

    /* Column Freeze - Aggressive Opaque Implementation */
    table.inventory-table td.freeze-column,
    table.inventory-table th.freeze-column {
        position: sticky !important;
        left: 0 !important;
        z-index: 15 !important;
        background-color: #ffffff !important; /* Force solid white */
        border-right: 2px solid #cbd5e0 !important; /* Prominent solid divider */
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05); /* Subtle shadow for depth */
    }

    /* Top-Left Cell intersection - Highest Priority */
    table.inventory-table thead th.freeze-column {
        z-index: 40 !important; /* Stays above everything */
        top: 0 !important;
        background-color: #002e65 !important;
        color: white !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2) !important;
    }

    /* Opaque backgrounds for frozen columns in special rows - High Specificity */
    table.inventory-table tr.bg-success-legacy td.freeze-column {
        background-color: #28c76f !important;
        color: white !important;
    }

    table.inventory-table tr.bg-danger-legacy td.freeze-column {
        background-color: #ea5455 !important;
        color: white !important;
    }

    table.inventory-table tr.bg-info-legacy td.freeze-column {
        background-color: #00cfe8 !important;
        color: white !important;
    }

    table.inventory-table tr.detail-row td.freeze-column {
        background-color: #f8f9fa !important;
    }

    .branch-row {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    /* Hover effect for frozen part */
    table.inventory-table tr.branch-row:hover td.freeze-column {
        background-color: #f1f5f9 !important;
    }

    /* Active state for frozen part */
    table.inventory-table tr.branch-row.active td.freeze-column {
        background-color: #e2e8f0 !important;
    }

    /* Unified text alignment and font for header */
    .inventory-table thead th {
        color: white !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 12px 8px;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        white-space: nowrap;
    }

    /* Main row background colors (for the non-frozen part) */
    .bg-success-legacy {
        background-color: #28c76f !important;
        color: white !important;
    }

    .bg-danger-legacy {
        background-color: #ea5455 !important;
        color: white !important;
    }

    .bg-info-legacy {
        background-color: #00cfe8 !important;
        color: white !important;
    }

    .detail-row {
        background-color: #f8f9fa !important;
    }
    
    .detail-row td {
        border-top: 1px solid rgba(0, 0, 0, 0.05) !important;
    }

    .detail-label {
        font-size: 0.7rem;
        color: #6e6b7b;
        font-weight: 600;
        text-transform: uppercase;
    }

    .detail-value {
        font-weight: 600;
        font-size: 0.8rem;
        color: #002e65;
    }
</style>

<div class="inventory-table-container">
    <table class="table inventory-table table-hover">
        <thead>
            <tr>
                <th rowspan="2" class="align-middle text-center freeze-column" style="width: 250px; min-width: 250px;">Unit / Cabang</th>
                <th colspan="{{ count($products) }}" class="text-center">Produk (Stock Utama)</th>
            </tr>
            <tr>
                @foreach ($products as $product)
                    <th class="text-center">{{ $product->kode_produk }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{-- PUSAT ROW --}}
            <tr class="fw-bold bg-success-legacy">
                <td class="ps-3 freeze-column bg-success-legacy"><i class="ti ti-building-warehouse me-2"></i>PUSAT</td>
                @foreach ($products as $product)
                    <td class="text-center">
                        {{ formatAngka($rekapgudang->{"saldoakhir_$product->kode_produk"}) }}
                    </td>
                @endforeach
            </tr>

            {{-- BRANCH ROWS --}}
            @foreach ($rekappersediaancabang as $data)
                <tr class="branch-row master" data-branch="{{ $data->kode_cabang }}">
                    <td class="ps-3 freeze-column">
                        <div class="branch-name-wrapper">
                            <i class="ti ti-chevron-right chevron-icon"></i>
                            <span class="fw-bold">{{ textUpperCase($data->nama_cabang) }}</span>
                        </div>
                    </td>
                    @foreach ($products as $product)
                        @php
                            $saldo_akhir =
                                ($data->{"saldo_$product->kode_produk"} +
                                    $data->{"mutasi_$product->kode_produk"} -
                                    $data->{"ambil_$product->kode_produk"} +
                                    $data->{"kembali_$product->kode_produk"}) /
                                $product->isi_pcs_dus;

                            $saldo_akhir = $saldo_akhir < 0 ? 0 : $saldo_akhir;

                            // Legacy Stock Level Coloring Logic
                            if ($saldo_akhir <= $data->{"buffer_$product->kode_produk"}) {
                                $colorClass = 'bg-danger-legacy';
                            } elseif ($saldo_akhir >= $data->{"max_$product->kode_produk"}) {
                                $colorClass = 'bg-info-legacy';
                            } else {
                                $colorClass = '';
                            }
                        @endphp
                        <td class="text-center {{ $colorClass }} fw-bold">
                            {{ formatAngka(floor($saldo_akhir)) }}
                        </td>
                    @endforeach
                </tr>

                {{-- EXPANDABLE DETAIL ROWS --}}
                <tr class="detail-row detail-{{ $data->kode_cabang }}" style="display: none;">
                    <td class="ps-4 detail-label freeze-column">Buffer Stock</td>
                    @foreach ($products as $product)
                        <td class="text-center detail-value">{{ formatAngka($data->{"buffer_$product->kode_produk"}) }}</td>
                    @endforeach
                </tr>
                <tr class="detail-row detail-{{ $data->kode_cabang }}" style="display: none;">
                    <td class="ps-4 detail-label freeze-column">Max. Stock</td>
                    @foreach ($products as $product)
                        <td class="text-center detail-value">{{ formatAngka($data->{"max_$product->kode_produk"}) }}</td>
                    @endforeach
                </tr>
                <tr class="detail-row detail-{{ $data->kode_cabang }}" style="display: none;">
                    <td class="ps-4 detail-label freeze-column">Sell Out <small>(This Month)</small></td>
                    @foreach ($products as $product)
                        <td class="text-center detail-value">
                            {{ formatAngka(floor($data->{"penjualan_$product->kode_produk"} / $product->isi_pcs_dus)) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $(".branch-row").click(function() {
            const branchId = $(this).data('branch');
            const details = $(`.detail-${branchId}`);

            $(this).toggleClass('active');
            details.toggle();
        });
    });
</script>
