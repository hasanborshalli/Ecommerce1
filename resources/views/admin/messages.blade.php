@extends('admin.layout')

@section('breadcrumb', 'Messages')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Messages</h1>
        <p class="page-subtitle">{{ $messages->total() }} messages total</p>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:8px;"></th>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                <tr style="{{ !$msg->is_read ? 'background:rgba(184,92,56,0.04);' : '' }}" id="msg-{{ $msg->id }}">
                    <td>
                        @if(!$msg->is_read)
                        <div style="width:6px;height:6px;border-radius:50%;background:var(--admin-accent);"></div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:{{ $msg->is_read ? '400' : '500' }};">{{ $msg->name }}</div>
                        <div style="font-size:11px;color:var(--admin-muted);">{{ $msg->email }}</div>
                        @if($msg->phone)<div style="font-size:11px;color:var(--admin-muted);">{{ $msg->phone }}</div>
                        @endif
                    </td>
                    <td style="font-size:13px;">{{ $msg->subject ?? '—' }}</td>
                    <td style="max-width:320px;">
                        <p
                            style="font-size:13px;color:var(--admin-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:300px;">
                            {{ $msg->message }}
                        </p>
                    </td>
                    <td class="td-muted" style="white-space:nowrap;">{{ $msg->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <div style="display:flex;gap:6px; align-items:center;">
                            <button onclick="expandMsg({{ $msg->id }})" class="abtn abtn-outline abtn-sm abtn-icon"
                                title="Read">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                            @if(!$msg->is_read)
                            <button onclick="markRead({{ $msg->id }}, this)" class="abtn abtn-outline abtn-sm"
                                title="Mark read" style="font-size:11px; padding:4px 10px;">
                                Mark Read
                            </button>
                            @endif
                            <a href="mailto:{{ $msg->email }}" class="abtn abtn-outline abtn-sm abtn-icon"
                                title="Reply">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <line x1="22" y1="2" x2="11" y2="13" />
                                    <polygon points="22 2 15 22 11 13 2 9 22 2" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                {{-- Expanded message row --}}
                <tr id="expand-{{ $msg->id }}" style="display:none;">
                    <td colspan="6" style="padding:0 20px 16px 28px;">
                        <div
                            style="background:var(--admin-bg); border-radius:6px; padding:16px 20px; font-size:13px; color:var(--admin-text); line-height:1.8; border-left:3px solid var(--admin-accent);">
                            {{ $msg->message }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--admin-muted);padding:48px;">No messages yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($messages->hasPages())
    <div style="padding:12px 20px;border-top:1px solid var(--admin-border);">
        <div class="admin-pagination">{{ $messages->links('partials.pagination') }}</div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    function expandMsg(id) {
    const row = document.getElementById(`expand-${id}`);
    row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
}

function markRead(id, btn) {
    fetch(`/admin/messages/${id}/read`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    }).then(() => {
        const row = document.getElementById(`msg-${id}`);
        row.style.background = '';
        row.querySelector('div[style*="border-radius:50%"]')?.remove();
        btn.remove();
        // Update sidebar badge
        const badge = document.querySelector('.sidebar-badge');
        if (badge) {
            const count = parseInt(badge.textContent) - 1;
            count > 0 ? badge.textContent = count : badge.remove();
        }
    });
}
</script>
@endpush