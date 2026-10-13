@extends('layouts.admin')
@section('title', 'Announcements')
@section('page_title', 'Announcements CMS')

@section('content')
<div class="table-wrap">
    <div class="table-toolbar">
        <h3 style="font-size:15px;font-weight:700;flex:1">All Announcements</h3>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary btn-sm">+ New Announcement</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Slug</th>
                <th>Published</th>
                <th>Status</th>
                <th>Files</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($announcements as $announcement)
            <tr>
                <td style="font-weight:700">{{ $announcement->getTitle() }}</td>
                <td><span class="badge badge-{{ str_replace('_', '-', $announcement->type ?? 'news') }}">{{ ucfirst(str_replace('_', ' ', $announcement->type ?? 'news')) }}</span></td>
                <td style="color:var(--muted);font-size:12px">{{ $announcement->slug }}</td>
                <td style="color:var(--muted);font-size:12px">
                    {{ $announcement->published_at?->format('M d, Y') ?? 'Draft' }}
                </td>
                <td>
                    @if($announcement->is_active)
                        <span class="badge badge-active">Active</span>
                    @else
                        <span class="badge badge-inactive">Inactive</span>
                    @endif
                </td>
                <td>{{ $announcement->attachments->count() }}</td>
                <td style="white-space:nowrap">
                    <a href="{{ route('admin.announcements.edit', $announcement->public_id) }}" class="btn btn-outline btn-sm">Edit</a>
                    <form action="{{ route('admin.announcements.destroy', $announcement->public_id) }}" method="POST" style="display:inline" onsubmit="return confirm('Permanently delete this announcement? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:40px">No announcements yet. Create your first news, press release, or event.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($announcements->hasPages())
    <div class="pagination-bar">
        <span>Page {{ $announcements->currentPage() }} of {{ $announcements->lastPage() }}</span>
        {{ $announcements->links() }}
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .badge-news{background:#ebf8ff;color:#2b6cb0}
    .badge-press-release{background:#faf5ff;color:#6b46c1}
    .badge-event{background:#fefcbf;color:#744210}
    .badge-active{background:#f0fff4;color:#276749}
    .badge-inactive{background:#edf2f7;color:#4a5568}
</style>
@endpush
