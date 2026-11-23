@props(['id' => '', 'size' => '', 'show' => '', 'title' => ''])
<div class="modal fade" id="{{ $id }}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog {{ $size }} modal-dialog-centered">
        <div class="modal-content rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden bg-white dark:bg-slate-900">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="package" class="w-4 h-4"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2" id="myModalLabel18">
                        <span class="modal-title">{{ $title }}</span>
                    </h4>
                </div>
                <button type="button" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer" data-bs-dismiss="modal" aria-label="Close">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="modal-body p-4 overflow-x-auto">
                <div class="{{ $show }}" id="{{ $show }}"></div>
            </div>
        </div>
    </div>
</div>
