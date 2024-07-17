@extends('layouts.app')
@section('titlepage', 'Buat Penilaian')
@section('content')
@section('navigasi')
    <span>Buat Penilaian</span>
@endsection
<form action="{{ route('penilaiankaryawan.update', Crypt::encrypt($penilaiankaryawan->kode_penilaian)) }}" method="POST" id="formPenilaian">
    @method('PUT')
    @csrf
    <div class="row">
        <div class="col-lg-8 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 ">
                            <table class="table">
                                <tr>
                                    <th>NIK</th>
                                    <td class="text-end">{{ $penilaiankaryawan->nik }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td class="text-end">{{ $penilaiankaryawan->nama_karyawan }}</td>
                                </tr>
                                <tr>
                                    <th>Departemen</th>
                                    <td class="text-end">{{ $penilaiankaryawan->nama_dept }}</td>
                                </tr>
                                <tr>
                                    <th>Jabatan</th>
                                    <td class="text-end">{{ $penilaiankaryawan->nama_jabatan }}</td>
                                </tr>
                                <tr>
                                    <th>Periode Kontrak</th>
                                    <td class="text-end">{{ DateToIndo($penilaiankaryawan->kontrak_dari) }} s.d
                                        {{ DateToIndo($penilaiankaryawan->kontrak_sampai) }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td class="text-end">{{ DateToIndo($penilaiankaryawan->tanggal) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 m-auto text-center">
                            <span class="mb-3">Total Score</span>
                            <h1 id="totalscore" style="font-size: 4rem"></h1>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <p>
                            <b>A. Penilaian</b> <br>
                            Checklist bobot penilaian dibawah ini (semakin besar angka yang dipilih semakin baik
                            penilaian karyawan tersebut)
                        <table class="table">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%">No</th>
                                    <th style="width:75%">Faktor Penilaian</th>
                                    <th style="width:20%">Bobot Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penilaian_item as $d)
                                    <tr>
                                        <td rowspan="2">{{ $loop->iteration }}</td>
                                        <td class="bg-info text-white">{{ $d->nama_kategori }}</td>
                                        <td rowspan="2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input skor" type="radio" name="skor[{{ $d->kode_item }}]"
                                                    id="skor_{{ $d->kode_item }}_m" value="1" {{ $d->nilai == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="skor_{{ $d->kode_item }}_m">Memuaskan</label>
                                            </div>
                                            <div class="form-check-danger form-check-inline">
                                                <input class="form-check-input skor" type="radio" name="skor[{{ $d->kode_item }}]"
                                                    id="skor_{{ $d->kode_item }}_tm" value="0" {{ $d->nilai === 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="skor_{{ $d->kode_item }}_tm">Tidak
                                                    Memuaskan</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ $d->item_penilaian }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </p>

                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <x-input-with-icon-label label="Sakit" name="sakit" icon="ti ti-heart-broken" :value="$penilaiankaryawan->sakit" />
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <x-input-with-icon-label label="Izin" name="izin" icon="ti ti-file-description" :value="$penilaiankaryawan->izin" />
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <x-input-with-icon-label label="Alfa" name="alfa" icon="ti ti-clock-cancel" :value="$penilaiankaryawan->alfa" />
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <x-input-with-icon-label label="SID" name="sid" icon="ti ti-receipt" :value="$penilaiankaryawan->sid" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <b>B. Masa Kontrak Kerja</b>
                            <div class="form-group mb-3">
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input chbmk" type="checkbox" id="inlineCheckbox1" value="TP" name="masa_kontrak"
                                        {{ $penilaiankaryawan->masa_kontrak == 'TP' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineCheckbox1">Tidak di Perpanjang</label>
                                </div>
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input chbmk" type="checkbox" id="inlineCheckbox2" value="K3" name="masa_kontrak"
                                        {{ $penilaiankaryawan->masa_kontrak == 'K3' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineCheckbox2">3 Bulan</label>
                                </div>
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input chbmk" type="checkbox" id="inlineCheckbox3" value="K6" name="masa_kontrak"
                                        {{ $penilaiankaryawan->masa_kontrak == 'K6' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineCheckbox3">6 Bulan</label>
                                </div>
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input chbmk" type="checkbox" id="inlineCheckbox4" value="KT" name="masa_kontrak"
                                        {{ $penilaiankaryawan->masa_kontrak == 'KT' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineCheckbox4">Karyawan tetap</label>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <b>C. Riwayat Absensi dan Rekomendasi User</b>
                            <br>
                            <x-textarea label="Rekomendasi" name="rekomendasi" icon="ti ti-receipt" :value="$penilaiankaryawan->rekomendasi" />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <b>D. Evaluasi Skill Teknis / Kinerja (Wajib Diisi User)</b>
                            <br>
                            <x-textarea label="Evaluasi" name="evaluasi" icon="ti ti-receipt" :value="$penilaiankaryawan->evaluasi" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="form-check form-check-inline mt-3">
                                <input class="form-check-input" type="checkbox" id="status_pemutihan" value="1" name="status_pemutihan"
                                    {{ $penilaiankaryawan->status_pemutihan == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_pemutihan">Pemutihan</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@push('myscript')
<script>
    $(function() {
        const form = $('#formPenilaian');
        $(".chbmk").change(function() {
            $(".chbmk").prop('checked', false);
            $(this).prop('checked', true);
        });

        function calculateScore() {
            let totalScore = 0;
            $('#formPenilaian input[type=radio]:checked').each(function() {
                totalScore += parseInt($(this).val());
            });

            $('#totalscore').text(totalScore);
            console.log(totalScore);
        }

        calculateScore();
        let uncheckedRadio = 0;
        $('input[type=radio]').click(function() {
            calculateScore();
            const uncheckedRadiosCount = calculateUncheckedRadios();
            console.log('Jumlah radio button yang belum dicek: ' + uncheckedRadiosCount);
        });




        function calculateUncheckedRadios() {

            let uncheckedRadiosCount = 0;
            $('.skor').each(function() {
                if (!$(this).closest('td').find('input[type=radio]:checked').length) {
                    uncheckedRadiosCount++;
                }
            });
            uncheckedRadio = uncheckedRadiosCount;
            return uncheckedRadiosCount;
        }

        calculateUncheckedRadios();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        form.submit(function(e) {
            const rekomendasi = form.find('#rekomendasi').val();
            const evaluasi = form.find('#evaluasi').val();
            const masaKontrakChecked = $("input[name='masa_kontrak']:checked").length > 0;
            if (uncheckedRadio > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Masih Ada Pertanyaan Yang Belum Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        calculateScore();
                    },
                });

                return false;
            } else if (rekomendasi == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Rekomendasi Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find('#rekomendasi').focus();
                    },
                });
                return false;
            } else if (evaluasi == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Evaluasi Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find('#evaluasi').focus();
                    }
                });
                return false;

            } else if (!masaKontrakChecked) {
                Swal.fire({
                    title: "Oops!",
                    text: "Masa Kontrak Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find('#masa_kontrak').focus();
                    }
                });
                return false;
            } else {
                buttonDisable();
            }

        });


    })
</script>
@endpush
