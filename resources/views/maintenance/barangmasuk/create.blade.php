<form action="{{ route('barangmasukmtc.store') }}" method="POST" id="formBarangMasuk">
    @csrf
    <x-input-with-icon icon="ti ti-file-text" label="No Bukti" name="no_bukti" required />

    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" value="{{ date('Y-m-d') }}"
        datepicker="flatpickr-date" required />

    <div class="divider text-start">
        <div class="divider-text">Detail Barang</div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <select class="form-select select2Barang" id="kode_barang">
                <option value="">Pilih Barang</option>
                @php

                @endphp
                @foreach ($barang as $b)
                    <option value="{{ $b->kode_barang }}">
                        {{ $b->kode_barang }} | {{ strtoupper($b->nama_barang) }}
                        ({{ strtoupper($b->satuan) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3">
            <x-input-with-icon icon="ti ti-box" label="Jumlah" name="jumlah" numberFormat="true" align="right" />
        </div>

        <div class="col-lg-1">
            <button class="btn btn-primary" id="btnTambah">
                <i class="ti ti-plus"></i>
            </button>
        </div>
    </div>

    <x-input-with-icon icon="ti ti-notes" label="Keterangan" name="keterangan" />

    <div class="table-responsive mt-3">
        <table class="table table-bordered" id="tableDetail">
            <thead class="table-dark">
                <tr>
                    <th width="15%">Kode</th>
                    <th>Nama Barang</th>
                    <th width="10%">Jumlah</th>
                    <th>Keterangan</th>
                    <th width="5%">#</th>
                </tr>
            </thead>
            <tbody id="loadDetail"></tbody>
        </table>
    </div>

    <div class="form-check mt-3 mb-2">
        <input class="form-check-input agreement" type="checkbox" id="agree">
        <label class="form-check-label">Yakin akan disimpan?</label>
    </div>

    <div class="form-group" id="saveButton">
        <button class="btn btn-success w-100">
            <i class="ti ti-send me-1"></i> Simpan
        </button>
    </div>

</form>
<script>
    $(function() {

        const form = $('#formBarangMasuk');

        // Flatpickr
        $('.flatpickr-date').flatpickr({
            dateFormat: "Y-m-d"
        });

        // Select2
        $('.select2Barang').select2({
            placeholder: 'Pilih Barang',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#loadmodal')
        });

        // Hide save button
        $('#saveButton').hide();

        $('.agreement').on('change', function() {
            $('#saveButton').toggle(this.checked);
        });

        function resetInput() {
            $('#kode_barang').val('').trigger('change');
            $('#jumlah').val('');
            $('#keterangan').val('');
        }

        $('#btnTambah').on('click', function(e) {
            e.preventDefault();

            const kode = $('#kode_barang').val();
            const nama = $('#kode_barang option:selected').text();
            const jumlah = $('#jumlah').val();
            const ket = $('#keterangan').val();

            if (!kode) {
                Swal.fire('Oops!', 'Barang wajib dipilih', 'warning');
                return;
            }

            if (!jumlah) {
                Swal.fire('Oops!', 'Jumlah wajib diisi', 'warning');
                return;
            }

            if ($('#row_' + kode).length > 0) {
                Swal.fire('Oops!', 'Barang sudah ditambahkan', 'warning');
                return;
            }

            let row = `
            <tr id="row_${kode}">
                <td>
                    ${kode}
                    <input type="hidden" name="kode_barang[]" value="${kode}">
                </td>
                <td>${nama}</td>
                <td class="text-end">
                    ${jumlah}
                    <input type="hidden" name="jumlah[]" value="${jumlah}">
                </td>
                <td>
                    ${ket}
                    <input type="hidden" name="keterangan[]" value="${ket}">
                </td>
                <td class="text-center">
                    <a href="#" class="btnRemove" data-id="${kode}">
                        <i class="ti ti-trash text-danger"></i>
                    </a>
                </td>
            </tr>
        `;

            $('#loadDetail').append(row);
            resetInput();
        });

        $('#loadDetail').on('click', '.btnRemove', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            $('#row_' + id).remove();
        });

        // Submit validation
        form.on('submit', function() {

            if ($('#loadDetail tr').length === 0) {
                Swal.fire('Oops!', 'Detail barang masih kosong', 'warning');
                return false;
            }

            return true;
        });

    });
</script>
