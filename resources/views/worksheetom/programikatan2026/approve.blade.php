<form action="{{ route('programikatan2026.storeapprove', Crypt::encrypt($programikatan->no_pengajuan)) }}" method="POST">
    @csrf
    <div class="row mb-3">
        <div class="col-12">
            <div class="card card-hover shadow-sm border">
                <div class="card-body p-3">
                    <div class="row align-items-center gx-3">
                        {{-- ID & Tanggal (Expanded to col-2) --}}
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 mb-2 mb-md-0">
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-primary fs-5">#{{ $programikatan->no_pengajuan }}</span>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="ti ti-calendar me-1" style="font-size: 0.8rem;"></i>
                                    <small class="text-nowrap">{{ formatIndo($programikatan->tanggal) }}</small>
                                </div>
                            </div>
                        </div>

                        {{-- Program & Cabang --}}
                        <div class="col-xl-3 col-lg-4 col-md-5 col-sm-12 mb-2 mb-md-0">
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark text-truncate" style="font-size: 1rem;" title="{{ $programikatan->nama_program }}">{{ $programikatan->nama_program }}</span>
                                <small class="text-secondary text-uppercase fw-semibold">{{ $programikatan->kode_cabang }}</small>
                                <div class="d-flex align-items-center mt-1 text-muted d-md-none flex-wrap gap-1">
                                        {{-- Mobile only period check --}}
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-clock me-1" style="font-size: 0.8rem;"></i>
                                        <small>{{ date('m/y', strtotime($programikatan->periode_dari)) }} - {{ date('m/y', strtotime($programikatan->periode_sampai)) }}</small>
                                    </div>
                                    @if(!empty($programikatan->semester))
                                        <small class="badge bg-label-info p-1" style="font-size: 0.7rem;">Sem {{ $programikatan->semester }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Periode (Hidden on very small, visible on lg) --}}
                        <div class="col-xl-2 col-lg-2 d-none d-lg-block mb-2 mb-md-0">
                            <div class="d-flex align-items-center text-secondary bg-label-secondary px-2 py-1 rounded" style="width: fit-content;">
                                <i class="ti ti-clock me-2"></i>
                                <small class="fw-bold text-nowrap">
                                    {{ date('m/y', strtotime($programikatan->periode_dari)) }} - {{ date('m/y', strtotime($programikatan->periode_sampai)) }}
                                </small>
                            </div>
                            @if(!empty($programikatan->semester))
                                <span class="badge bg-label-info mt-1" style="width: fit-content;">Semester {{ $programikatan->semester }}</span>
                            @endif
                        </div>

                        {{-- Approval (Expanded to col-3) --}}
                        <div class="col-xl-3 col-lg-2 col-md-4 col-sm-12 mb-2 mb-md-0">
                            <div class="d-flex justify-content-start align-items-center gap-3">
                                {{-- OM --}}
                                <div class="text-center position-relative">
                                    <small class="d-block text-muted fw-bold" style="font-size: 0.65rem; margin-bottom: 2px;">OM</small>
                                    @if (empty($programikatan->om)) 
                                        <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary text-warning"><i class="ti ti-hourglass-empty"></i></span></div>
                                    @else 
                                        <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-success text-white"><i class="ti ti-check"></i></span></div>
                                    @endif
                                </div>
                                {{-- RSM --}}
                                <div class="text-center position-relative">
                                    <small class="d-block text-muted fw-bold" style="font-size: 0.65rem; margin-bottom: 2px;">RSM</small>
                                    @if (empty($programikatan->rsm)) 
                                        <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary text-warning"><i class="ti ti-hourglass-empty"></i></span></div>
                                    @else 
                                        @if (empty($programikatan->gm) && $programikatan->status == '2') 
                                            <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-danger text-white"><i class="ti ti-x"></i></span></div>
                                        @else 
                                            <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-success text-white"><i class="ti ti-check"></i></span></div>
                                        @endif
                                    @endif
                                </div>
                                {{-- GM --}}
                                <div class="text-center position-relative">
                                    <small class="d-block text-muted fw-bold" style="font-size: 0.65rem; margin-bottom: 2px;">GM</small>
                                    @if (empty($programikatan->gm)) 
                                            <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary text-warning"><i class="ti ti-hourglass-empty"></i></span></div>
                                    @else
                                        @if (empty($programikatan->direktur) && $programikatan->status == '2') 
                                            <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-danger text-white"><i class="ti ti-x"></i></span></div>
                                        @else 
                                            <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-success text-white"><i class="ti ti-check"></i></span></div>
                                        @endif
                                    @endif
                                </div>
                                {{-- DIR --}}
                                <div class="text-center position-relative">
                                    <small class="d-block text-muted fw-bold" style="font-size: 0.65rem; margin-bottom: 2px;">DIR</small>
                                    @if (empty($programikatan->direktur)) 
                                            <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-label-secondary text-warning"><i class="ti ti-hourglass-empty"></i></span></div>
                                    @else
                                        @if ($programikatan->status == '2') 
                                            <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-danger text-white"><i class="ti ti-x"></i></span></div>
                                        @else 
                                            <div class="avatar avatar-xs"><span class="avatar-initial rounded-circle bg-success text-white"><i class="ti ti-check"></i></span></div>
                                        @endif
                                    @endif
                                </div>

                                {{-- Status Badge Inline --}}
                                    <div class="ms-2 border-start ps-3 d-flex align-items-center">
                                    @if ($programikatan->status == '0')
                                        <span class="badge bg-label-warning text-warning d-flex align-items-center gap-1 px-2 py-1"><i class="ti ti-hourglass-empty fs-6"></i> Pending</span>
                                    @elseif ($programikatan->status == '1')
                                        <span class="badge bg-success d-flex align-items-center gap-1 px-2 py-1"><i class="ti ti-check fs-6"></i> Disetujui</span>
                                    @elseif($programikatan->status == '2')
                                        <span class="badge bg-danger d-flex align-items-center gap-1 px-2 py-1"><i class="ti ti-ban fs-6"></i> Ditolak</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
           <div class="card shadow-sm border">
                <div class="card-header border-bottom py-3" style="background-color: #002e65;">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-users me-2"></i>Daftar Pelanggan</h6>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead class="text-white" style="background-color: #002e65;">
                            <tr>
                                <th class="fw-bold text-white">No</th>
                                <th class="fw-bold text-white">Kode</th>
                                <th class="fw-bold text-white">Nama Pelanggan</th>
                                <th class="fw-bold text-white text-center">Rata-Rata</th>
                                <th class="fw-bold text-white text-center">Target (Tambahan)</th>
                                <th class="fw-bold text-white text-center">Total</th>
                                <th class="fw-bold text-white text-end">Ach (%)</th>
                                <th class="fw-bold text-white text-end">TOP</th>
                                <th class="fw-bold text-white">Metode</th>
                                <th class="fw-bold text-white text-end">Pencairan</th>
                                <th class="fw-bold text-white text-center">Doc</th>
                                <th class="fw-bold text-white text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $metode_pembayaran = [
                                    'TN' => 'Tunai',
                                    'TF' => 'Transfer',
                                    'VC' => 'Voucher',
                                ];
                            @endphp
                            @foreach ($detail as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="fw-semibold">{{ $d->kode_pelanggan }}</span></td>
                                    <td>{{ $d->nama_pelanggan }}</td>
                                    <td class="text-center">{{ formatAngka($d->qty_avg) }}</td>
                                    <td class="text-center"><span class="badge bg-label-primary">{{ formatAngka($d->qty_target) }}</span></td>
                                    <td class="text-center">{{ formatAngka($d->qty_avg + $d->qty_target) }}</td>
                                    <td class="text-end">
                                        @php
                                            $kenaikan = $d->qty_target;
                                            $persentase = $d->qty_avg == 0 ? 0 : ($kenaikan / $d->qty_avg) * 100;
                                            $persentase = formatAngkaDesimal($persentase);
                                            $color = $persentase >= 0 ? 'success' : 'danger';
                                        @endphp
                                        <span class="text-{{ $color }} fw-bold">{{ $persentase }}%</span>
                                    </td>
                                    <td class="text-end">{{ $d->top }}</td>
                                    <td>{{ $metode_pembayaran[$d->metode_pembayaran] ?? $d->metode_pembayaran }}</td>
                                    <td class="text-end">{{ formatAngka($d->periode_pencairan) }} Bulan</td>
                                    <td class="text-center">
                                        @if ($d->file_doc != null)
                                            <a href="{{ asset('storage/programikatan2026/' . $d->file_doc) }}" target="_blank" class="text-info" data-bs-toggle="tooltip" title="Lihat Dokumen">
                                                <i class="ti ti-file-text fs-4"></i>
                                            </a>
                                        @else
                                             <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btnDetailTarget text-primary" kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}"
                                            no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}" data-bs-toggle="tooltip" title="Detail Target">
                                            <i class="ti ti-file-description"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-thumb-up me-1"></i>Approve</button></button>
        </div>
        <div class="col">
            <button class="btn btn-danger w-100" id="btnSimpan" name="decline" value="1"><i
                    class="ti ti-thumb-down me-1"></i>Tolak</button></button>
        </div>
        @php
            $user = auth()->user();
            $showCancel = false;
            
            if ($user->hasRole('operation manager') && !empty($programikatan->om)) {
                $showCancel = true;
            } elseif ($user->hasRole('regional sales manager') && !empty($programikatan->rsm)) {
                $showCancel = true;
            } elseif ($user->hasRole('gm marketing') && !empty($programikatan->gm)) {
                $showCancel = true;
            } elseif ($user->hasRole('direktur') && !empty($programikatan->direktur)) {
                $showCancel = true;
            }
        @endphp

        @if($showCancel || $user->hasRole('super admin'))
        <div class="col">
            <button class="btn btn-warning w-100" id="btnCancel" name="cancel" value="1"><i
                    class="ti ti-rotate-clockwise-2 me-1"></i>Batalkan</button></button>
        </div>
        @endif
    </div>
</form>
