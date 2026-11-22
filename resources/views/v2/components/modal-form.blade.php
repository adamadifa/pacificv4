@props(['id' => '', 'size' => '', 'show' => '', 'title' => ''])
<div class="modal fade" id="{{ $id }}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog {{ $size }} modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title font-bold text-sm text-slate-800 dark:text-slate-100 flex items-center gap-2" id="myModalLabel18">
                    <span>{{ $title }}</span>
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="{{ $show }}" id="{{ $show }}"></div>
            </div>
        </div>
    </div>
</div>
