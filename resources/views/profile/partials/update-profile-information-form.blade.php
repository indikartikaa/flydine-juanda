<section x-data="{
    photoPreview: null,
    previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photoPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}">
    <header class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
                {{ __('Informasi & Profil Tenant') }}
            </h2>
            <p class="mt-1 text-xs font-medium text-slate-500">
                {{ __('Kelola foto gerai/logo, data operasional restoran, dan kontak penanggung jawab.') }}
            </p>
        </div>
        @if(isset($tenant))
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold {{ $tenant->isOpen() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                    <span class="w-2 h-2 rounded-full {{ $tenant->isOpen() ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                    {{ $tenant->isOpen() ? 'Restoran Buka' : 'Restoran Tutup' }}
                </span>
            </div>
        @endif
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        @if(isset($tenant) && $user->role === 'tenant_staff')
            <!-- 1. Bagian Foto Gerai / Logo Restoran -->
            <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 p-5 rounded-2xl border border-slate-200/80">
                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-3">
                    Foto Gerai / Logo Restoran
                </label>
                
                <div class="flex flex-col sm:flex-row items-center gap-5">
                    <!-- Photo Preview Box -->
                    <div class="relative group">
                        <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl overflow-hidden border-2 border-white shadow-md bg-white flex items-center justify-center">
                            <!-- Ketika user memilih foto baru -->
                            <template x-if="photoPreview">
                                <img :src="photoPreview" alt="Preview Foto" class="w-full h-full object-cover">
                            </template>

                            <!-- Ketika belum memilih foto baru, tampilkan foto lama di DB jika ada -->
                            <template x-if="!photoPreview">
                                @if($tenant->logo)
                                    <img src="{{ asset($tenant->logo) }}" alt="{{ $tenant->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-[#005ea2]/10 flex flex-col items-center justify-center text-[#005ea2]">
                                        <span class="text-3xl font-black">{{ strtoupper(substr(str_replace([' ', "'"], '', $tenant->name), 0, 2)) }}</span>
                                        <span class="text-[10px] font-bold mt-1 text-slate-400">Belum Ada Foto</span>
                                    </div>
                                @endif
                            </template>
                        </div>

                        <!-- Badge Status Baru -->
                        <span x-show="photoPreview" x-cloak class="absolute -top-2 -right-2 bg-emerald-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow-sm">
                            Foto Baru
                        </span>
                    </div>

                    <!-- Upload Button & Guidance -->
                    <div class="flex-1 space-y-2 text-center sm:text-left">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                            <label for="tenant_logo_input" class="cursor-pointer inline-flex items-center gap-2 bg-white hover:bg-slate-50 active:scale-95 text-[#005ea2] border border-[#005ea2]/30 hover:border-[#005ea2] px-4 py-2.5 rounded-xl text-xs font-bold shadow-xs transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Pilih Foto Gerai</span>
                            </label>

                            <input id="tenant_logo_input" name="logo" type="file" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" @change="previewImage($event)" />
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Gunakan foto tampak depan gerai atau logo resmi gerai Anda. Format: <strong>JPG, PNG, WebP</strong> (maksimal 20MB).
                        </p>
                        @error('logo')
                            <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 2. Informasi Ruang & Lokasi Bandara (Data Resmi Database - Readonly Info Box) -->
            <div class="bg-slate-50/80 p-5 rounded-2xl border border-slate-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#005ea2]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        Identitas Gerai Resmi Bandara
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 bg-white px-2 py-0.5 rounded border border-slate-200">Sistem Angkasa Pura</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase">Nama Brand / Gerai</label>
                        <p class="text-sm font-black text-slate-800 mt-0.5">{{ $tenant->name }}</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase">Kode Ruang Tenant</label>
                        <p class="text-sm font-mono font-bold text-[#005ea2] mt-0.5">{{ $tenant->tenant_code }}</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase">Terminal & Area Zona</label>
                        <p class="text-xs font-bold text-slate-700 mt-0.5">
                            {{ $tenant->terminal ?? 'Terminal 1' }} • {{ $tenant->zone ?? 'Landside' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase">Titik Lokasi Lantai / Gate</label>
                        <p class="text-xs font-bold text-slate-700 mt-0.5">{{ $tenant->floor_location ?? '-' }}</p>
                    </div>
                    @if($tenant->contract_end)
                    <div class="sm:col-span-2 pt-2 border-t border-slate-200/60 flex items-center justify-between text-[11px]">
                        <span class="text-slate-500 font-medium">Masa Berlaku Kontrak Usaha:</span>
                        <span class="font-bold text-slate-800">
                            {{ \Carbon\Carbon::parse($tenant->contract_start ?? now())->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($tenant->contract_end)->format('d M Y') }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- 3. Pengaturan Kontak Gerai & Jam Operasional (Editable) -->
            <div class="space-y-4">
                <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                    Operasional & Kontak Gerai
                </h3>

                <div>
                    <label for="phone" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                        Nomor Telepon Gerai / Outlet
                    </label>
                    <input id="phone" name="phone" type="text" 
                           class="w-full border border-slate-200 bg-white rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:border-[#005ea2] focus:ring-2 focus:ring-blue-100 outline-none transition-all" 
                           value="{{ old('phone', $tenant->phone) }}" 
                           placeholder="Contoh: 031-8667555 atau 08123456789" />
                    @error('phone')
                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jam Operasional -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="opening_time" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Jam Buka (WIB)
                        </label>
                        <input id="opening_time" name="opening_time" type="time" 
                               class="w-full border border-slate-200 bg-white rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:border-[#005ea2] focus:ring-2 focus:ring-blue-100 outline-none transition-all" 
                               value="{{ old('opening_time', $tenant->opening_time ? substr($tenant->opening_time, 0, 5) : '06:00') }}" />
                        @error('opening_time')
                            <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="closing_time" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Jam Tutup (WIB)
                        </label>
                        <input id="closing_time" name="closing_time" type="time" 
                               class="w-full border border-slate-200 bg-white rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:border-[#005ea2] focus:ring-2 focus:ring-blue-100 outline-none transition-all" 
                               value="{{ old('closing_time', $tenant->closing_time ? substr($tenant->closing_time, 0, 5) : '22:00') }}" />
                        @error('closing_time')
                            <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 4. Data Penanggung Jawab (PIC) & Akun Login (Editable) -->
            <div class="space-y-4 pt-2 border-t border-slate-100">
                <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                    Data Penanggung Jawab (PIC) & Akun
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="pic_name" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Nama PIC / Supervisor
                        </label>
                        <input id="pic_name" name="pic_name" type="text" 
                               class="w-full border border-slate-200 bg-white rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:border-[#005ea2] focus:ring-2 focus:ring-blue-100 outline-none transition-all" 
                               value="{{ old('pic_name', $tenant->pic_name ?? $user->name) }}" 
                               placeholder="Nama PIC penanggung jawab" />
                        @error('pic_name')
                            <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pic_phone" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            No. WhatsApp / HP PIC
                        </label>
                        <input id="pic_phone" name="pic_phone" type="text" 
                               class="w-full border border-slate-200 bg-white rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:border-[#005ea2] focus:ring-2 focus:ring-blue-100 outline-none transition-all" 
                               value="{{ old('pic_phone', $tenant->pic_phone) }}" 
                               placeholder="0812xxxxxxxx" />
                        @error('pic_phone')
                            <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Nama Tampilan Akun Staff
                        </label>
                        <input id="name" name="name" type="text" 
                               class="w-full border border-slate-200 bg-white rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:border-[#005ea2] focus:ring-2 focus:ring-blue-100 outline-none transition-all" 
                               value="{{ old('name', $user->name) }}" required />
                        @error('name')
                            <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                            Email Login (Username)
                        </label>
                        <input id="email" type="email" 
                               class="w-full border-0 bg-slate-100/80 rounded-xl px-4 py-3 text-sm font-bold text-slate-500 cursor-not-allowed select-none" 
                               value="{{ $user->email }}" readonly disabled />
                        <p class="mt-1 text-[10px] text-slate-400 font-medium">*Email ini digunakan untuk login dan hanya dapat diubah oleh Admin Pusat.</p>
                    </div>
                </div>
            </div>

        @else
            <!-- Form untuk Admin / Non-Tenant -->
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Nama Akun</label>
                    <input id="name" name="name" type="text" class="w-full border border-slate-200 bg-white rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:border-[#005ea2] focus:ring-2 focus:ring-blue-100 outline-none" value="{{ old('name', $user->name) }}" required />
                    @error('name')
                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Email Akun</label>
                    <input id="email" name="email" type="email" class="w-full border border-slate-200 bg-white rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:border-[#005ea2] focus:ring-2 focus:ring-blue-100 outline-none" value="{{ old('email', $user->email) }}" required />
                    @error('email')
                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        @endif

        <!-- Action Button -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <button type="submit" class="bg-[#005ea2] hover:bg-[#004a82] active:scale-95 text-white px-7 py-3 rounded-xl text-xs font-extrabold shadow-md shadow-blue-600/25 hover:shadow-lg transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>Simpan Perubahan Data</span>
            </button>

            @if (session('status') === 'profile-updated')
                <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Tersimpan!
                </span>
            @endif
        </div>
    </form>
</section>
