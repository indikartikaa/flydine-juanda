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

    private function parseCustomizationOptions(Request $request, ?Product $existingProduct = null): ?array
    {
        if ($request->has('variant_groups')) {
            $rawGroups = $request->input('variant_groups', []);
            if (!is_array($rawGroups)) {
                return null;
            }

            $parsed = [];
            foreach ($rawGroups as $grp) {
                if (!is_array($grp)) {
                    continue;
                }
                $label = mb_substr(trim((string) ($grp['label'] ?? '')), 0, 50);
                $rawChoices = $grp['choices'] ?? '';
                $choiceArr = is_array($rawChoices) ? $rawChoices : explode(',', (string) $rawChoices);
                $choices = [];
                foreach ($choiceArr as $c) {
                    $cleanChoice = mb_substr(trim((string) $c), 0, 40);
                    if ($cleanChoice !== '' && !in_array($cleanChoice, $choices, true)) {
                        $choices[] = $cleanChoice;
                    }
                }
                if ($label !== '' && count($choices) > 0) {
                    $parsed[] = [
                        'label' => $label,
                        'choices' => $choices,
                    ];
                }
            }

            return !empty($parsed) ? $parsed : null;
        }

        if ($request->has('customization_options')) {
            $raw = $request->input('customization_options', []);
            if (is_array($raw)) {
                $filtered = array_values(array_filter($raw));
                return !empty($filtered) ? $filtered : null;
            }
            return null;
        }

        if ($request->boolean('customization_options_submitted')) {
            return null;
        }

        return $existingProduct ? ($existingProduct->customization_options ?: null) : null;
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'note' => 'nullable|string|max:1000',
            'customization_options' => 'nullable|array',
            'variant_groups' => 'nullable|array',
            'stock' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $options = $this->parseCustomizationOptions($request);

        Product::create([
            'tenant_id' => auth()->user()->tenant_id,
            'name' => $data['name'],
            'category' => $data['category'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'image' => $data['image'] ?? null,
            'note' => $data['note'] ?? null,
            'customization_options' => $options,
            'stock' => $data['stock'],
            'is_available' => $request->boolean('is_available'),
        ]);

        return redirect()
            ->route('tenant.products')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $this->checkTenant($product);
        return redirect()->route('tenant.products');
    }

    public function edit(Product $product)
    {
        $this->checkTenant($product);
        return redirect()->route('tenant.products');
    }

    public function update(Request $request, Product $product)
    {
        $this->checkTenant($product);

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'category' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:300',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'note' => 'nullable|string|max:1000',
            'customization_options' => 'nullable|array',
            'variant_groups' => 'nullable|array',
            'stock' => 'required|integer|min:0',
        ]);

        $oldImage = $product->getRawOriginal('image');

        if ($request->hasFile('image')) {
            if ($oldImage && !str_starts_with($oldImage, 'images/') && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldImage)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldImage);
            }

            $data['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $options = $this->parseCustomizationOptions($request, $product);

        $product->update([
            'name' => $data['name'],
            'category' => $data['category'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'image' => $data['image'] ?? $oldImage,
            'note' => $data['note'] ?? null,
            'customization_options' => $options,
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

        try {
            $productName = $product->name;

            // Hapus file foto custom hanya jika produk belum pernah dipesan (menjaga integritas historis)
            $hasOrders = \App\Models\OrderItem::where('product_id', $product->id)->exists();
            if (!$hasOrders) {
                $oldImage = $product->getRawOriginal('image');
                if ($oldImage && !str_starts_with($oldImage, 'images/') && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldImage)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldImage);
                }
            }

            // Hapus produk (SoftDeletes memastikan integritas foreign key order_items terjaga)
            $product->delete();

            return redirect()
                ->route('tenant.products')
                ->with('success', "Menu '{$productName}' berhasil dihapus dari katalog.");
        } catch (\Illuminate\Database\QueryException $e) {
            \Illuminate\Support\Facades\Log::warning("QueryException saat menghapus produk ID {$product->id}: " . $e->getMessage());

            // Pengaman ganda: nonaktifkan ketersediaan menu jika terdapat kendala integritas DB
            $product->update(['is_available' => false]);

            return redirect()
                ->route('tenant.products')
                ->with('success', "Menu '{$product->name}' berhasil dihapus dari katalog.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Gagal menghapus produk ID {$product->id}: " . $e->getMessage());

            return redirect()
                ->route('tenant.products')
                ->with('error', 'Terjadi kesalahan saat menghapus menu: ' . $e->getMessage());
        }
    }

    public function toggle(Product $product)
    {
        $this->checkTenant($product);

        $product->update([
            'is_available' => !$product->is_available,
        ]);

        $statusText = $product->is_available ? 'diaktifkan' : 'dinonaktifkan';

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_available' => $product->is_available,
                'message' => "Status menu '{$product->name}' berhasil {$statusText}.",
            ]);
        }

        return redirect()
            ->route('tenant.products')
            ->with('success', "Status menu '{$product->name}' berhasil {$statusText}.");
    }
}
