@php

@endphp
<form action="{{ route('bpbpembelian.serahterimabpbstore') }}" method="POST" id="formSerahTerima" autocomplete="off">
    @csrf

    <!-- Hidden wajib -->
    <input type="hidden" name="kode_dept" value="{{ $bpb->kode_dept }}">
    <input type="hidden" name="kode_cabang" value="{{ $bpb->kode_cabang }}">
    <input type="hidden" name="no_ref" value="{{ $bpb->no_bpb }}">

    <!-- INFORMASI BPB -->
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong>Informasi BPB</strong>
        </div>
        <div class="card-body p-2">
            <table class="table table-sm mb-0">
                <tr>
                    <th width="25%">No. BPB</th>
                    <td>{{ $bpb->no_bpb }}</td>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($bpb->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td>{{ $bpb->nama_dept }}</td>
                    <th>Cabang</th>
                    <td>{{ $bpb->nama_cabang }}</td>
                </tr>
                <tr>
                    <th>User Input</th>
                    <td>{{ $bpb->nama_user }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- DETAIL BARANG -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <strong>Detail Barang</strong>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm table-bordered align-middle">
                    <thead class="table-secondary text-center">
                        <tr>
                            <th style="width:3%">No</th>
                            <th style="width:10%">Kode</th>
                            <th style="width:25%">Nama Barang</th>
                            <th style="width:8%" class="text-end">Jumlah</th>
                            <th style="width:6%">Satuan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalSisa = 0;
                        @endphp
                        @foreach ($detail as $d)
                            @php
                                $sudahDiserahkan = $diserahkanTotal[$d->kode_barang] ?? 0;
                                $sisa = $d->jumlah - $sudahDiserahkan;
                                $totalSisa += $sisa;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $d->kode_barang }}</td>
                                <td>{{ textCamelCase($d->nama_barang) }}</td>
                                <td class="text-end">{{ formatAngkaDesimal($d->jumlah) }}</td>
                                <td>{{ $d->satuan }}</td>
                                <td>{{ $d->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    {{-- <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="5" class="text-end">TOTAL SISA</td>
                            <td class="text-end text-danger">
                                {{ formatAngkaDesimal($totalSisa) }}
                            </td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot> --}}
                </table>
            </div>
        </div>
    </div>

</form>

<script>
    $(document).ready(function() {
        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }, ]
        });

        $(".editJumlah").on("change", function() {

            let jumlah = $(this).val();
            let no_bukti = $(this).data("no_bukti");
            let kode_barang = $(this).data("kode_barang");

            $.post("{{ route('bpbpembelian.updateSerahTerima') }}", {
                _token: "{{ csrf_token() }}",
                no_bukti: no_bukti,
                kode_barang: kode_barang,
                jumlah: jumlah
            }, function(resp) {

                Swal.fire({
                    icon: "success",
                    title: "Jumlah diperbarui",
                    timer: 900,
                    showConfirmButton: false
                });

            });

        });


        // ===========================
        // AUTO UPDATE KETERANGAN
        // ===========================
        $(".editKeterangan").on("change", function() {

            let keterangan = $(this).val();
            let no_bukti = $(this).data("no_bukti");
            let kode_barang = $(this).data("kode_barang");

            $.post("{{ route('bpbpembelian.updateSerahTerima') }}", {
                _token: "{{ csrf_token() }}",
                no_bukti: no_bukti,
                kode_barang: kode_barang,
                keterangan: keterangan
            }, function(resp) {

                Swal.fire({
                    icon: "success",
                    title: "Keterangan diperbarui",
                    timer: 900,
                    showConfirmButton: false
                });

            });

        });

        $(".terimaSurat").on("change", function() {

            let no_bukti = $(this).data("no_bukti");

            $.post("{{ route('bpbpembelian.updateSerahTerima') }}", {
                _token: "{{ csrf_token() }}",
                no_bukti: no_bukti,
                diterima: 1
            }, function(resp) {

                Swal.fire({
                    icon: "success",
                    title: "Keterangan diperbarui",
                    timer: 900,
                    showConfirmButton: false
                });

            });

        });


        // DELETE
        $(".deleteHistoryBtn").on("click", function() {
            let no_bukti = $(this).data("no_bukti");
            let kode_barang = $(this).data("kode_barang");

            Swal.fire({
                title: "Hapus Data?",
                text: "Data serah terima akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ route('bpbpembelian.deleteSerahTerima') }}", {
                        _token: "{{ csrf_token() }}",
                        no_bukti: no_bukti,
                        kode_barang: kode_barang
                    }, function(res) {
                        location.reload();
                    });
                }
            });
        });

        $(".diserahkan").on("input", function() {

            let row = $(this).closest("tr");
            let sisa = parseFloat($(this).data("sisa")) || 0;
            let val = parseFloat($(this).val()) || 0;

            if (val > sisa) {
                Swal.fire({
                    icon: "error",
                    title: "Tidak Valid!",
                    text: "Jumlah diserahkan (" + val + ") melebihi sisa (" + sisa + ")",
                    confirmButtonColor: "#d33"
                });

                $(this).val(sisa); // set input ke sisa
                row.addClass("table-danger");
            } else {
                row.removeClass("table-danger");
            }
        });

    });
</script>
