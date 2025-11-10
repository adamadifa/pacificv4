@php
    use Jenssegers\Agent\Agent;
    $agent = new Agent();
    $isMobile = $agent->isMobile();
@endphp


<form action="{{ route('laporanmarketing.cetaklhp') }}" method="POST" id="formlhp" target="{{ $isMobile ? '_self' : '_blank' }}">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_lhp" class="form-select select2Kodecabanglhp">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole
    <div class="form-group mb-3">
        @hasanyrole('salesman')
            <input type="hidden" name="kode_salesman" value="{{ auth()->user()->kode_salesman }}">
        @else
            <select name="kode_salesman" id="kode_salesman_lhp" class="select2Kodesalemanlhp form-select">
            </select>
        @endhasanyrole

    </div>

    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" />
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonlhp">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonlhp">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formlhp = $("#formlhp");
            const select2Kodecabanglhp = $(".select2Kodecabanglhp");
            if (select2Kodecabanglhp.length) {
                select2Kodecabanglhp.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalemanlhp = $(".select2Kodesalemanlhp");
            if (select2Kodesalemanlhp.length) {
                select2Kodesalemanlhp.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih  Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }



            function getsalesmanbyCabanglhp() {
                var kode_cabang = formlhp.find("#kode_cabang_lhp").val();
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
                        formlhp.find("#kode_salesman_lhp").html(respond);
                    }
                });
            }



            getsalesmanbyCabanglhp();
            formlhp.find("#kode_cabang_lhp").change(function(e) {
                getsalesmanbyCabanglhp();
            });







            formlhp.submit(function(e) {

                const kode_cabang = formlhp.find('#kode_cabang_lhp').val();
                const kode_salesman = formlhp.find('#kode_salesman_lhp').val();
                const tanggal = formlhp.find('#tanggal').val();
                if (kode_cabang == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Cabang Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#kode_cabang_lhp").focus();
                        },
                    });
                    return false;
                } else if (kode_salesman == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Salesman Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#kode_salesman_lhp").focus();
                        },
                    })
                } else if (tanggal == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Sampai Tanggal Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#sampai").focus();
                        },
                    });
                    return false;
                }
            })
        });
    </script>
@endpush
