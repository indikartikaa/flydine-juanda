<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class TenantProductController extends Controller
{
    private function checkTenant(Product $product)
    {
        abort_unless(
            auth()->user()->role === 'tenant_staff' &&
            auth()->user()->tenant_id === $product->tenant_id,
            403
        );
    }

    public function create()
    {
        abort_unless(
            auth()->user()->role === 'tenant_staff' &&
            auth()->user()->tenant_id,
            403
        );

        return view('tenant.create-product');
    }

    public function store(Request $request)
    {
        abort_unless(
            auth()->user()->role === 'tenant_staff' &&
            auth()->user()->tenant_id,
            403
        );

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'category' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:300',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'note' => 'nullable|string|max:1000',
            'stock' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('products', 'public');
        }

        Product::create([
            'tenant_id' => auth()->user()->tenant_id,
            'name' => $data['name'],
            'category' => $data['category'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'image' => $data['image'] ?? null,
            'note' => $data['note'] ?? null,
            'stock' => $data['stock'],
            'is_available' => $request->boolean('is_available'),
        ]);

        return redirect()
            ->route('tenant.products')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $this->checkTenant($product);

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'category' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:300',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'note' => 'nullable|string|max:1000',
            'stock' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $product->update([
            'name' => $data['name'],
            'category' => $data['category'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'image' => $data['image'] ?? $product->image,
            'note' => $data['note'] ?? null,
            'stock' => $data['stock'],
            'is_available' => $request->boolean('is_available'),
        ]);

        return redirect()
            ->route('tenant.products')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $this->checkTenant($product);

        $product->delete();

        return redirect()
            ->route('tenant.products')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
