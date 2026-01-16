@php
    $total_reward = 0;
    $color_reward = '';
    $status = 0;
@endphp
@foreach ($peserta as $d)
    @php
        $total_target_pencapaian = ($d->avg + $d->target_perbulan);
        $color_reward = $d->jml_dus >= $total_target_pencapaian ? 'bg-success text-white' : ($d->reward_rate > 0 ? 'bg-warning text-dark' : 'bg-danger text-white');
        
        $reward_tunai = $d->calculated_reward_tunai;
        $reward_kredit = $d->calculated_reward_kredit;
        $reward = $d->calculated_reward_total;
        $status = $reward == 0 ? 0 : 1;
    @endphp

    <div class="col-12 mb-2">
        <div class="card card-body p-3 shadow-sm border {{ $color_reward }}">
             <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                {{-- Input Hidden --}}
                <input type="hidden" name="kode_pelanggan[{{ $loop->index }}]" value="{{ $d->kode_pelanggan }}">
                <input type="hidden" name="status[{{ $loop->index }}]" value="{{ $status }}">
                <input type="hidden" name="qty_tunai[{{ $loop->index }}]" value="0">
                <input type="hidden" name="qty_kredit[{{ $loop->index }}]" value="0">
                <input type="hidden" name="jumlah[{{ $loop->index }}]" value="{{ $d->jml_dus }}">
                <input type="hidden" name="reward_tunai[{{ $loop->index }}]" value="0">
                <input type="hidden" name="reward_kredit[{{ $loop->index }}]" value="0">
                <input type="hidden" name="total_reward[{{ $loop->index }}]" value="{{ $reward }}">
                <input type="hidden" name="status_pencairan[{{ $loop->index }}]" value="{{ $reward >= 100000 ? 1 : 0 }}">
                
                 {{-- Pelanggan --}}
                <div class="d-flex align-items-center" style="min-width: 250px;">
                    <div class="me-3 text-center">
                        <span class="badge bg-white text-dark mb-1">{{ $loop->iteration }} {{ $d->kode_program }}</span>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold {{ $color_reward ? 'text-white' : '' }}">{{ $d->nama_pelanggan }}</h6>
                        <small class="{{ $color_reward ? 'text-white' : 'text-muted' }}">{{ $d->kode_pelanggan }}</small>
                    </div>
                </div>

                {{-- Target --}}
                <div class="">
                    <small class="d-block uppercase {{ $color_reward ? 'text-white' : 'text-muted' }}" style="font-size: 0.7rem;">
                        <i class="ti ti-target me-1"></i>Target
                    </small>
                    <div class="d-flex gap-3 text-center">
                        <div>
                            <span class="d-block fw-bold {{ $color_reward ? 'text-white' : 'text-dark' }}">{{ formatAngka($d->avg) }}</span>
                            <small class="{{ $color_reward ? 'text-white' : 'text-muted' }}" style="font-size: 10px;">Avg</small>
                        </div>
                         <div>
                            <span class="d-block fw-bold {{ $color_reward ? 'text-white' : 'text-info' }}">{{ formatAngka($d->kenaikan_per_bulan) }}</span>
                            <small class="{{ $color_reward ? 'text-white' : 'text-muted' }}" style="font-size: 10px;">Incr</small>
                        </div>
                        <div>
                            <span class="d-block fw-bold {{ $color_reward ? 'text-white' : 'text-primary' }}">{{ formatAngka($d->target_perbulan) }}</span>
                            <small class="{{ $color_reward ? 'text-white' : 'text-muted' }}" style="font-size: 10px;">Target</small>
                        </div>
                        <div>
                            <span class="d-block fw-bold {{ $color_reward ? 'text-white' : 'text-success' }}">{{ formatAngka($total_target_pencapaian) }}</span>
                            <small class="{{ $color_reward ? 'text-white' : 'text-muted' }}" style="font-size: 10px;">Total</small>
                        </div>
                    </div>
                </div>

                {{-- Realisasi --}}
                <div class="text-center">
                        <small class="d-block uppercase {{ $color_reward ? 'text-white' : 'text-muted' }}" style="font-size: 0.7rem;">
                            <i class="ti ti-chart-bar me-1"></i>Realisasi
                        </small>
                        <h6 class="mb-0 fw-bold {{ $color_reward ? 'text-white' : 'text-warning' }}">{{ empty($d->jml_dus) ? '0' : formatAngka($d->jml_dus) }}</h6>
                </div>

                {{-- Reward --}}
                <div class="text-end" style="min-width: 100px;">
                    <small class="d-block uppercase {{ $color_reward ? 'text-white' : 'text-muted' }}" style="font-size: 0.7rem;">
                        <i class="ti ti-gift me-1"></i>Reward
                    </small>
                    <h6 class="mb-0 fw-bold {{ $color_reward ? 'text-white' : 'text-success' }}">{{ empty($reward) ? '0' : formatAngka($reward) }}</h6>
                </div>

                {{-- Checkbox --}}
                <div>
                     @if ($reward > 0)
                        <div class="form-check">
                            <input class="form-check-input checkpelanggan" name="checkpelanggan[{{ $loop->index }}]" value="1" type="checkbox"
                                id="checkpelanggan">
                        </div>
                    @else
                        <input class="form-check-input checkpelanggan pelangganna" name="checkpelanggan[{{ $loop->index }}]" value="1" type="checkbox"
                            id="checkpelanggan" checked>
                    @endif
                </div>

             </div>
        </div>
    </div>
@endforeach
{{-- <tr class="table-dark">
    <td colspan="6" class="text-end">TOTAL REWARD</td>
    <td class="text-end">{{ formatAngka($total_reward) }}</td>
    <td></td>
</tr> --}}
<script>
    $(document).ready(function() {
        function hide() {
            $(".pelangganna").hide();
        }

        hide();
    });
</script>
