<form action="{{ route('ajuanlimit.store') }}" aria-autocomplete="false" id="formAjuanlimit" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="cek_foto_toko" id="cek_foto_toko">
    <input type="hidden" name="cek_foto_owner" id="cek_foto_owner">
    <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-barcode" label="Auto" disabled="true" name="no_pengajuan" />
            <x-input-with-icon icon="ti ti-calendar" label="Tanggal Pengajuan" name="tanggal" datepicker="flatpickr-date" />
            <div class="divider">
                <div class="divider-text">Data Pelanggan</div>
            </div>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="kode_pelanggan" id="kode_pelanggan" readonly placeholder="Kode Pelanggan"
                    aria-label="Kode Pelanggan" aria-describedby="kode_pelanggan_search">
                <a class="btn btn-primary waves-effect" id="kode_pelanggan_search"><i class="ti ti-search text-white"></i></a>
            </div>

            <x-input-with-icon icon="ti ti-credit-card" label="NIK" name="nik" />
            <x-input-with-icon icon="ti ti-user" label="Nama Pelanggan" name="nama_pelanggan" />
            <x-textarea label="Alamat Pelanggan" name="alamat_pelanggan" />
            <x-input-with-icon icon="ti ti-phone" label="No. HP" name="no_hp_pelanggan" />
            <x-input-with-icon icon="ti ti-building" label="Cabang" name="nama_cabang" disabled="true" />
            <x-input-with-icon icon="ti ti-user" label="Salesman" name="nama_salesman" disabled="true" />




            <div class="form-group mb-3">
                <select name="hari" id="hari" class="form-select">
                    <option value="">Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                    <option value="Minggu">Minggu</option>
                </select>
            </div>
            <x-input-with-icon icon="ti ti-map-pin" label="Titik Koordinat" name="lokasi" />
            <x-input-with-icon icon="ti ti-moneybag" label="Jumlah Ajuan Limit" name="jumlah" align="right" money="true" />

            <div class="form-group mb-3">
                <select name="ljt" id="ljt" class="form-select">
                    <option value="">LJT</option>
                    <option value="14">14</option>
                    <option value="30">30</option>
                    <option value="45">45</option>
                </select>
            </div>
            <div class="row">
                <div class="col text-center">
                    <div class="card h-100">
                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="user image" class="card-img-top"
                            style="height:100px; object-fit:cover" id="foto">
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="user image" class="card-img-top"
                            style="height:100px; object-fit:cover" id="foto_owner">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-1 col-md-12 col-sm-12">
            <div class="divider divider-vertical">
                <div class="divider-text">
                    <i class="ti ti-crown"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">

            <div class="form-group mb-3">
                <select name="kepemilikan" id="kepemilikan" class="form-select">
                    <option value="">Kepemilikan</option>
                    <option value="SW">Sewa</option>
                    <option value="MS">Milik Sendiri</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="lama_berjualan" id="lama_berjualan" class="form-select">
                    <option value="">Lama Usaha</option>
                    <option value="LU01">
                        < 2 Tahun</option>
                    <option value="LU02">2 - 5 Tahun</option>
                    <option value="LU03">> 5 Tahun</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="status_outlet" id="status_outlet" class="form-select">
                    <option value="">Status Outlet</option>
                    <option value="NO">New Outlet</option>
                    <option value="EO">Existing Outlet</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="type_outlet" id="type_outlet" class="form-select">
                    <option value="">Type Outlet</option>
                    <option value="GR">Grosir</option>
                    <option value="RT">Retail</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="cara_pembayaran" id="cara_pembayaran" class="form-select">
                    <option value="">Cara Pembayaran</option>
                    <option value="BT">Bank Transfer</option>
                    <option value="AC">Advance Cash</option>
                    <option value="CQ">Cheque</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <select name="lama_langganan" id="lama_langganan" class="form-select">
                    <option value="">Lama Langganan</option>
                    <option value="LL01">
                        < 2 Tahun</option>
                    <option value="LL02">> 2 Tahun</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="jaminan" id="jaminan" class="form-select">
                    <option value="">Jaminan</option>
                    <option value="1">Ada</option>
                    <option value="0">Tidak Ada</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <select name="histori_transaksi" id="histori_transaksi" class="form-select">
                    <option value="">Histori Pembayaran 6 Bulan Terakhir</option>
                    <option value="HT01">
                        < 14 Hari</option>
                    <option value="HT02"> = 14 Hari</option>
                    <option value="HT03"> > 14 Hari</option>
                </select>
            </div>
            <x-input-with-icon label="Terakhir Top UP" name="topup_terakhir" icon="ti ti-calendar" datepicker="flatpickr-date" />
            <input type="hidden" id="lama_topup" name="lama_topup">
            <span id="usia_topup" class="mb-3"></span>
            <x-input-with-icon icon="ti ti-moneybag" label="Omset Toko" name="omset_toko" align="right" money="true" />
            <div class="divider">
                <div class="divider-text">Faktur Belum Lunas</div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No. Faktur</th>
                                <th>Sisa Piutang</th>
                            </tr>
                        </thead>
                        <tbody id="loadlistfakturkredit"></tbody>
                    </table>
                    <input type="hidden" id="jml_faktur" name="jml_faktur">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <input type="hidden" name="skor" id="skor">
                    <table class="table">
                        <tr>
                            <td>Skor</td>
                            <td id="total_score">

                            </td>
                        </tr>
                        <tr>
                            <td>Rekomendasi</td>
                            <td id="rekomendasi"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <x-textarea label="Uraian Analisa" name="uraian_analisa" />
            <div class="form-group mb-3">
                <label>Referensi</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input smm" name="referensi[]" value="smm" type="checkbox" id="smm">
                    <label class="form-check-label" for="smm"> SMM </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input rsm" name="referensi[]" value="rsm" type="checkbox" id="rsm">
                    <label class="form-check-label" for="rsm"> RSM </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input gm" name="referensi[]" value="gm" type="checkbox" id="gm">
                    <label class="form-check-label" for="gm"> GM </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input direktur" name="referensi[]" value="direktur" type="checkbox" id="direktur">
                    <label class="form-check-label" for="direktur"> DIREKTUR </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input external" name="referensi[]" value="external" type="checkbox" id="external">
                    <label class="form-check-label" for="external"> External </label>
                </div>
            </div>
            <div class="row" id="ket_ref">
                <div class="col">
                    <x-input-with-icon icon="ti ti-user" label="Keterangan Referensi" name="ket_referensi" />
                </div>
            </div>

            <div class="form-group">
                <button class="btn btn-primary w-100" type="submit" id="submitAjuanlimit">
                    <ion-icon name="send-outline" class="me-1"></ion-icon>
                    Submit
                </button>
            </div>
        </div>
    </div>

