<style>
    .table-modal {
        height: auto;
        max-height: 520px;
        overflow-y: auto;
        border-radius: 8px;
        border: 1px solid #e6e8eb;
    }
    .table-target-detail {
        font-family: inherit;
    }
    .table-target-detail th {
        font-size: 0.72rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        vertical-align: middle !important;
        padding: 8px 10px !important;
        font-weight: 600;
    }
    .table-target-detail td {
        font-size: 0.78rem;
        padding: 6px 10px !important;
        vertical-align: middle !important;
    }
    .cell-avg {
        background-color: rgba(40, 199, 111, 0.08) !important;
        color: #28c76f !important;
        font-weight: 600;
    }
    .cell-realisasi {
        background-color: rgba(0, 207, 232, 0.08) !important;
        color: #00cfe8 !important;
        font-weight: 500;
    }
    .cell-last-target {
        background-color: rgba(115, 103, 240, 0.08) !important;
        color: #7367f0 !important;
        font-weight: 600;
    }
    .cell-target-akhir {
        background-color: rgba(253, 172, 52, 0.08) !important;
        color: #fdac34 !important;
        font-weight: 600;
    }
    .noborder-form {
        border: 1px solid #d4d8dd !important;
        border-radius: 4px !important;
        padding: 4px 8px !important;
        background-color: #fff !important;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        width: 70px;
    }
    .noborder-form:focus {
        border-color: #7367f0 !important;
        box-shadow: 0 0 0 0.2rem rgba(115, 103, 240, 0.25) !important;
        outline: 0 !important;
    }
    .noborder-form[readonly] {
        background-color: #f8f9fa !important;
        border-color: #e9ecef !important;
        color: #6c757d !important;
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
                            select2="select2Kodecabang" showKey="true" upperCase="true" selected="{{ $targetkomisi->kode_cabang }}" hideLabel="true" />
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
            <div class="table-responsive table-modal">
                <table class="table table-bordered table-hover table-target-detail" style="width: 600%">
                    <thead class="table-dark">
                        <tr>
                            <th rowspan="4" align="middle" class="text-center" style="width: 1%">Kode</th>
                            <th rowspan="4" align="middle" class="text-center" style="width: 1%">NIK</th>
                            <th rowspan="4" align="middle" class="text-center" style="width: 3%">Salesman</th>
                            <th rowspan="4" align="middle" class="text-center" style="width: 2%">Masa Kerja</th>
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
                                <th rowspan="2" class="text-center cell-avg">AVG</th>
                                <th colspan="3" class="text-center cell-realisasi">Realisasi</th>
                                <th rowspan="2" class="text-center cell-last-target">Last</th>
                                <th colspan="5" class="text-center">Target</th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($produk as $d)
                                <th class="text-center cell-realisasi">{{ getMonthName2($lasttigabulan) }}</th>
                                <th class="text-center cell-realisasi">{{ getMonthName2($lastduabulan) }}</th>
                                <th class="text-center cell-realisasi">{{ getMonthName2($lastbulan) }}</th>
                                <th class="text-center">AWAL</th>
                                <th style="width: 1%" class="text-center">RSM</th>
                                <th style="width: 1%" class="text-center">GM</th>
                                <th style="width: 1%" class="text-center">DIRUT</th>
                                <th style="width: 1%" class="text-center cell-target-akhir">AKHIR</th>
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
                success: function(respond) {
                    console.log(respond);
                    form.find("#gettargetsalesman").html(respond);
                    $(".table-modal").freezeTable({
                        'scrollable': true,
                        'columnNum': 4,
                        'shadow': true,
                    });
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
                // Build JSON of target data
                var targetData = [];
                $(".table-modal > table > tbody > tr").each(function() {
                    var row = $(this);
                    var kode_salesman = row.find("input[name='kode_salesman[]']").val();
                    if (!kode_salesman) return;
                    
                    var salesmanData = {
                        kode_salesman: kode_salesman,
                        products: {}
                    };
                    
                    @foreach($produk as $p)
                        salesmanData.products['{{ $p->kode_produk }}'] = {
                            target_awal: row.find(".t_awal_{{ $p->kode_produk }}").val(),
                            rsm: row.find(".t_rsm_{{ $p->kode_produk }}").val(),
                            gm: row.find(".t_gm_{{ $p->kode_produk }}").val(),
                            dirut: row.find(".t_dirut_{{ $p->kode_produk }}").val(),
                            akhir: row.find(".target_akhir_{{ $p->kode_produk }}").val()
                        };
                    @endforeach
                    
                    targetData.push(salesmanData);
                });

                // Append hidden field
                $("<input>").attr({
                    type: "hidden",
                    name: "target_data",
                    value: JSON.stringify(targetData)
                }).appendTo(form);

                // Strip names from other inputs to avoid max_input_vars limit
                form.find("input").not("[name='target_data'], [name='_token'], [name='_method'], [name='bulan'], [name='tahun'], [name='kode_cabang']").removeAttr("name");

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
