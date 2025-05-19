<style>
    .table-modal {
        height: auto;
        max-height: 400px;
        overflow-y: scroll;

    }
</style>
<form action="{{ route('lembur.storeapprove', Crypt::encrypt($lembur->kode_lembur)) }}" method="POST" id="formApprovelembur">
    @csrf
    <div class="row mb-3">
        <table class="table">
            <tr>
                <th>Kode Lembur</th>
                <td class="text-end">{{ $lembur->kode_lembur }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td class="text-end">{{ DateToIndo($lembur->tanggal) }}</td>
            </tr>
            <tr>
                <th>Mulai</th>
                <td class="text-end">{{ date('d-m-Y H:i', strtotime($lembur->tanggal_dari)) }}</td>
            </tr>
            <tr>
                <th>Selesai</th>
                <td class="text-end">{{ date('d-m-Y H:i', strtotime($lembur->tanggal_sampai)) }}</td>
            </tr>
            <tr>
                <th>Istirahat</th>
                <td class="text-end">
                    @if ($lembur->istirahat == 1)
                        <i class="ti ti-checks text-success"></i>
                    @else
                        <i class="ti ti-square-rounded-x text-danger"></i>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Jumla Jam</th>
                <td class="text-end">
                    @php
                        $istirahat = $lembur->istirahat == 1 ? 1 : 0;
                        $jmljam = hitungjamdesimal($lembur->tanggal_dari, $lembur->tanggal_sampai);
                        // $jmljam = $jmljam > 7 ? 7 : $jmljam - $istirahat;
                        $jmljam = $jmljam - $istirahat;
                    @endphp
                    {{ $jmljam }} Jam
                </td>
            </tr>
            <th>Departemen</th>
            <td class="text-end">{{ $lembur->nama_dept }}</td>
            <tr>
                <th>Keterangan</th>
                <td class="text-end">{{ $lembur->keterangan }}</td>
            </tr>
        </table>
    </div>
    <div class="row">
        <div class="table-modal">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>Nik</th>
                        <th>Nama Karyawan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->nik }}</td>
                            <td>{{ $d->nama_karyawan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="form-group mb-3 mt-3">
        <button class="btn btn-primary w-100" id="btnSimpan">
            <i class="ti ti-thumb-up me-1"></i> Setuju,
            @if ($level_user != $end_role)
                Teruskan ke {{ textCamelCase($nextrole) }} ({{ $userrole->name }})
            @endif
        </button>
    </div>
</form>
<script>
    $(function() {
        const form = $('#formApprovelembur');
        $(".table-modal").freezeTable({
            'scrollable': true,
            'freezeColumn': false,
        });

        function buttonDisable() {
            $('#btnSimpan').prop('disabled', true);
            $('#btnSimpan').html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        form.submit(function(e) {
            buttonDisable();
            // return false;
        });
    });
</script>
