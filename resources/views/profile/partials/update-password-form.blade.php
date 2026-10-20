<section>
    <header class="mb-6 border-b border-slate-100 pb-4">
        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-blue-50 text-[#005ea2] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <span>{{ __('Keamanan Password') }}</span>
        </h2>
        <p class="mt-1 text-xs font-semibold text-slate-500">
            {{ __('Pastikan akun Anda menggunakan password yang kuat dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span>{{ __('Password Saat Ini') }}</span>
            </label>
            <input id="update_password_current_password" 
                   name="current_password" 
                   type="password" 
                   class="w-full border-2 border-slate-200 bg-white hover:border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/15 outline-none transition" 
                   autocomplete="current-password" 
                   placeholder="Masukkan password saat ini" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-rose-500 text-xs font-bold" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                <span>{{ __('Password Baru') }}</span>
            </label>
            <input id="update_password_password" 
                   name="password" 
                   type="password" 
                   class="w-full border-2 border-slate-200 bg-white hover:border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/15 outline-none transition" 
                   autocomplete="new-password" 
                   placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-rose-500 text-xs font-bold" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ __('Konfirmasi Password Baru') }}</span>
            </label>
            <input id="update_password_password_confirmation" 
                   name="password_confirmation" 
                   type="password" 
                   class="w-full border-2 border-slate-200 bg-white hover:border-slate-300 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/15 outline-none transition" 
                   autocomplete="new-password" 
                   placeholder="Ulangi password baru" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-rose-500 text-xs font-bold" />
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center gap-4">
            <button type="submit" 
                    class="bg-[#005ea2] hover:bg-[#004a82] active:scale-95 text-white px-6 py-2.5 rounded-xl text-xs font-black shadow-md shadow-blue-600/25 hover:shadow-lg transition-all flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>{{ __('Perbarui Password') }}</span>
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 3000)"
                   class="text-xs font-black text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ __('Password berhasil diperbarui!') }}</span>
                </p>
            @endif
        </div>
    </form>
</section>
