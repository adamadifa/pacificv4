<div class="row mb-2">
    <div class="col">
        <table class="table">
            <tr>
                <th>No. Kontrabon</th>
                <td class="text-end">{{ $kontrabon->no_kontrabon }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td class="text-end">{{ DateToIndo($kontrabon->tanggal) }}</td>
            </tr>
            <tr>
                <th>Angkutan</th>
                <td class="text-end">{{ $kontrabon->nama_angkutan }}</td>
            </tr>
        </table>

    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No. Dok</th>
                    <th>Tanggal</th>
                    <th>No. Polisi</th>
                    <th>Tujuan</th>
                    <th>Tarif</th>
                    <th>Tepung</th>
                    <th>BS</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandtotaltarif = 0;
                @endphp
                @foreach ($detail as $d)
                    @php
                        $totaltarif = $d->tarif + $d->tepung + $d->bs;
                        $grandtotaltarif += $totaltarif;
                    @endphp
                    <tr>
                        <td>{{ $d->no_dok }}</td>
                        <td>{{ formatIndo($d->tanggal) }}</td>
                        <td>{{ $d->no_polisi }}</td>
                        <td>{{ $d->tujuan }}</td>
                        <td class="text-end">{{ formatAngka($d->tarif) }}</td>
                        <td class="text-end">{{ formatAngka($d->tepung) }}</td>
                        <td class="text-end">{{ formatAngka($d->bs) }}</td>
                        <td class="text-end">{{ formatAngka($totaltarif) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <td colspan="7">TOTAL</td>
                    <td class="text-end">{{ formatAngka($grandtotaltarif) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
