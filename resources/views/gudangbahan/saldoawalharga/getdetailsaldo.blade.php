@foreach ($barang as $d)
    @php
        $berat_liter = getBeratliter($dari);

        if ($d->satuan == 'LITER') {
            $qty_saldo_awal = $d->saldo_awal_qty_berat * 1000 * $berat_liter;
            $qty_pembelian = $d->bm_qty_berat_pembelian * 1000 * $berat_liter;
            $qty_lainnya = $d->bm_qty_berat_lainnya * 1000 * $berat_liter;
            $qty_returpengganti = $d->bm_qty_berat_returpengganti * 1000 * $berat_liter;

            $qty_produksi = $d->bk_qty_berat_produksi * 1000 * $berat_liter;
            $qty_seasoning = $d->bk_qty_berat_seasoning * 1000 * $berat_liter;
            $qty_pdqc = $d->bk_qty_berat_pdqc * 1000 * $berat_liter;
            $qty_susut = $d->bk_qty_berat_susut * 1000 * $berat_liter;
            $qty_cabang = $d->bk_qty_berat_cabang * 1000 * $berat_liter;
            $qty_lainnya_keluar = $d->bk_qty_berat_lainnya * 1000 * $berat_liter;

            $qty_opname = $d->opname_qty_berat * 1000 * $berat_liter;
        } elseif ($d->satuan == 'KG') {
            $qty_saldo_awal = $d->saldo_awal_qty_berat * 1000;
            $qty_pembelian = $d->bm_qty_berat_pembelian * 1000;
            $qty_lainnya = $d->bm_qty_berat_lainnya * 1000;
            $qty_returpengganti = $d->bm_qty_berat_returpengganti * 1000;

            $qty_produksi = $d->bk_qty_berat_produksi * 1000;
            $qty_seasoning = $d->bk_qty_berat_seasoning * 1000;
            $qty_pdqc = $d->bk_qty_berat_pdqc * 1000;
            $qty_susut = $d->bk_qty_berat_susut * 1000;
            $qty_cabang = $d->bk_qty_berat_cabang * 1000;
            $qty_lainnya_keluar = $d->bk_qty_berat_lainnya * 1000;

            $qty_opname = $d->opname_qty_berat * 1000;
        } else {
            $qty_saldo_awal = $d->saldo_awal_qty_unit;
            $qty_pembelian = $d->bm_qty_unit_pembelian;
            $qty_lainnya = $d->bm_qty_unit_lainnya;
            $qty_returpengganti = $d->bm_qty_unit_returpengganti;

            $qty_produksi = $d->bk_qty_unit_produksi;
            $qty_seasoning = $d->bk_qty_unit_seasoning;
            $qty_pdqc = $d->bk_qty_unit_pdqc;
            $qty_susut = $d->bk_qty_unit_susut;
            $qty_cabang = $d->bk_qty_unit_cabang;
            $qty_lainnya_keluar = $d->bk_qty_unit_lainnya;

            $qty_opname = $d->opname_qty_unit;
        }

        //Saldo Awal
        if (!empty($qty_saldo_awal)) {
            $harga_saldo_awal = $d->saldo_awal_harga / $qty_saldo_awal;
        } else {
            $harga_saldo_awal = 0;
        }
        $jumlah_saldo_awal = $d->saldo_awal_harga;

        //Pembelian
        if (!empty($qty_pembelian)) {
            $harga_pembelian = $d->total_harga / $qty_pembelian;
        } else {
            $harga_pembelian = $harga_saldo_awal;
        }
        $jumlah_pembelian = $d->total_harga;

        //Lainnya
        if (!empty($qty_lainnya)) {
            if ($d->kode_barang == 'BK-45' and date('m', strtotime($dari)) == '9' and date('Y', strtotime($dari)) == '2021') {
                $harga_lainnya = 9078.43;
            } elseif ($d->kode_barang == 'BK-44' and date('m', strtotime($dari)) == '9' and date('Y', strtotime($dari)) == '2021') {
                $harga_lainnya = 14612.79;
            } else {
                $harga_lainnya = $harga_pembelian;
            }
        } else {
            $harga_lainnya = 0;
        }
        $jumlah_lainnya = $qty_lainnya * $harga_lainnya;

        //Retur Pengganti

        if (!empty($qty_returpengganti)) {
            $harga_returpengganti = $harga_pembelian;
        } else {
            $harga_returpengganti = 0;
        }
        $jumlah_returpengganti = $qty_returpengganti * $harga_returpengganti;

        //Produksi
        $qty_masuk = $qty_saldo_awal + $qty_pembelian + $qty_lainnya + $qty_returpengganti;
        $jumlah_masuk = $jumlah_saldo_awal + $jumlah_pembelian + $jumlah_lainnya + $jumlah_returpengganti;

        if (!empty($qty_produksi)) {
            $harga_produksi = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
        } else {
            $harga_produksi = 0;
        }
        $jumlah_produksi = $qty_produksi * $harga_produksi;

        //Seasoning

        if (!empty($qty_seasoning)) {
            $harga_seasoning = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
        } else {
            $harga_seasoning = 0;
        }
        $jumlah_seasoning = $qty_seasoning * $harga_seasoning;

        //PDQC

        if (!empty($qty_pdqc)) {
            $harga_pdqc = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
        } else {
            $harga_pdqc = 0;
        }
        $jumlah_pdqc = $qty_pdqc * $harga_pdqc;

        //SUSUT

        if (!empty($qty_susut)) {
            $harga_susut = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
        } else {
            $harga_susut = 0;
        }
        $jumlah_susut = $qty_susut * $harga_susut;

        //Cabang

        if (!empty($qty_cabang)) {
            $harga_cabang = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
        } else {
            $harga_cabang = 0;
        }
        $jumlah_cabang = $qty_cabang * $harga_cabang;

        //Lainnya

        if (!empty($qty_lainnya_keluar)) {
            $harga_lainnya_keluar = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
        } else {
            $harga_lainnya_keluar = 0;
        }
        $jumlah_lainnya_keluar = $qty_lainnya_keluar * $harga_lainnya_keluar;

        $qty_keluar = $qty_produksi + $qty_seasoning + $qty_pdqc + $qty_susut + $qty_cabang + $qty_lainnya_keluar;

        //Saldo Akhir
        $qty_saldo_akhir = $qty_masuk - $qty_keluar;
        if (!empty($qty_saldo_akhir)) {
            $harga_saldo_akhir = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
        } else {
            $harga_saldo_akhir = 0;
        }
        $jumlah_saldo_akhir = $qty_saldo_akhir * $harga_saldo_akhir;
    @endphp
    <tr>
        <td>
            <input type="hidden" name="kode_barang[]" value="{{ $d->kode_barang }}">
            {{ $d->kode_barang }}
        </td>
        <td>{{ $d->nama_barang }}</td>
        <td>{{ $d->satuan }}</td>
        <td class="text-end">
            @if ($readonly)
                <input type="hidden" name="harga[]" value="{{ formatAngkaDesimal($jumlah_saldo_akhir) }}"
                    style="text-align: right" class="noborder-form">
                {{ formatAngkaDesimal($jumlah_saldo_akhir) }}
            @else
                <input type="text" name="harga[]" value="{{ formatAngkaDesimal($jumlah_saldo_akhir) }}"
                    style="text-align: right" class="noborder-form money">
            @endif
        </td>

    </tr>
@endforeach
