<style>
    .table-modal {
        height: auto;
        max-height: 500px;
        overflow-y: scroll;

    }
</style>

<div class="table-responsive mb-2">
    <div class="table-modal">
        <table class="table  table-bordered" id="tableKlaimKasKecil">
            <thead class="table-dark">
                <tr>
                    <th style="width: 3%">No</th>
                    <th style="width: 10%">Tanggal</th>
                    <th style="width: 10%">No. Bukti</th>
                    <th style="width: 20%">Keterangan</th>
                    <th style="width: 20%">Akun</th>
                    <th>Penerimaan</th>
                    <th>Pengeluaran</th>
                    <th>Saldo</th>
                    <th>Aksi</th>
                </tr>
                <tr>
                    <th colspan="7"><b>SALDO AWAL</b></th>
                    <td class="text-end">{{ $saldoawal != null ? formatAngka($saldoawal->saldo_awal) : 0 }}</td>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $saldo = $saldoawal != null ? $saldoawal->saldo_awal : 0;
                    $total_penerimaan = 0;
                    $total_pengeluaran = 0;
                @endphp
                @foreach ($kaskecil as $d)
                    @php
                        $penerimaan = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                        $pengeluaran = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                        $color = $d->debet_kredit == 'K' ? 'success' : 'danger';
                        $saldo += $penerimaan - $pengeluaran;
                        $total_penerimaan += $penerimaan;
                        $total_pengeluaran += $pengeluaran;
                        $colorklaim = !empty($d->kode_klaim) ? 'bg-success text-white' : '';
                    @endphp
                    <tr>
                        <td class="{{ $colorklaim }}">{{ $loop->iteration }} </td>
                        <td>{{ formatIndo($d->tanggal) }}</td>
                        <td>{{ $d->no_bukti }}</td>
                        <td>{{ textCamelcase($d->keterangan) }}</td>
                        <td>{{ $d->kode_akun }} - {{ $d->nama_akun }}</td>
                        <td class="text-end text-{{ $color }}">{{ formatAngka($penerimaan) }}</td>
                        <td class="text-end text-{{ $color }}">{{ formatAngka($pengeluaran) }}</td>
                        <td class="text-end text-primary"> {{ formatAngka($saldo) }}</td>
                        <td for="id_kaskecil">
                            @if (!empty($d->kode_klaim))
                                <span class="badge bg-success">{{ $d->kode_klaim }}</span>
                            @else
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input" type="checkbox" name="id_kaskecil[]" id="id_kaskecil"
                                        value="{{ $d->id }}" />
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach

            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <th colspan="5">TOTAL</th>
                    <td class="text-end">{{ formatAngka($total_penerimaan) }}</td>
                    <td class="text-end">{{ formatAngka($total_pengeluaran) }}</td>
                    <td class="text-end">{{ formatAngka($saldo) }}</td>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".table-modal").freezeTable({
            'scrollable': true,
            'freezeColumn': false,
        });



    })
</script>
