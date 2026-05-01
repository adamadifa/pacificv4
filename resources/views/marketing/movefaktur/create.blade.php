<form action="{{ route('movefaktur.store') }}" method="POST" id="formMovefaktur">
    @csrf
    <div class="row">
        <div class="col-12">
            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
            
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="ti ti-barcode"></i></span>
                <input type="text" class="form-control" placeholder="Cari No. Faktur" name="no_faktur" id="no_faktur" readonly>
                <button class="btn btn-primary" type="button" id="btnCariFaktur"><i class="ti ti-search"></i></button>
            </div>

            <div class="card bg-light border-0 mb-3 shadow-none">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted d-block">Pelanggan</small>
                            <span id="nama_pelanggan_display" class="fw-bold">-</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Salesman Lama</small>
                            <span id="nama_salesman_lama_display" class="fw-bold">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <select name="kode_cabang" id="kode_cabang_baru" class="form-select select2">
                    <option value="">Pilih Cabang Sales Baru</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ $d->nama_cabang }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <select name="kode_salesman_baru" id="kode_salesman_baru" class="form-select select2">
                    <option value="">Pilih Salesman Baru</option>
                </select>
            </div>

            <div class="form-group">
                <button class="btn btn-primary w-100" type="submit"><i class="ti ti-send me-1"></i> Simpan</button>
            </div>
        </div>
    </div>
</form>

{{-- Inner Modal for Faktur Selection --}}
<div class="modal fade" id="modalFaktur" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Faktur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search_faktur" placeholder="Cari No. Faktur / Nama Pelanggan...">
                            <button class="btn btn-outline-primary" type="button" id="btnSearchFaktur"><i class="ti ti-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 400px">
                    <table class="table table-hover table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>No. Faktur</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Salesman</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody id="loadFaktur">
                            <tr>
                                <td colspan="5" class="text-center">Silahkan cari faktur...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $(".select2").select2({
            dropdownParent: $('#modal')
        });

        $(".datepicker").flatpickr({
            dateFormat: "Y-m-d",
        });

        $("#btnCariFaktur").click(function(e) {
            e.preventDefault();
            $("#modalFaktur").modal("show");
        });

        function getsalesman() {
            var kode_cabang = $("#kode_cabang_baru").val();
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(respond) {
                    $("#kode_salesman_baru").html(respond);
                }
            });
        }

        $("#kode_cabang_baru").change(function() {
            getsalesman();
        });

        $("#btnSearchFaktur").click(function() {
            var search = $("#search_faktur").val();
            if (search.length < 3) {
                Swal.fire("Oops", "Minimal 3 karakter untuk mencari", "warning");
                return;
            }

            $.ajax({
                type: 'POST',
                url: "{{ route('movefaktur.getfakturajax') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    search: search
                },
                beforeSend: function() {
                    $("#loadFaktur").html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
                },
                success: function(respond) {
                    $("#loadFaktur").html(respond);
                }
            });
        });

        $(document).on('click', '.pilihFaktur', function(e) {
            e.preventDefault();
            var no_faktur = $(this).attr('no_faktur');
            var nama_pelanggan = $(this).attr('nama_pelanggan');
            var nama_salesman = $(this).attr('nama_salesman');

            $("#no_faktur").val(no_faktur);
            $("#nama_pelanggan_display").text(nama_pelanggan);
            $("#nama_salesman_lama_display").text(nama_salesman);

            $("#modalFaktur").modal("hide");
        });
    });
</script>
