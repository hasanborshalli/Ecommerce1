@extends('layouts.app')

@section('content')
<div class="section-pad-sm">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-info">
                <span class="label-caps" style="color:var(--terra); display:block; margin-bottom:16px;">Get in
                    Touch</span>
                <h1 class="display-md" style="margin-bottom:24px;">We'd love to<br>hear from you.</h1>
                <p style="font-size:15px; color:var(--charcoal-mid); line-height:1.8; margin-bottom:40px;">
                    Have a question about a product, your order, or anything else? Our team is here to help.
                </p>
                <div class="contact-details">
                    @if($siteEmail)
                    <div class="contact-detail-item">
                        <div class="contact-detail-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                        </div>
                        <div>
                            <p class="label-caps" style="font-size:10px; margin-bottom:4px;">Email</p>
                            <a href="mailto:{{ $siteEmail }}">{{ $siteEmail }}</a>
                        </div>
                    </div>
                    @endif
                    @if($sitePhone)
                    <div class="contact-detail-item">
                        <div class="contact-detail-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5">
                                <path
                                    d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.7A2 2 0 012.18 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.16a16 16 0 006.93 6.93l1.52-1.52a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z" />
                            </svg>
                        </div>
                        <div>
                            <p class="label-caps" style="font-size:10px; margin-bottom:4px;">Phone</p>
                            <a href="tel:{{ $sitePhone }}">{{ $sitePhone }}</a>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="footer-social" style="margin-top:32px;">
                    @if($socialLinks['instagram'])
                    <a href="{{ $socialLinks['instagram'] }}" target="_blank"
                        style="border-color:var(--sand-light); color:var(--charcoal-mid);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <rect x="2" y="2" width="20" height="20" rx="5" />
                            <circle cx="12" cy="12" r="4" />
                            <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
                        </svg>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Form --}}
            <div class="contact-form-wrap">
                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="form-row-2">
                        <div class="form-group">
                            <label class="form-label">Your Name *</label>
                            <input type="text" name="name"
                                class="form-control {{ $errors->has('name') ? 'error' : '' }}" value="{{ old('name') }}"
                                placeholder="Jane Doe" required>
                            @error('name')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email"
                                class="form-control {{ $errors->has('email') ? 'error' : '' }}"
                                value="{{ old('email') }}" placeholder="jane@example.com" required>
                            @error('email')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" value="{{ old('subject') }}"
                            placeholder="How can we help?">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Message *</label>
                        <textarea name="message" class="form-control {{ $errors->has('message') ? 'error' : '' }}"
                            rows="6" placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                        @error('message')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn btn-dark btn-full">
                        Send Message
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <line x1="22" y1="2" x2="11" y2="13" />
                            <polygon points="22 2 15 22 11 13 2 9 22 2" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="/css/contact.css">
@endpush