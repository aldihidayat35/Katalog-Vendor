<x-admin-layout>
    <x-slot:heading>Dashboard Admin</x-slot:heading>

    <x-admin.page-header
        title="Ringkasan Operasional"
        subtitle="Pantau pesanan masuk, tren transaksi tahunan, dan performa katalog produk terkini."
    >
        <x-slot:actions>
            <x-button href="{{ route('admin.products.create') }}" variant="primary" size="sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                + Tambah Produk
            </x-button>
            <x-button href="{{ route('admin.orders.index') }}" variant="outline" size="sm">
                Lihat Semua Pesanan
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- 1. STATISTIC CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
        <!-- New Leads -->
        <x-admin.stat-card
            title="Lead Baru"
            :value="$stats['new_leads']"
            description="Menunggu follow-up WhatsApp"
            color="amber"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </x-slot:icon>
        </x-admin.stat-card>

        <!-- Orders This Month -->
        <x-admin.stat-card
            title="Pesanan Bulan Ini"
            :value="$stats['monthly_orders']"
            description="Total checkout bulan ini"
            color="blue"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </x-slot:icon>
        </x-admin.stat-card>

        <!-- Revenue This Month -->
        <x-admin.stat-card
            title="Nilai Pesanan Bulan Ini"
            :value="rupiah($stats['monthly_revenue'])"
            description="Akumulasi omset pesanan"
            color="emerald"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </x-slot:icon>
        </x-admin.stat-card>

        <!-- Products (Active / Inactive) -->
        <x-admin.stat-card
            title="Total Produk"
            :value="$stats['total_products']"
            :description="$stats['active_products'] . ' aktif / ' . $stats['inactive_products'] . ' nonaktif'"
            color="champagne"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </x-slot:icon>
        </x-admin.stat-card>

        <!-- Total Members -->
        <x-admin.stat-card
            title="Total Member"
            :value="$stats['total_members']"
            description="Pengguna terdaftar"
            color="purple"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </x-slot:icon>
        </x-admin.stat-card>
    </div>

    <!-- 2. ANALYTICS CHARTS SECTION (TREN BULANAN & PIE CHART STATUS) -->
    <div
        x-data="dashboardAnalytics()"
        x-init="init()"
        class="mb-8 space-y-4"
    >
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            
            <!-- LEFT COLUMN: GRAFIK TREN PEMESANAN BULANAN (7 COLS ON LG, 8 ON XL) -->
            <div class="lg:col-span-7 xl:col-span-8 bg-white rounded-3xl p-6 sm:p-7 border border-stone-200/80 shadow-subtle flex flex-col justify-between">
                <div>
                    <!-- Header with Title & Year Filter -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 border-b border-stone-100">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-champagne-600 animate-pulse"></span>
                                <h3 class="text-lg font-serif font-bold text-charcoal-900">Tren Pemesanan Bulanan</h3>
                            </div>
                            <p class="text-xs text-stone-500 mt-0.5">Grafik total pemesanan dan estimasi omset per bulan dalam setahun</p>
                        </div>

                        <!-- Year Filter Dropdown -->
                        <div class="flex items-center gap-2">
                            <label for="yearFilter" class="text-xs font-semibold text-stone-500 uppercase tracking-wider shrink-0">Tahun:</label>
                            <div class="relative">
                                <select
                                    id="yearFilter"
                                    x-model="selectedYear"
                                    @change="fetchData()"
                                    :disabled="isLoading"
                                    class="text-xs font-bold font-serif text-charcoal-900 bg-stone-50 border border-stone-200 rounded-xl px-3 py-2 pr-8 focus:ring-champagne-500 focus:border-champagne-500 transition-colors cursor-pointer"
                                >
                                    @foreach($availableYears as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Year Summary Metrics -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-4 pb-2">
                        <div class="bg-ivory-100/70 rounded-2xl p-3 border border-champagne-200/60">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-stone-500 block">Total Pesanan (<span x-text="selectedYear"></span>)</span>
                            <div class="flex items-baseline gap-1 mt-0.5">
                                <span class="text-xl font-serif font-bold text-charcoal-900" x-text="trendTotalOrders"></span>
                                <span class="text-xs text-stone-500">order</span>
                            </div>
                        </div>

                        <div class="bg-ivory-100/70 rounded-2xl p-3 border border-champagne-200/60">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-stone-500 block">Total Omset (<span x-text="selectedYear"></span>)</span>
                            <span class="text-base sm:text-lg font-serif font-bold text-champagne-700 block truncate mt-0.5" x-text="formatRupiah(trendTotalRevenue)"></span>
                        </div>

                        <div class="col-span-2 sm:col-span-1 bg-stone-50 rounded-2xl p-3 border border-stone-200/60 flex items-center justify-between sm:block">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-stone-500 block">Rata-rata / Bulan</span>
                            <span class="text-sm font-semibold text-stone-700 block mt-0.5" x-text="(trendTotalOrders / 12).toFixed(1) + ' order/bln'"></span>
                        </div>
                    </div>
                </div>

                <!-- Canvas Chart Area with Loading Overlay -->
                <div class="relative mt-4 flex-1 min-h-[280px]">
                    <div x-show="isLoading" class="absolute inset-0 z-10 bg-white/70 backdrop-blur-2xs flex items-center justify-center rounded-2xl" style="display: none;">
                        <div class="flex items-center gap-2 text-xs font-semibold text-charcoal-800 bg-white px-4 py-2 rounded-xl shadow-md border border-stone-200">
                            <svg class="w-4 h-4 animate-spin text-champagne-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Memuat data...</span>
                        </div>
                    </div>
                    <canvas id="monthlyTrendChart" class="w-full h-full"></canvas>
                </div>

                <!-- Footer Hint -->
                <div class="pt-3 mt-2 border-t border-stone-100 flex items-center justify-between text-[11px] text-stone-400">
                    <span>Garis Emas: Jumlah Pesanan • Garis Gelap: Nilai Transaksi (Rp)</span>
                    <span class="hidden sm:inline">Arahkan kursor ke titik grafik untuk detail</span>
                </div>
            </div>

            <!-- RIGHT COLUMN: PIE/DOUGHNUT CHART STATUS PESANAN (5 COLS ON LG, 4 ON XL) -->
            <div class="lg:col-span-5 xl:col-span-4 bg-white rounded-3xl p-6 sm:p-7 border border-stone-200/80 shadow-subtle flex flex-col justify-between">
                <div>
                    <!-- Header with Title & Month Filter -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-5 border-b border-stone-100">
                        <div>
                            <h3 class="text-lg font-serif font-bold text-charcoal-900">Status Pesanan</h3>
                            <p class="text-xs text-stone-500 mt-0.5">Proporsi status order</p>
                        </div>

                        <!-- Month Filter Dropdown -->
                        <div class="flex items-center gap-2">
                            <div class="relative w-full sm:w-auto">
                                <select
                                    id="monthFilter"
                                    x-model="selectedMonth"
                                    @change="fetchData()"
                                    :disabled="isLoading"
                                    class="w-full text-xs font-semibold text-charcoal-900 bg-stone-50 border border-stone-200 rounded-xl px-3 py-2 pr-8 focus:ring-champagne-500 focus:border-champagne-500 transition-colors cursor-pointer"
                                >
                                    @foreach($monthsList as $mVal => $mTitle)
                                        <option value="{{ $mVal }}">{{ $mTitle }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Pie / Doughnut Chart Container -->
                    <div class="relative my-4 flex items-center justify-center">
                        <div class="w-48 h-48 sm:w-52 sm:h-52 relative">
                            <canvas id="orderStatusPieChart" class="w-full h-full"></canvas>
                            <!-- Center Total Counter inside doughnut -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">
                                <span class="text-2xl font-serif font-bold text-charcoal-900" x-text="pieTotalOrders"></span>
                                <span class="text-[10px] uppercase font-bold tracking-wider text-stone-400">Total Order</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Distribution Details List -->
                <div class="space-y-2.5 pt-3 border-t border-stone-100">
                    <template x-for="item in statusDetails" :key="item.key">
                        <div class="text-xs">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" :style="`background-color: ${item.color}`"></span>
                                    <span class="font-medium text-charcoal-800" x-text="item.label"></span>
                                </div>
                                <div class="flex items-center gap-1.5 font-mono">
                                    <span class="font-bold text-charcoal-900" x-text="item.count"></span>
                                    <span class="text-stone-400 text-[10px]" x-text="`(${item.percentage}%)`"></span>
                                </div>
                            </div>
                            <div class="w-full bg-stone-100 h-1.5 rounded-full overflow-hidden">
                                <div
                                    class="h-full rounded-full transition-all duration-500"
                                    :style="`width: ${item.percentage}%; background-color: ${item.color}`"
                                ></div>
                            </div>
                        </div>
                    </template>

                    <div x-show="pieTotalOrders === 0" class="py-4 text-center text-xs text-stone-400">
                        Tidak ada transaksi pesanan pada periode terpilih.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. RECENT LEADS & RECENT PRODUCTS TABLES -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Leads (2 cols) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-serif font-bold text-charcoal-900">Pesanan Masuk Terbaru (Leads)</h3>
                    <p class="text-xs text-stone-500">Pelanggan yang baru saja melakukan checkout katalog</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-champagne-700 hover:text-champagne-800">
                    Lihat Semua &rarr;
                </a>
            </div>

            <x-admin.table>
                <x-slot:header>
                    <th class="px-5 py-3">No. Pesanan</th>
                    <th class="px-5 py-3">Customer</th>
                    <th class="px-5 py-3">Total</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </x-slot:header>

                @forelse ($recentLeads as $lead)
                    <tr class="hover:bg-stone-50/70 transition-colors">
                        <td class="px-5 py-4 font-mono font-medium text-xs text-charcoal-900">
                            {{ $lead->order_number }}
                            <span class="block text-[11px] text-stone-400 font-sans">{{ $lead->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-medium text-charcoal-900 block">{{ $lead->customer_name }}</span>
                            <span class="text-xs text-stone-500 block">{{ $lead->customer_phone }}</span>
                        </td>
                        <td class="px-5 py-4 font-semibold text-charcoal-900">
                            {{ rupiah($lead->total_price) }}
                        </td>
                        <td class="px-5 py-4">
                            <x-admin.status-badge :status="$lead->status" type="order" />
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $lead) }}" class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-stone-100 text-stone-700 hover:bg-champagne-100 hover:text-champagne-800 transition-colors">
                                Detail &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-stone-400 text-sm">
                            Belum ada pesanan masuk.
                        </td>
                    </tr>
                @endforelse
            </x-admin.table>
        </div>

        <!-- Recent Products (1 col) -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-serif font-bold text-charcoal-900">Produk Terbaru</h3>
                    <p class="text-xs text-stone-500">5 produk yang baru ditambahkan</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-champagne-700 hover:text-champagne-800">
                    Semua &rarr;
                </a>
            </div>

            <div class="bg-white rounded-2xl border border-stone-200/80 shadow-subtle divide-y divide-stone-100 overflow-hidden">
                @forelse ($recentProducts as $prod)
                    <div class="p-4 flex items-center justify-between gap-3 hover:bg-stone-50 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="{{ $prod->primary_image_url }}" alt="{{ $prod->name }}" class="w-12 h-12 rounded-xl object-cover border border-stone-200 shrink-0">
                            <div class="min-w-0">
                                <h4 class="text-sm font-semibold text-charcoal-900 truncate">{{ $prod->name }}</h4>
                                <p class="text-xs text-stone-500">{{ $prod->category->name ?? 'Tanpa Kategori' }}</p>
                                <p class="text-xs font-bold text-champagne-700 mt-0.5">{{ rupiah($prod->final_price) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.products.edit', $prod) }}" class="p-2 rounded-lg text-stone-400 hover:text-charcoal hover:bg-stone-100 transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>
                    </div>
                @empty
                    <div class="p-6 text-center text-stone-400 text-sm">
                        Belum ada produk.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Chart.js and Dashboard Script -->
    @push('scripts')
        <script src="{{ asset('vendor/chartjs/chart.umd.min.js') }}"></script>
        <script>
            function dashboardAnalytics() {
                return {
                    selectedYear: {{ $selectedYear }},
                    selectedMonth: '{{ $selectedMonth }}',
                    isLoading: false,
                    analyticsData: @json($initialAnalytics),
                    trendChart: null,
                    pieChart: null,

                    get trendTotalOrders() {
                        return this.analyticsData?.monthly_trend?.total_orders ?? 0;
                    },
                    get trendTotalRevenue() {
                        return this.analyticsData?.monthly_trend?.total_revenue ?? 0;
                    },
                    get pieTotalOrders() {
                        return this.analyticsData?.status_breakdown?.total ?? 0;
                    },
                    get statusDetails() {
                        return this.analyticsData?.status_breakdown?.details ?? [];
                    },

                    init() {
                        this.$nextTick(() => {
                            this.initCharts();
                        });
                    },

                    formatRupiah(num) {
                        return 'Rp ' + (new Intl.NumberFormat('id-ID').format(num || 0));
                    },

                    async fetchData() {
                        this.isLoading = true;
                        try {
                            const url = `{{ route('admin.dashboard.analytics') }}?year=${this.selectedYear}&month=${this.selectedMonth}`;
                            const res = await fetch(url, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            if (res.ok) {
                                this.analyticsData = await res.json();
                                this.updateCharts();
                            }
                        } catch (err) {
                            console.error('Gagal mengambil data analitik:', err);
                        } finally {
                            this.isLoading = false;
                        }
                    },

                    initCharts() {
                        if (typeof Chart === 'undefined') {
                            setTimeout(() => this.initCharts(), 150);
                            return;
                        }

                        // 1. Inisialisasi Grafik Tren Bulanan (Line Area)
                        const trendCtx = document.getElementById('monthlyTrendChart');
                        if (trendCtx) {
                            this.trendChart = new Chart(trendCtx, {
                                type: 'line',
                                data: {
                                    labels: this.analyticsData.monthly_trend.labels,
                                    datasets: [
                                        {
                                            label: 'Jumlah Pesanan',
                                            data: this.analyticsData.monthly_trend.orders,
                                            borderColor: '#C5A059',
                                            backgroundColor: 'rgba(197, 160, 89, 0.12)',
                                            borderWidth: 3,
                                            fill: true,
                                            tension: 0.38,
                                            pointBackgroundColor: '#C5A059',
                                            pointBorderColor: '#ffffff',
                                            pointBorderWidth: 2,
                                            pointRadius: 4.5,
                                            pointHoverRadius: 7,
                                            yAxisID: 'yOrders',
                                        },
                                        {
                                            label: 'Nilai Pesanan (Rp)',
                                            data: this.analyticsData.monthly_trend.revenue,
                                            borderColor: '#2A2826',
                                            backgroundColor: 'transparent',
                                            borderWidth: 2,
                                            borderDash: [5, 5],
                                            pointBackgroundColor: '#2A2826',
                                            pointBorderColor: '#ffffff',
                                            pointBorderWidth: 2,
                                            pointRadius: 4,
                                            pointHoverRadius: 6,
                                            tension: 0.38,
                                            yAxisID: 'yRevenue',
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    interaction: {
                                        mode: 'index',
                                        intersect: false,
                                    },
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'top',
                                            align: 'end',
                                            labels: {
                                                usePointStyle: true,
                                                boxWidth: 8,
                                                padding: 16,
                                                font: { family: 'Plus Jakarta Sans', size: 12, weight: '500' }
                                            }
                                        },
                                        tooltip: {
                                            backgroundColor: '#1C1917',
                                            titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: 'bold' },
                                            bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                                            padding: 12,
                                            cornerRadius: 12,
                                            boxPadding: 6,
                                            callbacks: {
                                                label: function(context) {
                                                    if (context.datasetIndex === 0) {
                                                        return ` Pesanan: ${context.parsed.y} order`;
                                                    }
                                                    return ` Nilai: Rp ${new Intl.NumberFormat('id-ID').format(context.parsed.y)}`;
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        x: {
                                            grid: { display: false },
                                            ticks: {
                                                font: { family: 'Plus Jakarta Sans', size: 11, weight: '500' },
                                                color: '#78716C'
                                            }
                                        },
                                        yOrders: {
                                            type: 'linear',
                                            display: true,
                                            position: 'left',
                                            beginAtZero: true,
                                            ticks: {
                                                precision: 0,
                                                font: { family: 'Plus Jakarta Sans', size: 11 },
                                                color: '#78716C'
                                            },
                                            grid: { color: 'rgba(0, 0, 0, 0.04)' }
                                        },
                                        yRevenue: {
                                            type: 'linear',
                                            display: true,
                                            position: 'right',
                                            beginAtZero: true,
                                            grid: { drawOnChartArea: false },
                                            ticks: {
                                                callback: function(val) {
                                                    if (val >= 1000000) return (val / 1000000).toFixed(1) + ' jt';
                                                    if (val >= 1000) return (val / 1000).toFixed(0) + ' rb';
                                                    return val;
                                                },
                                                font: { family: 'Plus Jakarta Sans', size: 11 },
                                                color: '#78716C'
                                            }
                                        }
                                    }
                                }
                            });
                        }

                        // 2. Inisialisasi Donut / Pie Chart Status Pesanan
                        const pieCtx = document.getElementById('orderStatusPieChart');
                        if (pieCtx) {
                            const total = this.analyticsData.status_breakdown.total;
                            const hasData = total > 0;

                            this.pieChart = new Chart(pieCtx, {
                                type: 'doughnut',
                                data: {
                                    labels: hasData ? this.analyticsData.status_breakdown.labels : ['Belum Ada Pesanan'],
                                    datasets: [{
                                        data: hasData ? this.analyticsData.status_breakdown.counts : [1],
                                        backgroundColor: hasData ? this.analyticsData.status_breakdown.colors : ['#E7E5E4'],
                                        borderWidth: 2,
                                        borderColor: '#ffffff',
                                        hoverOffset: 6
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    cutout: '68%',
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            enabled: hasData,
                                            backgroundColor: '#1C1917',
                                            bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                                            padding: 10,
                                            cornerRadius: 10,
                                            callbacks: {
                                                label: (context) => {
                                                    const count = context.parsed;
                                                    const pct = total > 0 ? ((count / total) * 100).toFixed(1) : 0;
                                                    return ` ${context.label}: ${count} order (${pct}%)`;
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    },

                    updateCharts() {
                        if (this.trendChart) {
                            this.trendChart.data.labels = this.analyticsData.monthly_trend.labels;
                            this.trendChart.data.datasets[0].data = this.analyticsData.monthly_trend.orders;
                            this.trendChart.data.datasets[1].data = this.analyticsData.monthly_trend.revenue;
                            this.trendChart.update();
                        }

                        if (this.pieChart) {
                            const total = this.analyticsData.status_breakdown.total;
                            const hasData = total > 0;

                            this.pieChart.data.labels = hasData ? this.analyticsData.status_breakdown.labels : ['Belum Ada Pesanan'];
                            this.pieChart.data.datasets[0].data = hasData ? this.analyticsData.status_breakdown.counts : [1];
                            this.pieChart.data.datasets[0].backgroundColor = hasData ? this.analyticsData.status_breakdown.colors : ['#E7E5E4'];
                            this.pieChart.options.plugins.tooltip.enabled = hasData;
                            this.pieChart.update();
                        }
                    }
                };
            }
        </script>
    @endpush
</x-admin-layout>
