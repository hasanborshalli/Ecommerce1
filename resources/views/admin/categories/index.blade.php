@extends('admin.layout')

@section('breadcrumb', 'Categories')

@section('topbar-actions')
<a href="{{ route('admin.categories.create') }}" class="topbar-btn">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="12" y1="5" x2="12" y2="19" />
        <line x1="5" y1="12" x2="19" y2="12" />
    </svg>
    New Category
</a>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Categories</h1>
        <p class="page-subtitle">{{ $categories->count() }} categories</p>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th>Sort</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>
                        <div
                            style="width:40px;height:44px;background:var(--admin-bg);border-radius:4px;overflow:hidden;">
                            @if($category->image)
                            <img src="{{ asset('storage/'.$category->image) }}"
                                style="width:100%;height:100%;object-fit:contain;">
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:500;">{{ $category->name }}</div>
                        <div style="font-size:11px;color:var(--admin-muted);">{{ $category->slug }}</div>
                    </td>
                    <td><span class="abadge abadge-blue">{{ $category->products_count }}</span></td>
                    <td>
                        <span class="abadge {{ $category->is_active ? 'abadge-green' : 'abadge-gray' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="td-muted">{{ $category->sort_order }}</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                                class="abtn abtn-outline abtn-sm abtn-icon">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                onsubmit="return confirm('Delete {{ addslashes($category->name) }}? Products will also be deleted.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="abtn abtn-danger abtn-sm abtn-icon">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path
                                            d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--admin-muted);padding:48px;">
                        No categories. <a href="{{ route('admin.categories.create') }}"
                            style="color:var(--admin-accent);">Create one →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection