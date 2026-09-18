@extends('layouts.app')
@section('titlepage', 'Atur Hari Libur')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Atur Hari Libur</h4>
            <small class="text-muted">Kelola penetapan dan daftar karyawan yang diliburkan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>HRD</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('harilibur.index') }}"><i class="ti ti-calendar me-1"></i>Hari Libur</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-users me-1"></i>Atur Karyawan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    {{-- Kolom Kiri: Detail Informasi Hari Libur --}}
    <div class="col-lg-4 col-md-5 col-sm-12 mb-3">
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3" style="background-color: #284c9a; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-calendar-event me-2"></i>Informasi Hari Libur</h6>
                    @if ($harilibur->status === '1')
                        <span class="badge bg-success"><i class="ti ti-checks me-1"></i>Disetujui</span>
                    @else
                        <span class="badge bg-warning text-dark"><i class="ti ti-clock me-1"></i>Pending</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <tbody>
                        <tr>
                            <td class="fw-semibold text-muted ps-3" style="width: 40%;">Kode Libur</td>
                            <td class="text-end pe-3 fw-bold">{{ $harilibur->kode_libur }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted ps-3">Tanggal</td>
                            <td class="text-end pe-3">{{ DateToIndo($harilibur->tanggal) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted ps-3">Kategori</td>
                            <td class="text-end pe-3">
                                <span class="badge bg-{{ $harilibur->color ?? 'primary' }}">
                                    {{ $harilibur->nama_kategori }}
                                </span>
                            </td>
                        </tr>
                        @if (!empty($harilibur->tanggal_diganti))
                            <tr>
                                <td class="fw-semibold text-muted ps-3">Pengganti</td>
                                <td class="text-end pe-3 text-primary">{{ DateToIndo($harilibur->tanggal_diganti) }}</td>
                            </tr>
                        @endif
                        @if (!empty($harilibur->tanggal_limajam))
                            <tr>
                                <td class="fw-semibold text-muted ps-3">Tanggal 5 Jam</td>
                                <td class="text-end pe-3 text-warning">{{ DateToIndo($harilibur->tanggal_limajam) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="fw-semibold text-muted ps-3">Cabang</td>
                            <td class="text-end pe-3">{{ $harilibur->nama_cabang }}</td>
                        </tr>
                        @if ($harilibur->kode_cabang == 'PST')
                            <tr>
                                <td class="fw-semibold text-muted ps-3">Departemen</td>
                                <td class="text-end pe-3">{{ $harilibur->nama_dept ?: 'Semua Dept' }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="fw-semibold text-muted ps-3">Keterangan</td>
                            <td class="text-end pe-3">{{ $harilibur->keterangan ?: '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-top py-2 px-3">
                <a href="{{ route('harilibur.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Daftar Karyawan yang Diliburkan --}}
    <div class="col-lg-8 col-md-7 col-sm-12">
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3" style="background-color: #284c9a; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="m-0 fw-bold text-white"><i class="ti ti-users me-2"></i>Karyawan Diliburkan</h6>
                        <span class="badge bg-white text-dark" id="countKaryawan">0 Karyawan</span>
                    </div>
                    @can('harilibur.setharilibur')
                        @if ($harilibur->status === '0')
                            <button id="btnCreate" class="btn btn-primary btn-sm border-white">
                                <i class="ti ti-user-plus me-1"></i> Tambah Karyawan
                            </button>
                        @endif
                    @endcan
                </div>
            </div>
            <div class="card-body p-3">
                {{-- Search Filter Simple & Clean --}}
                <div class="row mb-2">
                    <div class="col-md-6 col-12">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" id="searchKaryawan" placeholder="Cari NIK, nama, atau grup..." autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" id="btnResetSearch">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Tabel Karyawan --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0" id="tableKaryawanLibur">
                        <thead class="text-white">
                            <tr style="background-color: #284c9a;">
                                <th class="text-white text-center" style="width: 50px;">No</th>
                                <th class="text-white" style="width: 140px;">NIK</th>
                                <th class="text-white">Nama Karyawan</th>
                                <th class="text-white" style="width: 110px;">Dept</th>
                                <th class="text-white" style="width: 130px;">Grup</th>
                                <th class="text-white text-center" style="width: 70px;">#</th>
                            </tr>
                        </thead>
                        <tbody id="loadliburkaryawan">
                            <tr>
                                <td colspan="6" class="text-center py-3 text-muted">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="" />
@endsection

@push('myscript')
<script>
    $(function() {
        const canDelete = {{ $harilibur->status === '0' ? 'true' : 'false' }};

        function updateKaryawanCount() {
            const rows = $("#loadliburkaryawan tr.row-karyawan");
            const visibleRows = $("#loadliburkaryawan tr.row-karyawan:visible");
            const total = rows.length;
            const visible = visibleRows.length;
            
            if ($("#searchKaryawan").val().trim() !== "") {
                $("#countKaryawan").text(`${visible} dari ${total} Karyawan`);
            } else {
                $("#countKaryawan").text(`${total} Karyawan`);
            }
        }

        function loadliburkaryawan() {
            const kode_libur = "{{ Crypt::encrypt($harilibur->kode_libur) }}";
            $("#loadliburkaryawan").html(`
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                        <span class="text-muted">Memuat data karyawan libur...</span>
                    </td>
                </tr>
            `);
            $.ajax({
                type: 'GET',
                url: `/harilibur/${kode_libur}/getkaryawanlibur`,
                cache: false,
                success: function(html) {
                    $("#loadliburkaryawan").html(html);
                    updateKaryawanCount();
                    // Re-apply filter if active
                    const searchVal = $("#searchKaryawan").val().toLowerCase();
                    if (searchVal) {
                        filterKaryawan(searchVal);
                    }
                },
                error: function() {
                    $("#loadliburkaryawan").html(`
                        <tr>
                            <td colspan="6" class="text-center py-4 text-danger">
                                <i class="ti ti-alert-circle me-1"></i> Gagal memuat data karyawan libur.
                            </td>
                        </tr>
                    `);
                }
            });
        }
        loadliburkaryawan();

        function filterKaryawan(query) {
            let matched = 0;
            $("#loadliburkaryawan tr.row-karyawan").each(function() {
                const text = $(this).text().toLowerCase();
                if (text.indexOf(query) !== -1) {
                    $(this).show();
                    matched++;
                } else {
                    $(this).hide();
                }
            });

            if (matched === 0 && $("#loadliburkaryawan tr.row-karyawan").length > 0) {
                if ($("#noMatchRow").length === 0) {
                    $("#loadliburkaryawan").append(`
                        <tr id="noMatchRow">
                            <td colspan="6" class="text-center py-3 text-muted">
                                <i class="ti ti-search-off me-1"></i> Tidak ditemukan karyawan yang cocok dengan pencarian.
                            </td>
                        </tr>
                    `);
                }
            } else {
                $("#noMatchRow").remove();
            }
            updateKaryawanCount();
        }

        $("#searchKaryawan").on('input keyup', function() {
            const query = $(this).val().toLowerCase().trim();
            filterKaryawan(query);
        });

        $("#btnResetSearch").on('click', function() {
            $("#searchKaryawan").val('');
            filterKaryawan('');
        });

        function loading() {
            $("#loadmodal").html(`
                <div class="sk-wave sk-primary py-5" style="margin:auto">
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                </div>
            `);
        }

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            const kode_libur = "{{ Crypt::encrypt($harilibur->kode_libur) }}";
            $("#modal").modal("show");
            $(".modal-title").html('<i class="ti ti-user-plus me-2 text-primary"></i>Input Karyawan Hari Libur');
            $("#loadmodal").load(`/harilibur/${kode_libur}/aturkaryawan`);
        });

        // Re-load list when modal closes to make sure counts and rows sync
        $('#modal').on('hidden.bs.modal', function() {
            loadliburkaryawan();
        });

        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            const kode_libur = "{{ $harilibur->kode_libur }}";
            const nik = $(this).attr("nik");
            const nama = $(this).attr("nama") || nik;

            Swal.fire({
                title: "Hapus Karyawan?",
                html: `Karyawan <b>${nama}</b> akan dikeluarkan dari daftar hari libur ini.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#82868b",
                confirmButtonText: "<i class='ti ti-trash me-1'></i> Ya, Hapus",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: 'btn btn-danger me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: `/harilibur/deletekaryawanlibur`,
                        data: {
                            _token: "{{ csrf_token() }}",
                            kode_libur: kode_libur,
                            nik: nik
                        },
                        cache: false,
                        success: function(respond) {
                            if (respond.success == true) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Karyawan berhasil dikeluarkan.",
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                loadliburkaryawan();
                            } else {
                                Swal.fire({
                                    title: "Oops!",
                                    text: respond.message,
                                    icon: "warning",
                                    showConfirmButton: true,
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: "Error!",
                                text: "Terjadi kesalahan pada server saat menghapus data.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
