@extends('layouts.admin')

@section('title', 'Drill-Down Pesanan')

@section('content')
<script hidden nonce="{{ $cspNonce ?? '' }}">
window.drillDownApp = function() {
    return {
        // Query & Filter States
        startDate: {!! json_encode($startDateFormatted) !!},
        endDate: {!! json_encode($endDateFormatted) !!},
        date: {!! json_encode($initialDate) !!},
        tenantId: {!! json_encode($tenantId ? (string)$tenantId : "") !!},
        category: {!! json_encode($initialCategory) !!},
        status: {!! json_encode($initialStatus) !!},
        sla: {!! json_encode($initialSla) !!},
        hour: {!! json_encode($initialHour) !!},
        pickupMethod: {!! json_encode($initialPickupMethod) !!},
        search: {!! json_encode($initialSearch) !!},
        categoryMetric: 'volume',
        cachedChartsData: null,
        activeTab: 'primary', // 'primary' (Kategori, Status, Jam) | 'secondary' (Harian, Tenant, Menu)
        showVisualCharts: true,

        // Pagination & Sorting States
        page: 1,
        perPage: 10,
        sort_by: 'ordered_at',
        sort_dir: 'desc',

        // Data Storage
        orders: [],
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 10,
            total: 0,
            from: 0,
            to: 0
        },
        summary: {
            total_orders: 0,
            total_revenue: 0,
            total_revenue_formatted: 'Rp 0',
            avg_sla_minutes: 0,
            sla_compliant_percentage: 0
        },

        // Loading & UI States
        ordersLoading: false,
        ordersError: false,
        ordersErrorMessage: '',
        chartsLoading: false,
        chartsError: false,
        copiedUrl: false,
        copiedOrderCode: false,

        // Drawer Panel States
        drawerOpen: false,
        drawerLoading: false,
        drawerError: false,
        activeOrder: null,

        // Chart.js Instances
        chartInstances: {
            daily: null,
            status: null,
            category: null,
            hourly: null,
            tenant: null,
            menu: null,
        },

        init() {
            // Read all URL query params if present
            const params = new URLSearchParams(window.location.search);
            if (params.get('start_date')) this.startDate = params.get('start_date');
            if (params.get('end_date')) this.endDate = params.get('end_date');
            if (params.get('date')) this.date = params.get('date');
            if (params.get('category')) this.category = params.get('category');
            if (params.get('tenant_id')) this.tenantId = params.get('tenant_id');
            if (params.get('status')) this.status = params.get('status');
            if (params.get('sla')) this.sla = params.get('sla');
            if (params.get('hour')) this.hour = params.get('hour');
            if (params.get('pickup_method')) this.pickupMethod = params.get('pickup_method');
            if (params.get('search')) this.search = params.get('search');
            if (params.get('sort_by')) this.sort_by = params.get('sort_by');
            if (params.get('sort_dir')) this.sort_dir = params.get('sort_dir');
            if (params.get('page')) this.page = parseInt(params.get('page'), 10) || 1;
            if (params.get('per_page')) this.perPage = parseInt(params.get('per_page'), 10) || 10;

            // Sync tenant selector if rendered in form
            const selectTenantEl = document.getElementById('tenantSelect');
            if (selectTenantEl && this.tenantId) {
                selectTenantEl.value = this.tenantId;
            }

            // Sync category selector if rendered in form
            const selectCatEl = document.getElementById('filterCategorySelect');
            if (selectCatEl && this.category) {
                selectCatEl.value = this.category;
            }

            // Fetch initial data
            this.fetchSummaryAndOrders();
            this.fetchCharts();

            // Keyboard shortcut for closing drawer
            window.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.drawerOpen) {
                    this.closeDrawer();
                }
            });
        },

        buildQueryParams() {
            const params = new URLSearchParams();
            if (this.startDate) params.set('start_date', this.startDate);
            if (this.endDate) params.set('end_date', this.endDate);
            if (this.date) params.set('date', this.date);
            if (this.category && this.category !== 'semua') params.set('category', this.category);
            if (this.tenantId) params.set('tenant_id', this.tenantId);
            if (this.status && this.status !== 'semua') params.set('status', this.status);
            if (this.sla && this.sla !== 'semua') params.set('sla', this.sla);
            if (this.hour !== 'semua' && this.hour !== '' && this.hour !== null) params.set('hour', this.hour);
            if (this.pickupMethod && this.pickupMethod !== 'semua') params.set('pickup_method', this.pickupMethod);
            if (this.search) params.set('search', this.search);
            return params;
        },

        updateBrowserUrl() {
            const params = this.buildQueryParams();
            if (this.sort_by && this.sort_by !== 'ordered_at') params.set('sort_by', this.sort_by);
            if (this.sort_dir && this.sort_dir !== 'desc') params.set('sort_dir', this.sort_dir);
            if (this.page > 1) params.set('page', this.page);
            if (this.perPage !== 10) params.set('per_page', this.perPage);

            const queryString = params.toString();
            const newUrl = window.location.pathname + (queryString ? '?' + queryString : '');
            window.history.replaceState({}, '', newUrl);
        },

        copyShareableUrl() {
            this.updateBrowserUrl();
            navigator.clipboard.writeText(window.location.href).then(() => {
                this.copiedUrl = true;
                setTimeout(() => { this.copiedUrl = false; }, 2500);
            });
        },

        applyFilter() {
            this.page = 1;
            this.updateBrowserUrl();
            this.fetchSummaryAndOrders();
            this.fetchCharts();
        },

        switchTab(tab) {
            this.activeTab = tab;
            this.$nextTick(() => {
                if (this.cachedChartsData) {
                    this.renderAllCharts(this.cachedChartsData);
                }
            });
        },

        resetHierarchy() {
            this.category = 'semua';
            this.tenantId = '';
            this.date = '';
            this.hour = 'semua';
            const selectTenantEl = document.getElementById('tenantSelect');
            if (selectTenantEl) selectTenantEl.value = '';
            const selectCatEl = document.getElementById('filterCategorySelect');
            if (selectCatEl) selectCatEl.value = 'semua';
            this.applyFilter();
        },

        clearCategory() {
            this.category = 'semua';
            const selectCatEl = document.getElementById('filterCategorySelect');
            if (selectCatEl) selectCatEl.value = 'semua';
            this.applyFilter();
        },

        clearTenant() {
            this.tenantId = '';
            const selectTenantEl = document.getElementById('tenantSelect');
            if (selectTenantEl) selectTenantEl.value = '';
            this.applyFilter();
        },

        clearDate() {
            this.date = '';
            this.applyFilter();
        },

        clearHour() {
            this.hour = 'semua';
            this.applyFilter();
        },

        resetAllFilters() {
            this.category = 'semua';
            this.date = '';
            this.tenantId = '';
            this.status = 'semua';
            this.sla = 'semua';
            this.hour = 'semua';
            this.pickupMethod = 'semua';
            this.search = '';
            this.page = 1;
            const selectTenantEl = document.getElementById('tenantSelect');
            if (selectTenantEl) selectTenantEl.value = '';
            const selectCatEl = document.getElementById('filterCategorySelect');
            if (selectCatEl) selectCatEl.value = 'semua';
            this.applyFilter();
        },

        sortBy(column) {
            if (this.sort_by === column) {
                this.sort_dir = this.sort_dir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sort_by = column;
                this.sort_dir = 'desc';
            }
            this.page = 1;
            this.updateBrowserUrl();
            this.fetchSummaryAndOrders();
        },

        goToPage(targetPage) {
            if (targetPage < 1 || targetPage > this.pagination.last_page) return;
            this.page = targetPage;
            this.updateBrowserUrl();
            this.fetchSummaryAndOrders();
            const section = document.getElementById('ordersTableSection');
            if (section) section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        },

        getTenantName(id) {
            const selectTenantEl = document.getElementById('tenantSelect');
            if (selectTenantEl) {
                const opt = selectTenantEl.querySelector(`option[value="${id}"]`);
                if (opt) return opt.textContent.trim();
            }
            return 'ID ' + id;
        },

        formatDateLabel(d) {
            try {
                const parts = d.split('-');
                const dt = new Date(parts[0], parts[1] - 1, parts[2]);
                return dt.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            } catch(e) {
                return d;
            }
        },

        padHour(h) {
            return String(h).padStart(2, '0');
        },

        formatGate(gate) {
            if (!gate || gate === '-' || gate === '') return '-';
            const clean = String(gate).trim();
            return clean.toLowerCase().startsWith('gate') ? clean : 'Gate ' + clean;
        },

        // Fetch Orders & Summary
        fetchSummaryAndOrders() {
            this.ordersLoading = true;
            this.ordersError = false;

            const params = this.buildQueryParams();
            params.set('page', this.page);
            params.set('per_page', this.perPage);
            params.set('sort_by', this.sort_by);
            params.set('sort_dir', this.sort_dir);

            fetch(`{{ route('admin.analytics.drilldown.orders', [], false) }}?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP Error ${res.status}`);
                return res.json();
            })
            .then(json => {
                this.orders = json.data || [];
                this.pagination = json.pagination || this.pagination;
                this.summary = json.summary || this.summary;
            })
            .catch(err => {
                console.error('Fetch orders error:', err);
                this.ordersError = true;
                this.ordersErrorMessage = err.message;
            })
            .finally(() => {
                this.ordersLoading = false;
            });
        },

        // Fetch & Render Charts
        fetchCharts() {
            this.chartsLoading = true;
            this.chartsError = false;

            const params = this.buildQueryParams();

            fetch(`{{ route('admin.analytics.drilldown.charts', [], false) }}?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP Error ${res.status}`);
                return res.json();
            })
            .then(data => {
                this.renderAllCharts(data);
            })
            .catch(err => {
                console.error('Fetch charts error:', err);
                this.chartsError = true;
            })
            .finally(() => {
                this.chartsLoading = false;
            });
        },

        renderAllCharts(data) {
            this.cachedChartsData = data;
            if (!data || typeof window.Chart === 'undefined') return;

            if (this.activeTab === 'primary') {
                this.renderCategoryChart();
                this.renderStatusChart(data);
                this.renderHourlyChart(data);
            } else {
                this.renderDailyChart(data);
                this.renderTenantChart(data);
                this.renderMenuChart(data);
            }
        },

        renderDailyChart(data) {
            const self = this;
            const dailyCanvas = document.getElementById('drillChartDaily');
            if (!dailyCanvas) return;
            if (this.chartInstances.daily) this.chartInstances.daily.destroy();

            const dailyData = data.orders_per_day || { labels: [], raw_dates: [], values: [] };
            this.chartInstances.daily = new Chart(dailyCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: dailyData.labels,
                    datasets: [{
                        label: 'Jumlah Pesanan',
                        data: dailyData.values,
                        backgroundColor: dailyData.raw_dates.map(d => (self.date === d ? '#8dc63f' : '#005ea2')),
                        borderRadius: 6,
                        maxBarThickness: 32,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onHover: (event, chartElement) => {
                        const target = event.native ? event.native.target : (event.chart ? event.chart.canvas : null);
                        if (target) target.style.cursor = chartElement.length ? 'pointer' : 'default';
                    },
                    onClick: (event, elements) => {
                        if (elements.length > 0) {
                            const idx = elements[0].index;
                            const selectedDate = dailyData.raw_dates[idx];
                            if (selectedDate) {
                                self.date = (self.date === selectedDate) ? '' : selectedDate;
                                self.applyFilter();
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 10,
                            cornerRadius: 10,
                            callbacks: {
                                afterLabel: () => 'Klik untuk isolasi tanggal ini'
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0 } },
                        x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                    }
                }
            });
        },

        renderStatusChart(data) {
            const self = this;
            const statusCanvas = document.getElementById('drillChartStatus');
            if (!statusCanvas) return;
            if (this.chartInstances.status) this.chartInstances.status.destroy();

            const statusData = data.status_distribution || { labels: [], raw_statuses: [], values: [], colors: [] };
            this.chartInstances.status = new Chart(statusCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: statusData.labels,
                    datasets: [{
                        data: statusData.values,
                        backgroundColor: statusData.colors,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    onHover: (event, chartElement) => {
                        const target = event.native ? event.native.target : (event.chart ? event.chart.canvas : null);
                        if (target) target.style.cursor = chartElement.length ? 'pointer' : 'default';
                    },
                    onClick: (event, elements) => {
                        if (elements.length > 0) {
                            const idx = elements[0].index;
                            const selectedStatus = statusData.raw_statuses[idx];
                            if (selectedStatus) {
                                self.status = (self.status === selectedStatus) ? 'semua' : selectedStatus;
                                self.applyFilter();
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 10, padding: 8, font: { size: 10, weight: 'bold' } }
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 10,
                            cornerRadius: 10,
                            callbacks: {
                                afterLabel: () => 'Klik untuk memfilter status ini'
                            }
                        }
                    }
                }
            });
        },

        renderHourlyChart(data) {
            const self = this;
            const hourlyCanvas = document.getElementById('drillChartHourly');
            if (!hourlyCanvas) return;
            if (this.chartInstances.hourly) this.chartInstances.hourly.destroy();

            const hourlyData = data.orders_per_hour || { labels: [], raw_hours: [], values: [] };
            this.chartInstances.hourly = new Chart(hourlyCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: hourlyData.labels,
                    datasets: [{
                        label: 'Pesanan',
                        data: hourlyData.values,
                        backgroundColor: hourlyData.raw_hours.map(h => (String(self.hour) === String(h) ? '#8dc63f' : '#f59e0b')),
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onHover: (event, chartElement) => {
                        const target = event.native ? event.native.target : (event.chart ? event.chart.canvas : null);
                        if (target) target.style.cursor = chartElement.length ? 'pointer' : 'default';
                    },
                    onClick: (event, elements) => {
                        if (elements.length > 0) {
                            const idx = elements[0].index;
                            const selectedHour = hourlyData.raw_hours[idx];
                            if (selectedHour !== undefined) {
                                self.hour = (String(self.hour) === String(selectedHour)) ? 'semua' : selectedHour;
                                self.applyFilter();
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 8,
                            cornerRadius: 8,
                            callbacks: {
                                afterLabel: () => 'Klik jam untuk telusuri'
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0 } },
                        x: { grid: { display: false }, ticks: { font: { size: 9 }, maxTicksLimit: 12 } }
                    }
                }
            });
        },

        renderTenantChart(data) {
            const self = this;
            const tenantCanvas = document.getElementById('drillChartTenant');
            if (!tenantCanvas) return;
            if (this.chartInstances.tenant) this.chartInstances.tenant.destroy();

            const tenantData = data.top_tenants || { labels: [], tenant_ids: [], values: [] };
            this.chartInstances.tenant = new Chart(tenantCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: tenantData.labels,
                    datasets: [{
                        label: 'Total Pesanan',
                        data: tenantData.values,
                        backgroundColor: tenantData.tenant_ids.map(tId => (String(self.tenantId) === String(tId) ? '#8dc63f' : '#005ea2')),
                        borderRadius: 4,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    onHover: (event, chartElement) => {
                        const target = event.native ? event.native.target : (event.chart ? event.chart.canvas : null);
                        if (target) target.style.cursor = chartElement.length ? 'pointer' : 'default';
                    },
                    onClick: (event, elements) => {
                        if (elements.length > 0) {
                            const idx = elements[0].index;
                            const selectedTenantId = tenantData.tenant_ids[idx];
                            if (selectedTenantId) {
                                self.tenantId = (String(self.tenantId) === String(selectedTenantId)) ? '' : String(selectedTenantId);
                                const selectTenantEl = document.getElementById('tenantSelect');
                                if (selectTenantEl) selectTenantEl.value = self.tenantId;
                                self.applyFilter();
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 8,
                            cornerRadius: 8,
                            callbacks: {
                                afterLabel: () => 'Klik untuk isolasi tenant ini'
                            }
                        }
                    },
                    scales: {
                        x: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0 } },
                        y: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' } } }
                    }
                }
            });
        },

        renderMenuChart(data) {
            const self = this;
            const menuCanvas = document.getElementById('drillChartMenu');
            if (!menuCanvas) return;
            if (this.chartInstances.menu) this.chartInstances.menu.destroy();

            const menuData = data.top_menus || { labels: [], menu_names: [], values: [] };
            this.chartInstances.menu = new Chart(menuCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: menuData.labels,
                    datasets: [{
                        label: 'Qty Terjual',
                        data: menuData.values,
                        backgroundColor: menuData.menu_names.map(m => (self.search === m ? '#8dc63f' : '#10b981')),
                        borderRadius: 4,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    onHover: (event, chartElement) => {
                        const target = event.native ? event.native.target : (event.chart ? event.chart.canvas : null);
                        if (target) target.style.cursor = chartElement.length ? 'pointer' : 'default';
                    },
                    onClick: (event, elements) => {
                        if (elements.length > 0) {
                            const idx = elements[0].index;
                            const selectedMenu = menuData.menu_names[idx];
                            if (selectedMenu) {
                                self.search = (self.search === selectedMenu) ? '' : selectedMenu;
                                self.applyFilter();
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 8,
                            cornerRadius: 8,
                            callbacks: {
                                afterLabel: () => 'Klik untuk memfilter menu ini'
                            }
                        }
                    },
                    scales: {
                        x: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0 } },
                        y: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' } } }
                    }
                }
            });
        },

        renderCategoryChart() {
            const self = this;
            if (!this.cachedChartsData || typeof window.Chart === 'undefined') return;
            const catCanvas = document.getElementById('drillChartCategory');
            if (!catCanvas) return;
            if (this.chartInstances.category) this.chartInstances.category.destroy();

            const catData = this.cachedChartsData.category_distribution || { labels: [], categories: [], order_counts: [], revenues: [], revenues_formatted: [] };
            const isVolume = this.categoryMetric === 'volume';
            const values = isVolume ? catData.order_counts : catData.revenues;

            this.chartInstances.category = new Chart(catCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: catData.labels,
                    datasets: [{
                        label: isVolume ? 'Volume Pesanan' : 'Total Omset (Rp)',
                        data: values,
                        backgroundColor: catData.categories.map(c => (self.category === c ? '#8dc63f' : '#005ea2')),
                        borderRadius: 6,
                        maxBarThickness: 38,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onHover: (event, chartElement) => {
                        const target = event.native ? event.native.target : (event.chart ? event.chart.canvas : null);
                        if (target) target.style.cursor = chartElement.length ? 'pointer' : 'default';
                    },
                    onClick: (event, elements) => {
                        if (elements.length > 0) {
                            const idx = elements[0].index;
                            const selectedCat = catData.categories[idx];
                            if (selectedCat) {
                                self.category = (self.category === selectedCat) ? 'semua' : selectedCat;
                                self.applyFilter();
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 10,
                            cornerRadius: 10,
                            callbacks: {
                                label: (ctx) => {
                                    const idx = ctx.dataIndex;
                                    const vol = catData.order_counts[idx] || 0;
                                    const rev = catData.revenues_formatted ? catData.revenues_formatted[idx] : ('Rp ' + (catData.revenues[idx] || 0));
                                    return [
                                        `Volume: ${vol} Pesanan`,
                                        `Omset: ${rev}`
                                    ];
                                },
                                afterLabel: () => 'Klik batang untuk cross-filter kategori'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                precision: isVolume ? 0 : undefined,
                                callback: function(value) {
                                    if (isVolume) return value;
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                    if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'k';
                                    return 'Rp ' + value;
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 10, weight: 'bold' },
                                maxRotation: 20,
                                minRotation: 0,
                            }
                        }
                    }
                }
            });
        },

        // Drawer Operations
        openDrawer(orderId) {
            this.drawerOpen = true;
            this.drawerLoading = true;
            this.drawerError = false;
            this.activeOrder = null;

            fetch(`/admin/analytics/drilldown/orders/${orderId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP Error ${res.status}`);
                return res.json();
            })
            .then(data => {
                this.activeOrder = data;
            })
            .catch(err => {
                console.error('Fetch order detail error:', err);
                this.drawerError = true;
            })
            .finally(() => {
                this.drawerLoading = false;
            });
        },

        copyOrderCode() {
            if (!this.activeOrder) return;
            navigator.clipboard.writeText(this.activeOrder.order_code).then(() => {
                this.copiedOrderCode = true;
                setTimeout(() => { this.copiedOrderCode = false; }, 2000);
            });
        },

        closeDrawer() {
            this.drawerOpen = false;
            this.activeOrder = null;
            this.copiedOrderCode = false;
        }
    };
};

