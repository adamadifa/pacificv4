<style>
    .table-modal {
        height: auto;
        max-height: 600px;
        overflow-y: scroll;

    }
</style>
<div class="d-flex justify-content-end">
    <a href="{{ route('pembayaranpjp.show', [Crypt::encrypt($potonganpjp->kode_potongan), 'true']) }}" class="btn btn-success mb-3" target="_blank"> <i
            class="ti ti-download me-1"></i>Export Excel</a>
</div>
<div class="row mb-3">
    <dv class="col">
        <table class="table">
            <tr>
                <th>Bulan</th>
                <td>{{ $namabulan[$potonganpjp->bulan] }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $potonganpjp->tahun }}</td>
            </tr>
        </table>
    </dv>
</div>

<div class="table-responsive">
    <div class="table-modal">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>No. Pinjaman</th>
                    <th>Nik</th>
                    <th>Nama Karyawan</th>
                    {{-- <th>Jabatan</th> --}}
                    <th>Dept</th>
                    <th>Kantor</th>
                    <th>Jumlah</th>
                    <th class="text-center">Cicilan Ke</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($historibayar as $d)
                    @php
                        $total += $d->jumlah;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->no_pinjaman }}</td>
                        <td>{{ $d->nik }}</td>
                        <td>{{ textUpperCase($d->nama_karyawan) }}</td>
                        {{-- <td>{{ textUpperCase($d->nama_jabatan) }}</td> --}}
                        <td>{{ textUpperCase($d->kode_dept) }}</td>
                        <td>{{ textUpperCase($d->kode_cabang) }}</td>
                        <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                        <td class="text-center">{{ $d->cicilan_ke }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <td colspan="6">TOTAL</td>
                    <td class="text-end">{{ formatAngka($total) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<script>
    $(function() {
        $(".table-modal").freezeTable({
            'scrollable': true,
            'freezeColumn': false,
        });
    });
</script>
