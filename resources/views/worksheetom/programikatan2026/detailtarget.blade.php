{{-- <div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>No. Pengajuan</th>
                <td class="text-end">{{ $programikatan->no_pengajuan }}</td>
            </tr>
            <tr>
                <th>No. Dokumen</th>
                <td class="text-end">{{ $programikatan->nomor_dokumen }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td class="text-end">{{ DateToIndo($programikatan->tanggal) }}</td>
            </tr>
            <tr>
                <th>Periode Penjualan</th>
                <td class="text-end">{{ DateToIndo($programikatan->periode_dari) }} s.d
                    {{ DateToIndo($programikatan->periode_sampai) }}</td>
            </tr>
            <tr>
                <th>Program</th>
                <td class="text-end">{{ $programikatan->nama_program }}</td>
            </tr>
            <tr>
                <th>Cabang</th>
                <td class="text-end">{{ $programikatan->kode_cabang }}</td>
            </tr>

        </table>
    </div>
</div> --}}
<table class="table table-bordered mb-2" id="targetperbulantable">
    <thead class="table-dark">
        <tr>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Target</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_target = 0;
        @endphp
        @foreach ($detailtarget as $d)
            @php
                $total_target += $d->target_perbulan;
            @endphp
            <tr class="targetbulanan">
                <td>
                    {{ getMonthName($d->bulan) }}
                </td>
                <td>

                    {{ $d->tahun }}
                </td>
                <td class="text-end">
                    {{ formatAngka($d->target_perbulan) }}
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot class="table-dark">
        <tr>
            <td colspan="2">TOTAL</td>
            <td class="text-end" id="gradTotaltarget"> {{ formatAngka($total_target) }}</td>
        </tr>
    </tfoot>
</table>
