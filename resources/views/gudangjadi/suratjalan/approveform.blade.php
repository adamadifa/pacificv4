<form action="{{ route('suratjalan.approve', Crypt::encrypt($surat_jalan->no_mutasi)) }}" method="post" id="formApprovesuratjalan">
    @csrf
    <input type="hidden" id="cektutuplaporan">
    <div class="row mb-3">
        <div class="col">
            <table class="table">
                <tr>
                    <th>No. Surat Jalan</th>
                    <td>{{ $surat_jalan->no_mutasi }}</td>
                </tr>
                <tr>
                    <th>No. Dokumen</th>
                    <td>{{ $surat_jalan->no_dok }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($surat_jalan->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td>{{ textUpperCase($surat_jalan->nama_cabang) }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $surat_jalan->keterangan }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if ($surat_jalan->status_surat_jalan == 0)
                            <span class="badge bg-danger">Belum Diterima Cabang</span>
                        @elseif($surat_jalan->status_surat_jalan == 1)
                            <span class="badge bg-success">Sudah Diterima Cabang</span>
                        @elseif($d->status_surat_jalan == 2)
                            <span class="badge bg-info">Transit Out</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Kode</th>
                        <th style="width:50%">Nama Produk</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail as $d)
                        <tr>
                            <td>{{ $d->kode_produk }}</td>
                            <td>{{ $d->nama_produk }}</td>
                            <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="form-group mb-3">
        <select name="status" id="status" class="form-select">
            <option value="">Pilih Status</option>
            <option value="1">Diterima</option>
            <option value="2">Transit Out</option>
        </select>
    </div>
    <x-input-with-icon label="Tanggal Diterima / Transit Out" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
    <div class="form-group mb-3" id="saveButton">
        <button class="btn btn-primary w-100" type="submit" id="btnSimpan">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script>
    $(function() {
        const form = $("#formApprovesuratjalan");
        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }, ]
        });

        function cektutuplaporan(tanggal, jenis_laporan) {
            $.ajax({

                type: "POST",
                url: "/tutuplaporan/cektutuplaporan",
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    jenis_laporan: jenis_laporan
                },
                cache: false,
                success: function(respond) {
                    $("#cektutuplaporan").val(respond);
                }
            });
        }

        $("#tanggal").change(function(e) {
            cektutuplaporan($(this).val(), "gudangcabang");
        });

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }
        form.submit(function() {
            const status = form.find("#status").val();
            const tanggal = form.find("#tanggal").val();
            const cektutuplaporan = form.find("#cektutuplaporan").val();
            if (status == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih Status Terlebih Dahulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#status").focus();
                    },

                });

                return false;
            } else if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },

                });

                return false;
            } else if (cektutuplaporan === '1') {
                Swal.fire("Oops!", "Laporan Untuk Periode Ini Sudah Ditutup", "warning");
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