if (typeof window.Alpine !== 'undefined') {
    window.Alpine.data('drillDownApp', () => window.drillDownApp());
} else {
    document.addEventListener('alpine:init', () => {
        if (typeof window.Alpine !== 'undefined') {
            window.Alpine.data('drillDownApp', () => window.drillDownApp());
        }
    });
}
</script>

<div class="space-y-6 pb-12" x-data="window.drillDownApp()">

    <!-- Page Header & Identity -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <!-- Breadcrumb -->
                <nav class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-2">
                    <a href="{{ route('admin.executive-dashboard') }}" class="hover:text-slate-600 transition">Analitik Operasional</a>
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-[#005ea2] font-extrabold">Drill-Down Pesanan</span>
                </nav>

                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Drill-Down & Analisis Multidimensi Pesanan
                </h1>
                <p class="text-sm font-semibold text-slate-600 mt-1">
                    Telusuri metrik agregat di grafik hingga ke rincian baris transaksi individual dengan kemampuan <em>cross-filtering</em> interaktif.
                </p>

                <div class="mt-2.5 inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-xs font-bold text-slate-800">
                    <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Rentang Terpilih: <strong class="text-[#005ea2]">{{ \Carbon\Carbon::parse($startDateFormatted)->format('d M Y') }}</strong> s/d <strong class="text-[#005ea2]">{{ \Carbon\Carbon::parse($endDateFormatted)->format('d M Y') }}</strong> ({{ (int)$days }} Hari)</span>
                </div>
            </div>

            <!-- Share / Copy URL Action -->
            <div class="shrink-0 flex items-center gap-2">
                <button type="button" 
                        @click="copyShareableUrl()" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold shadow-2xs transition cursor-pointer"
                        title="Salin tautan aktif beserta seluruh filter yang sedang diterapkan">
                    <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                    <span x-text="copiedUrl ? 'Tautan Tersalin!' : 'Bagikan / Salin Tautan'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- 1. Filter Bar Bersama & Filter Multidimensi di Slot -->
    <x-analytics.filter-bar 
        :action="route('admin.analytics.drilldown')" 
        :startDateFormatted="$startDateFormatted" 
        :endDateFormatted="$endDateFormatted" 
        :tenantId="$tenantId" 
        :tenants="$tenants"
    >
        <!-- Slot: Filter Multidimensi Terpadu (Clean Grid, Tanpa Emoji) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3.5 w-full">
            
            <!-- 1. Filter Kategori Tenant -->
            <div>
                <label for="filterCategorySelect" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span>Kategori Tenant</span>
                </label>
                <select id="filterCategorySelect" 
                        name="category"
                        x-model="category" 
                        @change="applyFilter()" 
                        class="w-full px-3 py-2 bg-slate-50 hover:bg-white border border-slate-200 hover:border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#005ea2] transition cursor-pointer shadow-2xs h-[40px]">
                    <option value="semua" {{ $initialCategory === 'semua' ? 'selected' : '' }}>Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $initialCategory === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 2. Filter Status Pesanan -->
            <div>
                <label for="filterStatusSelect" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Status Pesanan</span>
                </label>
                <select id="filterStatusSelect" 
                        name="status"
                        x-model="status" 
                        @change="applyFilter()" 
                        class="w-full px-3 py-2 bg-slate-50 hover:bg-white border border-slate-200 hover:border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#005ea2] transition cursor-pointer shadow-2xs h-[40px]">
                    <option value="semua" {{ $initialStatus === 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="menunggu" {{ $initialStatus === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diproses" {{ $initialStatus === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="siap" {{ $initialStatus === 'siap' ? 'selected' : '' }}>Siap</option>
                    <option value="selesai" {{ $initialStatus === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ $initialStatus === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- 3. Filter Kepatuhan SLA (15M) -->
            <div>
                <label for="filterSlaSelect" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Kepatuhan SLA (15M)</span>
                </label>
                <select id="filterSlaSelect" 
                        name="sla"
                        x-model="sla" 
                        @change="applyFilter()" 
                        class="w-full px-3 py-2 bg-slate-50 hover:bg-white border border-slate-200 hover:border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#005ea2] transition cursor-pointer shadow-2xs h-[40px]">
                    <option value="semua" {{ $initialSla === 'semua' ? 'selected' : '' }}>Semua Kepatuhan</option>
                    <option value="compliant" {{ $initialSla === 'compliant' ? 'selected' : '' }}>Sesuai SOP (≤ 15 mnt)</option>
                    <option value="breached" {{ $initialSla === 'breached' ? 'selected' : '' }}>Melewati SOP (> 15 mnt)</option>
                </select>
            </div>

            <!-- 4. Filter Jam Pesan (0-23) -->
            <div>
                <label for="filterHourSelect" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Jam Pesan</span>
                </label>
                <select id="filterHourSelect" 
                        name="hour"
                        x-model="hour" 
                        @change="applyFilter()" 
                        class="w-full px-3 py-2 bg-slate-50 hover:bg-white border border-slate-200 hover:border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#005ea2] transition cursor-pointer shadow-2xs h-[40px]">
                    <option value="semua" {{ $initialHour === 'semua' ? 'selected' : '' }}>Semua Jam</option>
                    @for($h = 0; $h < 24; $h++)
                        <option value="{{ $h }}" {{ (string)$initialHour === (string)$h ? 'selected' : '' }}>{{ sprintf('%02d:00 WIB', $h) }}</option>
                    @endfor
                </select>
            </div>

            <!-- 5. Filter Metode Ambil -->
            <div>
                <label for="filterPickupSelect" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Metode Ambil</span>
                </label>
                <select id="filterPickupSelect" 
                        name="pickup_method"
                        x-model="pickupMethod" 
                        @change="applyFilter()" 
                        class="w-full px-3 py-2 bg-slate-50 hover:bg-white border border-slate-200 hover:border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#005ea2] transition cursor-pointer shadow-2xs h-[40px]">
                    <option value="semua" {{ $initialPickupMethod === 'semua' ? 'selected' : '' }}>Semua Metode</option>
                    <option value="ambil_sendiri" {{ $initialPickupMethod === 'ambil_sendiri' ? 'selected' : '' }}>Ambil di Gerai</option>
                    <option value="diantar" {{ $initialPickupMethod === 'diantar' ? 'selected' : '' }}>Diantar ke Gate</option>
                </select>
            </div>

            <!-- 6. Pencarian Bebas Multi-Kriteria -->
            <div>
                <label for="searchInput" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Pencarian Bebas</span>
                </label>
                <div class="relative">
                    <input type="text" 
                           id="searchInput" 
                           x-model="search" 
                           @input.debounce.350ms="applyFilter()" 
                           placeholder="Kode order, flight, gate..." 
                           class="w-full pl-8 pr-7 py-2 bg-slate-50 hover:bg-white border border-slate-200 hover:border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#005ea2] transition shadow-2xs h-[40px]">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button x-show="search" 
                            @click="search = ''; applyFilter()" 
                            type="button" 
                            class="absolute inset-y-0 right-0 pr-2 flex items-center text-slate-400 hover:text-slate-600 text-sm font-bold cursor-pointer">
                        &times;
                    </button>
                </div>
            </div>

        </div>
    </x-analytics.filter-bar>

    <!-- 2. Jalur Drill (Hierarki Pembongkaran Data) -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2 text-xs font-bold">
                <span class="text-slate-500 uppercase tracking-wider flex items-center gap-1.5 mr-1 font-extrabold text-[11px]">
                    <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    <span>Jalur Drill (Hierarki):</span>
                </span>

                <!-- Level 0: Semua -->
                <button type="button" 
                        @click="resetHierarchy()" 
                        :class="((category === 'semua' || !category) && !tenantId && !date && (hour === 'semua' || hour === '')) ? 'bg-[#005ea2] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                        class="px-3 py-1.5 rounded-lg transition flex items-center gap-1 cursor-pointer font-extrabold text-xs">
                    <span>Semua Data</span>
                </button>

                <!-- Chevron -->
                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>

                <!-- Level 1: Kategori -->
                <template x-if="category && category !== 'semua'">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 font-extrabold text-xs">
                        <span>Kategori: <span x-text="category"></span></span>
                        <button type="button" @click="clearCategory()" class="hover:text-rose-600 p-0.5 rounded-md hover:bg-emerald-100 transition" title="Hapus Filter Kategori">&times;</button>
                    </span>
                </template>
                <template x-if="!category || category === 'semua'">
                    <span class="px-2 py-1 text-slate-400 font-medium italic text-xs">Semua Kategori</span>
                </template>

                <!-- Chevron -->
                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>

                <!-- Level 2: Tenant -->
                <template x-if="tenantId">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-[#005ea2] border border-blue-200 font-extrabold text-xs">
                        <span>Tenant: <span x-text="getTenantName(tenantId)"></span></span>
                        <button type="button" @click="clearTenant()" class="hover:text-rose-600 p-0.5 rounded-md hover:bg-blue-100 transition" title="Hapus Filter Tenant">&times;</button>
                    </span>
                </template>
                <template x-if="!tenantId">
                    <span class="px-2 py-1 text-slate-400 font-medium italic text-xs">Semua Tenant</span>
                </template>

                <!-- Chevron -->
                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>

                <!-- Level 3: Tanggal -->
                <template x-if="date">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200 font-extrabold text-xs">
                        <span>Tanggal: <span x-text="formatDateLabel(date)"></span></span>
                        <button type="button" @click="clearDate()" class="hover:text-rose-600 p-0.5 rounded-md hover:bg-indigo-100 transition" title="Hapus Filter Tanggal">&times;</button>
                    </span>
                </template>
                <template x-if="!date">
                    <span class="px-2 py-1 text-slate-400 font-medium italic text-xs">Semua Tanggal</span>
                </template>

                <!-- Chevron (jika jam aktif) -->
                <template x-if="hour !== 'semua' && hour !== '' && hour !== null">
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-800 border border-amber-200 font-extrabold text-xs">
                            <span>Jam: <span x-text="padHour(hour) + ':00 WIB'"></span></span>
                            <button type="button" @click="clearHour()" class="hover:text-rose-600 p-0.5 rounded-md hover:bg-amber-100 transition" title="Hapus Filter Jam">&times;</button>
                        </span>
                    </div>
                </template>

                <!-- Extra Active Filter Chips (Status / SLA / Pickup / Search) -->
                <template x-if="status !== 'semua' && status !== '' && status !== null">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 border border-slate-200 text-xs font-bold">
                        <span>Status: <strong x-text="status"></strong></span>
                        <button type="button" @click="status = 'semua'; applyFilter()" class="hover:text-rose-600">&times;</button>
                    </span>
                </template>

                <template x-if="sla !== 'semua' && sla !== '' && sla !== null">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 border border-slate-200 text-xs font-bold">
                        <span>SLA: <strong x-text="sla === 'compliant' ? '≤ 15m' : '> 15m'"></strong></span>
                        <button type="button" @click="sla = 'semua'; applyFilter()" class="hover:text-rose-600">&times;</button>
                    </span>
                </template>

                <template x-if="pickupMethod !== 'semua' && pickupMethod !== '' && pickupMethod !== null">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 border border-slate-200 text-xs font-bold">
                        <span>Metode: <strong x-text="pickupMethod === 'diantar' ? 'Diantar ke Gate' : 'Ambil di Gerai'"></strong></span>
                        <button type="button" @click="pickupMethod = 'semua'; applyFilter()" class="hover:text-rose-600">&times;</button>
                    </span>
                </template>

                <template x-if="search">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 border border-slate-200 text-xs font-bold">
                        <span>Cari: "<strong x-text="search"></strong>"</span>
                        <button type="button" @click="search = ''; applyFilter()" class="hover:text-rose-600">&times;</button>
                    </span>
                </template>
            </div>

            <!-- Reset All Filters Button -->
            <button type="button" 
                    @click="resetAllFilters()" 
                    class="text-xs font-bold text-rose-600 hover:text-rose-800 hover:bg-rose-50 px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span>Reset Semua Filter</span>
            </button>
        </div>
    </div>

    <!-- 3. Empat Kartu Ringkasan Hasil Filter (Clean SVG, No Emojis) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Kartu 1: Jumlah Pesanan -->
        <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200 hover:border-[#005ea2]/50 hover:shadow-sm transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Volume Pesanan</span>
                    <template x-if="ordersLoading">
                        <div class="h-8 w-24 bg-slate-200 animate-pulse rounded-lg mt-2"></div>
                    </template>
                    <template x-if="!ordersLoading">
                        <span class="text-2xl sm:text-3xl font-black text-slate-900 mt-1 block" x-text="summary.total_orders"></span>
                    </template>
                    <span class="text-[11px] font-semibold text-slate-500 mt-1 block">Pesanan sesuai filter aktif</span>
                </div>
                <div class="h-11 w-11 rounded-2xl bg-blue-50 text-[#005ea2] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full mt-4 overflow-hidden">
                <div class="bg-[#005ea2] h-full rounded-full transition-all duration-500" style="width: 100%;"></div>
            </div>
        </div>

        <!-- Kartu 2: Total Omset (Hanya is_paid) -->
        <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200 hover:border-emerald-400 hover:shadow-sm transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Omset Terfilter</span>
                    <template x-if="ordersLoading">
                        <div class="h-8 w-32 bg-slate-200 animate-pulse rounded-lg mt-2"></div>
                    </template>
                    <template x-if="!ordersLoading">
                        <span class="text-2xl sm:text-3xl font-black text-emerald-600 mt-1 block" x-text="summary.total_revenue_formatted"></span>
                    </template>
                    <span class="text-[11px] font-semibold text-slate-500 mt-1 block">Hanya pesanan lunas (is_paid)</span>
                </div>
                <div class="h-11 w-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full mt-4 overflow-hidden">
                <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: 100%;"></div>
            </div>
        </div>

        <!-- Kartu 3: Rata-Rata Waktu Saji -->
        <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200 hover:border-amber-400 hover:shadow-sm transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Rata-Rata Waktu Saji</span>
                    <template x-if="ordersLoading">
                        <div class="h-8 w-24 bg-slate-200 animate-pulse rounded-lg mt-2"></div>
                    </template>
                    <template x-if="!ordersLoading">
                        <span class="text-2xl sm:text-3xl font-black mt-1 block" 
                              :class="summary.avg_sla_minutes <= 15 ? 'text-slate-900' : 'text-rose-600'" 
                              x-text="summary.avg_sla_minutes + ' mnt'"></span>
                    </template>
                    <span class="text-[11px] font-semibold text-slate-500 mt-1 block">Standar SOP: maksimal 15.0 menit</span>
                </div>
                <div class="h-11 w-11 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full mt-4 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500" 
                     :class="summary.avg_sla_minutes <= 15 ? 'bg-emerald-500' : 'bg-rose-500'" 
                     :style="'width: ' + Math.min(100, Math.round((summary.avg_sla_minutes / 20) * 100)) + '%;'"></div>
            </div>
        </div>

        <!-- Kartu 4: Persentase Patuh SLA -->
        <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200 hover:border-purple-400 hover:shadow-sm transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Kepatuhan Standar SLA</span>
                    <template x-if="ordersLoading">
                        <div class="h-8 w-24 bg-slate-200 animate-pulse rounded-lg mt-2"></div>
                    </template>
                    <template x-if="!ordersLoading">
                        <span class="text-2xl sm:text-3xl font-black text-purple-700 mt-1 block" x-text="summary.sla_compliant_percentage + '%'"></span>
                    </template>
                    <span class="text-[11px] font-semibold text-slate-500 mt-1 block">Pesanan selesai ≤ 15 menit</span>
                </div>
                <div class="h-11 w-11 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full mt-4 overflow-hidden">
                <div class="bg-purple-600 h-full rounded-full transition-all duration-500" :style="'width: ' + summary.sla_compliant_percentage + '%;'"></div>
            </div>
        </div>
    </div>

    <!-- 4. Visual Cross-Filtering Hub (Ergonomis: 2 Tab Segmentasi Dimensi Visual) -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <!-- Visual Instruction & Segmented Switcher Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-100">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-blue-50 text-[#005ea2] border border-blue-200 mb-1.5">
                    <svg class="w-3.5 h-3.5 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Analisis Multidimensi & Fitur Drill-Down</span>
                </div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">
                    Eksplorasi Grafik Agregat (Cross-Filtering Engine)
                </h2>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">
                    Klik irisan status, batang kategori, jam, tenant, atau menu untuk mengisolasi data di tabel bawah secara dinamis.
                </p>
            </div>

            <!-- Controls: Tab Switcher & Collapse Toggle -->
            <div class="flex flex-wrap items-center gap-2.5">
                <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200 text-xs font-bold">
                    <button type="button" 
                            @click="switchTab('primary')" 
                            :class="activeTab === 'primary' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-3 py-1.5 rounded-lg transition cursor-pointer">
                        Dimensi Utama
                    </button>
                    <button type="button" 
                            @click="switchTab('secondary')" 
                            :class="activeTab === 'secondary' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-3 py-1.5 rounded-lg transition cursor-pointer">
                        Dimensi Sekunder
                    </button>
                </div>

                <button type="button" 
                        @click="showVisualCharts = !showVisualCharts" 
                        class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer"
                        title="Sembunyikan atau tampilkan grafik visual untuk efisiensi ruang layar">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="showVisualCharts ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                    </svg>
                    <span x-text="showVisualCharts ? 'Sembunyikan Grafik' : 'Tampilkan Grafik'"></span>
                </button>
            </div>
        </div>

        <!-- Charts Content (Collapsible) -->
        <div x-show="showVisualCharts" class="mt-6 space-y-6">
            
            <!-- Tab 1: Dimensi Utama (Kategori Tenant, Status, Jam Operasional) -->
            <div x-show="activeTab === 'primary'" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Chart 1: Volume & Omset per Kategori (2 Kolom) -->
                    <div class="lg:col-span-2 bg-slate-50/70 rounded-2xl p-5 border border-slate-200">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                            <div>
                                <h3 class="text-sm font-black text-slate-800 flex items-center gap-2">
                                    <span>Volume & Omset per Kategori Tenant</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Klik Batang = Filter Kategori</span>
                                </h3>
                                <p class="text-[11px] font-semibold text-slate-500">Perbandingan transaksi dan omset antar kategori mitra F&B</p>
                            </div>
                            <div class="inline-flex p-1 bg-white border border-slate-200 rounded-xl shadow-2xs text-[11px] font-bold shrink-0">
                                <button type="button" 
                                        @click="categoryMetric = 'volume'; renderCategoryChart()" 
                                        :class="categoryMetric === 'volume' ? 'bg-[#005ea2] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'" 
                                        class="px-2.5 py-1 rounded-lg transition cursor-pointer">
                                    Volume Pesanan
                                </button>
                                <button type="button" 
                                        @click="categoryMetric = 'revenue'; renderCategoryChart()" 
                                        :class="categoryMetric === 'revenue' ? 'bg-[#005ea2] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'" 
                                        class="px-2.5 py-1 rounded-lg transition cursor-pointer">
                                    Total Omset (Rp)
                                </button>
                            </div>
                        </div>
                        <div class="h-64 relative">
                            <canvas id="drillChartCategory"></canvas>
                        </div>
                    </div>

                    <!-- Chart 2: Distribusi Status (1 Kolom) -->
                    <div class="bg-slate-50/70 rounded-2xl p-5 border border-slate-200">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="text-sm font-black text-slate-800 flex items-center gap-2">
                                    <span>Distribusi Status Pesanan</span>
                                </h3>
                                <p class="text-[11px] font-semibold text-slate-500">Klik irisan untuk memfilter status</p>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">Klik Irisan</span>
                        </div>
                        <div class="h-64 relative flex items-center justify-center">
                            <canvas id="drillChartStatus"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Chart 3: Pesanan per Jam (00-23) (Full Width) -->
                <div class="bg-slate-50/70 rounded-2xl p-5 border border-slate-200">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="text-sm font-black text-slate-800 flex items-center gap-2">
                                <span>Pola Transaksi per Jam Operasional (00:00 - 23:00 WIB)</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">Klik Bar Jam = Isolasi Jam Sibuk</span>
                            </h3>
                            <p class="text-[11px] font-semibold text-slate-500">Analisis beban transaksi pada jam keberangkatan & transit penerbangan bandara</p>
                        </div>
                    </div>
                    <div class="h-56 relative">
                        <canvas id="drillChartHourly"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Dimensi Sekunder (Tren Harian, Sebaran Mitra Tenant, Menu Favorit) -->
            <div x-show="activeTab === 'secondary'" class="space-y-6">
                <!-- Chart 4: Pesanan per Hari (Harian) -->
                <div class="bg-slate-50/70 rounded-2xl p-5 border border-slate-200">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="text-sm font-black text-slate-800 flex items-center gap-2">
                                <span>Pesanan per Hari (Rentang Harian)</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-[#005ea2]">Klik Bar = Filter Tanggal</span>
                            </h3>
                            <p class="text-[11px] font-semibold text-slate-500">Volume transaksi harian dalam rentang waktu terpilih</p>
                        </div>
                    </div>
                    <div class="h-60 relative">
                        <canvas id="drillChartDaily"></canvas>
                    </div>
                </div>

                <!-- Chart 5 & 6: Top Mitra & Menu Terlaris -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Top Tenant -->
                    <div class="bg-slate-50/70 rounded-2xl p-5 border border-slate-200">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="text-sm font-black text-slate-800 flex items-center gap-2">
                                    <span>Top Mitra Tenant F&B</span>
                                </h3>
                                <p class="text-[11px] font-semibold text-slate-500">Tenant dengan volume pesanan terbanyak</p>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-[#005ea2]">Klik Bar</span>
                        </div>
                        <div class="h-60 relative">
                            <canvas id="drillChartTenant"></canvas>
                        </div>
                    </div>

                    <!-- Menu Terlaris -->
                    <div class="bg-slate-50/70 rounded-2xl p-5 border border-slate-200">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="text-sm font-black text-slate-800 flex items-center gap-2">
                                    <span>Menu Terlaris (Top Items)</span>
                                </h3>
                                <p class="text-[11px] font-semibold text-slate-500">Klik menu untuk mencari pesanan terkait</p>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Klik Menu</span>
                        </div>
                        <div class="h-60 relative">
                            <canvas id="drillChartMenu"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- 5. Tabel Transaksi Individual (Pembongkaran Agregat ke Rincian Data) -->
    <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-sm border border-slate-200" id="ordersTableSection">
        <!-- Table Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 mb-5 border-b border-slate-200">
            <div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span>Daftar Transaksi Individual</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-blue-50 text-[#005ea2] border border-blue-200" 
                          x-text="'Total: ' + pagination.total + ' Pesanan'"></span>
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">
                    Klik baris pesanan mana saja untuk membuka <strong>Drawer Rincian Lengkap & Linimasa SLA</strong>.
                </p>
            </div>

            <!-- Page Size & Refresh -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                    <span>Tampilkan:</span>
                    <select x-model.number="perPage" 
                            @change="page = 1; fetchSummaryAndOrders()" 
                            class="px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-bold text-slate-900 focus:outline-none cursor-pointer">
                        <option :value="10">10 Baris</option>
                        <option :value="25">25 Baris</option>
                        <option :value="50">50 Baris</option>
                    </select>
                </div>

                <button type="button" 
                        @click="fetchSummaryAndOrders(); fetchCharts()" 
                        class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer border border-slate-200"
                        title="Segarkan data">
                    <svg class="w-3.5 h-3.5" :class="ordersLoading ? 'animate-spin' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Segarkan</span>
                </button>
            </div>
        </div>

        <!-- Table Element -->
        <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-2xs mb-4">
            <table class="w-full min-w-[880px] text-left text-xs">
                <thead class="bg-slate-100 border-b border-slate-200 text-slate-600 font-black uppercase text-[10px] tracking-wider select-none">
                    <tr>
                        <!-- Waktu Order -->
                        <th class="py-3 px-3.5 cursor-pointer hover:bg-slate-200 transition" @click="sortBy('ordered_at')">
                            <div class="flex items-center gap-1">
                                <span>Waktu Order</span>
                                <span x-show="sort_by === 'ordered_at'" x-text="sort_dir === 'asc' ? '▲' : '▼'" class="text-[#005ea2]"></span>
                            </div>
                        </th>

                        <!-- ID Pesanan -->
                        <th class="py-3 px-3.5 cursor-pointer hover:bg-slate-200 transition" @click="sortBy('order_code')">
                            <div class="flex items-center gap-1">
                                <span>ID Order</span>
                                <span x-show="sort_by === 'order_code'" x-text="sort_dir === 'asc' ? '▲' : '▼'" class="text-[#005ea2]"></span>
                            </div>
                        </th>

                        <!-- Mitra Tenant -->
                        <th class="py-3 px-3.5">Mitra Tenant</th>

                        <!-- Penumpang & Flight -->
                        <th class="py-3 px-3.5">Penumpang & Gate</th>

                        <!-- Rincian Menu -->
                        <th class="py-3 px-3.5">Menu Dipesan</th>

                        <!-- Waktu Saji (SLA) -->
                        <th class="py-3 px-3.5 text-center cursor-pointer hover:bg-slate-200 transition" @click="sortBy('sla_minutes')">
                            <div class="flex items-center justify-center gap-1">
                                <span>Waktu Saji (SLA)</span>
                                <span x-show="sort_by === 'sla_minutes'" x-text="sort_dir === 'asc' ? '▲' : '▼'" class="text-[#005ea2]"></span>
                            </div>
                        </th>

                        <!-- Total Transaksi -->
                        <th class="py-3 px-3.5 text-right cursor-pointer hover:bg-slate-200 transition" @click="sortBy('total_amount')">
                            <div class="flex items-center justify-end gap-1">
                                <span>Total (Rp)</span>
                                <span x-show="sort_by === 'total_amount'" x-text="sort_dir === 'asc' ? '▲' : '▼'" class="text-[#005ea2]"></span>
                            </div>
                        </th>

                        <!-- Status -->
                        <th class="py-3 px-3.5 text-center cursor-pointer hover:bg-slate-200 transition" @click="sortBy('status')">
                            <div class="flex items-center justify-center gap-1">
                                <span>Status</span>
                                <span x-show="sort_by === 'status'" x-text="sort_dir === 'asc' ? '▲' : '▼'" class="text-[#005ea2]"></span>
                            </div>
                        </th>

                        <!-- Aksi -->
                        <th class="py-3 px-3.5 text-center">Aksi Detail</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 bg-white">
                    <!-- 1. Skeleton Loading State -->
                    <template x-if="ordersLoading">
                        <template x-for="i in [1,2,3,4,5]" :key="i">
                            <tr class="animate-pulse">
                                <td class="py-3.5 px-3.5"><div class="h-4 bg-slate-200 rounded w-20"></div></td>
                                <td class="py-3.5 px-3.5"><div class="h-4 bg-slate-200 rounded w-16"></div></td>
                                <td class="py-3.5 px-3.5"><div class="h-4 bg-slate-200 rounded w-28"></div></td>
                                <td class="py-3.5 px-3.5"><div class="h-4 bg-slate-200 rounded w-24"></div></td>
                                <td class="py-3.5 px-3.5"><div class="h-4 bg-slate-200 rounded w-36"></div></td>
                                <td class="py-3.5 px-3.5 text-center"><div class="h-4 bg-slate-200 rounded w-16 mx-auto"></div></td>
                                <td class="py-3.5 px-3.5 text-right"><div class="h-4 bg-slate-200 rounded w-20 ml-auto"></div></td>
                                <td class="py-3.5 px-3.5 text-center"><div class="h-4 bg-slate-200 rounded w-14 mx-auto"></div></td>
                                <td class="py-3.5 px-3.5 text-center"><div class="h-6 bg-slate-200 rounded w-16 mx-auto"></div></td>
                            </tr>
                        </template>
                    </template>

                    <!-- 2. Error State -->
                    <tr x-show="!ordersLoading && ordersError">
                        <td colspan="9" class="py-10 px-4 text-center">
                            <div class="max-w-md mx-auto text-rose-600 bg-rose-50 border border-rose-200 p-4 rounded-xl">
                                <svg class="w-8 h-8 text-rose-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p class="text-xs font-bold" x-text="ordersErrorMessage || 'Gagal mengambil data pesanan dari server.'"></p>
                                <button type="button" 
                                        @click="fetchSummaryAndOrders()" 
                                        class="mt-3 px-4 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-bold hover:bg-rose-700 transition cursor-pointer">
                                    Coba Lagi
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- 3. Empty State -->
                    <tr x-show="!ordersLoading && !ordersError && orders.length === 0">
                        <td colspan="9" class="py-12 px-4 text-center">
                            <div class="max-w-sm mx-auto text-slate-400">
                                <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <p class="text-sm font-extrabold text-slate-800">Tidak ada transaksi yang cocok</p>
                                <p class="text-xs text-slate-500 mt-1">Coba sesuaikan kata kunci pencarian, filter status, atau rentang tanggal.</p>
                                <button type="button" 
                                        @click="resetAllFilters()" 
                                        class="mt-4 px-4 py-2 rounded-xl text-xs font-bold bg-blue-50 text-[#005ea2] hover:bg-blue-100 transition cursor-pointer border border-blue-200">
                                    Reset Semua Filter
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- 4. Data Rows -->
                    <template x-if="!ordersLoading && !ordersError && orders.length > 0">
                        <template x-for="order in orders" :key="order.id">
                            <tr @click="openDrawer(order.id)" 
                                class="hover:bg-blue-50/50 cursor-pointer transition group">
                                <!-- Waktu Order -->
                                <td class="py-3 px-3.5 whitespace-nowrap">
                                    <span class="font-black text-slate-900 block text-xs" x-text="order.time_formatted"></span>
                                    <span class="text-[10px] text-slate-400 font-semibold" x-text="order.ordered_at_formatted"></span>
                                </td>

                                <!-- ID Pesanan -->
                                <td class="py-3 px-3.5 whitespace-nowrap">
                                    <span class="font-mono font-bold text-xs px-2 py-0.5 rounded bg-blue-50 text-[#005ea2] border border-blue-200 group-hover:bg-[#005ea2] group-hover:text-white transition-colors" x-text="order.order_code"></span>
                                </td>

                                <!-- Mitra Tenant & Lokasi -->
                                <td class="py-3 px-3.5">
                                    <span class="font-bold text-slate-900 block" x-text="order.tenant_name"></span>
                                    <span class="text-[10px] text-slate-500 font-medium" x-text="order.tenant_location + ' • ' + order.tenant_zone"></span>
                                </td>

                                <!-- Penumpang & Flight / Gate -->
                                <td class="py-3 px-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-800 block" x-text="order.customer_name"></span>
                                    <div class="flex items-center gap-1.5 mt-0.5 text-[10px]">
                                        <div class="flex items-center gap-1 text-slate-600 font-bold" x-show="order.flight_number !== '-'">
                                            <svg class="w-3 h-3 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                            <span x-text="order.flight_number"></span>
                                        </div>
                                        <span class="text-slate-300" x-show="order.flight_number !== '-'">•</span>
                                        <span class="font-bold text-slate-600 px-1.5 py-0.2 rounded bg-slate-100" x-text="formatGate(order.gate)"></span>
                                    </div>
                                </td>

                                <!-- Rincian Menu -->
                                <td class="py-3 px-3.5 max-w-xs">
                                    <p class="text-[11px] text-slate-600 truncate font-medium" :title="order.items_summary" x-text="order.items_summary"></p>
                                    <span class="text-[10px] font-bold text-slate-400" x-text="order.items_count + ' item • ' + order.pickup_method_label"></span>
                                </td>

                                <!-- Durasi SLA -->
                                <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                    <template x-if="order.sla_minutes !== null">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold"
                                              :class="order.sla_compliant ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'">
                                            <svg x-show="order.sla_compliant" class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <svg x-show="!order.sla_compliant" class="w-3 h-3 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span x-text="order.sla_minutes + ' mnt'"></span>
                                        </span>
                                    </template>
                                    <template x-if="order.sla_minutes === null">
                                        <span class="text-[11px] text-slate-400 font-bold">-</span>
                                    </template>
                                </td>

                                <!-- Total Transaksi -->
                                <td class="py-3 px-3.5 text-right whitespace-nowrap">
                                    <span class="font-black text-slate-900 block" x-text="order.total_amount_formatted"></span>
                                    <span class="text-[10px] font-bold" :class="order.is_paid ? 'text-emerald-600' : 'text-amber-600'" x-text="order.is_paid ? 'Lunas (' + order.payment_method + ')' : 'Belum Bayar'"></span>
                                </td>

                                <!-- Status Pesanan -->
                                <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                                          :class="{
                                              'bg-amber-100 text-amber-800': order.status === 'menunggu',
                                              'bg-blue-100 text-blue-800': order.status === 'diproses',
                                              'bg-purple-100 text-purple-800': order.status === 'siap',
                                              'bg-emerald-100 text-emerald-800': order.status === 'selesai',
                                              'bg-rose-100 text-rose-800': order.status === 'dibatalkan'
                                          }"
                                          x-text="order.status">
                                    </span>
                                </td>

                                <!-- Tombol Aksi -->
                                <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                    <button type="button" 
                                            @click.stop="openDrawer(order.id)" 
                                            class="px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 hover:bg-[#005ea2] hover:text-white text-slate-700 transition cursor-pointer">
                                        Detail &rarr;
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs font-bold text-slate-600">
            <div>
                <span x-text="'Menampilkan baris ' + (pagination.from || 0) + ' s/d ' + (pagination.to || 0) + ' dari total ' + pagination.total + ' data'"></span>
                <span class="text-slate-400 mx-1">•</span>
                <span x-text="'Halaman ' + pagination.current_page + ' dari ' + (pagination.last_page || 1)"></span>
            </div>

            <div class="flex items-center gap-1.5">
                <button type="button" 
                        @click="goToPage(pagination.current_page - 1)" 
                        :disabled="pagination.current_page <= 1 || ordersLoading" 
                        :class="pagination.current_page <= 1 ? 'opacity-40 cursor-not-allowed bg-slate-100 text-slate-400' : 'bg-white hover:bg-slate-100 text-slate-800 cursor-pointer'"
                        class="px-3.5 py-1.5 rounded-lg border border-slate-200 transition font-bold">
                    &larr; Sebelumnya
                </button>

                <button type="button" 
                        @click="goToPage(pagination.current_page + 1)" 
                        :disabled="pagination.current_page >= pagination.last_page || ordersLoading" 
                        :class="pagination.current_page >= pagination.last_page ? 'opacity-40 cursor-not-allowed bg-slate-100 text-slate-400' : 'bg-white hover:bg-slate-100 text-slate-800 cursor-pointer'"
                        class="px-3.5 py-1.5 rounded-lg border border-slate-200 transition font-bold">
                    Berikutnya &rarr;
                </button>
            </div>
        </div>
    </div>

    <!-- 6. Panel Samping (Slide-Over Drawer) Rincian Lengkap Pesanan -->
    <div x-show="drawerOpen" 
         class="fixed inset-0 z-50 overflow-hidden" 
         x-cloak>
        <!-- Overlay Backdrop -->
        <div x-show="drawerOpen" 
             @click="closeDrawer()" 
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-0 sm:pl-10">
            <!-- Drawer Content Panel -->
            <div x-show="drawerOpen" 
                 x-transition:enter="transform transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-xl bg-[#f8fafc] shadow-2xl flex flex-col h-full border-l border-slate-200">
                
                <!-- Drawer Header: Executive Aviation Identity -->
                <div class="px-6 py-5 bg-gradient-to-r from-[#002d54] via-[#004b80] to-[#0066a4] text-white flex items-center justify-between shrink-0 border-b border-blue-900/50 shadow-md relative overflow-hidden">
                    <div class="flex items-center gap-3.5 relative z-10">
                        <div class="h-11 w-11 rounded-2xl bg-white/15 border border-white/20 backdrop-blur-md flex items-center justify-center text-white shadow-inner shrink-0">
                            <svg class="w-5 h-5 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-blue-200 block">
                                Bandara Juanda • Rincian Pesanan
                            </span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <h3 class="text-base sm:text-lg font-black text-white tracking-tight flex items-center gap-1.5" x-text="activeOrder ? '#' + activeOrder.order_code : 'Memuat Data...'"></h3>
                                
                                <template x-if="activeOrder">
                                    <button type="button" 
                                            @click="copyOrderCode()" 
                                            class="px-2 py-0.5 rounded bg-white/15 hover:bg-white/25 active:bg-white/35 text-blue-100 text-[11px] font-bold transition flex items-center gap-1" 
                                            title="Salin Kode Pesanan">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <span x-text="copiedOrderCode ? 'Tersalin' : 'Salin'"></span>
                                    </button>
                                </template>

                                <template x-if="activeOrder">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-xs"
                                          :class="{
                                              'bg-amber-400 text-slate-950 font-bold': activeOrder.status === 'menunggu',
                                              'bg-sky-400 text-slate-950 font-bold': activeOrder.status === 'diproses',
                                              'bg-purple-300 text-purple-950 font-bold': activeOrder.status === 'siap',
                                              'bg-emerald-400 text-slate-950 font-bold': activeOrder.status === 'selesai',
                                              'bg-rose-500 text-white font-bold': activeOrder.status === 'dibatalkan'
                                          }"
                                          x-text="activeOrder.status_label || activeOrder.status">
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <button type="button" 
                            @click="closeDrawer()" 
                            class="relative z-10 w-9 h-9 rounded-full bg-white/10 hover:bg-white/25 active:bg-white/35 text-white/90 hover:text-white transition flex items-center justify-center cursor-pointer border border-white/20"
                            title="Tutup Panel">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Drawer Body (Scrollable) -->
                <div class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-4">
                    <!-- Loading Skeleton -->
                    <template x-if="drawerLoading">
                        <div class="space-y-4 animate-pulse">
                            <div class="h-28 bg-white rounded-2xl border border-slate-200"></div>
                            <div class="h-36 bg-white rounded-2xl border border-slate-200"></div>
                            <div class="h-44 bg-white rounded-2xl border border-slate-200"></div>
                        </div>
                    </template>

                    <!-- Error State -->
                    <template x-if="!drawerLoading && drawerError">
                        <div class="p-8 text-center text-rose-600 bg-white rounded-2xl border border-rose-200 shadow-sm">
                            <svg class="w-12 h-12 text-rose-500 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-base font-bold text-rose-900">Gagal Mengambil Data Rincian Pesanan</p>
                            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Terjadi kendala saat memuat data dari server. Silakan coba kembali.</p>
                            <button type="button" 
                                    @click="openDrawer(activeOrder ? activeOrder.id : 0)" 
                                    class="mt-4 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition">
                                Coba Lagi
                            </button>
                        </div>
                    </template>

                    <!-- Order Details Content -->
                    <template x-if="!drawerLoading && !drawerError && activeOrder">
                        <div class="space-y-4">
                            
                            <!-- 1. Hero Financial & Summary Card -->
                            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm relative overflow-hidden">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Total Tagihan Transaksi</span>
                                        <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5" x-text="activeOrder.total_amount_formatted"></div>
                                        <div class="flex items-center gap-1.5 mt-1.5 text-xs font-bold text-slate-500">
                                            <span>Biaya Antar Porter:</span>
                                            <span class="text-emerald-700 font-extrabold" x-text="activeOrder.delivery_fee > 0 ? activeOrder.delivery_fee_formatted : 'Gratis (Rp 0)'"></span>
                                        </div>
                                    </div>
                                    <div class="text-right flex flex-col items-end">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-2xs border"
                                              :class="activeOrder.is_paid 
                                                  ? 'bg-emerald-50 text-emerald-800 border-emerald-200' 
                                                  : 'bg-amber-50 text-amber-800 border-amber-200'">
                                            <svg x-show="activeOrder.is_paid" class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span x-text="activeOrder.is_paid ? 'Lunas • ' + activeOrder.payment_method : 'Belum Bayar'"></span>
                                        </span>
                                        <span class="text-[11px] font-bold text-slate-400 mt-2 block" x-text="activeOrder.ordered_at_formatted"></span>
                                    </div>
                                </div>

                                <!-- Quick Highlights Row -->
                                <div class="grid grid-cols-2 gap-2.5 mt-4 pt-4 border-t border-slate-100 text-xs">
                                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/70">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Mitra Gerai F&B</span>
                                        <span class="font-extrabold text-slate-900 block mt-0.5 truncate" x-text="activeOrder.tenant.name"></span>
                                        <span class="text-[10px] text-slate-500 block truncate" x-text="activeOrder.tenant.location + ' • ' + activeOrder.tenant.zone"></span>
                                    </div>
                                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/70">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Metode Ambil</span>
                                        <span class="font-extrabold text-[#005ea2] block mt-0.5 truncate" x-text="activeOrder.pickup_method_label"></span>
                                        <span class="text-[10px] text-slate-500 block truncate" x-text="activeOrder.delivery_target"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. SLA SOP Cockpit (Standar Pelayanan 15 Menit) -->
                            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm space-y-3.5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-bold">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="text-xs font-bold text-slate-900 block">Kepatuhan Standar Waktu Saji (SOP)</span>
                                            <span class="text-[10px] font-bold text-slate-400 block">Batas Maksimal Layanan F&B Juanda: 15.0 Menit</span>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold shadow-2xs border"
                                          :class="activeOrder.sla.compliant === true 
                                              ? 'bg-emerald-50 text-emerald-800 border-emerald-200' 
                                              : (activeOrder.sla.compliant === false 
                                                  ? 'bg-rose-50 text-rose-800 border-rose-200' 
                                                  : 'bg-slate-100 text-slate-600 border-slate-200')">
                                        <span x-text="activeOrder.sla.compliant === true 
                                            ? 'Sesuai SOP' 
                                            : (activeOrder.sla.compliant === false ? 'Melewati SOP' : 'Sedang Diproses')"></span>
                                    </span>
                                </div>

                                <!-- SLA Realization vs Threshold Comparison -->
                                <div class="grid grid-cols-2 gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200/70 text-xs">
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Realisasi Waktu Saji</span>
                                        <span class="text-xl font-black text-slate-900 mt-0.5 block" 
                                              x-text="activeOrder.sla.minutes !== null ? activeOrder.sla.minutes + ' Menit' : 'Dalam Proses'"></span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Target Maksimal KPI</span>
                                        <span class="text-xl font-black text-[#005ea2] mt-0.5 block">15.0 Menit</span>
                                    </div>
                                </div>

                                <!-- Visual Progress Gauge -->
                                <div>
                                    <div class="relative w-full bg-slate-200 h-2.5 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-700 ease-out" 
                                             :class="activeOrder.sla.compliant === false ? 'bg-gradient-to-r from-amber-500 to-rose-600' : 'bg-gradient-to-r from-teal-400 to-emerald-500'" 
                                             :style="'width: ' + Math.min(100, Math.round(((activeOrder.sla.minutes || 0) / 20) * 100)) + '%;'"></div>
                                    </div>
                                    <div class="flex justify-between text-[10px] font-bold text-slate-400 mt-1.5 px-0.5">
                                        <span>0 mnt</span>
                                        <span class="text-rose-600 font-bold">Batas Standar (15 mnt)</span>
                                        <span>20 mnt</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Aviation Boarding Pass Card (Penumpang & Tiket) -->
                            <div class="bg-gradient-to-br from-white to-blue-50/40 rounded-2xl border border-blue-200 shadow-sm overflow-hidden">
                                <!-- Boarding Pass Top Ribbon -->
                                <div class="px-4 py-2.5 bg-[#003860] text-white flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-blue-100">Informasi Penumpang & Boarding</span>
                                    </div>
                                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-white/20 text-white" x-text="activeOrder.customer.flight_number || 'PENERBANGAN'"></span>
                                </div>

                                <div class="p-4 space-y-3.5">
                                    <!-- Passenger Identity Row -->
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-blue-100 border border-blue-200 flex items-center justify-center text-sm font-bold text-[#005ea2]">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="text-sm font-bold text-slate-900 block" x-text="activeOrder.customer.name"></span>
                                                <span class="text-xs font-semibold text-slate-500 block" x-text="activeOrder.customer.phone"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Flight & Gate Aviation Grid -->
                                    <div class="grid grid-cols-3 gap-2 pt-3 border-t border-dashed border-blue-200">
                                        <div class="p-2.5 rounded-xl bg-white border border-blue-100 text-center">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Penerbangan</span>
                                            <span class="text-xs font-bold text-slate-900 mt-0.5 block truncate" x-text="activeOrder.customer.flight_number"></span>
                                        </div>
                                        <div class="p-2.5 rounded-xl bg-blue-50 border border-blue-200 text-center">
                                            <span class="text-[10px] font-bold text-[#005ea2] uppercase tracking-wider block">Boarding Gate</span>
                                            <span class="text-xs font-bold text-[#005ea2] mt-0.5 block truncate" x-text="formatGate(activeOrder.customer.gate)"></span>
                                        </div>
                                        <div class="p-2.5 rounded-xl bg-white border border-blue-100 text-center">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Jam Boarding</span>
                                            <span class="text-xs font-bold text-slate-900 mt-0.5 block truncate" x-text="activeOrder.customer.boarding_time"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Linimasa Progres Pesanan (Journey Timeline) -->
                            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm space-y-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Linimasa Perjalanan Pesanan</h4>
                                </div>

                                <div class="relative pl-6 space-y-3.5 border-l-2 border-slate-200 ml-2">
                                    <template x-for="(step, idx) in activeOrder.timeline" :key="idx">
                                        <div class="relative">
                                            <!-- Bullet Node -->
                                            <div class="absolute -left-[31px] top-0.5 h-4 w-4 rounded-full border-2 border-white shadow-xs" 
                                                 :class="step.is_cancelled ? 'bg-rose-500 ring-4 ring-rose-100' : (step.done ? 'bg-emerald-500 ring-4 ring-emerald-100' : 'bg-slate-300 ring-2 ring-slate-100')"></div>
                                            
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/70">
                                                <div class="flex items-center justify-between gap-2">
                                                    <span class="text-xs font-bold text-slate-900" x-text="step.stage"></span>
                                                    <span class="text-[10px] font-bold text-slate-400 whitespace-nowrap px-2 py-0.5 rounded bg-white border border-slate-200/80" x-text="step.time"></span>
                                                </div>
                                                <p class="text-[11px] font-medium text-slate-600 mt-1" x-text="step.desc"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- 5. Rincian Menu Pesanan (Itemized Gourmet Receipt) -->
                            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm space-y-3.5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center font-bold">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Rincian Menu Item</h4>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700" 
                                          x-text="activeOrder.items ? activeOrder.items.length + ' Variasi Menu' : ''"></span>
                                </div>

                                <div class="divide-y divide-slate-100">
                                    <template x-for="item in activeOrder.items" :key="item.id">
                                        <div class="py-3 flex items-start justify-between gap-3 text-xs">
                                            <div class="flex items-start gap-3">
                                                <span class="w-7 h-7 rounded-lg bg-[#005ea2] text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-2xs mt-0.5" 
                                                      x-text="item.quantity + 'x'"></span>
                                                <div>
                                                    <span class="font-bold text-slate-900 block text-xs leading-snug" x-text="item.name"></span>
                                                    <span class="text-[11px] font-medium text-slate-400 mt-0.5 block" x-text="'Harga Satuan: ' + item.unit_price_formatted"></span>
                                                </div>
                                            </div>
                                            <span class="font-bold text-slate-900 whitespace-nowrap text-xs sm:text-sm mt-0.5" x-text="item.subtotal_formatted"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- 6. Komplain Terkait (Jika Ada) -->
                            <template x-if="activeOrder.complaints && activeOrder.complaints.length > 0">
                                <div class="p-4 bg-rose-50 rounded-2xl border border-rose-200 text-xs space-y-2">
                                    <span class="font-bold text-rose-800 uppercase tracking-wider flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span>Riwayat Komplain Terkait Pesanan Ini</span>
                                    </span>
                                    <template x-for="c in activeOrder.complaints" :key="c.id">
                                        <div class="p-3 bg-white rounded-xl border border-rose-100 shadow-2xs">
                                            <div class="flex items-center justify-between font-bold">
                                                <span class="text-rose-700 uppercase text-[10px]" x-text="c.category"></span>
                                                <span class="text-[10px] px-2 py-0.5 rounded bg-rose-100 text-rose-800 uppercase" x-text="c.status"></span>
                                            </div>
                                            <p class="text-slate-700 mt-1 font-medium" x-text="c.description"></p>
                                        </div>
                                    </template>
                                </div>
                            </template>

                        </div>
                    </template>
                </div>

                <!-- Drawer Footer -->
                <div class="p-4 sm:px-6 bg-white border-t border-slate-200 flex items-center justify-between shrink-0 shadow-lg">
                    <button type="button" 
                            @click="copyOrderCode()" 
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer border border-slate-200">
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span x-text="copiedOrderCode ? 'Kode Tersalin!' : 'Salin Kode Order'"></span>
                    </button>

                    <button type="button" 
                            @click="closeDrawer()" 
                            class="px-5 py-2 bg-[#005ea2] hover:bg-[#004c80] active:bg-[#003b66] text-white rounded-xl text-xs font-bold transition cursor-pointer shadow-md flex items-center gap-2">
                        <span>Tutup Panel</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection