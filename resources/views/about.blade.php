@extends('layouts.app')

@section('content')

{{-- Hero --}}
<div class="about-hero">
    <div class="container">
        <span class="label-caps" style="color:var(--terra); display:block; margin-bottom:16px;">Our Story</span>
        <h1 class="display-xl" style="max-width:700px; line-height:1.1;">We believe in beauty,<br><em>craft, and
                intention.</em></h1>
    </div>
</div>

{{-- Story --}}
<section class="section-pad">
    <div class="container">
        <div class="about-grid">
            <div>
                <h2 class="display-md" style="margin-bottom:24px;">Why We Exist</h2>
                <p style="font-size:15px; color:var(--charcoal-mid); line-height:1.9; margin-bottom:20px;">
                    We started with a simple belief: the objects and products we surround ourselves with should be
                    extraordinary. Not flashy — extraordinary. Things made with care, that last, and that tell a story.
                </p>
                <p style="font-size:15px; color:var(--charcoal-mid); line-height:1.9; margin-bottom:20px;">
                    Every piece in our collection is individually sourced, tested, and approved by our team before it
                    ever reaches you. We work directly with makers who share our obsession with quality.
                </p>
                <a href="{{ route('shop') }}" class="btn btn-dark" style="margin-top:8px;">Explore the Collection</a>
            </div>
            <div class="about-image-wrap">
                <div class="about-image-placeholder"></div>
            </div>
        </div>
    </div>
</section>

{{-- Values --}}
<section class="section-pad-sm" style="background:var(--charcoal);">
    <div class="container">
        <div style="text-align:center; margin-bottom:56px;">
            <span class="label-caps" style="color:var(--terra); display:block; margin-bottom:12px;">What Drives
                Us</span>
            <h2 class="display-md" style="color:var(--ivory);">Our Principles</h2>
        </div>
        <div class="values-grid">
            @foreach([
            ['Quality Over Quantity', 'We would rather carry one extraordinary thing than ten ordinary ones.'],
            ['Radical Transparency', 'We tell you exactly where things come from and how they\'re made.'],
            ['Long-Lasting Design', 'We choose pieces that age gracefully, not ones designed to be replaced.'],
            ['Relationships First', 'We invest in makers who share our values, not just our margins.'],
            ] as $value)
            @php $title = $value[0]; $desc = $value[1]; @endphp
            <div class="about-value">
                <div class="about-value-line"></div>
                <h3 style="color:var(--ivory); font-family:var(--font-display); font-size:1.2rem; margin-bottom:10px;">
                    {{ $title }}</h3>
                <p style="font-size:13px; color:var(--sand); line-height:1.8;">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="section-pad" style="text-align:center;">
    <div class="container-sm">
        <span class="label-caps" style="color:var(--terra); display:block; margin-bottom:16px;">Ready to Begin?</span>
        <h2 class="display-md" style="margin-bottom:16px;">Discover Your Next Favourite</h2>
        <p style="color:var(--charcoal-mid); margin-bottom:36px;">Everything we carry is there for a reason. Go explore.
        </p>
        <a href="{{ route('shop') }}" class="btn btn-terra">Shop the Collection</a>
    </div>
</section>

@endsection

@push('styles')
<style>
    .about-hero {
        background: var(--white);
        padding: 80px 0;
        border-bottom: 1px solid var(--sand-light);
    }

    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 64px;
        align-items: center;
    }

    .about-image-wrap {
        position: relative;
    }

    .about-image-placeholder {
        aspect-ratio: 4/5;
        background: linear-gradient(135deg, var(--sand-light) 0%, var(--ivory-dark) 100%);
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0 48px;
    }

    .about-value {
        padding: 32px 0;
        border-bottom: 1px solid rgba(200, 184, 154, 0.15);
    }

    .about-value-line {
        width: 32px;
        height: 2px;
        background: var(--terra);
        margin-bottom: 16px;
    }

    @media (max-width: 768px) {
        .about-grid {
            grid-template-columns: 1fr;
        }

        .about-image-wrap {
            order: -1;
        }
    }
</style>
@endpush