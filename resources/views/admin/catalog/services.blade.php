@extends('layouts.admin')
@section('title', 'Services')
@section('page_title', 'Service Catalog')

@section('content')
<div class="table-wrap">
    <div class="table-toolbar">
        <h3 style="font-size:15px;font-weight:700;flex:1">All Services</h3>
        <a href="{{ route('admin.catalog.services.create') }}" class="btn btn-primary btn-sm">+ New Service</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th>Slug</th>
                <th>Files</th>
                <th>Status</th>
                <th>Updated</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $service)
            <tr>
                <td style="font-weight:700">{{ $service->getTitle() }}</td>
                <td style="color:var(--muted);font-size:12px">{{ $service->slug }}</td>
                <td>{{ $service->attachments->count() }}</td>
                <td>
                    @if($service->is_active)
                        <span class="badge badge-active">Active</span>
                    @else
                        <span class="badge badge-inactive">Inactive</span>
                    @endif
                </td>
                <td style="color:var(--muted);font-size:12px">{{ $service->updated_at?->format('M d, Y') }}</td>
                <td style="white-space:nowrap">
                    <a href="{{ route('admin.catalog.services.edit', $service->public_id) }}" class="btn btn-outline btn-sm">Edit</a>
                    <form action="{{ route('admin.catalog.services.destroy', $service->public_id) }}" method="POST" style="display:inline" onsubmit="return confirm('Permanently delete this service? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:40px">No services yet. Create your first service to populate the catalog.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($services->hasPages())
    <div class="pagination-bar">
        <span>Page {{ $services->currentPage() }} of {{ $services->lastPage() }}</span>
        {{ $services->links() }}
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .badge-active{background:#f0fff4;color:#276749}
    .badge-inactive{background:#edf2f7;color:#4a5568}
</style>
@endpush
