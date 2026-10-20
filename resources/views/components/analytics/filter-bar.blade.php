@props([
    'action',
    'startDateFormatted',
    'endDateFormatted',
    'tenantId' => null,
    'tenants' => [],
])

@props([
    'action',
    'startDateFormatted',
    'endDateFormatted',
    'tenantId' => null,
    'tenants' => [],
])

<div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-slate-200">
    <form method="GET" action="{{ $action }}" id="filterForm" class="space-y-4">
        <!-- Top Row: Date Range + Apply + Tenant Filter + Compact Quick Presets -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3 w-full lg:w-auto flex-1">
                <!-- Inline Date Range Pill -->
                <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 hover:border-slate-300 rounded-xl px-3.5 py-2 focus-within:border-[#005ea2] focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100 transition shadow-2xs">
                    <svg class="w-4 h-4 text-[#005ea2] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <input type="date" 
                           id="startDateInput" 
                           name="start_date" 
                           value="{{ $startDateFormatted }}" 
                           class="bg-transparent border-0 p-0 text-xs sm:text-sm font-bold text-slate-800 focus:ring-0 cursor-pointer w-28 sm:w-32" 
                           title="Dari Tanggal">
                    <span class="text-xs font-semibold text-slate-400 select-none">s/d</span>
                    <input type="date" 
                           id="endDateInput" 
                           name="end_date" 
                           value="{{ $endDateFormatted }}" 
                           class="bg-transparent border-0 p-0 text-xs sm:text-sm font-bold text-slate-800 focus:ring-0 cursor-pointer w-28 sm:w-32" 
                           title="Sampai Tanggal">
                </div>

                <!-- Tombol Terapkan -->
                <button type="submit" 
                        class="px-4 py-2 bg-[#005ea2] hover:bg-[#004b82] active:bg-[#003860] text-white rounded-xl text-xs sm:text-sm font-bold shadow-2xs transition flex items-center gap-1.5 cursor-pointer h-[40px]"
                        title="Terapkan Rentang Tanggal">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Terapkan</span><span class="sr-only">Rentang</span>
                </button>

                <!-- Tenant Dropdown (Clean, No Emoji) -->
                <div class="relative">
                    <label for="tenantSelect" class="sr-only">Filter Mitra Tenant</label>
                    <select id="tenantSelect" 
                            name="tenant_id" 
                            class="pl-3.5 pr-8 py-2 bg-slate-50 hover:bg-white border border-slate-200 hover:border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:ring-2 focus:ring-[#005ea2] transition cursor-pointer shadow-2xs sm:min-w-[220px] sm:max-w-[320px] truncate h-[40px]" 
                            onchange="document.getElementById('filterForm').submit()"
                            title="Pilih Tenant Tertentu">
                        <option value="">Semua Tenant (Konsolidasi)</option>
                        @foreach($tenants as $t)
                            <option value="{{ $t->id }}" {{ (string)$tenantId === (string)$t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->terminal ?? 'T1' }} • {{ $t->floor_location ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Right Group: Compact Quick Presets -->
            <div class="flex items-center gap-2 shrink-0 ml-auto lg:ml-0">
                <label for="quickRangeSelect" class="text-xs font-bold text-slate-500 uppercase tracking-wider hidden sm:inline">Preset:</label>
                <select id="quickRangeSelect" 
                        onchange="setQuickDateRange(this.value)" 
                        class="pl-3 pr-8 py-2 bg-slate-50 hover:bg-white border border-slate-200 hover:border-slate-300 rounded-xl text-xs font-bold text-slate-700 focus:border-[#005ea2] focus:ring-2 focus:ring-blue-100 transition cursor-pointer shadow-2xs h-[40px]">
                    <option value="">Preset Cepat...</option>
                    <option value="7">7 Hari Terakhir</option>
                    <option value="30">30 Hari Terakhir</option>
                    <option value="month">Bulan Ini</option>
                    <option value="90">90 Hari Terakhir</option>
                </select>
            </div>
        </div>

        <!-- Extra Slot for Page-Specific Filters -->
        @if(isset($slot) && !$slot->isEmpty())
            <div class="pt-3.5 border-t border-slate-100">
                {{ $slot }}
            </div>
        @endif
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
