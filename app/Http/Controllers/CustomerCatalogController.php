<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\DeliveryLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerCatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with(['products' => function ($q) {
            $q->where('is_available', true);
        }])->where('is_active', true);

        // Filter by Search (Nama Tenant, Lokasi / Gate, atau Nama Menu)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $gateSearch = preg_replace('/^gate\s*(\d+)$/i', 'Gate $1', $search);

            $query->where(function($q) use ($search, $gateSearch) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('floor_location', 'like', '%' . $search . '%')
                  ->orWhere('floor_location', 'like', '%' . $gateSearch . '%')
                  ->orWhereHas('products', function($pq) use ($search) {
                      $pq->where('is_available', true)
                         ->where('name', 'like', '%' . $search . '%');
                  });
            });

            // Prioritaskan relevansi pencarian:
            // 1. Nama Restoran yang cocok (paling relevan)
            // 2. Lokasi / Gate yang cocok
            // 3. Menu yang cocok (restoran yang memiliki produk bernama cocok)
            $escapedSearch = str_replace(['%', '_'], ['\%', '\_'], $search);
            $query->orderByRaw("CASE 
                WHEN LOWER(name) LIKE ? THEN 1
                WHEN LOWER(floor_location) LIKE ? THEN 2
                ELSE 3 
            END ASC", [
                '%' . strtolower($escapedSearch) . '%',
                '%' . strtolower($escapedSearch) . '%'
            ])->orderBy('name', 'asc');
        }

        // Filter by Terminal
        if ($request->filled('terminal') && $request->terminal !== 'semua') {
            $terminalStr = strtoupper($request->terminal); // 'T1' atau 'T2'
            $terminalNum = str_ireplace('t', '', $request->terminal); // '1' atau '2'
            $query->where(function($q) use ($terminalStr, $terminalNum) {
                $q->where('terminal', $terminalStr)
                  ->orWhere('floor_location', 'like', "%Terminal {$terminalNum}%")
                  ->orWhere('floor_location', 'like', "%T{$terminalNum}%");
            });
        }

        // Filter by Zone
        if ($request->filled('zone') && $request->zone !== 'semua') {
            // Jika user mencari kata "Gate" di kolom pencarian, jangan benturkan dengan filter Landside
            if (!($request->filled('search') && stripos($request->search, 'gate') !== false && $request->zone === 'Landside')) {
                $query->where('zone', $request->zone);
            }
        }

        // Filter by Kategori Favorit Makanan (Disinkronkan 100% dengan 4 Kategori Database: Indonesian Traditional, Fast Food & Restaurant, Bakery & Pastry, Beverages & Coffee)
        if ($request->filled('category') && $request->category !== 'semua') {
            $catMap = [
                'makanan-berat' => ['Indonesian Traditional', 'Makanan Berat', 'Makanan Tradisional'],
                'makanan berat' => ['Indonesian Traditional', 'Makanan Berat', 'Makanan Tradisional'],
                'indonesian traditional' => ['Indonesian Traditional', 'Makanan Berat'],
                'indonesian-traditional' => ['Indonesian Traditional', 'Makanan Berat'],

                'cepat-saji' => ['Fast Food & Restaurant', 'Cepat Saji', 'Fast Food'],
                'cepat saji' => ['Fast Food & Restaurant', 'Cepat Saji', 'Fast Food'],
                'fast food & restaurant' => ['Fast Food & Restaurant', 'Cepat Saji'],
                'fast-food' => ['Fast Food & Restaurant', 'Cepat Saji'],
                'fast food' => ['Fast Food & Restaurant', 'Cepat Saji'],

                'roti-kue' => ['Bakery & Pastry', 'Roti & Kue', 'Bakery', 'Pastry'],
                'roti & kue' => ['Bakery & Pastry', 'Roti & Kue', 'Bakery', 'Pastry'],
                'bakery & pastry' => ['Bakery & Pastry', 'Roti & Kue'],
                'bakery-pastry' => ['Bakery & Pastry', 'Roti & Kue'],

                'minuman' => ['Beverages & Coffee', 'Minuman', 'Coffee', 'Beverages'],
                'beverages & coffee' => ['Beverages & Coffee', 'Minuman'],
                'beverages-coffee' => ['Beverages & Coffee', 'Minuman'],
            ];
            $catKey = strtolower(trim($request->category));
            $mapped = $catMap[$catKey] ?? [$request->category];

            $query->where(function($q) use ($mapped) {
                foreach ($mapped as $idx => $cat) {
                    if ($idx === 0) {
                        $q->where('category', $cat)
                          ->orWhere('category', 'like', '%' . $cat . '%');
                    } else {
                        $q->orWhere('category', $cat)
                          ->orWhere('category', 'like', '%' . $cat . '%');
                    }
                }
            });
        }

        $tenants = $query->paginate(6)->onEachSide(1)->withQueryString();
        
        return view('customer.catalog', compact('tenants'));
    }

    public function show($id)
    {
        $tenant = Tenant::with(['products' => function ($query) {
            $query->where('is_available', true);
        }])->findOrFail($id);
        
        if (!$tenant->isOpen()) {
            return redirect()->route('customer.menu')->with('error', 'Restoran sedang tutup.');
        }

        // Cek apakah view tenant-menu sudah ada, jika tidak render halaman fallback
        if (view()->exists('customer.tenant-menu')) {
            return view('customer.tenant-menu', compact('tenant'));
        }

        return abort(404, 'Halaman menu restoran sedang dalam tahap pengembangan.');
    }

    // Fungsi untuk menampilkan halaman Keranjang
    public function cart()
    {
        $cart = session()->get('cart', []);
        $tenant = null;
        $total = 0;

        if (count($cart) > 0) {
            $firstItem = reset($cart);
            $tenant = Tenant::find($firstItem['tenant_id']);
            
            foreach ($cart as $key => $item) {
                $product = Product::find($item['id']);
                if ($product) {
                    $cart[$key]['price'] = (float) $product->price;
                    $cart[$key]['name'] = $product->name;
                    $cart[$key]['preparation_time'] = (int) ($product->preparation_time ?? 15);
                }
                $total += $cart[$key]['price'] * $cart[$key]['quantity'];
            }

            session()->put('cart', $cart);
        }

        return view('customer.cart', compact('cart', 'tenant', 'total'));
    }

    // Fungsi untuk menampilkan halaman Pelacakan Pesanan
    /**
     * Passive Auto-Cancel Check:
     * Jika pesanan masih berstatus 'menunggu', belum dibayar, dan melewati batas waktu auto_cancel_at,
     * sistem secara instan mengubah statusnya menjadi 'dibatalkan' tanpa menunggu scheduler cron.
     */
    protected function checkAndApplyAutoCancel(Order $order): bool
    {
        if ($order->status === 'menunggu' && !$order->is_paid && $order->auto_cancel_at && now()->greaterThanOrEqualTo($order->auto_cancel_at)) {
            $order->status = 'dibatalkan';
            $order->save();

            if (session('order_code') === $order->order_code) {
                session()->forget('order_code');
            }

            // Kembalikan kuota stok produk jika DB trigger tidak aktif (misal di SQLite test)
            if (!in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
                foreach ($order->orderItems as $item) {
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    }
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Mendapatkan variasi format nomor HP Indonesia (08..., 628..., +628...).
     * Memungkinkan customer mencari riwayat pesanan dengan format apapun.
     */
    protected function getPhoneVariations(string $phone): array
    {
        $clean = preg_replace('/[^0-9+]/', '', $phone);
        if (empty($clean)) {
            return [];
        }

        $digits = preg_replace('/[^0-9]/', '', $clean);
        $variations = [$clean];

        $core = null;
        if (str_starts_with($digits, '628')) {
            $core = substr($digits, 2);
        } elseif (str_starts_with($digits, '08')) {
            $core = substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $core = $digits;
        }

        if ($core) {
            $variations[] = '0' . $core;
            $variations[] = '62' . $core;
            $variations[] = '+62' . $core;
        }

        return array_values(array_unique(array_filter($variations)));
    }

    public function tracking(Request $request)
    {
        $orderCode = $request->query('order') ?? session('order_code');
        if (!$orderCode) {
            return redirect()->route('customer.menu');
        }

        $order = Order::with(['orderItems.product', 'tenant', 'deliveryLocation', 'customer'])->where('order_code', $orderCode)->firstOrFail();

        // Passive Auto-Cancel Check: jika waktu habis dan belum dibayar, batalkan instan
        $this->checkAndApplyAutoCancel($order);

        // Jika pesanan telah selesai, dibatalkan, atau ditolak, hapus session order_code
        // agar tombol pulsing "LACAK" di navbar tidak terus muncul
        if (in_array($order->status, ['selesai', 'dibatalkan', 'ditolak'])) {
            if (session('order_code') === $order->order_code) {
                session()->forget('order_code');
            }
        }

        return view('customer.tracking', compact('order'));
    }

    // Fungsi untuk menampilkan Struk Digital / Billing Rincian Pesanan
    public function receipt($orderCode)
    {
        $order = Order::with(['orderItems.product', 'tenant', 'customer', 'deliveryLocation'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        // Passive Auto-Cancel Check
        $this->checkAndApplyAutoCancel($order);

        return view('customer.receipt', compact('order'));
    }

    public function cancelOrder($orderCode)
    {
        $order = Order::where('order_code', $orderCode)->firstOrFail();

        // Hanya bisa dibatalkan jika belum dibayar dan statusnya menunggu
        if (!$order->is_paid && $order->status === 'menunggu') {
            $order->status = 'dibatalkan';
            $order->save();
            session()->forget('order_code');

            // Kembalikan kuota stok produk yang dibatalkan jika DB trigger tidak aktif (misal di SQLite test)
            if (!in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
                foreach ($order->orderItems as $item) {
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    }
                }
            }

            // Deteksi Pola Fraud / Spam (jika customer batalkan >= 5 order dalam 30 menit)
            if ($order->customer_id) {
                $fraudService = app(\App\Services\FraudDetectionService::class);
                $isBlocked = $fraudService->checkCancelledOrderPattern($order->customer_id);

                if ($isBlocked) {
                    return redirect()->route('customer.tracking', ['order' => $order->order_code])
                        ->with('warning', 'Pesanan telah dibatalkan. PERHATIAN: Akun Anda otomatis diblokir karena terdeteksi membatalkan pesanan sebanyak 5 kali atau lebih dalam 30 menit.');
                }
            }

            return redirect()->route('customer.tracking', ['order' => $order->order_code])
                ->with('success', 'Pesanan Anda telah berhasil dibatalkan.');
        }

        return redirect()->route('customer.tracking', ['order' => $order->order_code])
            ->with('error', 'Pesanan ini sudah tidak dapat dibatalkan.');
    }

    public function checkStatus($orderCode)
    {
        $order = Order::with('orderItems')->where('order_code', $orderCode)->firstOrFail();

        // Passive Auto-Cancel Check: batalkan instan jika waktu habis saat status di-poll
        $this->checkAndApplyAutoCancel($order);

        if (in_array($order->status, ['selesai', 'dibatalkan', 'ditolak'])) {
            if (session('order_code') === $order->order_code) {
                session()->forget('order_code');
            }
        }

        return response()->json([
            'status' => $order->status,
            'is_paid' => (bool) $order->is_paid
        ]);
    }

    public function history(Request $request)
    {
        $orders = collect();
        $customer = null;
        $searched = false;
        $cleanPhone = '';

        if ($request->filled('phone_number')) {
            $searched = true;
            $cleanPhone = substr(preg_replace('/[^0-9+]/', '', (string) $request->phone_number), 0, 20);
            
            $phoneVariations = $this->getPhoneVariations($cleanPhone);
            
            // Cari customer berdasarkan seluruh variasi nomor telepon (08..., 628..., +628...)
            $customers = Customer::whereIn('phone_number', $phoneVariations)->get();
            $customer = $customers->first();

            if ($customers->isNotEmpty()) {
                $customerIds = $customers->pluck('id')->toArray();
                $orders = Order::with(['tenant', 'orderItems.product'])
                    ->whereIn('customer_id', $customerIds)
                    ->latest('ordered_at')
                    ->get();
            }
        }

        return view('customer.history', compact('orders', 'customer', 'searched', 'cleanPhone'));
    }

    public function simulatePayment(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string',
        ]);

        $order = Order::with('orderItems')->where('order_code', $request->order_code)->firstOrFail();

        // Passive Auto-Cancel Check: batalkan pesanan terlebih dahulu jika batas waktu bayar (auto_cancel_at) sudah terlewati
        $this->checkAndApplyAutoCancel($order);

        // Tolak konfirmasi pembayaran jika status pesanan bukan 'menunggu' (misal: sudah 'dibatalkan' atau 'ditolak')
        if ($order->status !== 'menunggu') {
            if (in_array($order->status, ['selesai', 'dibatalkan', 'ditolak']) && session('order_code') === $order->order_code) {
                session()->forget('order_code');
            }

            if ($order->is_paid) {
                return redirect()->route('customer.tracking', ['order' => $order->order_code])
                    ->with('success', 'Pembayaran pesanan ini sudah terkonfirmasi.');
            }

            return redirect()->route('customer.tracking', ['order' => $order->order_code])
                ->with('error', 'Pembayaran ditolak: Waktu pembayaran telah habis atau pesanan telah dibatalkan. Silakan buat pesanan baru.');
        }

        if (!$order->is_paid) {
            $order->is_paid = true;
            $order->save();
        }

        return redirect()->route('customer.tracking', ['order' => $order->order_code])->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        $cart = session()->get('cart', []);
        $isJson = $request->expectsJson() || $request->wantsJson() || $request->ajax() || $request->isJson();

        // Validate multi-tenant (only 1 tenant per cart)
        if (count($cart) > 0) {
            $firstItem = reset($cart);
            if ($firstItem['tenant_id'] != $request->tenant_id) {
                if ($request->has('force_replace') && $request->force_replace == '1') {
                    $cart = []; // clear cart to replace
                } else {
                    if ($isJson) {
                        return response()->json(['error' => 'conflict', 'message' => 'Anda memiliki pesanan dari restoran lain. Ingin mengganti restoran?'], 409);
                    }
                    return redirect()->back()->with('error', 'Anda memiliki pesanan dari restoran lain.');
                }
            }
        }

        $product = Product::find($request->product_id);
        $tenant = Tenant::find($request->tenant_id);

        if (!$tenant->isOpen()) {
            if ($isJson) {
                return response()->json(['error' => 'closed', 'message' => 'Restoran sedang tutup.'], 400);
            }
            return redirect()->back()->with('error', 'Restoran sedang tutup.');
        }

        if (!$product || !$product->is_available || $product->stock <= 0) {
            if ($isJson) {
                return response()->json(['error' => 'unavailable', 'message' => 'Mohon maaf, menu ini sedang habis atau tidak tersedia.'], 400);
            }
            return redirect()->back()->with('error', 'Mohon maaf, menu ini sedang habis atau tidak tersedia.');
        }

        $productId = $product->id;
        $cleanVariants = $request->filled('variants')
            ? mb_substr(trim(preg_replace('/\s+/', ' ', (string) $request->variants)), 0, 180)
            : null;

        if (isset($cart[$productId])) {
            if ($cart[$productId]['quantity'] >= $product->stock) {
                if ($isJson) {
                    return response()->json(['error' => 'stock_limit', 'message' => "Stok hanya tersedia {$product->stock} porsi."], 400);
                }
                return redirect()->back()->with('error', "Stok hanya tersedia {$product->stock} porsi.");
            }
            $cart[$productId]['quantity']++;
            if ($cleanVariants !== null) {
                $cart[$productId]['variants'] = $cleanVariants;
            }
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'tenant_id' => $request->tenant_id,
                'preparation_time' => (int) ($product->preparation_time ?? 15),
                'variants' => $cleanVariants,
            ];
        }

        session()->put('cart', $cart);

        $total = array_reduce($cart, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        if ($isJson) {
            return response()->json([
                'success' => true,
                'message' => 'Produk ditambahkan ke keranjang',
                'cart_count' => count($cart),
                'item' => $cart[$productId],
                'jumlah' => $cart[$productId]['quantity'],
                'subtotal' => $cart[$productId]['price'] * $cart[$productId]['quantity'],
                'total' => $total,
                'cart' => $cart,
            ]);
        }

        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'action' => 'required|in:increase,decrease,remove,set',
            'quantity' => 'nullable|integer|min:0'
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->product_id;
        $isJson = $request->expectsJson() || $request->wantsJson() || $request->ajax() || $request->isJson();

        $targetItem = null;
        $jumlah = 0;
        $subtotal = 0;

        if (isset($cart[$productId])) {
            $product = Product::find($productId);

            if ($request->action == 'increase') {
                if ($product && $cart[$productId]['quantity'] >= $product->stock) {
                    if ($isJson) {
                        return response()->json(['error' => 'stock_limit', 'message' => "Stok maksimal tercapai ({$product->stock} porsi)."], 400);
                    }
                    return redirect()->back()->with('error', "Stok maksimal tercapai ({$product->stock} porsi).");
                }
                $cart[$productId]['quantity']++;
            } elseif ($request->action == 'decrease') {
                $cart[$productId]['quantity']--;
                if ($cart[$productId]['quantity'] <= 0) {
                    unset($cart[$productId]);
                }
            } elseif ($request->action == 'remove') {
                unset($cart[$productId]);
            } elseif ($request->action == 'set') {
                $targetQty = (int) $request->input('quantity', 1);
                if ($targetQty <= 0) {
                    unset($cart[$productId]);
                } else {
                    if ($product && $targetQty > $product->stock) {
                        if ($isJson) {
                            return response()->json([
                                'error' => 'stock_limit', 
                                'message' => "Stok hanya tersedia {$product->stock} porsi.",
                                'available_stock' => $product->stock,
                                'item' => $cart[$productId],
                                'jumlah' => $cart[$productId]['quantity'],
                            ], 400);
                        }
                        return redirect()->back()->with('error', "Stok hanya tersedia {$product->stock} porsi.");
                    }
                    $cart[$productId]['quantity'] = $targetQty;
                }
            }
            session()->put('cart', $cart);

            if (isset($cart[$productId])) {
                $targetItem = $cart[$productId];
                $jumlah = $cart[$productId]['quantity'];
                $subtotal = $cart[$productId]['price'] * $cart[$productId]['quantity'];
            }
        }

        $total = array_reduce($cart, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        $isJson = $request->expectsJson() || $request->wantsJson() || $request->ajax() || $request->isJson();

        if ($isJson) {
            return response()->json([
                'success' => true,
                'item' => $targetItem,
                'jumlah' => $jumlah,
                'subtotal' => $subtotal,
                'total' => $total,
                'cart_count' => count($cart),
                'cart' => $cart,
                'message' => 'Keranjang berhasil diperbarui',
            ]);
        }

        return redirect()->route('customer.cart');
    }

    public function clearCart(Request $request)
    {
        session()->forget('cart');
        $isJson = $request->expectsJson() || $request->wantsJson() || $request->ajax() || $request->isJson();
        if ($isJson) {
            return response()->json([
                'success' => true,
                'item' => null,
                'jumlah' => 0,
                'subtotal' => 0,
                'total' => 0,
                'cart_count' => 0,
                'cart' => [],
                'message' => 'Keranjang berhasil dikosongkan',
            ]);
        }
        return redirect()->back();
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('customer.cart')->withErrors(['Keranjang kosong']);
        }

        $tenantId = reset($cart)['tenant_id'];
        $tenant = Tenant::findOrFail($tenantId);

        // Validasi jam operasional tenant sebelum checkout
        if (!$tenant->is_active || !$tenant->isOpen()) {
            return redirect()->route('customer.cart')->with('error', "Mohon maaf, restoran '{$tenant->name}' saat ini sedang tutup dan tidak menerima pesanan.");
        }

        // Normalisasi nama pemesan (hapus spasi depan/belakang dan spasi ganda)
        $cleanCustomerName = trim(preg_replace('/\s+/', ' ', (string) $request->customer_name));

        // Normalisasi nomor penerbangan (auto-kapitalisasi & perapian spasi)
        $cleanFlightNumber = $request->filled('flight_number')
            ? strtoupper(trim(preg_replace('/\s+/', ' ', (string) $request->flight_number)))
            : null;

        // Normalisasi waktu boarding jika dikirim dalam format 12-jam AM/PM
        $cleanBoardingTime = $request->boarding_time;
        if ($request->customer_type === 'penumpang' && $request->filled('boarding_time')) {
            try {
                $cleanBoardingTime = \Carbon\Carbon::parse($request->boarding_time)->format('H:i');
            } catch (\Throwable $e) {
                // Biarkan nilai aslinya jika bukan format waktu valid agar ditangkap validator
            }
        }

        // Sanitasi catatan khusus pesanan (maks 100 karakter)
        $cleanNotes = $request->filled('notes')
            ? trim(preg_replace('/\s+/', ' ', (string) $request->notes))
            : null;

        $request->merge([
            'customer_name' => $cleanCustomerName,
            'flight_number' => $cleanFlightNumber,
            'boarding_time' => $cleanBoardingTime,
            'notes' => $cleanNotes,
        ]);

        $rules = [
            'customer_name' => 'required|string|min:2|max:100',
            'phone_number' => ['required', 'string', 'regex:/^(\+62|62|0)[0-9\- ]{8,18}$/'],
            'customer_type' => 'required|in:penumpang,pengunjung',
            'notes' => 'nullable|string|max:100',
            'payment_method' => 'required|in:qris,transfer,transfer_bca,transfer_mandiri,transfer_bri,transfer_bni',
            'pickup_method' => 'required|in:ambil_sendiri,diantar',
        ];

        $customMessages = [
            'customer_name.required' => 'Nama pemesan wajib diisi.',
            'customer_name.min' => 'Nama pemesan minimal terdiri dari 2 karakter.',
            'customer_name.max' => 'Nama pemesan maksimal 100 karakter.',
            'phone_number.required' => 'Nomor WhatsApp / telepon aktif wajib diisi.',
            'phone_number.regex' => 'Format nomor telepon tidak valid. Masukkan nomor ponsel aktif (contoh: 08123456789 atau +628123456789) dengan 9–15 digit angka.',
            'notes.max' => 'Catatan pesanan maksimal 100 karakter.',
        ];

        // Validasi conditional untuk penumpang
        if ($request->customer_type === 'penumpang') {
            $rules['flight_number'] = 'required|string|max:20';
            $rules['boarding_time'] = 'required|date_format:H:i';
        }

        // Validasi Zona Bandara (Opsi A Ketat per Zona: Restoran Airside 100% Khusus Penumpang)
        if ($tenant->isAirside() && $request->customer_type === 'pengunjung') {
            return redirect()->back()->withInput()->withErrors([
                'customer_type' => "Restoran '{$tenant->name}' berada di Zona Airside ({$tenant->floor_location} — Area Steril Ruang Tunggu Boarding). Pemesanan dari restoran ini khusus untuk Penumpang Pesawat yang memiliki tiket/boarding pass dan tidak melayani pengantaran ke luar area steril (Landside)."
            ]);
        }

        // Validasi conditional untuk delivery
        if ($request->pickup_method === 'diantar') {
            $rules['delivery_location_id'] = 'required|exists:delivery_locations,id';
            if (is_null($tenant->delivery_fee)) {
                return redirect()->back()->withErrors(['Tenant ini tidak menyediakan layanan antar.']);
            }

            // Validasi Integritas Terminal Antar Porter (Tidak Boleh Lintas Terminal)
            $deliveryLocation = DeliveryLocation::find($request->delivery_location_id);
            $tenantTerminalNum = str_ireplace('t', '', (string) $tenant->terminal);
            $locTerminalNum = $deliveryLocation ? str_ireplace('t', '', (string) $deliveryLocation->terminal) : null;

            if ($deliveryLocation && $tenantTerminalNum !== $locTerminalNum) {
                return redirect()->back()->withInput()->withErrors([
                    'delivery_location_id' => "Layanan antar porter hanya melayani area Terminal {$tenantTerminalNum}. Titik lokasi pengantaran tidak boleh berada di Terminal {$locTerminalNum} karena perbedaan gedung terminal bandara."
                ]);
            }

            // Validasi Integritas Zona Bandara (Porter Tidak Boleh Lintas Zona Landside <-> Airside)
            if ($deliveryLocation && $tenant->isAirside() !== $deliveryLocation->isAirside()) {
                $tenantZoneLabel = $tenant->isAirside() ? 'Zona Airside (Boarding Lounge)' : 'Zona Landside (Area Publik / Lobby)';
                $locZoneLabel = $deliveryLocation->isAirside() ? 'Zona Airside (Boarding Lounge)' : 'Zona Landside (Area Publik / Lobby)';
                return redirect()->back()->withInput()->withErrors([
                    'delivery_location_id' => "Layanan antar porter tidak dapat melintasi batas pemeriksaan keamanan antara {$tenantZoneLabel} dan {$locZoneLabel}. Silakan pilih titik pengantaran yang berada di {$tenantZoneLabel}."
                ]);
            }
        }

        $request->validate($rules, $customMessages);

        // Validasi ketersediaan, stok, waktu persiapan (preparation_time), dan sinkronisasi harga produk terkini dari database sebelum memproses checkout
        $maxPrepTime = 15;
        foreach ($cart as $key => $item) {
            $productCheck = Product::find($item['id']);
            if (!$productCheck || !$productCheck->is_available || $productCheck->stock < $item['quantity']) {
                $avail = ($productCheck && $productCheck->is_available) ? $productCheck->stock : 0;
                return redirect()->route('customer.cart')->with('error', "Mohon maaf, stok untuk menu '{$item['name']}' tidak mencukupi (tersedia {$avail} porsi). Silakan sesuaikan jumlah pesanan Anda.");
            }

            // Gunakan harga, nama, dan waktu persiapan terbaru langsung dari database (bukan data lama di session)
            $prepTime = (int) ($productCheck->preparation_time ?? 15);
            $cart[$key]['price'] = (float) $productCheck->price;
            $cart[$key]['name'] = $productCheck->name;
            $cart[$key]['preparation_time'] = $prepTime;

            if ($prepTime > $maxPrepTime) {
                $maxPrepTime = $prepTime;
            }
        }
        session()->put('cart', $cart);

        $minBoardingMinutes = max(15, $maxPrepTime);

        // Validasi SLA Waktu Boarding untuk Penumpang (Minimal >= max(15, preparation_time) menit dari sekarang)
        $boardingDateTime = null;
        if ($request->customer_type === 'penumpang') {
            $boardingCarbon = \Carbon\Carbon::createFromFormat('H:i', $request->boarding_time);
            $nowCarbon = now();
            $boardingDateTime = $nowCarbon->copy()->setTime($boardingCarbon->hour, $boardingCarbon->minute, 0);

            $diffMinutes = $nowCarbon->diffInMinutes($boardingDateTime, false);

            // Skenario Penerbangan Dini Hari (Cross-Midnight):
            // Jika jam boarding terlihat sudah lewat pada hari yang sama, tetapi jika dimajukan 1 hari (besok)
            // selisihnya berada dalam rentang wajar pemesanan (antara 0 hingga 8 jam / 480 menit ke depan),
            // maka ini adalah penerbangan dini hari berikutnya.
            if ($diffMinutes < 0 && ($diffMinutes + 1440) >= 0 && ($diffMinutes + 1440) <= 480) {
                $boardingDateTime->addDay();
                $diffMinutes = $nowCarbon->diffInMinutes($boardingDateTime, false);
            }

            if ($diffMinutes < 0) {
                return redirect()->back()->withInput()->withErrors([
                    'boarding_time' => 'Jadwal waktu boarding yang Anda masukkan sudah terlewat dari waktu saat ini.'
                ]);
            }

            if ($diffMinutes < $minBoardingMinutes) {
                return redirect()->back()->withInput()->withErrors([
                    'boarding_time' => "Waktu boarding terlalu dekat (kurang dari {$minBoardingMinutes} menit dari sekarang). Dapur membutuhkan waktu penyiapan makanan minimal {$minBoardingMinutes} menit dan gerbang keberangkatan pesawat ditutup sebelum lepas landas."
                ]);
            }
        }

        $totalAmount = array_reduce($cart, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        $deliveryFee = 0;
        if ($request->pickup_method === 'diantar') {
            $deliveryFee = $tenant->delivery_fee;
            $totalAmount += $deliveryFee;
        }

        // Auto Create / Reuse Customer by Phone Number
        $phoneNumber = preg_replace('/[^0-9+]/', '', $request->phone_number); // Clean up phone number
        $digitsOnly = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (strlen($digitsOnly) < 9 || strlen($digitsOnly) > 15) {
            return redirect()->back()->withInput()->withErrors([
                'phone_number' => 'Nomor telepon harus terdiri dari 9 sampai 15 digit angka.'
            ]);
        }
        
        $phoneVariations = $this->getPhoneVariations($phoneNumber);
        $customer = Customer::whereIn('phone_number', $phoneVariations)->first();
        if ($customer) {
            // Validasi Blokir Awal
            if ($customer->is_blocked) {
                return redirect()->route('customer.menu')->with('error', 'Nomor HP Anda sementara diblokir karena terdeteksi aktivitas mencurigakan.');
            }

            // Deteksi Pola Fraud / Spam
            $fraudService = app(\App\Services\FraudDetectionService::class);
            if ($fraudService->checkCancelledOrderPattern($customer->id)) {
                return redirect()->route('customer.menu')->with('error', 'Pesanan ditolak: Anda telah diblokir karena terdeteksi melakukan terlalu banyak pembatalan/spam pesanan dalam waktu singkat.');
            }

            // Passive Auto-Cancel untuk order milik customer ini yang sudah melewati auto_cancel_at
            $expiredUnpaidOrders = Order::with('orderItems')
                ->where('customer_id', $customer->id)
                ->where('status', 'menunggu')
                ->where('is_paid', false)
                ->whereNotNull('auto_cancel_at')
                ->where('auto_cancel_at', '<=', now())
                ->get();
            foreach ($expiredUnpaidOrders as $expiredOrder) {
                $this->checkAndApplyAutoCancel($expiredOrder);
            }

            // Validasi Batas Pesanan Aktif (Mencegah Kuras Stok / Stock Hoarding Tanpa Bayar)
            $orderSecurityService = app(\App\Services\OrderSecurityService::class);
            if (!$orderSecurityService->checkActiveOrderLimit($customer->id)) {
                return redirect()->route('customer.cart')->withInput()->with('error', 'Pesanan ditolak: Anda telah mencapai batas maksimal 5 pesanan aktif yang sedang berjalan atau menunggu pembayaran. Silakan selesaikan pembayaran atau batalkan pesanan sebelumnya terlebih dahulu.');
            }

            // Customer exists, update name and last order time
            $customer->name = $request->customer_name;
            $customer->last_order_at = now();
            $customer->total_orders += 1;
            $customer->save();
        } else {
            // Create new customer
            $customer = Customer::create([
                'phone_number' => $phoneNumber,
                'name' => $request->customer_name,
                'first_order_at' => now(),
                'last_order_at' => now(),
                'total_orders' => 1,
            ]);
        }
        
        $customerId = $customer->id;

        // Hitung Auto-Cancel Dinamis
        $autoCancelAt = now()->addMinutes(15);
        $fullBoardingTime = null;

        if ($request->customer_type === 'penumpang') {
            $fullBoardingTime = $boardingDateTime;
            // Jika boarding time lebih awal dari waktu pembatalan otomatis standar (15 menit),
            // batasi pembatalan tepat pada saat boarding time agar pelanggan tidak membayar setelah pesawat terbang.
            if ($fullBoardingTime->isBefore($autoCancelAt)) {
                $autoCancelAt = $fullBoardingTime;
            }
        }
        
        $order = DB::transaction(function () use ($tenantId, $customerId, $request, $fullBoardingTime, $deliveryFee, $autoCancelAt, $totalAmount, $cart) {
            do {
                $orderCode = 'ORD-' . strtoupper(Str::random(6));
            } while (Order::where('order_code', $orderCode)->exists());

            $newOrder = Order::create([
                'order_code' => $orderCode,
                'tenant_id' => $tenantId,
                'customer_id' => $customerId,
                'customer_name' => $request->customer_name,
                'flight_number' => $request->customer_type === 'penumpang' ? strtoupper($request->flight_number) : null,
                'gate' => null, // Gate manual dihapus dari form, gunakan delivery_location_id jika diantar
                'boarding_time' => $fullBoardingTime,
                'notes' => $request->notes,
                'pickup_method' => $request->pickup_method,
                'delivery_location_id' => $request->pickup_method === 'diantar' ? $request->delivery_location_id : null,
                'delivery_fee' => $request->pickup_method === 'diantar' ? $deliveryFee : null,
                'status' => 'menunggu',
                'payment_method' => $request->payment_method,
                'is_paid' => false,
                'auto_cancel_at' => $autoCancelAt,
                'total_amount' => $totalAmount,
                'ordered_at' => now(),
            ]);

            foreach ($cart as $item) {
                $snapshotName = !empty($item['variants'])
                    ? $item['name'] . ' (' . $item['variants'] . ')'
                    : $item['name'];

                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $item['id'],
                    'product_name_snapshot' => mb_substr($snapshotName, 0, 120),
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Kurangi stok produk secara otomatis saat dibeli jika DB trigger tidak aktif (misal di SQLite test)
                if (!in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
                    $product = Product::find($item['id']);
                    if ($product && $product->stock > 0) {
                        $newStock = max(0, $product->stock - $item['quantity']);
                        $product->update(['stock' => $newStock]);
                    }
                }
            }

            return $newOrder;
        });

        session()->forget('cart');
        session()->put('order_code', $order->order_code); // Simpan permanen di session agar tidak hilang saat direfresh
        
        return redirect()->route('customer.tracking', ['order' => $order->order_code])->with('success', 'Pesanan berhasil dibuat!');
    }

    public function getDeliveryLocations(Request $request)
    {
        $terminal = $request->get('terminal');
        if (!$terminal) {
            return response()->json([]);
        }
        $terminalNum = str_ireplace('t', '', (string) $terminal);
        $zone = $request->get('zone'); // 'Airside' atau 'Landside'
        $customerType = $request->get('customer_type');

        $locations = DeliveryLocation::active()
            ->byTerminal($terminalNum)
            ->get(['id', 'name'])
            ->filter(function (DeliveryLocation $loc) use ($zone, $customerType) {
                if (!empty($zone)) {
                    $wantAirside = strcasecmp(trim((string) $zone), 'Airside') === 0;
                    return $loc->isAirside() === $wantAirside;
                }
                if ($customerType === 'pengunjung' && $loc->isAirside()) {
                    return false;
                }
                return true;
            })
            ->map(function (DeliveryLocation $loc) {
                return [
                    'id' => $loc->id,
                    'name' => $loc->name,
                    'is_airside' => $loc->isAirside(),
                ];
            })
            ->values();

        return response()->json($locations);
    }

    public function storeComplaint(Request $request)
    {
        $request->validate([
            'order_code' => 'nullable|exists:orders,order_code',
            'reporter_name' => 'required|string|max:100',
            'reporter_contact' => 'required|string|max:50',
            'category' => 'required|in:pesanan_salah,status_tidak_update,lainnya',
            'description' => 'required|string|max:1000'
        ]);

        $orderId = null;
        $reporterVariations = $this->getPhoneVariations((string) $request->reporter_contact);

        if ($request->filled('order_code')) {
            $order = Order::with('customer')->where('order_code', trim((string) $request->order_code))->first();
            if ($order) {
                // 1. Verifikasi kepemilikan pesanan: nomor WhatsApp pelapor harus cocok dengan nomor pemesan (setelah dinormalisasi)
                $orderPhone = (string) ($order->customer->phone_number ?? $order->customer_phone ?? '');
                $orderPhoneVariations = $this->getPhoneVariations($orderPhone);

                if (empty($reporterVariations) || empty(array_intersect($reporterVariations, $orderPhoneVariations))) {
                    return redirect()->back()->withInput()->withErrors([
                        'reporter_contact' => 'Nomor WhatsApp pelapor tidak sesuai dengan nomor pemesan pada ID Pesanan tersebut.'
                    ]);
                }

                // 2. Verifikasi status pembayaran: hanya pesanan yang sudah dibayar lunas (is_paid = true) yang dapat dilaporkan
                if (!$order->is_paid) {
                    return redirect()->back()->withInput()->withErrors([
                        'order_code' => 'Laporan kendala pesanan hanya dapat diajukan untuk pesanan yang sudah dibayar lunas.'
                    ]);
                }

                $orderId = $order->id;
            }
        } elseif (in_array($request->category, ['pesanan_salah', 'status_tidak_update'])) {
            // Jika order_code dikosongkan namun memilih kategori masalah pesanan, cari otomatis pesanan lunas terbaru dari nomor WA pelapor
            $customerIds = !empty($reporterVariations)
                ? Customer::whereIn('phone_number', $reporterVariations)->pluck('id')->all()
                : [];

            $latestPaidOrder = !empty($customerIds)
                ? Order::whereIn('customer_id', $customerIds)->where('is_paid', true)->latest('ordered_at')->first()
                : null;

            if (!$latestPaidOrder) {
                return redirect()->back()->withInput()->withErrors([
                    'order_code' => 'Tidak ditemukan riwayat pesanan lunas pada nomor WhatsApp tersebut. Silakan cek ID Pesanan (ORD-...) Anda di menu Riwayat, atau pilih kategori Pertanyaan Umum / Saran.'
                ]);
            }

            $orderId = $latestPaidOrder->id;
        }

        \App\Models\Complaint::create([
            'complaint_code' => 'CMP-' . strtoupper(Str::random(6)),
            'order_id' => $orderId,
            'reporter_name' => $request->reporter_name,
            'reporter_contact' => $request->reporter_contact,
            'category' => $request->category,
            'description' => $request->description,
            'status' => 'open'
        ]);

        return redirect()->route('page.faq')->with('success', 'Pesan Anda berhasil dikirim! Tim Support kami akan menghubungi Anda melalui WhatsApp secepatnya.');
    }
}