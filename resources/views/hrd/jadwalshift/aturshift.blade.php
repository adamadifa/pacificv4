<style>
    .nav-tabs-container {
        overflow-x: scroll;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        /* Untuk Firefox */
    }

    .nav-tabs-container::-webkit-scrollbar {
        display: none;
        /* Untuk Chrome, Safari, dan Opera */
    }

    .nav-tabs {
        white-space: nowrap;
        flex-wrap: nowrap;
    }
</style>

<div class="nav-tabs-container" id="listgroup">
    <ul class="nav nav-tabs" role="tablist">
        @foreach ($group as $d)
            <li class="nav-item" role="presentation">
                <button type="button" class="nav-link {{ $loop->iteration == 1 ? 'active' : '' }} waves-effect getgroup"
                    kode_group="{{ $d->kode_group }}" role="tab" data-bs-toggle="tab" aria-controls="navs-top-home"
                    aria-selected="true">{{ $d->nama_group }}</button>
            </li>
        @endforeach
    </ul>
</div>
<div class="row mt-3">
    <div class="col">
        <div class="d-flex justify-content-between">
            <button class="btn btn-primary" id="tambahkansemua"><i class="ti ti-plus me-1"></i> Tambahkan Semua </button>
            <button class="btn btn-danger" id="batalkansemua"><i class="ti ti-circle-minus me-1"></i> Batalkan Semua </button>
        </div>
    </div>
</div>
</div>
<div class="row mt-3">
    <div class="col">
        <table class="table table-bordered table-striped" id="tabelgroup">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Nik</th>
                    <th>Nama</th>
                    <th>Jadwal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="loadgroup">
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {

        function loadgroup() {
            var kodeGroup = $('.nav-link.active').attr('kode_group');
            var kode_jadwalshift = "{{ $kode_jadwalshift }}";
            // $("#loadgroup").html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
            $("#loadgroup").load(`/jadwalshift/${kodeGroup}/${kode_jadwalshift}/getgroup`);
        }

        loadgroup();

        $(".getgroup").click(function() {
            loadgroup();
        });


        function loadshift(shift, kode_jadwal) {
            let kode_jadwalshift = "{{ $kode_jadwalshift }}";
            $("#loadshift" + shift).html(`<tr><td colspan="3" class="text-center">Tunggu Sebentar....</td></tr>`);
            $.ajax({
                type: 'POST',
                url: '/jadwalshift/getshift',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_jadwal: kode_jadwal,
                    kode_jadwalshift: kode_jadwalshift
                },
                cache: false,
                success: function(respond) {
                    //console.log(respond);
                    $("#loadshift" + shift).html(respond);
                }
            });
        }

        function getjadwal(shift) {
            let kode_jadwal;
            if (shift == '1') {
                kode_jadwal = 'JD002'
            } else if (shift == '2') {
                kode_jadwal = 'JD003'
            } else if (shift == '3') {
                kode_jadwal = 'JD004'
            }

            return kode_jadwal;
        }


        $(document).off('click').on('click', '#tabelgroup .updateJadwal', function(e) {
            e.preventDefault();
            const nik = $(this).attr('nik');
            const kode_jadwalshift = "{{ $kode_jadwalshift }}";
            const shift = "{{ $shift }}";
            const kode_jadwal = getjadwal(shift);
            //Ubah pada kolom Status Jadwal menjadi loading
            $(this).html('<i class="fas fa-spinner fa-spin"></i>');
            $.ajax({
                type: 'POST',
                url: `/jadwalshift/updatejadwal`,
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik,
                    kode_jadwalshift: kode_jadwalshift,
                    shift: shift
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        loadgroup();
                        loadshift(1, "JD002");
                        loadshift(2, "JD003");
                        loadshift(3, "JD004");
                    } else {
                        Swal.fire({
                            title: "Oops!",
                            text: respond.message,
                            icon: "warning",
                            showConfirmButton: true,
                        });

                    }
                }
            });
        });

        $("#tambahkansemua").click(function() {
            const kode_jadwalshift = "{{ $kode_jadwalshift }}";
            const shift = "{{ $shift }}";
            const kode_group = $('.nav-link.active').attr('kode_group');
            $("#loadgroup").html(`<tr><td colspan="5" class="text-center">Tunggu Sebentar....</td></tr>`);
            $.ajax({
                type: 'POST',
                url: `/jadwalshift/tambahkansemua`,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_jadwalshift: kode_jadwalshift,
                    shift: shift,
                    kode_group: kode_group
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        loadgroup();
                        loadshift(1, "JD002");
                        loadshift(2, "JD003");
                        loadshift(3, "JD004");
                    } else {
                        Swal.fire({
                            title: "Oops!",
                            text: respond.message,
                            icon: "warning",
                            showConfirmButton: true,
                        });
                    }
                }
            });
        });

        $("#batalkansemua").click(function() {
            const kode_jadwalshift = "{{ $kode_jadwalshift }}";
            const shift = "{{ $shift }}";
            const kode_group = $('.nav-link.active').attr('kode_group');
            $("#loadgroup").html(`<tr><td colspan="5" class="text-center">Tunggu Sebentar....</td></tr>`);
            $.ajax({
                type: 'POST',
                url: `/jadwalshift/batalkansemua`,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_jadwalshift: kode_jadwalshift,
                    shift: shift,
                    kode_group: kode_group
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        loadgroup();
                        loadshift(1, "JD002");
                        loadshift(2, "JD003");
                        loadshift(3, "JD004");
                    } else {
                        Swal.fire({
                            title: "Oops!",
                            text: respond.message,
                            icon: "warning",
                            showConfirmButton: true,
                        });
                    }
                }
            });
        })
    });
</script>
