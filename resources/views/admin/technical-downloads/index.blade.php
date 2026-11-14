@extends('layouts.admin')
@section('title', 'Technical Downloads')
@section('page_title', 'Technical Downloads')

@section('content')
<div class="table-wrap">
    <div class="table-toolbar">
        <h3 style="font-size:15px;font-weight:700;flex:1">Technical Downloads</h3>
        <a href="{{ route('admin.technical-downloads.create') }}" class="btn btn-primary btn-sm">+ New Download</a>
    </div>
    <table id="technical-downloads-table">
        <thead>
            <tr>
                <th style="width:30px">⇅</th>
                <th>Title (EN)</th>
                <th>Title (AR)</th>
                <th>Preview</th>
                <th>File</th>
                <th>Issued</th>
                <th>Order</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($downloads as $download)
            <tr data-id="{{ $download->id }}" data-sort="{{ $download->sort_order }}" class="reorder-row">
                <td style="width:30px;cursor:grab;color:var(--muted);font-size:14px;user-select:none" title="Drag to reorder">
                    ⋮⋮
                </td>
                <td style="font-weight:700">{{ $download->getTitle('en') }}</td>
                <td style="font-size:13px;color:var(--muted)">{{ $download->getTitle('ar') }}</td>
                <td>
                    @if($download->preview_image)
                        <img src="{{ asset($download->preview_image) }}" alt="Preview" style="width:48px;height:48px;object-fit:cover;border-radius:4px;border:1px solid var(--line)">
                    @else
                        <span style="font-size:11px;color:var(--muted)">—</span>
                    @endif
                </td>
                <td style="font-size:12px;color:var(--muted);max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                    {{ basename($download->file_path ?? '') }}
                </td>
                <td style="font-size:12px;color:var(--muted)">
                    {{ $download->issuance_date?->format('M d, Y') ?? '—' }}
                </td>
                <td style="font-size:12px;font-weight:700;color:var(--ink)">{{ $download->sort_order ?? 0 }}</td>
                <td>
                    @if($download->is_active)
                        <span class="badge badge-active">Active</span>
                    @else
                        <span class="badge badge-inactive">Inactive</span>
                    @endif
                </td>
                <td style="white-space:nowrap">
                    <a href="{{ route('admin.technical-downloads.edit', $download->id) }}" class="btn btn-outline btn-sm">Edit</a>
                    <a href="{{ route('admin.technical-downloads.download', $download->id) }}" class="btn btn-sun btn-sm">Download</a>
                    <button class="sort-btn move-up" data-id="{{ $download->id }}" data-direction="up" title="Move up">▲</button>
                    <button class="sort-btn move-down" data-id="{{ $download->id }}" data-direction="down" title="Move down">▼</button>
                    <form action="{{ route('admin.technical-downloads.destroy', $download->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Permanently delete this download and its files? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center;color:var(--muted);padding:40px">No technical downloads yet. Create your first download.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($downloads->hasPages())
    <div class="pagination-bar">
        <span>Page {{ $downloads->currentPage() }} of {{ $downloads->lastPage() }}</span>
        {{ $downloads->links() }}
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .badge-active{background:#f0fff4;color:#276749}
    .badge-inactive{background:#edf2f7;color:#4a5568}
    .reorder-row td{cursor:grab}
    .reorder-row:active{cursor:grabbing}
    .sort-btn{padding:4px 8px;font-size:10px;border-radius:4px;border:1px solid var(--line);background:var(--white);color:var(--ink);cursor:pointer}
    .sort-btn:hover{border-color:var(--sun);color:var(--sun)}
    .sort-btn:disabled{opacity:.5;cursor:not-allowed}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tableBody = document.querySelector('#technical-downloads-table tbody');
    if (!tableBody) return;

    var sortable = Sortable.create(tableBody, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        dragHandle: 'td:first-child',
        onEnd: function (evt) {
            var newOrder = [];
            tableBody.querySelectorAll('tr[data-id]').forEach(function (row, index) {
                newOrder.push({ id: row.dataset.id, sort_order: index });
            });
            fetch('{{ route('admin.technical-downloads.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order: newOrder.map(function (o) { return o.id; }) })
            }).then(function (res) { return res.json(); }).then(function (data) {
                if (data.success) {
                    tableBody.querySelectorAll('tr[data-id]').forEach(function (row, index) {
                        row.querySelector('td:nth-child(7)').textContent = index;
                        row.dataset.sort = index;
                    });
                }
            });
        }
    });

    // Up/Down move buttons
    tableBody.addEventListener('click', function (e) {
        var btn = e.target.closest('.move-up, .move-down');
        if (!btn) return;
        var id = btn.dataset.id;
        var direction = btn.dataset.direction;
        fetch('{{ route('admin.technical-downloads.move', 0) }}/'.replace('/0', '/' + id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ direction: direction })
        }).then(function (res) { return res.json(); }).then(function (data) {
            if (data.success) {
                location.reload();
            }
        });
    });
});
</script>
@endpush
