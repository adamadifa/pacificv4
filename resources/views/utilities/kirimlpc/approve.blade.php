<form action="{{ route('kirimlpc.storeapprove', Crypt::encrypt($kirim_lpc->kode_kirim_lpc)) }}" id="formApprove" method="POST">
    @csrf
    <table class="table">
        <tr>
            <th>Kode</th>
            <td>{{ $kirim_lpc->kode_kirim_lpc }}</td>
        </tr>
        <tr>
            <th>Cabang</th>
            <td>{{ textUpperCase($kirim_lpc->nama_cabang) }}</td>
        </tr>
        <tr>
            <th>Bulan</th>
            <td>{{ $namabulan[$kirim_lpc->bulan] }}</td>
        </tr>
        <tr>
            <th>Tahun</th>
            <td>{{ $kirim_lpc->tahun }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ formatIndo($kirim_lpc->tanggal) }} {{ $kirim_lpc->jam }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if ($kirim_lpc->status == '1')
                    <i class="ti ti-checks text-success"></i>
                @else
                    <i class="ti ti-hourglass-empty text-warning"></i>
                @endif
            </td>
        </tr>
        <tr>
            <th>Attachment</th>
            <td>
                @if (!empty($kirim_lpc->foto))
                    @php
                        $path = Storage::url('lpc/' . $kirim_lpc->foto);
                    @endphp
                    <a href="{{ url($path) }}" target="_blank">
                        <i class="ti ti-paperclip me-1"></i> Lihat Dokumen
                    </a>
                @endif
            </td>
        </tr>
    </table>
    <div class="row">
        <div class="form-group mb-3">
            <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-thumb-up me-1"></i> Approve</button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const form = $('#formApprove');

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
        });
    });
</script>
