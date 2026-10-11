<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    // ─── PRODUCTS ─────────────────────────────────────────────────────────────

    public function productsIndex()
    {
        $products = Product::withTrashed()->latest()->paginate(20);
        return view('admin.catalog.products', compact('products'));
    }

    public function productCreate()
    {
        return view('admin.catalog.product-form');
    }

    public function productStore(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'slug' => ['required', 'string', 'max:150', 'unique:products,slug'],
            'spec_keys' => ['nullable', 'array'],
            'spec_values' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ]);

        $specs = [];
        if (!empty($validated['spec_keys'])) {
            foreach ($validated['spec_keys'] as $i => $key) {
                if (!empty($key) && isset($validated['spec_values'][$i])) {
                    $specs[$key] = $validated['spec_values'][$i];
                }
            }
        }

        $product = Product::create([
            'public_id' => (string) Str::ulid(),
            'title' => ['ar' => $validated['title_ar'], 'en' => $validated['title_en']],
            'description' => ['ar' => $validated['description_ar'] ?? '', 'en' => $validated['description_en'] ?? ''],
            'slug' => $validated['slug'],
            'specifications' => $specs,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->flushCatalogCache();
        AuditLogger::log('product.created', $product, null, $product->toArray());

        return redirect()->route('admin.catalog.products')->with('success', 'Product created successfully.');
    }

    public function productEdit(Product $product)
    {
        return view('admin.catalog.product-form', compact('product'));
    }

    public function productUpdate(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'spec_keys' => ['nullable', 'array'],
            'spec_values' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ]);

        $specs = [];
        if (!empty($validated['spec_keys'])) {
            foreach ($validated['spec_keys'] as $i => $key) {
                if (!empty($key) && isset($validated['spec_values'][$i])) {
                    $specs[$key] = $validated['spec_values'][$i];
                }
            }
        }

        $old = $product->toArray();
        $product->update([
            'title' => ['ar' => $validated['title_ar'], 'en' => $validated['title_en']],
            'description' => ['ar' => $validated['description_ar'] ?? '', 'en' => $validated['description_en'] ?? ''],
            'specifications' => $specs,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->flushCatalogCache();
        AuditLogger::log('product.updated', $product, $old, $product->fresh()->toArray());

        return redirect()->route('admin.catalog.products')->with('success', 'Product updated successfully.');
    }

    public function productDestroy(Product $product)
    {
        AuditLogger::log('product.soft_deleted', $product, $product->toArray(), null);
        $product->delete();
        $this->flushCatalogCache();
        return redirect()->route('admin.catalog.products')->with('success', 'Product archived.');
    }

    // ─── SERVICES ─────────────────────────────────────────────────────────────

    public function servicesIndex()
    {
        $services = Service::latest()->paginate(20);
        return view('admin.catalog.services', compact('services'));
    }

    public function serviceStore(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'slug' => ['required', 'string', 'max:150', 'unique:services,slug'],
            'is_active' => ['boolean'],
        ]);

        $service = Service::create([
            'public_id' => (string) Str::ulid(),
            'title' => ['ar' => $validated['title_ar'], 'en' => $validated['title_en']],
            'description' => ['ar' => $validated['description_ar'] ?? '', 'en' => $validated['description_en'] ?? ''],
            'slug' => $validated['slug'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->flushCatalogCache();
        AuditLogger::log('service.created', $service, null, $service->toArray());

        return redirect()->route('admin.catalog.services')->with('success', 'Service created.');
    }

    public function serviceDestroy(Service $service)
    {
        AuditLogger::log('service.deleted', $service, $service->toArray(), null);
        $service->delete();
        $this->flushCatalogCache();
        return redirect()->route('admin.catalog.services')->with('success', 'Service deleted.');
    }

    private function flushCatalogCache(): void
    {
        foreach (['ar', 'en'] as $locale) {
            Cache::forget('public.services.all.' . $locale);
            Cache::forget('public.products.all.' . $locale);
            Cache::forget('public.services.' . $locale);
        }
    }
}
