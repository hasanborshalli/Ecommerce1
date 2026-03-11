@extends('admin.layout')

@section('breadcrumb', isset($product) ? 'Edit Product' : 'New Product')

@section('topbar-actions')
<a href="{{ route('admin.products.index') }}" class="topbar-btn topbar-btn-outline">← Back to Products</a>
@endsection

@section('content')

@php $isEdit = isset($product); @endphp

<div class="page-header">
    <div>
        <h1 class="page-title">{{ $isEdit ? 'Edit Product' : 'New Product' }}</h1>
        <p class="page-subtitle">{{ $isEdit ? 'Update product details below' : 'Fill in the details to add a new
            product' }}</p>
    </div>
</div>

<form action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div style="display:grid; grid-template-columns:1fr 320px; gap:20px; align-items:start;">

        {{-- ── Left Column ──────────────────────────────── --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- Basic Info --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Basic Information</span></div>
                <div class="card-body">
                    <div class="aform-group">
                        <label class="aform-label">Product Name <span class="req">*</span></label>
                        <input type="text" name="name" class="aform-control {{ $errors->has('name') ? 'error' : '' }}"
                            value="{{ old('name', $product->name ?? '') }}" required placeholder="e.g. Silk Slip Dress">
                        @error('name')<p class="aform-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="aform-row-2">
                        <div class="aform-group">
                            <label class="aform-label">Category <span class="req">*</span></label>
                            <select name="category_id"
                                class="aform-control {{ $errors->has('category_id') ? 'error' : '' }}" required>
                                <option value="">Select category</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') ==
                                    $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="aform-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="aform-group">
                            <label class="aform-label">SKU</label>
                            <input type="text" name="sku" class="aform-control"
                                value="{{ old('sku', $product->sku ?? '') }}" placeholder="e.g. SKU-ABC123">
                        </div>
                    </div>
                    <div class="aform-group">
                        <label class="aform-label">Short Description</label>
                        <textarea name="short_description" class="aform-control" rows="2"
                            placeholder="One or two lines shown in listings…">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                    </div>
                    <div class="aform-group">
                        <label class="aform-label">Full Description</label>
                        <textarea name="description" class="aform-control" rows="6"
                            placeholder="Full product details, materials, care instructions…">{{ old('description', $product->description ?? '') }}</textarea>
                        <p class="aform-hint">HTML is supported.</p>
                    </div>
                </div>
            </div>

            {{-- Variants: Sizes & Colors --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Variants</span>
                    <span style="font-size:11px; color:var(--admin-muted);">Sizes, colors, or any other options</span>
                </div>
                <div class="card-body">
                    @php
                    $existingVariants = old('variants_json',
                    ($product->variants ?? null)
                    ? json_encode($product->variants)
                    : '{}'
                    );
                    @endphp
                    <input type="hidden" name="variants_json" id="variantsJsonInput" value="{{ $existingVariants }}">

                    <div id="variantGroups" style="display:flex; flex-direction:column; gap:16px;"></div>

                    <button type="button" onclick="addVariantGroup()" class="abtn abtn-outline abtn-sm"
                        style="margin-top:12px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Add Option (e.g. Size, Color)
                    </button>

                    <p class="aform-hint" style="margin-top:10px;">
                        Examples: <strong>Size</strong> → S, M, L, XL &nbsp;|&nbsp; <strong>Color</strong> → Black,
                        White, Beige
                    </p>
                </div>
            </div>

            {{-- Pricing & Inventory --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Pricing & Inventory</span></div>
                <div class="card-body">
                    <div class="aform-row-3">
                        <div class="aform-group">
                            <label class="aform-label">Regular Price <span class="req">*</span></label>
                            <input type="number" name="price"
                                class="aform-control {{ $errors->has('price') ? 'error' : '' }}"
                                value="{{ old('price', $product->price ?? '') }}" step="0.01" min="0" required
                                placeholder="0.00">
                            @error('price')<p class="aform-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="aform-group">
                            <label class="aform-label">Sale Price</label>
                            <input type="number" name="sale_price" class="aform-control"
                                value="{{ old('sale_price', $product->sale_price ?? '') }}" step="0.01" min="0"
                                placeholder="0.00">
                            <p class="aform-hint">Leave blank if not on sale.</p>
                        </div>
                        <div class="aform-group">
                            <label class="aform-label">Stock Quantity <span class="req">*</span></label>
                            <input type="number" name="stock"
                                class="aform-control {{ $errors->has('stock') ? 'error' : '' }}"
                                value="{{ old('stock', $product->stock ?? 0) }}" min="0" required>
                            @error('stock')<p class="aform-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="card">
                <div class="card-header"><span class="card-title">SEO</span></div>
                <div class="card-body">
                    <div class="aform-group">
                        <label class="aform-label">Meta Title</label>
                        <input type="text" name="meta_title" class="aform-control" maxlength="160"
                            value="{{ old('meta_title', $product->meta_title ?? '') }}"
                            placeholder="Shown in search engine results…">
                    </div>
                    <div class="aform-group">
                        <label class="aform-label">Meta Description</label>
                        <textarea name="meta_description" class="aform-control" rows="2" maxlength="320"
                            placeholder="Brief summary for search engines…">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                    </div>
                    <div class="aform-group" style="margin-bottom:0;">
                        <label class="aform-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="aform-control"
                            value="{{ old('meta_keywords', $product->meta_keywords ?? '') }}"
                            placeholder="keyword1, keyword2…">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Right Column ──────────────────────────────── --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- Status & Flags --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Status & Flags</span></div>
                <div class="card-body" style="display:flex; flex-direction:column; gap:14px;">
                    <label class="toggle-wrap">
                        <div class="toggle">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ??
                            true) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </div>
                        <span class="toggle-label">Active (visible on store)</span>
                    </label>
                    <label class="toggle-wrap">
                        <div class="toggle">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured',
                                $product->is_featured ?? false) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </div>
                        <span class="toggle-label">Featured on homepage</span>
                    </label>
                    <label class="toggle-wrap">
                        <div class="toggle">
                            <input type="hidden" name="is_new" value="0">
                            <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new ?? false) ?
                            'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </div>
                        <span class="toggle-label">Mark as New Arrival</span>
                    </label>
                    <label class="toggle-wrap">
                        <div class="toggle">
                            <input type="hidden" name="is_on_sale" value="0">
                            <input type="checkbox" name="is_on_sale" value="1" {{ old('is_on_sale', $product->is_on_sale
                            ?? false) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </div>
                        <span class="toggle-label">On Sale (use Sale Price)</span>
                    </label>
                    <label class="toggle-wrap">
                        <div class="toggle">
                            <input type="hidden" name="show_when_out_of_stock" value="0">
                            <input type="checkbox" name="show_when_out_of_stock" value="1" {{
                                old('show_when_out_of_stock', $product->show_when_out_of_stock ?? true) ? 'checked' : ''
                            }}>
                            <span class="toggle-slider"></span>
                        </div>
                        <span class="toggle-label">Show when out of stock</span>
                    </label>
                    <div class="aform-group" style="margin-bottom:0; margin-top:4px;">
                        <label class="aform-label">Sort Order</label>
                        <input type="number" name="sort_order" class="aform-control"
                            value="{{ old('sort_order', $product->sort_order ?? 0) }}" min="0">
                    </div>
                </div>
            </div>

            {{-- Main Image --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Main Image</span></div>
                <div class="card-body">
                    {{-- Preview: existing or newly selected --}}
                    <div id="mainImagePreview"
                        style="{{ ($isEdit && $product->main_image) ? '' : 'display:none;' }} margin-bottom:12px;">
                        <img id="mainImagePreviewImg"
                            src="{{ ($isEdit && $product->main_image) ? asset('storage/'.$product->main_image) : '' }}"
                            style="width:100%; height:160px; object-fit:contain; border-radius:6px; border:1px solid var(--admin-border); background:var(--admin-bg);">
                    </div>
                    <label class="image-upload-area" for="mainImageInput">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--admin-muted)"
                            stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                        <p style="font-size:12px; color:var(--admin-muted); margin-top:8px;">Click to upload</p>
                        <p style="font-size:11px; color:var(--admin-muted); margin-top:4px;">JPG, PNG, WebP</p>
                        <input type="file" name="main_image" id="mainImageInput" accept="image/*"
                            onchange="previewSingle(this, 'mainImagePreview', 'mainImagePreviewImg')">
                    </label>
                </div>
            </div>

            {{-- Gallery --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Gallery Images</span>
                    <span style="font-size:11px;color:var(--admin-muted);">Drag to reorder · click × to remove</span>
                </div>
                <div class="card-body">
                    {{-- Hidden input: tracks which existing images to keep and their order --}}
                    <input type="hidden" name="gallery_keep" id="galleryKeepInput"
                        value="{{ json_encode($product->gallery ?? []) }}">

                    {{-- Existing gallery (sortable) --}}
                    <div id="existingGallery" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px;">
                        @if($isEdit && $product->gallery && count($product->gallery))
                        @foreach($product->gallery as $img)
                        <div class="gallery-item" data-path="{{ $img }}"
                            style="position:relative;width:80px;height:80px;border:1px solid var(--admin-border);border-radius:4px;overflow:visible;cursor:grab;background:var(--admin-bg);">
                            <img src="{{ asset('storage/'.$img) }}"
                                style="width:100%;height:100%;object-fit:contain;border-radius:4px;">
                            <button type="button" onclick="removeExistingGalleryImg(this)"
                                style="position:absolute;top:-6px;right:-6px;width:18px;height:18px;background:#ef4444;border:none;border-radius:50%;color:white;font-size:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;">×</button>
                        </div>
                        @endforeach
                        @endif
                    </div>

                    {{-- New images preview --}}
                    <div id="galleryPreview" style="display:none;flex-wrap:wrap;gap:8px;margin-bottom:12px;"></div>

                    <label class="image-upload-area" for="galleryInput">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--admin-muted)"
                            stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                        <p style="font-size:12px;color:var(--admin-muted);margin-top:8px;">Add more gallery images</p>
                        <p style="font-size:11px;color:var(--admin-muted);margin-top:4px;">Hold Ctrl / Cmd to pick
                            several</p>
                        <input type="file" name="gallery[]" id="galleryInput" accept="image/*" multiple
                            onchange="previewGallery(this)">
                    </label>
                </div>
            </div>

            {{-- Save button --}}
            <button type="submit" class="abtn abtn-primary" style="width:100%; justify-content:center; padding:12px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                    <polyline points="17 21 17 13 7 13 7 21" />
                    <polyline points="7 3 7 8 15 8" />
                </svg>
                {{ $isEdit ? 'Update Product' : 'Save Product' }}
            </button>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    // ── Image Previews ─────────────────────────────────────
function previewSingle(input, wrapId, imgId) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById(imgId).src = e.target.result;
        document.getElementById(wrapId).style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
}

function previewGallery(input) {
    const wrap = document.getElementById('galleryPreview');
    wrap.innerHTML = '';
    if (!input.files || !input.files.length) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'flex';
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.style.cssText = 'position:relative;width:80px;height:80px;border:1px solid var(--admin-border);border-radius:4px;background:var(--admin-bg);';
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'width:100%;height:100%;object-fit:contain;border-radius:4px;';
            div.appendChild(img);
            wrap.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

// Remove existing gallery image and update hidden input
function removeExistingGalleryImg(btn) {
    const item = btn.closest('.gallery-item');
    const path = item.dataset.path;
    item.remove();
    syncGalleryKeep();
}

// Sync gallery keep hidden input from visible items order
function syncGalleryKeep() {
    const items = document.querySelectorAll('#existingGallery .gallery-item');
    const paths = Array.from(items).map(el => el.dataset.path);
    document.getElementById('galleryKeepInput').value = JSON.stringify(paths);
}

// Simple drag-to-sort for existing gallery
(function() {
    let dragged = null;
    document.addEventListener('dragstart', e => {
        const item = e.target.closest('.gallery-item');
        if (item) { dragged = item; item.style.opacity = '0.4'; }
    });
    document.addEventListener('dragend', e => {
        const item = e.target.closest('.gallery-item');
        if (item) { item.style.opacity = '1'; syncGalleryKeep(); }
    });
    document.addEventListener('dragover', e => {
        e.preventDefault();
        const item = e.target.closest('.gallery-item');
        if (item && item !== dragged) {
            const rect = item.getBoundingClientRect();
            const after = e.clientX > rect.left + rect.width / 2;
            item.parentNode.insertBefore(dragged, after ? item.nextSibling : item);
        }
    });
    // Enable draggable
    setTimeout(() => {
        document.querySelectorAll('.gallery-item').forEach(el => el.setAttribute('draggable', 'true'));
    }, 100);
})();

// ── Variants ───────────────────────────────────────────
let variantData = {};
try {
    const raw = document.getElementById('variantsJsonInput').value;
    variantData = JSON.parse(raw || '{}');
} catch(e) { variantData = {}; }

function renderVariants() {
    const wrap = document.getElementById('variantGroups');
    wrap.innerHTML = '';
    Object.entries(variantData).forEach(([name, values]) => {
        const group = document.createElement('div');
        group.style.cssText = 'background:var(--admin-bg);border:1px solid var(--admin-border);border-radius:6px;padding:14px 16px;';
        group.innerHTML = `
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                <input type="text" value="${name}" placeholder="Option name (e.g. Size)"
                    style="flex:1;background:var(--admin-surface);border:1px solid var(--admin-border);color:var(--admin-text);padding:7px 10px;border-radius:4px;font-size:12px;font-family:inherit;"
                    onchange="renameGroup('${name}', this.value)">
                <button type="button" onclick="removeGroup('${name}')"
                    style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#ef4444;padding:5px 10px;border-radius:4px;font-size:11px;cursor:pointer;">
                    Remove
                </button>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:8px;" id="tags-${name}">
                ${values.map(v => `
                    <span style="display:inline-flex;align-items:center;gap:5px;background:var(--admin-accent);color:white;padding:3px 10px 3px 12px;border-radius:20px;font-size:12px;">
                        ${v}
                        <button type="button" onclick="removeValue('${name}','${v}')"
                            style="background:none;border:none;color:white;cursor:pointer;font-size:14px;line-height:1;padding:0;">×</button>
                    </span>
                `).join('')}
            </div>
            <div style="display:flex;gap:8px;">
                <input type="text" id="newval-${name}" placeholder="Add value (e.g. M, Red…)"
                    style="flex:1;background:var(--admin-surface);border:1px solid var(--admin-border);color:var(--admin-text);padding:6px 10px;border-radius:4px;font-size:12px;font-family:inherit;"
                    onkeydown="if(event.key==='Enter'){event.preventDefault();addValue('${name}');}">
                <button type="button" onclick="addValue('${name}')"
                    style="background:var(--admin-border);border:none;color:var(--admin-text);padding:6px 14px;border-radius:4px;font-size:12px;cursor:pointer;">Add</button>
            </div>
        `;
        wrap.appendChild(group);
    });
    syncHidden();
}

function addVariantGroup() {
    const name = prompt('Option name (e.g. Size, Color):');
    if (!name || !name.trim()) return;
    const key = name.trim();
    if (!variantData[key]) variantData[key] = [];
    renderVariants();
}

function removeGroup(name) {
    delete variantData[name];
    renderVariants();
}

function renameGroup(oldName, newName) {
    newName = newName.trim();
    if (!newName || newName === oldName) return;
    const vals = variantData[oldName];
    delete variantData[oldName];
    variantData[newName] = vals;
    renderVariants();
}

function addValue(groupName) {
    const input = document.getElementById('newval-' + groupName);
    const val = (input.value || '').trim();
    if (!val) return;
    if (!variantData[groupName].includes(val)) {
        variantData[groupName].push(val);
        renderVariants();
    }
    input.value = '';
}

function removeValue(groupName, value) {
    variantData[groupName] = variantData[groupName].filter(v => v !== value);
    renderVariants();
}

function syncHidden() {
    document.getElementById('variantsJsonInput').value = JSON.stringify(variantData);
}

// Init on page load
renderVariants();
</script>
@endpush