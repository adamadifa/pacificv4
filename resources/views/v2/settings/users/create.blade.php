<form action="{{ route('users.store') }}" id="formcreateUser" method="POST" class="space-y-3">
    @csrf

    <!-- Informasi Akun -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 space-y-2.5">
        <div class="flex items-center gap-2 pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="user" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Informasi Akun</h4>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
            <div>
                <label for="name_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="name" id="name_create" placeholder="Masukkan Nama Lengkap"
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <div>
                <label for="username_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Username <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="at-sign" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="username" id="username_create" placeholder="Masukkan Username"
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <div>
                <label for="email_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Email <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="email" name="email" id="email_create" placeholder="Masukkan Email"
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <div>
                <label for="password_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Password <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="key" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="password" name="password" id="password_create" placeholder="Masukkan Password"
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>
        </div>
    </div>

    <!-- Organisasi & Role -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 space-y-2.5">
        <div class="flex items-center gap-2 pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="building" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Organisasi & Role</h4>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
            <div>
                <label for="role_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Role Utama <span class="text-rose-500">*</span>
                </label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="role" id="role_create" class="select2UserModal w-full">
                        <option value="">Pilih Role Utama</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->name }}">{{ strtoupper($r->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="kode_dept_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Departemen Utama <span class="text-rose-500">*</span>
                </label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="briefcase" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_dept" id="kode_dept_create" class="select2UserModal w-full">
                        <option value="">Pilih Departemen</option>
                        @foreach ($departemen as $dept)
                            <option value="{{ $dept->kode_dept }}">{{ $dept->nama_dept }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="kode_cabang_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Cabang <span class="text-rose-500">*</span>
                </label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_create" class="select2UserModal w-full">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}">{{ textUpperCase($c->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="kode_regional_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Regional
                </label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="map" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_regional" id="kode_regional_create" class="select2UserModal w-full">
                        <option value="">Pilih Regional</option>
                        @foreach ($regional as $reg)
                            <option value="{{ $reg->kode_regional }}">{{ $reg->nama_regional }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Akses Tambahan -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 space-y-2.5">
        <div class="flex items-center gap-2 pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="lock" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Akses Tambahan</h4>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Akses Cabang</label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="cabang_access[]" multiple class="select2Multi w-full" data-placeholder="Pilih Akses Cabang">
                        <option value="all">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}">{{ textUpperCase($c->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Akses Departemen</label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="briefcase" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="dept_access[]" multiple class="select2Multi w-full" data-placeholder="Pilih Akses Departemen">
                        <option value="all">Semua Departemen</option>
                        @foreach ($departemen as $dept)
                            <option value="{{ $dept->kode_dept }}">{{ $dept->nama_dept }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Akses Jabatan</label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="award" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="jabatan_access[]" multiple class="select2Multi w-full" data-placeholder="Pilih Akses Jabatan">
                        <option value="all">Semua Jabatan</option>
                        @foreach ($jabatan as $j)
                            <option value="{{ $j->kode_jabatan }}">{{ $j->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Akses Group</label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="group_access[]" multiple class="select2Multi w-full" data-placeholder="Pilih Akses Group">
                        @foreach ($group as $g)
                            <option value="{{ $g->kode_group }}">{{ $g->nama_group }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Akses Karyawan</label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="users" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="karyawan_access[]" multiple class="select2Multi w-full" data-placeholder="Pilih Akses Karyawan">
                        <option value="all">Semua Karyawan</option>
                        @foreach ($karyawan as $k)
                            <option value="{{ $k->nik }}">{{ $k->nik }} - {{ $k->nama_karyawan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
            <label class="flex items-start gap-2.5 p-2.5 rounded-lg border border-slate-200 dark:border-slate-800 hover:border-[#284c9a] cursor-pointer transition">
                <input type="checkbox" name="is_pic_presensi" value="1" class="mt-0.5 w-4 h-4 rounded-sm border-slate-300 text-[#284c9a]">
                <div>
                    <span class="block text-xs font-bold text-slate-800 dark:text-slate-100">PIC Presensi</span>
                    <span class="block text-[10.5px] text-slate-400">Otomatis beri izin kelola presensi & izin.</span>
                </div>
            </label>

            <label class="flex items-start gap-2.5 p-2.5 rounded-lg border border-slate-200 dark:border-slate-800 hover:border-[#284c9a] cursor-pointer transition">
                <input type="checkbox" name="is_approval_presensi" value="1" class="mt-0.5 w-4 h-4 rounded-sm border-slate-300 text-[#284c9a]">
                <div>
                    <span class="block text-xs font-bold text-slate-800 dark:text-slate-100">Approval Presensi</span>
                    <span class="block text-[10.5px] text-slate-400">Otomatis beri izin approval cuti & absensi.</span>
                </div>
            </label>
        </div>
    </div>

    <!-- Modal Footer Actions -->
    <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" data-bs-dismiss="modal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanUser"
            class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan User</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const form = $("#formcreateUser");
        form.find('.select2UserModal').select2({ placeholder: 'Pilih Opsi', allowClear: true, width: '100%' });
        form.find('.select2Multi').each(function() {
            $(this).select2({ placeholder: $(this).data('placeholder') || 'Pilih Opsi', allowClear: true, width: '100%' });
        });

        function showInlineError($el, msg) {
            clearInlineError($el);
            $el.addClass("is-invalid border-rose-500 ring-1 ring-rose-500");
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find(".select2-selection").addClass("is-invalid !border-rose-500 !ring-1 !ring-rose-500");
                $rel.find(".select2-container").addClass("is-invalid");
                $rel.find("i, svg").addClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
                $rel.after(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>${msg}</span>
                    </p>
                `);
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
            $el.focus();
        }

        function clearInlineError($el) {
            $el.removeClass("is-invalid border-rose-500 ring-1 ring-rose-500");
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find(".select2-selection").removeClass("is-invalid !border-rose-500 !ring-1 !ring-rose-500");
                $rel.find(".select2-container").removeClass("is-invalid");
                $rel.find("i, svg").removeClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
            }
        }

        form.submit(function(e) {
            let hasError = false;
            if (!form.find("#name_create").val().trim()) { showInlineError(form.find("#name_create"), "Nama lengkap wajib diisi!"); hasError = true; }
            if (!form.find("#username_create").val().trim()) { showInlineError(form.find("#username_create"), "Username wajib diisi!"); hasError = true; }
            if (!form.find("#email_create").val().trim()) { showInlineError(form.find("#email_create"), "Email wajib diisi!"); hasError = true; }
            if (!form.find("#password_create").val().trim()) { showInlineError(form.find("#password_create"), "Password wajib diisi!"); hasError = true; }
            if (!form.find("#role_create").val()) { showInlineError(form.find("#role_create"), "Role utama wajib dipilih!"); hasError = true; }
            if (!form.find("#kode_dept_create").val()) { showInlineError(form.find("#kode_dept_create"), "Departemen wajib dipilih!"); hasError = true; }
            if (!form.find("#kode_cabang_create").val()) { showInlineError(form.find("#kode_cabang_create"), "Cabang wajib dipilih!"); hasError = true; }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            const $btn = form.find("#btnSimpanUser");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> <span>Menyimpan...</span>`);
        });

        form.on("input change", "input, select", function() { clearInlineError($(this)); });
        form.find("select").on("select2:select select2:clear", function(e) { clearInlineError($(e.target)); });
    });
</script>
