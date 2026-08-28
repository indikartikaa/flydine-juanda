<section>
    <header class="mb-6">
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
            {{ __('Keamanan Password') }}
        </h2>
        <p class="mt-1 text-xs font-medium text-slate-500">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">{{ __('Password Saat Ini') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none transition-all" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-rose-500 text-xs font-bold" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">{{ __('Password Baru') }}</label>
            <input id="update_password_password" name="password" type="password" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none transition-all" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-rose-500 text-xs font-bold" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">{{ __('Konfirmasi Password Baru') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none transition-all" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-rose-500 text-xs font-bold" />
        </div>

        <div class="pt-2 flex items-center gap-4">
            <button type="submit" class="bg-[#005ea2] hover:bg-[#004a82] active:scale-95 text-white px-6 py-3 rounded-xl text-xs font-extrabold shadow-md shadow-blue-600/25 hover:shadow-lg transition-all">
                {{ __('Perbarui Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-extrabold text-[#8dc63f] bg-green-50 px-3 py-1.5 rounded-lg border border-green-200"
                >{{ __('Berhasil diperbarui.') }}</p>
            @endif
        </div>
    </form>
</section>
