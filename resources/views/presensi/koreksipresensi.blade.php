<form action="{{ route('presensi.updatepresensi', Crypt::encrypt($presensi->id)) }}" method="POST" id="formKoreksiPresensi">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table ">
                <tr>
                    <th>NIK</th>
                    <td>{{ $karyawan->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td>{{ $karyawan->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td>{{ $karyawan->nama_dept }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td>{{ $karyawan->nama_jabatan }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td>{{ $karyawan->kode_cabang }}</td>
                </tr>
            </table>
        </div>
        <div class="col">
            <div class="form-group mb-3">
                <select name="kode_jadwal" id="kode_jadwal" class="form-select">
                    <option value="">Jadwal</option>
                    @foreach ($jadwal as $d)
                        <option {{ $presensi->kode_jadwal == $d->kode_jadwal ? 'selected' : '' }} value="{{ $d->kode_jadwal }}">{{ $d->nama_jadwal }}
                        </option>
                    @endforeach
                </select>

            </div>
            <div class="form-group mb-3">
                <select name="kode_jam_kerja" id="kode_jam_kerja" class="form-select">
                    <option value="">Jam Kerja</option>
                </select>
            </div>
            @hasanyrole(['super admin', 'asst. manager hrd', 'spv presensi'])
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <x-input-with-icon icon="ti ti-clock " label="Jam Masuk" name="jam_in"
                            value="{{ !empty($presensi->jam_in) ? date('H:i', strtotime($presensi->jam_in)) : '' }}" />
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <x-input-with-icon icon="ti ti-clock " label="Jam Keluar" name="jam_out"
                            value="{{ !empty($presensi->jam_out) ? date('H:i', strtotime($presensi->jam_out)) : '' }}" />
                    </div>
                </div>
            @endhasanyrole
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Update Presensi</button>
            </div>
        </div>

    </div>
</form>
<script>
    $(function() {
        const formKoreksipresensi = $('#formKoreksiPresensi');

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }

        function loadjamkerja() {
            let kode_jadwal = $('#kode_jadwal').val();
            $.ajax({
                url: "{{ route('presensi.getjamkerja') }}",
                type: "GET",
                data: {
                    kode_jadwal: kode_jadwal,
                    'kode_jam_kerja': "{{ $presensi->kode_jam_kerja }}"
                },
                success: function(respond) {
                    $('#kode_jam_kerja').html(respond);
                }
            });
        }

        $("#kode_jadwal").change(function() {
            loadjamkerja();
        });
        loadjamkerja();


        formKoreksipresensi.submit(function(e) {
            const kode_jadwal = $('#kode_jadwal').val();
            const kode_jam_kerja = $('#kode_jam_kerja').val();
            const jam_in = $('#jam_in').val();
            const jam_out = $('#jam_out').val();
            if (kode_jadwal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jadwal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formKoreksipresensi.find('#kode_jadwal').focus();
                    },
                });
                return false;
            } else if (kode_jam_kerja == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jam Kerja Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formKoreksipresensi.find('#kode_jam_kerja').focus();
                    },
                })
                return false;
            } else {
                buttonDisable();
            }

        });

    });
</script>
