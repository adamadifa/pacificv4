<form action="{{ route('pembelian.storeapprovemtc', Crypt::encrypt($pembelian->no_bukti)) }}" id="formApprovemtc" method="POST">
    @csrf
    <div class="row mb-3">
        <div class="col">
            <table class="table">
                <tr>
                    <th>No. Bukti</th>
                    <td class="text-end">{{ $pembelian->no_bukti }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($pembelian->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Supplier</th>
                    <td class="text-end">{{ $pembelian->nama_supplier }}</td>
                </tr>
                <tr>
                    <th>Asal Ajuan</th>
                    <td class="text-end">
                        {{ array_key_exists($pembelian->kode_asal_pengajuan, $asal_pengajuan) ? $asal_pengajuan[$pembelian->kode_asal_pengajuan] : 'UNDIFINED' }}
                    </td>
                </tr>
                <tr>
                    <th>PPN</th>
                    <td class="text-end">{!! $pembelian->ppn == '1' ? '<i class="ti ti-checks text-success"></i>' : '<i class="ti ti-square-rounded-x text-danger"></i>' !!} </td>
                </tr>
            </table>

        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <table class="table table-bordered  table-hover">
                <thead class="table-dark">
                    <tr>
                        <th colspan="8">Data Pembelian</th>
                    </tr>
                    <tr>
                        <th style="width: 15%">Kode</th>
                        <th style="width: 35%">Nama Barang</th>
                        <th style="width: 40%">Keterangan</th>
                        <th style="width: 10%">Qty</th>
                        @can('pembelian.harga')
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th>Peny</th>
                            <th>Total</th>
                        @endcan

                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_pembelian = 0;
                    @endphp
                    @foreach ($detail as $d)
                        @php
                            $subtotal = $d->jumlah * $d->harga;
                            $total = $subtotal + $d->penyesuaian;
                            $total_pembelian += $total;
                            $bg = '';
                            if (!empty($d->kode_cr)) {
                                $bg = 'bg-info text-white';
                            }
                        @endphp
                        <tr class="{{ $bg }}">
                            <td>{{ $d->kode_barang }}</td>
                            <td>{{ textCamelCase($d->nama_barang) }}</td>
                            <td>{{ textCamelCase($d->keterangan) }}</td>
                            <td class="text-center">{{ formatAngkaDesimal($d->jumlah) }}</td>
                            @can('pembelian.harga')
                                <td class="text-end">{{ formatAngkaDesimal($d->harga) }}</td>
                                <td class="text-end">{{ formatAngkaDesimal($subtotal) }}</td>
                                <td class="text-end">{{ formatAngkaDesimal($d->penyesuaian) }}</td>
                                <td class="text-end">{{ formatAngkaDesimal($total) }}</td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
                @can('pembelian.harga')
                    <tfoot class="table-dark">
                        <tr>
                            <td colspan="7">TOTAL</td>
                            <td class="text-end">{{ formatAngkaDesimal($total_pembelian) }}</td>
                        </tr>
                    </tfoot>
                @endcan
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-thumb-up me-1"></i>Terima</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formApprovemtc");
        $(".flatpickr-date").flatpickr();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        form.submit(function(e) {
            const tanggal = form.find("#tanggal").val();
            if (tanggal == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });

                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
