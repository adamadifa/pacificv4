<form action="#" method="POST" id="formGantiShift">
    <div class="row">
        <div class="col">
            <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" />
            <div class="form-group mb-3">
                <select name="nik" id="nik" class="form-select select2Nik">
                    <option value="">Karyawan</option>
                    @foreach ($karyawan as $d)
                        <option value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="kode_jadwal" id="kode_jadwal" class="form-select">
                    <option value="">Pilih Shift</option>
                    <option value="JD002">Shift 1</option>
                    <option value="JD003">Shift 2</option>
                    <option value="JD004">Shift 3</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-thumb-up me-1"></i>Simpan</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Nik</th>
                        <th>Nama Karyawan</th>
                        <th>Shift</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="loadgantishift"></tbody>
            </table>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        const form = $("#formGantiShift");
        const select2Nik = $(".select2Nik");
        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $jadwalshift->dari }}",
                to: "{{ $jadwalshift->sampai }}",
            }, ]
        });
        if (select2Nik.length) {
            select2Nik.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Karyawan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function loadgantishift() {
            const kode_jadwalshift = "{{ Crypt::encrypt($kode_jadwalshift) }}";
            $("#loadgantishift").html("<tr><td colspan=5 class='text-center'>Loading...</td></tr>");
            $("#loadgantishift").load(`/jadwalshift/${kode_jadwalshift}/getgantishift`);
        }

        loadgantishift();
        form.submit(function(e) {
            e.preventDefault();
            const tanggal = form.find("#tanggal").val();
            const nik = form.find("#nik").val();
            const kode_jadwal = form.find("#kode_jadwal").val();
            const kode_jadwalshift = "{{ $kode_jadwalshift }}";
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (nik == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Karyawan harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#nik").focus();
                    },
                });
                return false;
            } else if (kode_jadwal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Shift harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_jadwal").focus();
                    },
                });
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: "/jadwalshift/storegantishift",
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal,
                        nik: nik,
                        kode_jadwal: kode_jadwal,
                        kode_jadwalshift: kode_jadwalshift
                    },
                    cache: false,
                    success: function(respond) {
                        if (respond.success == true) {
                            Swal.fire({
                                title: "Success!",
                                text: respond.message,
                                icon: "success",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    loadgantishift();
                                },
                            });
                        } else {
                            Swal.fire({
                                title: "Oops!",
                                text: respond.message,
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    form.find("#nik").focus();
                                },
                            });
                        }
                    }
                });
            }
        });

        $(document).on("click", ".deletegs", function(e) {
            e.preventDefault();
            const kode_gs = $(this).attr("kode_gs");
            Swal.fire({
                title: "Hapus?",
                text: "Apakah Anda Yakin Ingin Menghapus Data Ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Hapus",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "/jadwalshift/deletegantishift",
                        data: {
                            _token: "{{ csrf_token() }}",
                            kode_gs: kode_gs
                        },
                        cache: false,
                        success: function(respond) {
                            if (respond.success == true) {
                                Swal.fire({
                                    title: "Success!",
                                    text: respond.message,
                                    icon: "success",
                                    showConfirmButton: true,
                                    didClose: (e) => {
                                        loadgantishift();
                                    },
                                });
                            } else {
                                Swal.fire({
                                    title: "Oops!",
                                    text: respond.message,
                                    icon: "warning",
                                    showConfirmButton: true,
                                    didClose: (e) => {
                                        loadgantishift();
                                    }
                                });
                            }
                        }
                    });
                }
            });
        });
    });
</script>
