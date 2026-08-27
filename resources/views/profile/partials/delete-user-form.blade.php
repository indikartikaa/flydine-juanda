<section class="space-y-6" x-data="{ confirmingUserDeletion: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
    <header>
        <h2 class="text-xl font-extrabold text-rose-600 tracking-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            {{ __('Hapus Akun Permanen') }}
        </h2>

        <p class="mt-2 text-xs font-semibold text-rose-800 leading-relaxed">
            {{ __('Setelah akun Anda dihapus, semua data akan hilang secara permanen. Pastikan Anda telah mengunduh informasi penting sebelum melanjutkan tindakan ini.') }}
        </p>
    </header>

    <button @click="confirmingUserDeletion = true" class="bg-rose-500 hover:bg-rose-600 active:scale-95 text-white px-6 py-3 rounded-xl text-xs font-extrabold shadow-md shadow-rose-500/25 hover:shadow-lg transition-all">
        {{ __('Hapus Akun Saya') }}
    </button>

    <!-- Modal Hapus Akun -->
    <div x-show="confirmingUserDeletion" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div x-show="confirmingUserDeletion"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @click.away="confirmingUserDeletion = false"
             class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-100 text-left">
            
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="p-6 sm:p-8">
                    <h2 class="text-xl font-black text-slate-900 mb-2">
                        {{ __('Apakah Anda yakin?') }}
                    </h2>
                    <p class="text-xs text-slate-500 font-medium mb-6">
                        {{ __('Tindakan ini tidak dapat dibatalkan. Masukkan password Anda untuk mengonfirmasi penghapusan akun.') }}
                    </p>

                    <div>
                        <label for="password" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">{{ __('Password Anda') }}</label>
                        <input id="password" name="password" type="password" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 outline-none transition-all" placeholder="{{ __('Masukkan password') }}" x-ref="password" />
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-rose-500 text-xs font-bold" />
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3">
                    <button type="button" @click="confirmingUserDeletion = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-white hover:text-slate-900 text-xs font-bold transition-all">
                        {{ __('Batal') }}
                    </button>
                    <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-5 py-2.5 rounded-xl text-xs font-extrabold shadow-md shadow-rose-500/25 transition-all">
                        {{ __('Konfirmasi Hapus') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
