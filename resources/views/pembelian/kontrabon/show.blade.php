<div class="row mb-3">
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
                <th>Terima Dari</th>
                <td class="text-end">{{ $kontrabon->nama_supplier }}</td>
            </tr>
        </table>

    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>No. Bukti</th>
                    <th>Jumlah</th>
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
                    <tr class="cursor-pointer btnShowpembelian" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ DateToIndo($d->tanggal) }}</td>
                        <td>{{ $d->no_bukti }}</td>
                        <td class="text-end">{{ formatAngkaDesimal($d->jumlah) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <td colspan="3">TOTAL</td>
                    <td class="text-end">{{ formatAngkaDesimal($total) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
