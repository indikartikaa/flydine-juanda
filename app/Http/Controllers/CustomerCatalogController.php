<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Illuminate\Support\Str;

class CustomerCatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with('products')->where('is_active', true);

        // Filter by Search (Tenant Name)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by Terminal
        if ($request->filled('terminal') && $request->terminal !== 'semua') {
            // Because terminal in DB is like '1', '2' or 'Terminal 1', etc.
            // Adjust logic based on how floor_location stores terminal data.
            // The previous frontend mapped it using: substr(strtolower($tenant->floor_location ?? '1'), 0, 1)
            $terminalStr = str_replace('t', '', $request->terminal); // 't1' becomes '1'
            $query->where('floor_location', 'like', '%' . $terminalStr . '%');
        }

        $tenants = $query->paginate(6)->withQueryString();
        
        return view('customer.catalog', compact('tenants'));
    }

    public function show($id)
    {
        $tenant = Tenant::with('products')->findOrFail($id);
        
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
            
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }

        return view('customer.cart', compact('cart', 'tenant', 'total'));
    }

    // Fungsi untuk menampilkan halaman Pelacakan Pesanan
    public function tracking(Request $request)
    {
        $orderCode = session('order_code') ?? $request->query('order');
        if (!$orderCode) {
            return redirect()->route('customer.menu');
        }

        $order = Order::with(['orderItems.product', 'tenant'])->where('order_code', $orderCode)->firstOrFail();

        return view('customer.tracking', compact('order'));
    }

    public function simulatePayment(Request $request)
    {
        $order = Order::where('order_code', $request->order_code)->firstOrFail();
        $order->is_paid = true;
        $order->save();
        
        return redirect()->route('customer.tracking', ['order' => $order->order_code])->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        $cart = session()->get('cart', []);

        // Validate multi-tenant (only 1 tenant per cart)
        if (count($cart) > 0) {
            $firstItem = reset($cart);
            if ($firstItem['tenant_id'] != $request->tenant_id) {
                if ($request->has('force_replace') && $request->force_replace == '1') {
                    $cart = []; // clear cart to replace
                } else {
                    return response()->json(['error' => 'conflict', 'message' => 'Anda memiliki pesanan dari restoran lain. Ingin mengganti restoran?'], 409);
                }
            }
        }

        $product = Product::find($request->product_id);
        $tenant = Tenant::find($request->tenant_id);

        if (!$tenant->isOpen()) {
            return response()->json(['error' => 'closed', 'message' => 'Restoran sedang tutup.'], 400);
        }

        $productId = $product->id;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'tenant_id' => $request->tenant_id,
            ];
        }

        session()->put('cart', $cart);

        return response()->json(['success' => true, 'message' => 'Produk ditambahkan ke keranjang', 'cart_count' => count($cart)]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'action' => 'required|in:increase,decrease,remove'
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            if ($request->action == 'increase') {
                $cart[$productId]['quantity']++;
            } elseif ($request->action == 'decrease') {
                $cart[$productId]['quantity']--;
                if ($cart[$productId]['quantity'] <= 0) {
                    unset($cart[$productId]);
                }
            } elseif ($request->action == 'remove') {
                unset($cart[$productId]);
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('customer.cart');
    }

    public function clearCart(Request $request)
    {
        session()->forget('cart');
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back();
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.menu')->with('error', 'Keranjang Anda kosong.');
        }

        $rules = [
            'customer_type' => 'required|in:penumpang,pengunjung',
            'customer_name' => 'required|string|max:100',
            'payment_method' => 'required|in:qris,transfer'
        ];

        if ($request->customer_type === 'penumpang') {
            $rules['flight_number'] = 'required|string|max:15';
            $rules['gate'] = 'required|string|max:20';
            $rules['boarding_time'] = 'required';
        }

        $request->validate($rules);

        $firstItem = reset($cart);
        $tenantId = $firstItem['tenant_id'];

        $totalAmount = array_reduce($cart, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        // Mock customer for MVP
        $customer = Customer::first();
        $customerId = $customer ? $customer->id : 1; 

        // Hitung Auto-Cancel Dinamis
        $autoCancelAt = now()->addMinutes(15);
        $fullBoardingTime = null;

        if ($request->customer_type === 'penumpang') {
            $fullBoardingTime = \Carbon\Carbon::parse(date('Y-m-d') . ' ' . $request->boarding_time . ':00');
            // Jika boarding time lebih awal dari waktu pembatalan otomatis standar (15 menit),
            // batasi pembatalan tepat pada saat boarding time agar pelanggan tidak membayar setelah pesawat terbang.
            if ($fullBoardingTime->isBefore($autoCancelAt)) {
                $autoCancelAt = $fullBoardingTime;
            }
        }
        
        $order = Order::create([
            'order_code' => 'ORD-' . strtoupper(Str::random(6)),
            'tenant_id' => $tenantId,
            'customer_id' => $customerId,
            'customer_name' => $request->customer_name,
            'flight_number' => $request->customer_type === 'penumpang' ? strtoupper($request->flight_number) : null,
            'gate' => $request->customer_type === 'penumpang' ? strtoupper($request->gate) : null,
            'boarding_time' => $fullBoardingTime,
            'status' => 'menunggu',
            'payment_method' => $request->payment_method,
            'is_paid' => false,
            'auto_cancel_at' => $autoCancelAt,
            'total_amount' => $totalAmount,
            'ordered_at' => now(),
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name_snapshot' => $item['name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        session()->forget('cart');
        session()->put('order_code', $order->order_code); // Simpan permanen di session agar tidak hilang saat direfresh
        
        return redirect()->route('customer.tracking', ['order' => $order->order_code])->with('success', 'Pesanan berhasil dibuat!');
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
        if ($request->filled('order_code')) {
            $order = Order::where('order_code', $request->order_code)->first();
            if ($order) {
                $orderId = $order->id;
            }
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