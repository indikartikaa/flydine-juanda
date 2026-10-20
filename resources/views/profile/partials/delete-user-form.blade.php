@if(auth()->user()->role === 'tenant_staff')
<section class="space-y-3">
    <header>
        <h2 class="text-sm font-black text-slate-900 tracking-tight flex items-center gap-2">
            <div class="w-6 h-6 rounded-lg bg-blue-50 text-[#005ea2] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span>{{ __('Ketentuan Akun Mitra Tenant') }}</span>
        </h2>

        <p class="mt-2 text-xs text-slate-500 font-medium leading-relaxed">
            Akun ini terikat langsung dengan kontrak usaha dan operasional gerai resmi Bandara Juanda. Penonaktifan akun hanya dapat diproses melalui koordinasi resmi dengan tim Admin Operasional & Komersial Bandara.
        </p>
    </header>

    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 font-bold flex items-center gap-2.5">
        <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
        <span>Terverifikasi sebagai Mitra Resmi F&B FlyDine Juanda</span>
    </div>
</section>
@else
<section class="space-y-4" x-data="{ confirmingUserDeletion: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
    <header>
        <h2 class="text-base font-black text-rose-700 tracking-tight flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <span>{{ __('Hapus Akun Pengelola') }}</span>
        </h2>

        <p class="mt-1 text-xs font-semibold text-slate-500 leading-relaxed">
            {{ __('Setelah akun dihapus, seluruh akses operasional dan data terkait akan dihentikan secara permanen. Pastikan Anda telah mengonfirmasi tindakan ini.') }}
        </p>
    </header>

    <button type="button" 
            @click="confirmingUserDeletion = true" 
            class="bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 active:scale-95 px-5 py-2.5 rounded-xl text-xs font-black shadow-2xs hover:shadow-xs transition-all flex items-center gap-2 cursor-pointer">
        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        <span>{{ __('Hapus Akun Saya') }}</span>
    </button>

    <!-- Modal Hapus Akun -->
    <div x-show="confirmingUserDeletion" 
         style="display: none;" 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div x-show="confirmingUserDeletion"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="confirmingUserDeletion = false"
             class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200 text-left">
            
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="p-6 sm:p-7">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <h3 class="text-lg font-black text-slate-900 mb-1">
                        {{ __('Konfirmasi Hapus Akun?') }}
                    </h3>
                    <p class="text-xs text-slate-500 font-semibold mb-5 leading-relaxed">
                        {{ __('Tindakan ini tidak dapat dibatalkan. Masukkan password Anda untuk mengonfirmasi penghapusan akun dari sistem.') }}
                    </p>

                    <div>
                        <label for="password" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">{{ __('Password Konfirmasi') }}</label>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               class="w-full border-2 border-slate-200 bg-white rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/15 outline-none transition" 
                               placeholder="{{ __('Masukkan password akun Anda') }}" 
                               x-ref="password" />
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-rose-600 text-xs font-bold" />
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" 
                            @click="confirmingUserDeletion = false" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-white text-xs font-black transition cursor-pointer">
                        {{ __('Batal') }}
                    </button>
                    <button type="submit" 
                            class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2 rounded-xl text-xs font-black shadow-md shadow-rose-600/25 transition cursor-pointer">
                        {{ __('Ya, Hapus Akun') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endif
