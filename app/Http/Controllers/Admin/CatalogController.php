<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttachmentUploadRequest;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Requests\Admin\ServiceRequest;
use App\Jobs\ProcessFileUploadJob;
use App\Jobs\ScanAttachmentMalwareJob;
use App\Models\Attachment;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\Service;
use App\Services\AuditLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
        return view('admin.catalog.products.create');
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

        $this->syncAttributes($request, $product);
        $this->syncImages($request, $product);
        $this->syncTechnicalFile($request, $product);

        $this->queueAttachment($request, $product);
        $this->flushCatalogCache();
        AuditLogger::log('product.created', $product, null, $product->toArray());

        return redirect()->route('admin.catalog.products')->with('success', 'Product created successfully.');
    }

    public function productEdit(Product $product)
    {
        $product->load(['attachments', 'attributes', 'images']);
        return view('admin.catalog.products.edit', compact('product'));
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

        $this->syncAttributes($request, $product);
        $this->syncImages($request, $product);
        $this->syncTechnicalFile($request, $product);

        $this->queueAttachment($request, $product);
        $this->flushCatalogCache();
        AuditLogger::log('product.updated', $product, $old, $product->fresh()->toArray());

        return redirect()->route('admin.catalog.products')->with('success', 'Product updated successfully.');
    }

    public function productDestroy(Product $product)
    {
        // Clean up related attribute records.
        $product->attributes()->delete();

        // Delete related gallery images and their files from storage.
        $product->images()->each(function ($image) {
            $path = public_path($image->image_path);
            if (file_exists($path)) {
                @unlink($path);
            }
            $image->delete();
        });

        // Delete related attachment records and their files from storage.
        $product->attachments()->each(function ($attachment) {
            $path = public_path($attachment->file_path);
            if (file_exists($path)) {
                @unlink($path);
            }
            $attachment->delete();
        });

        if (!empty($product->file_path) && file_exists(public_path($product->file_path))) {
            @unlink(public_path($product->file_path));
        }

        AuditLogger::log('product.soft_deleted', $product, $product->toArray(), null);
        $product->delete();
        $this->flushCatalogCache();
        return redirect()->route('admin.catalog.products')->with('success', 'Product archived.');
    }

    public function restoreProduct($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        $this->flushCatalogCache();
        return redirect()->route('admin.catalog.products')->with('success', 'Product restored.');
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
     * Store the uploaded file and create an Attachment record immediately.
     * Uses standard native Laravel storage ('public' disk) so files are served
     * via the storage link and Attachment records are always created synchronously.
     */
    private function queueAttachment(Request $request, Model $model): void
    {
        if (!$request->hasFile('attachment')) {
            return;
        }

        $file = $request->file('attachment');

        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            // Capture metadata before store() moves the uploaded temp file.
            $sizeBytes = $file->getSize();
            $path = $file->store('attachments', 'public');
            $model->attachments()->create([
                'public_id' => (string) Str::ulid(),
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size_bytes' => $sizeBytes,
                'scan_status' => 'pending',
                'uploaded_at' => now(),
            ]);
        }
    }

    private function flushCatalogCache(): void
    {
        foreach (['ar', 'en'] as $locale) {
            Cache::forget('public.services.all.' . $locale);
            Cache::forget('public.products.all.' . $locale);
            Cache::forget('public.services.' . $locale);
        }
    }

    // ─── ATTRIBUTE & IMAGE SYNC HELPERS ──────────────────────────────────────

    /**
     * Replace product attributes from the dynamic repeater input.
     * Expected input names: attr_name_en[], attr_name_ar[],
     * attr_value_en[], attr_value_ar[].
     * sort_order is auto-assigned by array index ($index + 1).
     */
    private function syncAttributes(Request $request, Product $product): void
    {
        $product->attributes()->delete();

        $attrNamesEn = $request->input('attr_name_en', []);
        $attrNamesAr = $request->input('attr_name_ar', []);
        $attrValuesEn = $request->input('attr_value_en', []);
        $attrValuesAr = $request->input('attr_value_ar', []);

        foreach ($attrNamesEn as $index => $nameEn) {
            if (empty(trim($nameEn)) && empty(trim($attrNamesAr[$index] ?? ''))) {
                continue;
            }

            ProductAttribute::create([
                'product_id' => $product->id,
                'name_en' => trim($nameEn),
                'name_ar' => trim($attrNamesAr[$index] ?? ''),
                'value_en' => trim($attrValuesEn[$index] ?? ''),
                'value_ar' => trim($attrValuesAr[$index] ?? ''),
                'sort_order' => $index + 1,
            ]);
        }
    }

    /**
     * Handle the technical datasheet / CAD file upload.
     * Saved to public/uploads/products/files/ and the relative path is stored
     * directly on the products.file_path column (NOT the attachments table).
     * Old files are deleted on replace/remove.
     */
    private function syncTechnicalFile(Request $request, Product $product): void
    {
        $file = null;

        // Accept upload from either 'technical_file' or 'file' input name.
        if ($request->hasFile('technical_file')) {
            $file = $request->file('technical_file');
        } elseif ($request->hasFile('file')) {
            $file = $request->file('file');
        }

        $remove = $request->boolean('removed_technical_file') || $request->input('technical_file_remove') == '1';

        // Delete old file from disk if removing or replacing.
        if ($remove || $file) {
            if (!empty($product->file_path) && file_exists(public_path($product->file_path))) {
                @unlink(public_path($product->file_path));
            }
        }

        // Clear the column if removing and no replacement.
        if ($remove && !$file) {
            $product->update(['file_path' => null]);
            return;
        }

        if (!$file || !$file->isValid()) {
            return;
        }

        $dir = public_path('uploads/products/files');
        File::ensureDirectoryExists($dir, 0755, true);

        $ext = strtolower($file->getClientOriginalExtension());
        $titleEn = is_array($product->title) ? ($product->title['en'] ?? 'product') : ($product->title ?? 'product');
        $filename = Str::slug($titleEn ?: 'product') . '-datasheet-' . time() . '.' . $ext;
        $relativePath = 'uploads/products/files/' . $filename;

        // Capture metadata before move() removes the uploaded temp file.
        $sizeBytes = $file->getSize();
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();

        $file->move($dir, $filename);

        // Save relative path directly on the products.file_path column.
        $product->update(['file_path' => $relativePath]);
    }

    /**
     * Handle multiple product image uploads.
     * Images are stored under public/uploads/products/ for production-safe direct asset() access.
     * On edit, existing images marked for removal via `removed_images[]` are deleted.
     */
    private function syncImages(Request $request, Product $product): void
    {
        // Remove images marked for deletion on edit.
        $removed = $request->input('removed_images', []);
        if (!empty($removed)) {
            ProductImage::whereIn('id', $removed)->each(function ($image) {
                $path = public_path($image->image_path);
                if (file_exists($path)) {
                    @unlink($path);
                }
                $image->delete();
            });
        }

        if (!$request->hasFile('images')) {
            return;
        }

        $dir = public_path('uploads/products');
        File::ensureDirectoryExists($dir, 0755, true);

        $sort = (int) $product->images()->max('sort_order') + 1;
        foreach ($request->file('images') as $file) {
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = Str::slug($product->title['en'] ?? 'product') . '_' . time() . '_' . uniqid() . '.' . $ext;

            $file->move($dir, $filename);

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'uploads/products/' . $filename,
                'sort_order' => $sort++,
            ]);
        }
    }
}
