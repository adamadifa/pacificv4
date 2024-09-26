<form action="{{ route('kirimlhp.storeapprove', Crypt::encrypt($kirim_lhp->kode_kirim_lhp)) }}" id="formApprove" method="POST">
    @csrf
    <table class="table">
        <tr>
            <th>Kode</th>
            <td>{{ $kirim_lhp->kode_kirim_lhp }}</td>
        </tr>
        <tr>
            <th>Cabang</th>
            <td>{{ textUpperCase($kirim_lhp->nama_cabang) }}</td>
        </tr>
        <tr>
            <th>Bulan</th>
            <td>{{ $namabulan[$kirim_lhp->bulan] }}</td>
        </tr>
        <tr>
            <th>Tahun</th>
            <td>{{ $kirim_lhp->tahun }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ formatIndo($kirim_lhp->tanggal) }} {{ $kirim_lhp->jam }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if ($kirim_lhp->status == '1')
                    <i class="ti ti-checks text-success"></i>
                @else
                    <i class="ti ti-hourglass-empty text-warning"></i>
                @endif
            </td>
        </tr>
        <tr>
            <th>Attachment</th>
            <td>
                @if (!empty($kirim_lhp->foto))
                    @php
                        $path = Storage::url('lhp/' . $kirim_lhp->foto);
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
