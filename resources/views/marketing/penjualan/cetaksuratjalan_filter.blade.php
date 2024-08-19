<form action="{{ route('penjualan.cetaksuratjalanrange') }}" target="_blank" method="POST" id="formCetakfaktur">
    @csrf
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon label="Dari" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon label="Sampai" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" />
        </div>
    </div>
    @hasanyrole($roles_show_cabang)
        <x-select label="Semua Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
            select2="select2Kodecabang" />
    @endrole
    <div class="form-group mb-3">
        <select name="kode_salesman" id="kode_salesman" class="form-select select2Kodesalesman">
            <option value="">Salesman</option>
        </select>
    </div>
    {{-- <x-input-with-icon label="Kode Pelanggan" name="kode_pelanggan" icon="ti ti-barcode" /> --}}
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100"><i class="ti ti-printer me-1"></i> Cetak Faktur</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {

        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }, ]
        });
        const form = $("#formCetakfaktur");
        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodesalesman = $('.select2Kodesalesman');
        if (select2Kodesalesman.length) {
            select2Kodesalesman.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Salesman',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function getsalesmanbyCabang() {
            var kode_cabang = form.find("#kode_cabang").val();
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    form.find("#kode_salesman").html(respond);
                }
            });
        }
        form.find("#kode_cabang").change(function(e) {
            getsalesmanbyCabang();
        });

        getsalesmanbyCabang();
        form.submit(function() {
            const dari = $(this).find("#dari").val();
            const sampai = $(this).find("#sampai").val();
            const start = new Date(dari);
            const end = new Date(sampai);
            if (dari == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Dari Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#dari").focus();
                    },
                });
                return false;
            } else if (sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Sampai Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#sampai").focus();
                    },
                });
                return false;
            } else if (start.getTime() > end.getTime()) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Tidak Valid !, Periode Sampai Harus Lebih Akhir dari Periode Dari',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#sampai").focus();
                    },
                });
                return false;
            }
        });
    });
</script>
