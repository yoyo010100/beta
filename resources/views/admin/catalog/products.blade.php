@extends('layouts.admin')
@section('title', 'Products')
@section('page_title', 'Product Catalog')

@section('content')
<div class="table-wrap">
    <div class="table-toolbar">
        <h3 style="font-size:15px;font-weight:700;flex:1">All Products</h3>
        <a href="{{ route('admin.catalog.products.create') }}" class="btn btn-primary btn-sm">+ New Product</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Slug</th>
                <th>Specs</th>
                <th>Files</th>
                <th>Status</th>
                <th>Updated</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td style="font-weight:700">{{ $product->getTitle() }}</td>
                <td style="color:var(--muted);font-size:12px">{{ $product->slug }}</td>
                <td>{{ is_array($product->specifications) ? count($product->specifications) : 0 }}</td>
                <td>{{ $product->attachments->count() }}</td>
                <td>
                    @if($product->trashed())
                        <span class="badge badge-archived">Archived</span>
                    @elseif($product->is_active)
                        <span class="badge badge-active">Active</span>
                    @else
                        <span class="badge badge-inactive">Inactive</span>
                    @endif
                </td>
                <td style="color:var(--muted);font-size:12px">{{ $product->updated_at?->format('M d, Y') }}</td>
                <td style="white-space:nowrap">
                    @if(!$product->trashed())
                        <a href="{{ route('admin.catalog.products.edit', $product->public_id) }}" class="btn btn-outline btn-sm">Edit</a>
                        <form action="{{ route('admin.catalog.products.destroy', $product->public_id) }}" method="POST" style="display:inline" onsubmit="return confirm('Archive this product? It will be hidden from the public catalog.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Archive</button>
                        </form>
                    @else
                        <span style="font-size:11px;color:var(--muted)">Archived — no actions</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:40px">No products yet. Create your first product to populate the catalog.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($products->hasPages())
    <div class="pagination-bar">
        <span>Page {{ $products->currentPage() }} of {{ $products->lastPage() }}</span>
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .badge-active{background:#f0fff4;color:#276749}
    .badge-inactive{background:#edf2f7;color:#4a5568}
    .badge-archived{background:#fff5f5;color:var(--red)}
</style>
@endpush
