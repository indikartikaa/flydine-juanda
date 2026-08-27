<section>
    <header class="mb-6">
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
            {{ __('Informasi Dasar') }}
        </h2>
        <p class="mt-1 text-xs font-medium text-slate-500">
            {{ __("Perbarui nama dan alamat email akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">{{ __('Nama Lengkap') }}</label>
            <input id="name" name="name" type="text" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none transition-all" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-rose-500 text-xs font-bold" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">{{ __('Alamat Email') }}</label>
            <input id="email" name="email" type="email" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none transition-all" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2 text-rose-500 text-xs font-bold" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-amber-50 rounded-xl border border-amber-200">
                    <p class="text-xs font-semibold text-amber-800">
                        {{ __('Email Anda belum diverifikasi.') }}
                        <button form="send-verification" class="underline text-[#005ea2] hover:text-blue-800 focus:outline-none">
                            {{ __('Kirim ulang email verifikasi.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-xs text-[#8dc63f]">
                            {{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="pt-2 flex items-center gap-4">
            <button type="submit" class="bg-[#005ea2] hover:bg-[#004a82] active:scale-95 text-white px-6 py-3 rounded-xl text-xs font-extrabold shadow-md shadow-blue-600/25 hover:shadow-lg transition-all">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-extrabold text-[#8dc63f] bg-green-50 px-3 py-1.5 rounded-lg border border-green-200"
                >{{ __('Berhasil disimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