</form>

<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/pages/ajuanlimit.js') }}"></script> --}}
<script>
    $(function() {
        const form = $("#formAjuanlimit");
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney();

        function toggleKeteranganReferensi() {
            if ($("#external").is(':checked')) {
                $("#ket_ref").show();
            } else {
                $("#ket_ref").hide();
            }
        }

        toggleKeteranganReferensi();
        $("#external").change(toggleKeteranganReferensi);

        function convertToRupiah(number) {
            if (number) {
                var rupiah = "";
                var numberrev = number
                    .toString()
                    .split("")
                    .reverse()
                    .join("");
                for (var i = 0; i < numberrev.length; i++)
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
                return (
                    rupiah
                    .split("", rupiah.length - 1)
                    .reverse()
                    .join("")
                );
            } else {
                return number;
            }
        }
        //Get Pelanggan
        function getPelanggan(kode_pelanggan) {
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPelanggan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    //fill data to form
                    const status_aktif_pelanggan = response.data.status_aktif_pelanggan;
                    if (status_aktif_pelanggan === '0') {
                        Swal.fire({
                            title: "Oops!",
                            text: "Pelanggan Tidak Dapat Bertransaksi, Silahkan Hubungi Admin Untuk Mengaktifkan Pelanggan !",
                            icon: "warning",
                            showConfirmButton: true,
                        });
                    } else {
                        form.find("#kode_pelanggan").val(response.data.kode_pelanggan);
                        form.find("#nik").val(response.data.nik);
                        form.find("#nama_pelanggan").val(response.data.nama_pelanggan);
                        form.find("#alamat_pelanggan").val(response.data.alamat_pelanggan);
                        form.find("#no_hp_pelanggan").val(response.data.no_hp_pelanggan);
                        form.find("#nama_cabang").val(response.data.nama_cabang);
                        form.find("#nama_salesman").val(response.data.nama_salesman);
                        form.find("#hari").val(response.data.hari);
                        form.find("#lokasi").val(response.data.latitude + "," + response.data
                            .longitude);
                        form.find("#ljt").val(response.data.ljt);
                        form.find("#kepemilikan").val(response.data.kepemilikan);
                        form.find("#lama_berjualan").val(response.data.lama_berjualan);
                        form.find("#type_outlet").val(response.data.type_outlet);
                        form.find("#status_outlet").val(response.data.status_outlet);
                        form.find("#cara_pembayaran").val(response.data.cara_pembayaran);
                        form.find("#lama_langganan").val(response.data.lama_langganan);
                        form.find("#jaminan").val(response.data.jaminan);
                        form.find("#omset_toko").val(convertToRupiah(response.data.omset_toko));
                        let fileFoto = response.data.foto;
                        let fileFotoowner = response.data.foto_owner;
                        form.find("#cek_foto_toko").val(response.data.foto);
                        form.find("#cek_foto_owner").val(response.data.foto_owner);
                        checkFileExistence(fileFoto);
                        checkFileExistenceOwner(fileFotoowner);
                        $('#modalPelanggan').modal('hide');
                        getlistFakturkredit(response.data.kode_pelanggan);
                        loadSkor();
                    }

                }
            });
        }

        function checkFileExistence(fileFoto) {
            var xhr = new XMLHttpRequest();
            var filePath = '/pelanggan/' + fileFoto;
            var foto = "{{ url(Storage::url('pelanggan')) }}/" + fileFoto;
            var fotoDefault = "{{ asset('assets/img/elements/2.jpg') }}";
            console.log(foto);
            xhr.open('GET', '/pelanggan/cekfotopelanggan?file=' + filePath, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.exists) {
                            console.log('File exists');
                            $("#foto").attr("src", foto);
                        } else {
                            console.log('File does not exist');
                            $("#foto").attr("src", fotoDefault);
                        }
                    } else {
                        console.error('Error checking file existence:', xhr.statusText);
                    }
                }
            };
            xhr.send();
        }


        function checkFileExistenceOwner(fileFoto) {
            var xhr = new XMLHttpRequest();
            var filePath = '/pelanggan/owner/' + fileFoto;
            var foto = "{{ url(Storage::url('pelanggan/owner')) }}/" + fileFoto;
            var fotoDefault = "{{ asset('assets/img/elements/2.jpg') }}";
            console.log(foto);
            xhr.open('GET', '/pelanggan/cekfotopelanggan?file=' + filePath, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.exists) {
                            console.log('File exists');
                            $("#foto_owner").attr("src", foto);
                        } else {
                            console.log('File does not exist');
                            $("#foto_owner").attr("src", fotoDefault);
                        }
                    } else {
                        console.error('Error checking file existence:', xhr.statusText);
                    }
                }
            };
            xhr.send();
        }

        function getlistFakturkredit(kode_pelanggan) {
            $.ajax({
                type: 'GET',
                url: `/pelanggan/${kode_pelanggan}/getlistfakturkredit`,
                cache: false,
                success: function(respond) {
                    $("#loadlistfakturkredit").html(respond);
                    const jmlfaktur = $('#loadlistfakturkredit').find('tr').length;
                    $("#jml_faktur").val(jmlfaktur);
                    loadSkor();
                }
            });


        }
        $('#tabelpelanggan tbody').on('click', '.pilihpelanggan', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            getPelanggan(kode_pelanggan);
        });

        form.find("#omset_toko").on('keyup keydown', function() {
            var jumlah = $("#jumlah").val();
            $(this).prop('readonly', true);
            if (jumlah == "" || jumlah == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Ajuan Kredit Harus Diisi Dulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });

            }
            loadSkor();
        });


        form.find("#jumlah").on('keyup keydown', function() {
            var jumlah = $("#jumlah").val();
            if (jumlah == "" || jumlah == 0) {
                $("#omset_toko").prop('readonly', true);
            } else {
                $("#omset_toko").prop('readonly', false);
            }
            loadSkor();
        });


        function gettopupTerakhir() {
            const tanggal = $("#topup_terakhir").val();
            $.ajax({
                type: 'POST',
                url: '/ajuanlimit/gettopupterakhir',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal
                },
                cache: false,
                success: function(response) {
                    $("#usia_topup").text(response.data.usia_topup);
                    $("#lama_topup").val(response.data.lama_topup);
                    loadSkor();
                }
            });
        }

        $("#topup_terakhir").change(function() {
            gettopupTerakhir();
        });

        $("#status_outlet,#type_outlet,#ljt,#cara_pembayaran,#kepemilikan,#lama_topup,#lama_langganan,#lama_berjualan,#jaminan,#histori_transaksi")
            .change(function() {
                loadSkor();
            });

        function loadSkor() {
            let status_outlet = $("#status_outlet").val();
            let type_outlet = $("#type_outlet").val();
            let ljt = $("#ljt").val();
            let cara_pembayaran = $("#cara_pembayaran").val();
            let kepemilikan = $("#kepemilikan").val();
            let lama_topup = $("#lama_topup").val();
            let lama_langganan = $("#lama_langganan").val();
            let lama_berjualan = $("#lama_berjualan").val();
            let jaminan = $("#jaminan").val();
            let histori_transaksi = $("#histori_transaksi").val();
            let omset_toko = $("#omset_toko").val();
            let jumlah = $("#jumlah").val();
            ///var jmlfaktur = $("#jml_faktur").val();
            let ot = omset_toko.replace(/\./g, '');
            let jm = jumlah.replace(/\./g, '');

            let ratioomset = Math.round(parseInt(ot) / parseInt(jm));

            let jmlfaktur = $('#loadlistfakturkredit').find('tr').length;

            let score_omset = 0;
            let score_faktur = 0;
            let score_outlet = 0;
            let score_jaminan = 0;
            let score_typeoutlet = 0;
            let score_top = 0;
            let score_carabayar = 0;
            let score_kepemilikan = 0;
            let score_ht = 0;
            let score_lamausaha = 0;
            let score_lamalangganan = 0;
            let score_lamatopup = 0;

            if (omset_toko != "" || omset_toko !== '0') {
                if (ratioomset < 3) {
                    score_omset = 0.35;
                } else if (ratioomset >= 3) {
                    score_omset = 0.50;
                }
            } else {
                score_omset = 0;
            }


            if (jmlfaktur == 1) {
                score_faktur = 0.50;
            } else if (jmlfaktur == 2) {
                score_faktur = 0.40;
            } else if (jmlfaktur == 3) {
                score_faktur = 0.30;
            } else if (jmlfaktur > 3) {
                score_faktur = 0.20;
            } else {
                score_faktur = 0;
            }

            console.log(score_faktur);
            if (status_outlet == "NO") {
                score_outlet = 1.05;
            } else if (status_outlet == "EO") {
                score_outlet = 1.50;
            } else {
                score_outlet = 0.00;
            }

            if (jaminan == 1) {
                score_jaminan = 1.00;
            } else if (jaminan == 2) {
                score_jaminan = 0.70;
            } else {
                score_jaminan = 0.00;
            }

            if (type_outlet == "GR") {
                score_typeoutlet = 0.50;
            } else if (type_outlet == "RT") {
                score_typeoutlet = 0.35;
            } else {
                score_typeoutlet = 0.00;
            }

            if (ljt == 14) {
                score_top = 1.00;
            } else if (ljt == 30) {
                score_top = 0.70;
            } else if (ljt == 45) {
                score_top = 0.40;
            } else {
                score_top = 0.00;
            }

            if (cara_pembayaran == 'BT') {
                score_carabayar = 0.50;
            } else if (cara_pembayaran == 'AC') {
                score_carabayar = 0.35;
            } else if (cara_pembayaran == 'CQ') {
                score_carabayar = 0.20;
            } else {
                score_carabayar = 0.00;
            }
            //alert(score_carabayar);
            if (kepemilikan == "MS") {
                score_kepemilikan = 1.00;
            } else if (kepemilikan == "SW") {
                score_kepemilikan = 0.70;
            } else {
                score_kepemilikan = 0.00;
            }

            if (histori_transaksi == "HT01") {
                score_ht = 1.00;
            } else if (histori_transaksi == "HT02") {
                score_ht = 0.70;
            } else if (histori_transaksi == "HT03") {
                score_ht = 0.40;
            } else {
                score_ht = 0.00;
            }

            if (lama_berjualan == "LU01") {
                score_lamausaha = 0.40;
            } else if (lama_berjualan == "LU02") {
                score_lamausaha = 0.70;
            } else if (lama_berjualan == "LU03") {
                score_lamausaha = 1.00;
            } else {
                score_lamausaha = 0.00;
            }

            if (lama_langganan == "LL01") {
                score_lamalangganan = 0.70;
            } else if (lama_langganan == "LL02") {
                score_lamalangganan = 1.00;
            } else {
                score_lamalangganan = 0.00;
            }

            if (lama_topup == 0) {
                score_lamatopup = 0.50;
            } else if (lama_topup <= 31) {
                score_lamatopup = 0.50;
            } else if (lama_topup > 31) {
                score_lamatopup = 0.35;
            } else {
                score_lamatopup = 0.00;
            }

            let totalscore = parseFloat(score_outlet) + parseFloat(score_top) + parseFloat(score_carabayar) +
                parseFloat(score_kepemilikan) + parseFloat(score_lamausaha) + parseFloat(
                    score_jaminan) +
                parseFloat(score_lamatopup) + parseFloat(score_lamalangganan) + parseFloat(score_ht) +
                parseFloat(score_omset) + parseFloat(score_faktur) + parseFloat(score_typeoutlet);
            let scoreakhir = totalscore.toFixed(2);
            //$("#skor").val(scoreakhir);
            let rekomendasi = "";
            if (scoreakhir <= 2) {
                rekomendasi = "Tidak Layak";
            } else if (scoreakhir > 2 && scoreakhir <= 4) {
                rekomendasi = "Tidak Disarankan";
            } else if (scoreakhir > 4 && scoreakhir <= 6.75) {
                rekomendasi = "Beresiko";
            } else if (scoreakhir > 6.75 && scoreakhir <= 8.5) {
                rekomendasi = "Layak Dengan Pertimbangan";
            } else if (scoreakhir > 8.5 && scoreakhir <= 10) {
                rekomendasi = "Layak";
            }
            $("#total_score").text(scoreakhir);
            $("#skor").val(scoreakhir);
            $("#rekomendasi").text(rekomendasi);
        }

        $("#cekjmlfaktur").click(function(e) {
            const jmlfaktur = $('#loadlistfakturkredit').find('tr').length;
            console.log(jmlfaktur);
        });

        $("#formAjuanlimit").submit(function(e) {
            let cek_foto_toko = $("#cek_foto_toko").val();
            let cek_foto_owner = $("#cek_foto_owner").val();
            let jumlah = $("#jumlah").val();
            let jml = jumlah.replace(/\./g, '');
            let kode_pelanggan = $("#kode_pelanggan").val();
            let nik = $("#nik").val();
            let nama_pelanggan = $("#nama_pelanggan").val();
            let alamat_pelanggan = $("#alamat_pelanggan").val();
            let no_hp_pelanggan = $("#no_hp_pelanggan").val();
            let hari = $("#hari").val();
            let lokasi = $("#lokasi").val();
            let ljt = $("#ljt").val();
            let uraian_analisa = $("#uraian_analisa").val();
            let kepemilikan = $("#kepemilikan").val();
            let lama_berjualan = $("#lama_berjualan").val();
            let status_outlet = $("#status_outlet").val();
            let type_outlet = $("#type_outlet").val();
            let cara_pembayaran = $("#cara_pembayaran").val();
            let lama_langganan = $("#lama_langganan").val();
            let jaminan = $("#jaminan").val();
            let histori_transaksi = $("#histori_transaksi").val();
            let lama_topup = $("#lama_topup").val();
            let tanggal = $("#tanggal").val();
            let omset_toko = $("#omset_toko").val();

            let referensiChecked = [];
            $('input[name="referensi[]"]:checked').each(function() {
                referensiChecked.push($(this).val());
            });
            let referensi = referensiChecked.join(',');
            let ket_referensi = $("#ket_referensi").val();

            if (referensi.includes("external") && ket_referensi == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan Referensi Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#ket_referensi").focus();
                    },
                });
            } else if (kode_pelanggan == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Kode Pelanggan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kode_pelanggan").focus();
                    },
                });
            } else if (nik == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "NIK / No. KTP Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nik").focus();
                    },
                });
            } else if (nama_pelanggan == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Nama Pelanggan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#nama_pelanggan").focus();
                    },
                });
            } else if (alamat_pelanggan == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Alamat Pelanggan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#alamat_pelanggan").focus();
                    },
                });
            } else if (no_hp_pelanggan == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "No. HP Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#no_hp_pelanggan").focus();
                    },
                });
            } else if (hari == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Hari Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#hari").focus();
                    },
                });
            } else if (lokasi == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Lokasi Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#lokasi").focus();
                    },
                });
            } else if (jml == "" || jml == 0) {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Ajuan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#jumlah").focus();
                    },
                });
            } else if (cek_foto_toko == "" && cek_foto_owner == "" && jml > 15000000) {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Ajuan Lebih dari Rp. 15.000.000 Wajib Upload Foto Toko dan Foto Owner !, Silahkan Update di Data Master Pelanggan",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#cek_foto_toko").focus();
                    },
                });
            } else if (ljt == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "LJT Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#ljt").focus();
                    },
                });
            } else if (uraian_analisa == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Uraian Analisa Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#uraian_analisa").focus();
                    },
                });
            } else if (kepemilikan == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Kepemilikan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kepemilikan").focus();
                    },
                });
            } else if (lama_berjualan == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Lama Berjualan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#lama_berjualan").focus();
                    },
                });
            } else if (status_outlet == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Status Outlet Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#status_outlet").focus();
                    },
                });
            } else if (type_outlet == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Type Outlet Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#type_outlet").focus();
                    },
                });
            } else if (cara_pembayaran == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Cara Pembayaran Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#cara_pembayaran").focus();
                    },
                });
            } else if (lama_langganan == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Lama Langganan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#lama_langganan").focus();
                    },
                });
            } else if (jaminan == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Jaminan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#jaminan").focus();
                    },
                });
            } else if (histori_transaksi == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Histori Transaksi Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#histori_transaksi").focus();
                    },
                });
            } else if (lama_topup == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Lama Top Up Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#lama_topup").focus();
                    },
                });
            } else if (tanggal == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#tanggal").focus();
                    },
                });
            } else if (omset_toko == "") {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Omset Toko Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#omset_toko").focus();
                    }
                });
            } else {
                $("#submitAjuanlimit").attr("disabled", true);
                $("#submitAjuanlimit").html(
                    '<div class="spinner-border spinner-border-sm text-white me-2" role="status"><span class="visually-hidden">Loading...</span></div>Loading..'
                )
            }
        })
    });
</script>
