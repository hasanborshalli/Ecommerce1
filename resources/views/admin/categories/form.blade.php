@extends('admin.layout')

@section('breadcrumb', isset($category) ? 'Edit Category' : 'New Category')

@section('topbar-actions')
<a href="{{ route('admin.categories.index') }}" class="topbar-btn topbar-btn-outline">← Back</a>
@endsection

@section('content')

@php $isEdit = isset($category); @endphp

<div class="page-header">
    <h1 class="page-title">{{ $isEdit ? 'Edit Category' : 'New Category' }}</h1>
</div>

<form action="{{ $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
    method="POST" enctype="multipart/form-data" style="max-width:720px;">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="card" style="margin-bottom:20px;">
        <div class="card-header"><span class="card-title">Category Details</span></div>
        <div class="card-body">
            <div class="aform-row-2">
                <div class="aform-group">
                    <label class="aform-label">Name <span class="req">*</span></label>
                    <input type="text" name="name" class="aform-control {{ $errors->has('name') ? 'error' : '' }}"
                        value="{{ old('name', $category->name ?? '') }}" required placeholder="e.g. Skincare">
                    @error('name')<p class="aform-error">{{ $message }}</p>@enderror
                </div>
                <div class="aform-group">
                    <label class="aform-label">Sort Order</label>
                    <input type="number" name="sort_order" class="aform-control"
                        value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
                </div>
            </div>
            <div class="aform-group">
                <label class="aform-label">Description</label>
                <textarea name="description" class="aform-control" rows="2"
                    placeholder="Short description shown in category listings…">{{ old('description', $category->description ?? '') }}</textarea>
            </div>
            <div class="aform-group">
                <label class="aform-label">Category Image</label>
                <div id="catImgWrap"
                    style="{{ ($isEdit && !empty($category->image)) ? '' : 'display:none;' }} margin-bottom:10px;">
                    <img id="catImgPreview"
                        src="{{ ($isEdit && !empty($category->image)) ? asset('storage/'.$category->image) : '' }}"
                        style="width:120px;height:80px;object-fit:contain;border-radius:6px;border:1px solid var(--admin-border);background:var(--admin-bg);">
                </div>
                <input type="file" name="image" class="aform-control" accept="image/*" style="padding:6px;"
                    onchange="previewCatImg(this)">
            </div>
            <label class="toggle-wrap" style="margin-bottom:0;">
                <div class="toggle">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true)
                    ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </div>
                <span class="toggle-label">Active (visible on store)</span>
            </label>
        </div>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <div class="card-header"><span class="card-title">SEO</span></div>
        <div class="card-body">
            <div class="aform-group">
                <label class="aform-label">Meta Title</label>
                <input type="text" name="meta_title" class="aform-control" maxlength="160"
                    value="{{ old('meta_title', $category->meta_title ?? '') }}">
            </div>
            <div class="aform-group" style="margin-bottom:0;">
                <label class="aform-label">Meta Description</label>
                <textarea name="meta_description" class="aform-control" rows="2"
                    maxlength="320">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <button type="submit" class="abtn abtn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
            <polyline points="17 21 17 13 7 13 7 21" />
        </svg>
        {{ $isEdit ? 'Update Category' : 'Save Category' }}
    </button>
</form>

@endsection

@push('scripts')
<script>
    function previewCatImg(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('catImgPreview').src = e.target.result;
        document.getElementById('catImgWrap').style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush