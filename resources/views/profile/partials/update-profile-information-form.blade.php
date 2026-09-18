<section>
    <header class="mb-6">
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
            {{ __('Informasi Dasar') }}
        </h2>
        <p class="mt-1 text-xs font-medium text-slate-500">
            {{ __("Perbarui nama dan alamat email akun Anda.") }}
        </p>
    </header>

    <div class="space-y-5 mt-6">
        <div>
            <label for="name" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Nama Akun') }}</label>
            <input id="name" type="text" class="w-full border-0 bg-slate-50 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 focus:ring-0 cursor-not-allowed" value="{{ $user->name }}" readonly disabled />
            <p class="mt-1.5 text-[10px] text-slate-400 font-medium">*Nama akun ditentukan oleh Admin Pusat.</p>
        </div>

        <div>
            <label for="email" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Email Akses (Username)') }}</label>
            <input id="email" type="email" class="w-full border-0 bg-slate-50 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 focus:ring-0 cursor-not-allowed" value="{{ $user->email }}" readonly disabled />
            <p class="mt-1.5 text-[10px] text-slate-400 font-medium">*Email ini digunakan untuk login dan tidak dapat diubah.</p>
        </div>
    </div>
</section>
