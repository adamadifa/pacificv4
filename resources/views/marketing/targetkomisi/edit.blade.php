<style>
    .table-modal {
        height: auto;
        max-height: 550px;
        overflow-y: scroll;

    }
</style>
<form action="{{ route('targetkomisi.update', Crypt::encrypt($targetkomisi->kode_target)) }}" method="POST" id="formTargetkomisi">
    @method('PUT')
    @csrf
    <div class="row">
        <div class="co-12">

            <div class="row">
                @hasanyrole($roles_show_cabang)
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                            select2="select2Kodecabang" showKey="true" upperCase="true" selected="{{ $targetkomisi->kode_cabang }}" />
                    </div>

                    <div class="col-lg-3 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">Bulan</option>
                                @foreach ($list_bulan as $d)
                                    <option {{ $targetkomisi->bulan == $d['kode_bulan'] ? 'selected' : '' }} value="{{ $d['kode_bulan'] }}">
                                        {{ $d['nama_bulan'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12 col-md-12">
                        <div class="form-group mb-3">
                            <select name="tahun" id="tahun" class="form-select">
                                <option value="">Tahun</option>
                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                    <option {{ $targetkomisi->tahun == $t ? 'selected' : '' }} value="{{ $t }}">{{ $t }}</option>
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
                <table class="table table-bordered  table-hover" style="width: 600%">
                    <thead class="table-dark">
                        <tr>
                            <th rowspan="4" align="middle" style="width: 1%">Kode</th>
                            <th rowspan="4" align="middle" style="width: 1%">NIK</th>
                            <th rowspan="4" align="middle" style="width: 3%">Salesman</th>
                            <th rowspan="4" align="middle" style="width: 2%">Masa Kerja</th>
                            <th colspan="{{ count($produk) * 10 }}" class="text-center">Produk</th>
                        </tr>
                        <tr>
                            @foreach ($produk as $d)
                                <th class="text-center" colspan="10">
                                    {{ $d->kode_produk }}
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($produk as $d)
                                <th rowspan="2">AVG</th>
                                <th colspan="3">Realisasi</th>
                                <th rowspan="2">Last</th>
                                <th colspan="5" class="text-center">Target</th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($produk as $d)
                                <th>{{ getMonthName2($lasttigabulan) }}</th>
                                <th>{{ getMonthName2($lastduabulan) }}</th>
                                <th>{{ getMonthName2($lastbulan) }}</th>
                                <th>AWAL</th>
                                <th style="width: 1%">RSM</th>
                                <th style="width: 1%">GM</th>
                                <th style="width: 1%">DIRUT</th>
                                <th style="width: 1%">AKHIR</th>
                            @endforeach



                        </tr>

                    </thead>
                    <tbody id="gettargetsalesman"></tbody>
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
        const form = $("#formTargetkomisi");

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

        function gettargetsalesman() {
            $.ajax({
                type: 'POST',
                url: "{{ route('targetkomisi.gettargetsalesmanedit') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_target: "{{ $targetkomisi->kode_target }}",
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    form.find("#gettargetsalesman").html(respond);
                }
            });
        }

        gettargetsalesman();
        form.find("#kode_cabang").change(function() {
            gettargetsalesman();
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
