<form action="{{ route('penilaiankaryawan.storeapprove', Crypt::encrypt($penilaiankaryawan->kode_penilaian)) }}"
    method="POST" id="formApprove">
    @csrf
    <div class="row">
        <div class="col-lg-2 col-md-12 col-sm-12">
            @if (Storage::disk('public')->exists('/karyawan/' . $penilaiankaryawan->foto))
                <img src="{{ getfotoKaryawan($penilaiankaryawan->foto) }}" class="card-img"
                    style="width: 120px; height:150px; object-fit:cover; border-radius:10px;">
            @else
                @if ($penilaiankaryawan->jenis_kelamin == 'L')
                    <img src="{{ asset('assets/img/avatars/male.jpg') }}" class="card-img"
                        style="width: 120px; height:150px; object-fit:cover; border-radius:10px; ">
                @else
                    <img src="{{ asset('assets/img/avatars/female.jpg') }}" class="card-img"
                        style="width: 120px; height:150px; object-fit:cover; border-radius:10px; ">
                @endif
            @endif
        </div>
        <div class="col-lg-7 col-md-12 col-sm-12 ">
            <div class="row">
                <div class="col">
                    <table class="table">
                        <tr>
                            <th>NIK</th>
                            <td class="text-end">{{ $penilaiankaryawan->nik }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td class="text-end">{{ $penilaiankaryawan->nama_karyawan }}</td>
                        </tr>
                        <tr>
                            <th>Departemen</th>
                            <td class="text-end">{{ $penilaiankaryawan->nama_dept }}</td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td class="text-end">{{ $penilaiankaryawan->nama_jabatan }}</td>
                        </tr>
                        <tr>
                            <th>Periode Kontrak</th>
                            <td class="text-end">{{ DateToIndo($penilaiankaryawan->kontrak_dari) }} s.d
                                {{ DateToIndo($penilaiankaryawan->kontrak_sampai) }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td class="text-end">{{ DateToIndo($penilaiankaryawan->tanggal) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12 col-sm-12 m-auto text-center">
            <div class="row">
                <div class="col">
                    <span class="mb-3">Total Score</span>
                    <h1 id="totalscore" style="font-size: 4rem">{{ $total_score->total_score }}</h1>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col">
                    <a href="{{ route('penilaiankaryawan.cetak', Crypt::encrypt($penilaiankaryawan->kode_penilaian)) }}"
                        class="btn btn-primary w-100" target="_blank">
                        <i class="ti ti-external-link me-1"></i> Lihat Detail Penilaian
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="d-flex gap-4">
                <div class="">
                    {!! $penilaiankaryawan->masa_kontrak == 'TP'
                        ? '<i class="ti ti-square-check me-1"></i>'
                        : '<i class="ti ti-square me-1"></i>' !!}
                    Tidak Di Perpanjang
                </div>
                <div class="">
                    {!! $penilaiankaryawan->masa_kontrak == 'K3'
                        ? '<i class="ti ti-square-check me-1"></i>'
                        : '<i class="ti ti-square me-1"></i>' !!}
                    3 Bulan
                </div>
                <div class="">
                    {!! $penilaiankaryawan->masa_kontrak == 'K6'
                        ? '<i class="ti ti-square-check me-1"></i>'
                        : '<i class="ti ti-square me-1"></i>' !!}
                    6 Bulan
                </div>
                <div class="">
                    {!! $penilaiankaryawan->masa_kontrak == 'KT'
                        ? '<i class="ti ti-square-check me-1"></i>'
                        : '<i class="ti ti-square me-1"></i>' !!}
                    Karyawan Tetap
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <table style="font-size:14px" class="table">
                <tr>
                    <td>SID</td>
                    <td>:</td>
                    <td>{{ $penilaiankaryawan->sid }}</td>
                    <td>Izin</td>
                    <td>:</td>
                    <td>{{ $penilaiankaryawan->izin }}</td>
                </tr>
                <tr>
                    <td>Sakit</td>
                    <td>:</td>
                    <td>{{ $penilaiankaryawan->sakit }}</td>
                    <td>Alfa</td>
                    <td>:</td>
                    <td>{{ $penilaiankaryawan->alfa }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Rekomendasi</th>
                        <th>Evaluasi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $penilaiankaryawan->rekomendasi }}</td>
                        <td>{{ $penilaiankaryawan->evaluasi }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <button class="btn btn-primary w-100" id="btnSimpan">
                <i class="ti ti-thumb-up me-1"></i> Setuju,
                @if ($level_user != $end_role)
                    Teruskan ke {{ textCamelCase($nextrole) }} ({{ $userrole->name }})
                @endif

            </button>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        const form = $("#formApprove");

        function buttonDisabled() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        form.submit(function(e) {
            buttonDisabled();
        })

    })
</script>
