@extends('admin.dashboard_master')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Achievements</h3>
        <a href="{{ url('/admin/achievement/create') }}" class="btn btn-primary">Add New Achievement</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>{{ $items->firstItem() + $loop->index }}</td>
                    <td>{{ $item->title }}</td>
                    <td>
                        @if($item->image_url)
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" style="max-width:120px; border-radius:8px;">
                        @endif
                    </td>
                    <td>
                        <a href="{{ url('/admin/achievement/edit/'.$item->id) }}" class="btn btn-sm btn-secondary me-1">Edit</a>
                        <a href="{{ url('/admin/achievement/delete/'.$item->id) }}" onclick="return confirm('Delete this achievement?')" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No achievements found. Add one to display it on the homepage.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $items->links() }}
    </div>
</div>
@endsection
