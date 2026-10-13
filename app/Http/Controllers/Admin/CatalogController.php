<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttachmentUploadRequest;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Requests\Admin\ServiceRequest;
use App\Jobs\ProcessFileUploadJob;
use App\Models\Attachment;
use App\Models\Product;
use App\Models\Service;
use App\Services\AuditLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

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

    public function productStore(ProductRequest $request)
    {
        $validated = $request->validated();

        $product = Product::create([
            'title' => ['ar' => $validated['title_ar'], 'en' => $validated['title_en']],
            'description' => ['ar' => $validated['description_ar'] ?? '', 'en' => $validated['description_en'] ?? ''],
            'slug' => $validated['slug'],
            'specifications' => $this->buildSpecs($validated),
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->queueAttachment($request, $product);
        $this->flushCatalogCache();
        AuditLogger::log('product.created', $product, null, $product->toArray());

        return redirect()->route('admin.catalog.products')->with('success', 'Product created successfully.');
    }

    public function productEdit(Product $product)
    {
        $product->load('attachments');
        return view('admin.catalog.product-form', compact('product'));
    }

    public function productUpdate(ProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        $old = $product->toArray();
        $product->update([
            'title' => ['ar' => $validated['title_ar'], 'en' => $validated['title_en']],
            'description' => ['ar' => $validated['description_ar'] ?? '', 'en' => $validated['description_en'] ?? ''],
            'slug' => $validated['slug'],
            'specifications' => $this->buildSpecs($validated),
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->queueAttachment($request, $product);
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

    public function productAttachmentStore(AttachmentUploadRequest $request, Product $product)
    {
        $this->queueAttachment($request, $product);
        return redirect()->back()->with('success', 'Attachment upload queued for scanning.');
    }

    // ─── SERVICES ─────────────────────────────────────────────────────────────

    public function servicesIndex()
    {
        $services = Service::latest()->paginate(20);
        return view('admin.catalog.services', compact('services'));
    }

    public function serviceCreate()
    {
        return view('admin.catalog.service-form');
    }

    public function serviceStore(ServiceRequest $request)
    {
        $validated = $request->validated();

        $service = Service::create([
            'title' => ['ar' => $validated['title_ar'], 'en' => $validated['title_en']],
            'description' => ['ar' => $validated['description_ar'] ?? '', 'en' => $validated['description_en'] ?? ''],
            'slug' => $validated['slug'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->queueAttachment($request, $service);
        $this->flushCatalogCache();
        AuditLogger::log('service.created', $service, null, $service->toArray());

        return redirect()->route('admin.catalog.services')->with('success', 'Service created.');
    }

    public function serviceEdit(Service $service)
    {
        $service->load('attachments');
        return view('admin.catalog.service-form', compact('service'));
    }

    public function serviceUpdate(ServiceRequest $request, Service $service)
    {
        $validated = $request->validated();

        $old = $service->toArray();
        $service->update([
            'title' => ['ar' => $validated['title_ar'], 'en' => $validated['title_en']],
            'description' => ['ar' => $validated['description_ar'] ?? '', 'en' => $validated['description_en'] ?? ''],
            'slug' => $validated['slug'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->queueAttachment($request, $service);
        $this->flushCatalogCache();
        AuditLogger::log('service.updated', $service, $old, $service->fresh()->toArray());

        return redirect()->route('admin.catalog.services')->with('success', 'Service updated.');
    }

    public function serviceDestroy(Service $service)
    {
        AuditLogger::log('service.deleted', $service, $service->toArray(), null);
        $service->delete();
        $this->flushCatalogCache();
        return redirect()->route('admin.catalog.services')->with('success', 'Service deleted.');
    }

    public function serviceAttachmentStore(AttachmentUploadRequest $request, Service $service)
    {
        $this->queueAttachment($request, $service);
        return redirect()->back()->with('success', 'Attachment upload queued for scanning.');
    }

    // ─── ATTACHMENTS ──────────────────────────────────────────────────────────

    public function attachmentDestroy(Attachment $attachment)
    {
        Storage::disk('private')->delete($attachment->file_path);
        AuditLogger::log('attachment.deleted', $attachment, $attachment->toArray(), null);
        $attachment->delete();
        return redirect()->back()->with('success', 'Attachment removed.');
    }

    // ─── HELPERS ──────────────────────────────────────────────────────────────

    /**
     * Build the specifications JSON map from parallel key/value arrays.
     */
    private function buildSpecs(array $validated): array
    {
        $specs = [];
        if (!empty($validated['spec_keys'])) {
            foreach ($validated['spec_keys'] as $i => $key) {
                if (!empty($key) && isset($validated['spec_values'][$i])) {
                    $specs[$key] = $validated['spec_values'][$i];
                }
            }
        }
        return $specs;
    }

    /**
     * Push an uploaded file onto the Redis queue for asynchronous scanning/storage.
     */
    private function queueAttachment(Request $request, Model $model): void
    {
        if (!$request->hasFile('attachment')) {
            return;
        }

        $file = $request->file('attachment');
        $tempPath = $file->store('temp', 'private');

        ProcessFileUploadJob::dispatch(
            storage_path('app/private/' . $tempPath),
            $file->getClientOriginalName(),
            $model::class,
            $model->getKey()
        );
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
