@props([
    'action',
    'startDateFormatted',
    'endDateFormatted',
    'tenantId' => null,
    'tenants' => [],
])

<div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border-2 border-slate-200">
    <form method="GET" action="{{ $action }}" id="filterForm" class="flex flex-col xl:flex-row xl:items-end justify-between gap-4 sm:gap-5">
        <!-- Dual Calendar Pickers + Apply Button + Tenant Filter -->
        <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-end gap-3 sm:gap-4 w-full xl:w-auto">
            <!-- Dual Date Grid on Mobile -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 w-full sm:w-auto">
                <!-- Dari Tanggal (Start Date) -->
                <div class="w-full sm:w-44">
                    <label for="startDateInput" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Dari Tanggal</span>
                    </label>
                    <div class="relative">
                        <input type="date" 
                               id="startDateInput" 
                               name="start_date" 
                               value="{{ $startDateFormatted }}" 
                               class="w-full sm:w-44 px-3.5 py-2.5 bg-slate-50 hover:bg-white border-2 border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-sm font-extrabold text-slate-900 focus:ring-2 focus:ring-blue-100 transition shadow-xs cursor-pointer">
                    </div>
                </div>

                <!-- Sampai Tanggal (End Date) -->
                <div class="w-full sm:w-44">
                    <label for="endDateInput" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Sampai Tanggal</span>
                    </label>
                    <div class="relative">
                        <input type="date" 
                               id="endDateInput" 
                               name="end_date" 
                               value="{{ $endDateFormatted }}" 
                               class="w-full sm:w-44 px-3.5 py-2.5 bg-slate-50 hover:bg-white border-2 border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-sm font-extrabold text-slate-900 focus:ring-2 focus:ring-blue-100 transition shadow-xs cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- Tombol Terapkan Rentang -->
            <div class="w-full sm:w-auto">
                <button type="submit" 
                        class="w-full sm:w-auto px-5 py-2.5 bg-[#005ea2] hover:bg-[#004b82] active:bg-[#003860] text-white rounded-xl text-sm font-extrabold shadow-sm hover:shadow transition duration-150 flex items-center justify-center gap-2 cursor-pointer h-[44px]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Terapkan Rentang</span>
                </button>
            </div>

            <!-- Filter Mitra Tenant Dropdown -->
            <div class="w-full sm:w-auto">
                <label for="tenantSelect" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <span>🏢 Filter Mitra Tenant</span>
                </label>
                <div class="relative">
                    <select id="tenantSelect" 
                            name="tenant_id" 
                            class="w-full sm:w-auto pl-3.5 pr-9 py-2.5 bg-slate-50 hover:bg-white border-2 border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-sm font-extrabold text-slate-900 focus:ring-2 focus:ring-[#005ea2] transition cursor-pointer shadow-xs sm:min-w-[210px] sm:max-w-[280px] truncate h-[44px]" 
                            onchange="document.getElementById('filterForm').submit()">
                        <option value="">🏢 Semua Tenant (Konsolidasi)</option>
                        @foreach($tenants as $t)
                            <option value="{{ $t->id }}" {{ (string)$tenantId === (string)$t->id ? 'selected' : '' }}>🏢 {{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Extra Slot for Page-Specific Filters -->
            @if(isset($slot) && !$slot->isEmpty())
                <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-end gap-3 sm:gap-4 w-full xl:w-auto">
                    {{ $slot }}
                </div>
            @endif
        </div>

        <!-- Quick Shortcut Chips -->
        <div class="flex items-center flex-wrap gap-1.5 sm:gap-2 text-xs pt-2 xl:pt-0 border-t xl:border-t-0 border-slate-100">
            <span class="text-xs font-black text-slate-500 uppercase tracking-wider mr-1">Preset Cepat:</span>
            <button type="button" onclick="setQuickDateRange(7)" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-100 hover:text-[#005ea2] text-slate-800 font-extrabold border border-slate-200 transition cursor-pointer">
                7 Hari
            </button>
            <button type="button" onclick="setQuickDateRange(30)" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-100 hover:text-[#005ea2] text-slate-800 font-extrabold border border-slate-200 transition cursor-pointer">
                30 Hari
            </button>
            <button type="button" onclick="setQuickDateRange('month')" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-100 hover:text-[#005ea2] text-slate-800 font-extrabold border border-slate-200 transition cursor-pointer">
                Bulan Ini
            </button>
            <button type="button" onclick="setQuickDateRange(90)" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-100 hover:text-[#005ea2] text-slate-800 font-extrabold border border-slate-200 transition cursor-pointer">
                90 Hari
            </button>
        </div>
    </form>
</div>
            <span class="text-xs font-black text-slate-500 uppercase tracking-wider mr-1">Preset Cepat:</span>
            <button type="button" onclick="setQuickDateRange(7)" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-100 hover:text-[#005ea2] text-slate-800 font-extrabold border border-slate-200 transition cursor-pointer">
                7 Hari
            </button>
            <button type="button" onclick="setQuickDateRange(30)" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-100 hover:text-[#005ea2] text-slate-800 font-extrabold border border-slate-200 transition cursor-pointer">
                30 Hari
            </button>
            <button type="button" onclick="setQuickDateRange('month')" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-100 hover:text-[#005ea2] text-slate-800 font-extrabold border border-slate-200 transition cursor-pointer">
                Bulan Ini
            </button>
            <button type="button" onclick="setQuickDateRange(90)" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-100 hover:text-[#005ea2] text-slate-800 font-extrabold border border-slate-200 transition cursor-pointer">
                90 Hari
            </button>
        </div>
    </form>
</div>

<script nonce="{{ $cspNonce ?? '' }}">
if (typeof window.setQuickDateRange === 'undefined') {
    window.setQuickDateRange = function(type) {
        const today = new Date();
        let start = new Date();
        
        if (type === 'month') {
            start = new Date(today.getFullYear(), today.getMonth(), 1);
        } else {
            const days = parseInt(type, 10) || 7;
            start.setDate(today.getDate() - (days - 1));
        }
        
        const pad = (n) => String(n).padStart(2, '0');
        const formatYMD = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
        
        const startEl = document.getElementById('startDateInput');
        const endEl = document.getElementById('endDateInput');
        if (startEl && endEl) {
            startEl.value = formatYMD(start);
            endEl.value = formatYMD(today);
            const form = document.getElementById('filterForm');
            if (form) form.submit();
        }
    };
}

document.addEventListener('DOMContentLoaded', function() {
    const startInput = document.getElementById('startDateInput');
    const endInput = document.getElementById('endDateInput');
    if (startInput) {
        startInput.addEventListener('click', function() {
            if (typeof this.showPicker === 'function') {
                try { this.showPicker(); } catch(e) {}
            }
        });
        startInput.addEventListener('change', function() {
            if (endInput && this.value) {
                endInput.min = this.value;
            }
        });
    }
    if (endInput) {
        endInput.addEventListener('click', function() {
            if (typeof this.showPicker === 'function') {
                try { this.showPicker(); } catch(e) {}
            }
        });
        endInput.addEventListener('change', function() {
            if (startInput && this.value) {
                startInput.max = this.value;
            }
        });
    }
});
</script>
