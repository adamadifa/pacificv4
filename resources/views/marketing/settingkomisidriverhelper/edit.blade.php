<form action="{{ route('settingkomisidriverhelper.update', ['kode_komisi' => Crypt::encrypt($settingkomisidriverhelper->kode_komisi)]) }}"
    method="POST" id="formSettingkomisi">
    <div class="row">
        <div class="co-12">
            @csrf
            @method('PUT')
            <div class="row">
                @hasanyrole($roles_show_cabang)
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                            select2="select2Kodecabang" showKey="true" upperCase="true" :selected="$settingkomisidriverhelper->kode_cabang" />
                    </div>

                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">Bulan</option>
                                @foreach ($list_bulan as $d)
                                    <option value="{{ $d['kode_bulan'] }}" {{ $settingkomisidriverhelper->bulan == $d['kode_bulan'] ? 'selected' : '' }}>
                                        {{ $d['nama_bulan'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <select name="tahun" id="tahun" class="form-select">
                                <option value="">Tahun</option>
                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                    <option value="{{ $t }}" {{ $settingkomisidriverhelper->tahun == $t ? 'selected' : '' }}>
                                        {{ $t }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                @else
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">Bulan</option>
                                @foreach ($list_bulan as $d)
                                    <option value="{{ $d['kode_bulan'] }}" {{ $settingkomisidriverhelper->bulan == $d['kode_bulan'] ? 'selected' : '' }}>
                                        {{ $d['nama_bulan'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <select name="tahun" id="tahun" class="form-select">
                                <option value="">Tahun</option>
                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                    <option value="{{ $t }}" {{ $settingkomisidriverhelper->tahun == $t ? 'selected' : '' }}>
                                        {{ $t }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                @endhasanyrole
            </div>
            <x-input-with-icon icon="ti ti-moneybag" label="Komisi Salesman" name="komisi_salesman" align="right" money="true" :value="formatAngka($settingkomisidriverhelper->komisi_salesman)" />
            <x-input-with-icon icon="ti ti-file-description" label="Qty Flat" name="qty_flat" align="right" :value="formatAngka($settingkomisidriverhelper->qty_flat)" />
            <x-input-with-icon icon="ti ti-file-description" label="UMK" name="umk" align="right" :value="formatAngka($settingkomisidriverhelper->umk)" />
            <x-input-with-icon icon="ti ti-percentage" label="Persentase" name="persentase" align="right" :value="formatAngka($settingkomisidriverhelper->persentase)" />
        </div>
    </div>

    <div class="form-group" id="saveButton">
        <button class="btn btn-primary w-100" type="submit" id="btnSimpan">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Simpan
        </button>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formSettingkomisi");
        $(".money").maskMoney();
        // form.find("#saveButton").hide();

        // form.find('.agreement').change(function() {
        //     if (this.checked) {
        //         form.find("#saveButton").show();
        //     } else {
        //         form.find("#saveButton").hide();
        //     }
        // });


        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    dropdownParent: $this.parent(),

                });
            });
        }

        // function getratiodriverhelper() {
        //     var kode_cabang = form.find("#kode_cabang").val();
        //     //alert(selected);
        //     $.ajax({
        //         type: 'POST',
        //         url: "{{ route('ratiodriverhelper.getratiodriverhelper') }}",
        //         data: {
        //             _token: "{{ csrf_token() }}",
        //             kode_cabang: kode_cabang
        //         },
        //         cache: false,
        //         success: function(respond) {
        //             console.log(respond);
        //             form.find("#getratiodriverhelper").html(respond);
        //         }
        //     });
        // }

        // getratiodriverhelper();
        // form.find("#kode_cabang").change(function() {
        //     getratiodriverhelper();
        // });


        form.submit(function() {
            const kode_cabang = form.find("#kode_cabang").val();
            const bulan = form.find("#bulan").val();
            const tahun = form.find("#tahun").val();
            const komisi_salesman = form.find("#komisi_salesman").val();
            const qty_flat = form.find("#qty_flat").val();
            const umk = form.find("#umk").val();
            const persentase = form.find("#persentase").val();

            if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kode_cabang").focus();
                    },
                });

                return false;
            } else if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#bulan").focus();
                    },
                });

                return false;
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#tahun").focus();
                    },
                });
                return false;
            } else if (komisi_salesman == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Komisi Salesman Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#komisi_salesman").focus();
                    }
                })
                return false;
            } else if (qty_flat == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Qty Flat Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#qty_flat").focus();
                    }
                });
                return false;
            } else if (umk == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "UMK Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#umk").focus();
                    }
                });
                return false;
            } else if (persentase == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Persentase Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#persentase").focus();
                    }
                });
                return false;
            } else {
                $("#btnSimpan").attr("disabled", true);
                $("#btnSimpan").html(`
                <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Loading..`);

            }
        });
    });
</script>
