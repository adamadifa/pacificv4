<div class="row mb-3">
    <div class="col">
        <table class="table">
            <tr>
                <th>No. Bukti</th>
                <td class="text-end">{{ $pembelian->no_bukti }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td class="text-end">{{ DateToIndo($pembelian->tanggal) }}</td>
            </tr>
            <tr>
                <th>Supplier</th>
                <td class="text-end">{{ $pembelian->nama_supplier }}</td>
            </tr>
            <tr>
                <th>Asal Ajuan</th>
                <td class="text-end">
                    {{ array_key_exists($pembelian->kode_asal_pengajuan, $asal_pengajuan) ? $asal_pengajuan[$pembelian->kode_asal_pengajuan] : 'UNDIFINED' }}
                </td>
            </tr>
            <tr>
                <th>PPN</th>
                <td class="text-end">{!! $pembelian->ppn == '1' ? '<i class="ti ti-checks text-success"></i>' : '<i class="ti ti-square-rounded-x text-danger"></i>' !!} </td>
            </tr>
        </table>

    </div>
</div>
@can('pembelian.harga')
    <div class="row">
        <div class="col">
            <table class="table table-bordered  table-hover">
                <thead class="table-dark">
                    <tr>
                        <th colspan="8">Data Pembelian</th>
                    </tr>
                    <tr>
                        <th style="width: 10%">Kode</th>
                        <th style="width: 25%">Nama Barang</th>
                        <th style="width: 20%">Keterangan</th>
                        <th style="width: 10%">Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        <th>Peny</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_pembelian = 0;
                    @endphp
                    @foreach ($detail as $d)
                        @php
                            $subtotal = $d->jumlah * $d->harga;
                            $total = $subtotal + $d->penyesuaian;
                            $total_pembelian += $total;
                            $bg = '';
                            if (!empty($d->kode_cr)) {
                                $bg = 'bg-info text-white';
                            }
                        @endphp
                        <tr class="{{ $bg }}">
                            <td>{{ $d->kode_barang }}</td>
                            <td>{{ textCamelCase($d->nama_barang) }}</td>
                            <td>{{ textCamelCase($d->keterangan) }}</td>
                            <td class="text-center">{{ formatAngkaDesimal($d->jumlah) }}</td>
                            <td class="text-end">{{ formatAngkaDesimal($d->harga) }}</td>
                            <td class="text-end">{{ formatAngkaDesimal($subtotal) }}</td>
                            <td class="text-end">{{ formatAngkaDesimal($d->penyesuaian) }}</td>
                            <td class="text-end">{{ formatAngkaDesimal($total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="7">TOTAL</td>
                        <td class="text-end">{{ formatAngkaDesimal($total_pembelian) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <table class="table table-bordered table-striped table-hover">
                <thead class="bg-danger">
                    <tr>
                        <th class="text-white" colspan="4">Potongan</th>
                    </tr>
                    <tr>
                        <th class="text-white">Keterangan</th>
                        <th class="text-white">Qty</th>
                        <th class="text-white">Harga</th>
                        <th class="text-white">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_potongan = 0;
                    @endphp
                    @foreach ($potongan as $d)
                        @php
                            $subtotal = $d->jumlah * $d->harga;
                            $total_potongan += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ textCamelCase($d->keterangan_penjualan) }}</td>
                            <td class="text-center">{{ formatAngkaDesimal($d->jumlah) }}</td>
                            <td class="text-end">{{ formatAngkaDesimal($d->harga) }}</td>
                            <td class="text-end">{{ formatAngkaDesimal($subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="3">TOTAL</td>
                        <td class="text-end">{{ formatAngkaDesimal($total_potongan) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">PENY. JURNAL KOREKSI</td>
                        <td class="text-end">{{ formatAngkaDesimal($pembelian->penyesuaian_jk) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="bg-success">GRAND TOTAL</td>
                        <td class="text-end bg-success">{{ formatAngkaDesimal($total_pembelian - $total_potongan + $pembelian->penyesuaian_jk) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-striped table-hover">
                <thead class="bg-success">
                    <tr>
                        <th colspan="5" class="text-white">Histori Kontrabon</th>
                    </tr>
                    <tr>
                        <th class="text-white">No. Kontrabon</th>
                        <th class="text-white">Tanggal</th>
                        <th class="text-white">Jumlah</th>
                        <th class="text-white">Jenis Kontrabon</th>
                        <th class="text-white">Tanggal Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kontrabon as $d)
                        <tr>
                            <td>{{ $d->no_kontrabon }}</td>
                            <td>{{ DateToIndo($d->tanggal_kontrabon) }}</td>
                            <td class="text-end">{{ formatAngkaDesimal($d->jumlah) }}</td>
                            <td>{{ $d->kategori == 'TN' ? 'TUNAI' : textUpperCase($d->kategori) }}</td>
                            <td>
                                @if (!empty($d->tanggal_bayar))
                                    <span class="badge bg-success">{{ DateToIndo($d->tanggal_bayar) }}</span>
                                @else
                                    <span class="badge bg-danger">Belum Bayar</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="row">
        <div class="col">
            <table class="table table-bordered  table-hover">
                <thead class="table-dark">
                    <tr>
                        <th colspan="4">Data Pembelian</th>
                    </tr>
                    <tr>
                        <th style="width: 10%">Kode</th>
                        <th style="width: 25%">Nama Barang</th>
                        <th style="width: 20%">Keterangan</th>
                        <th style="width: 10%">Qty</th>

                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_pembelian = 0;
                    @endphp
                    @foreach ($detail as $d)
                        @php
                            $subtotal = $d->jumlah * $d->harga;
                            $total = $subtotal + $d->penyesuaian;
                            $total_pembelian += $total;
                            $bg = '';
                            if (!empty($d->kode_cr)) {
                                $bg = 'bg-info text-white';
                            }
                        @endphp
                        <tr class="{{ $bg }}">
                            <td>{{ $d->kode_barang }}</td>
                            <td>{{ textCamelCase($d->nama_barang) }}</td>
                            <td>{{ textCamelCase($d->keterangan) }}</td>
                            <td class="text-center">{{ formatAngkaDesimal($d->jumlah) }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endcan
