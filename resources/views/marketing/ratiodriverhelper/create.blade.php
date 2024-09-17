<form action="{{ route('ratiodriverhelper.store') }}" method="POST" id="formRatiodriverhelper">
    <div class="row">
        <div class="co-12">
            @csrf
            <div class="row">
                @hasanyrole($roles_show_cabang)
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                            select2="select2Kodecabang" showKey="true" upperCase="true" />
                    </div>

                    <div class="col-lg-3 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">Bulan</option>
                                @foreach ($list_bulan as $d)
                                    <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <select name="tahun" id="tahun" class="form-select">
                                <option value="">Tahun</option>
                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                @else
                    <div class="col-lg-6 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">Bulan</option>
                                @foreach ($list_bulan as $d)
                                    <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <select name="tahun" id="tahun" class="form-select">
                                <option value="">Tahun</option>
                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                @endhasanyrole
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode</th>
                            <th style="width: 40%">Nama Driver / Helper</th>
                            <th>Posisi</th>
                            <th>Ratio Default</th>
                            <th>Ratio Helper</th>
                        </tr>
                    </thead>
                    <tbody id="getratiodriverhelper"></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="form-check mt-3 mb-3">
                <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox" value="" id="defaultCheck3">
                <label class="form-check-label" for="defaultCheck3"> Yakin Akan Disimpan ? </label>
            </div>
            <div class="form-group" id="saveButton">
                <button class="btn btn-primary w-100" type="submit" id="btnSimpan">
                    <ion-icon name="send-outline" class="me-1"></ion-icon>
                    Submit
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formRatiodriverhelper");

        form.find("#saveButton").hide();

        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").show();
            } else {
                form.find("#saveButton").hide();
            }
        });


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

        function getratiodriverhelper() {
            var kode_cabang = form.find("#kode_cabang").val();
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: "{{ route('ratiodriverhelper.getratiodriverhelper') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    form.find("#getratiodriverhelper").html(respond);
                }
            });
        }

        getratiodriverhelper();
        form.find("#kode_cabang").change(function() {
            getratiodriverhelper();
        });


        form.submit(function() {
            const kode_cabang = form.find("#kode_cabang").val();
            const bulan = form.find("#bulan").val();
            const tahun = form.find("#tahun").val();

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
            }
        });
    });
</script>
