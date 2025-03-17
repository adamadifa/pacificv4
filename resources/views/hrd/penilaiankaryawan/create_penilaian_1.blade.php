@extends('layouts.app')
@section('titlepage', 'Buat Penilaian')
@section('content')
@section('navigasi')
    <span>Buat Penilaian</span>
@endsection
<form action="{{ route('penilaiankaryawan.store', Crypt::encrypt($kontrak->no_kontrak)) }}" method="POST" id="formPenilaian">
    <input type="hidden" name="tanggal" id="tanggal" value="{{ $tanggal }}">
    <input type="hidden" name="kode_doc" id="kode_doc" value="{{ $doc }}">
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
                                    <td class="text-end">{{ $karyawan->nik }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td class="text-end">{{ $karyawan->nama_karyawan }}</td>
                                </tr>
                                <tr>
                                    <th>Departemen</th>
                                    <td class="text-end">{{ $karyawan->nama_dept }}</td>
                                </tr>
                                <tr>
                                    <th>Jabatan</th>
                                    <td class="text-end">{{ $karyawan->nama_jabatan }}</td>
                                </tr>
                                <tr>
                                    <th>Periode Kontrak</th>
                                    <td class="text-end">{{ DateToIndo($kontrak->dari) }} s.d {{ DateToIndo($kontrak->sampai) }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td class="text-end">{{ DateToIndo($tanggal) }}</td>
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
                        <table class="table mt-3">
                            <tbody>
                                @php
                                    $no = 1;
                                    $kode_kategori = '';
                                    $jenis_kompetensi = '';
                                @endphp
                                @foreach ($penilaian_item as $d)
                                    @if ($kode_kategori != $d->kode_kategori)
                                        @php
                                            $no = 1;
                                        @endphp
                                        <tr class="table-dark">
                                            <th colspan="3">
                                                {{ $d->nama_kategori }}
                                            </th>
                                        </tr>
                                        <tr class="table-dark">
                                            <th>No.</th>
                                            <th>Sasaran Kerja</th>
                                            <th>Nilai</th>

                                        </tr>
                                    @endif

                                    @if ($d->jenis_kompetensi != 'C00' && $jenis_kompetensi != $d->jenis_kompetensi)
                                        <tr style="text-align: center; background-color:rgba(0, 255, 72, 0.235)">
                                            <td colspan="3" style="text-align: center">
                                                {{ $d->jenis_kompetensi == 'C01' ? 'Kompentensi Wajib' : 'Kompetensi' }}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>
                                            <input type="hidden" name="kode_item" value="{{ $d->kode_item }}">
                                            {{ $d->item_penilaian }}
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input skor" type="radio" name="skor[{{ $d->kode_item }}]"
                                                    id="skor_{{ $d->kode_item }}_m" value="1">
                                                <label class="form-check-label" for="skor_{{ $d->kode_item }}_m">Memuaskan</label>
                                            </div>
                                            <div class="form-check-danger form-check-inline">
                                                <input class="form-check-input skor" type="radio" name="skor[{{ $d->kode_item }}]"
                                                    id="skor_{{ $d->kode_item }}_tm" value="0">
                                                <label class="form-check-label" for="skor_{{ $d->kode_item }}_tm">Tidak
                                                    Memuaskan</label>
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                        $no++;
                                        $kode_kategori = $d->kode_kategori;
                                        $jenis_kompetensi = $d->jenis_kompetensi;
                                    @endphp
                                @endforeach

                            </tbody>
                        </table>
                        </p>

                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <x-input-with-icon-label label="Sakit" name="sakit" icon="ti ti-heart-broken"
                                value="{{ $rekappresensi->sakit != null ? $rekappresensi->sakit : 0 }}" readonly />
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <x-input-with-icon-label label="Izin" name="izin" icon="ti ti-file-description"
                                value="{{ $rekappresensi->izin != null ? $rekappresensi->izin : 0 }}" readonly />
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <x-input-with-icon-label label="Alfa" name="alfa" icon="ti ti-clock-cancel"
                                value="{{ $rekappresensi->alpa != null ? $rekappresensi->alpa : 0 }}" readonly />
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <x-input-with-icon-label label="SID" name="sid" icon="ti ti-receipt"
                                value="{{ $rekappresensi->sid != null ? $rekappresensi->sid : 0 }}" readonly />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <b>B. Masa Kontrak Kerja</b>
                            <div class="form-group mb-3">
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input chbmk" type="checkbox" id="inlineCheckbox1" value="TP" name="masa_kontrak">
                                    <label class="form-check-label" for="inlineCheckbox1">Tidak di Perpanjang</label>
                                </div>
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input chbmk" type="checkbox" id="inlineCheckbox2" value="K3" name="masa_kontrak">
                                    <label class="form-check-label" for="inlineCheckbox2">3 Bulan</label>
                                </div>
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input chbmk" type="checkbox" id="inlineCheckbox3" value="K6" name="masa_kontrak">
                                    <label class="form-check-label" for="inlineCheckbox3">6 Bulan</label>
                                </div>
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input chbmk" type="checkbox" id="inlineCheckbox4" value="KT"
                                        name="masa_kontrak">
                                    <label class="form-check-label" for="inlineCheckbox4">Karyawan tetap</label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <b>C. Riwayat Absensi dan Rekomendasi User</b>
                            <br>
                            <x-textarea label="Rekomendasi" name="rekomendasi" icon="ti ti-receipt" />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <b>D. Evaluasi Skill Teknis / Kinerja (Wajib Diisi User)</b>
                            <br>
                            <x-textarea label="Evaluasi" name="evaluasi" icon="ti ti-receipt" />
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
