<form action="{{ route('suratjalanangkutan.update', Crypt::encrypt($suratjalanangkutan->no_dok)) }}" id="formSuratjalanangkutan" method="POST">
    @method('PUT')
    @csrf
    <x-input-with-icon label="No. dok" icon="ti ti-barcode" name="no_dok" value="{{ $suratjalanangkutan->no_dok }}" />
    <x-select label="Tujuan" name="kode_tujuan" :data="$tujuan" key="kode_tujuan" textShow="tujuan" upperCase="true"
        selected="{{ $suratjalanangkutan->kode_tujuan }}" select2="select2Kodetujuan" />
    <x-input-with-icon label="No. Polisi" name="no_polisi" icon="ti ti-truck" value="{{ $suratjalanangkutan->no_polisi }}" />
    <x-input-with-icon label="Tarif" name="tarif" icon="ti ti-moneybag" value="{{ formatAngka($suratjalanangkutan->tarif) }}" align="right"
        money="true" />
    <x-input-with-icon label="Tepung" name="tepung" icon="ti ti-moneybag" value="{{ formatAngka($suratjalanangkutan->tepung) }}" align="right"
        money="true" />
    <x-input-with-icon label="BS" name="bs" icon="ti ti-moneybag" value="{{ formatAngka($suratjalanangkutan->bs) }}" align="right"
        money="true" />
    <x-select label="Angkutan" name="kode_angkutan" :data="$angkutan" key="kode_angkutan" textShow="nama_angkutan" upperCase="true"
        selected="{{ $suratjalanangkutan->kode_angkutan }}" select2="select2Kodeangkutan" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formSuratjalanangkutan");
        $(".money").maskMoney();
        const select2Kodeangkutan = $('.select2Kodeangkutan');
        if (select2Kodeangkutan.length) {
            select2Kodeangkutan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Angkutan',
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodetujuan = $('.select2Kodetujuan');
        if (select2Kodetujuan.length) {
            select2Kodetujuan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Tujuan',
                    dropdownParent: $this.parent()
                });
            });
        }

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
                <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Loading..
            `);
        }
        form.submit(function(e) {
            const no_dok = form.find("#no_dok").val();
            const kode_tujuan = form.find("#kode_tujuan").val();
            const no_polisi = form.find("#no_polisi").val();
            const tarif = form.find("#tarif").val();
            const tepung = form.find("#tepung").val();
            const bs = form.find("#bs").val();
            const kode_angkutan = form.find("#kode_angkutan").val();
            if (no_dok == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Dok Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_dok").focus();
                    },
                });
                return false;
            } else if (kode_tujuan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tujuan Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_tujuan").focus();
                    },
                });
                return false;
            } else if (no_polisi == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Polisi Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_polisi").focus();
                    },
                });
                return false;
            } else if (tarif == "" || tarif === 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Tarif Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tarif").focus();
                    },
                });
                return false;
            } else if (kode_angkutan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "sAngkutan Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_angkutan").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
