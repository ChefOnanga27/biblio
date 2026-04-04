@extends('admin.admin')

@section('title', 'Historique des lectures')
@section('page-title', 'Lectures')
@section('page-subtitle', 'Suivi en temps réel des lectures sur la plateforme')

@section('content')

<style>
    .search-input {
        font-size: 13px;
        padding: 8px 12px 8px 34px;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        outline: none;
        width: 220px;
        font-family: 'DM Sans', sans-serif;
        color: #374151;
        transition: border-color 0.15s;
        background: white;
    }
    .search-input:focus { border-color: #f97316; }
    .table-row { transition: background 0.1s; }
    .table-row:hover { background: #fafaf9; }
</style>

{{-- ── TOPBAR ── --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:22px;">
    <div style="display:flex; align-items:center; gap:10px;">
        <div style="position:relative;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);pointer-events:none;"
                 width="14" height="14" fill="none" stroke="#9ca3af" stroke-width="1.7" viewBox="0 0 24 24">
                <path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0015.803 15.803z"/>
            </svg>
            <input type="text" class="search-input" placeholder="Rechercher…"
                   oninput="filterRows(this.value)">
        </div>
        <span style="font-size:12px; color:#9ca3af; background:#f5f3ef;
                     padding:5px 12px; border-radius:20px; font-weight:500;">
            {{ $lectures->total() }} lecture{{ $lectures->total() > 1 ? 's' : '' }}
        </span>
    </div>
</div>

{{-- ── TABLE ── --}}
<div style="background:white; border-radius:16px; border:1px solid #f0ede8; overflow:hidden; margin-bottom:20px;">

    <table style="width:100%; border-collapse:collapse;" id="lectures-table">
        <thead>
            <tr style="background:#fafaf9; border-bottom:1px solid #f0ede8;">
                <th style="padding:12px 20px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">Date</th>
                <th style="padding:12px 16px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">Lecteur</th>
                <th style="padding:12px 16px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">Livre</th>
                <th style="padding:12px 16px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">ISBN</th>
                <th style="padding:12px 20px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:center;">Pages</th>
            </tr>
        </thead>

        <tbody id="table-body">
        @forelse($lectures as $lecture)
            <tr class="table-row" style="border-top:1px solid #f5f3ef;">

                {{-- Date --}}
                <td style="padding:14px 20px; white-space:nowrap;">
                    <p style="font-size:13px; font-weight:500; color:#1f2937;">
                        {{ $lecture->read_at->format('d/m/Y') }}
                    </p>
                    <p style="font-size:11.5px; color:#9ca3af; margin-top:1px;">
                        {{ $lecture->read_at->format('H:i') }}
                    </p>
                </td>

                {{-- Lecteur --}}
                <td style="padding:14px 16px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:32px; height:32px; border-radius:8px; background:#fff7ed;
                                    display:flex; align-items:center; justify-content:center;
                                    font-size:12px; font-weight:700; color:#f97316; flex-shrink:0;">
                            {{ strtoupper(substr($lecture->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p style="font-size:13px; font-weight:500; color:#1f2937;">
                                {{ $lecture->user->name }}
                            </p>
                            <p style="font-size:11.5px; color:#9ca3af; margin-top:1px;">
                                {{ $lecture->user->email }}
                            </p>
                        </div>
                    </div>
                </td>

                {{-- Livre --}}
                <td style="padding:14px 16px; max-width:200px;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div style="width:6px; height:6px; border-radius:50%; background:#f97316;
                                    flex-shrink:0; opacity:0.5;"></div>
                        <p style="font-size:13px; font-weight:500; color:#374151;
                                  white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:180px;">
                            {{ $lecture->livre->titre }}
                        </p>
                    </div>
                </td>

                {{-- ISBN --}}
                <td style="padding:14px 16px;">
                    <span style="font-size:12px; color:#6b7280; font-family:monospace;
                                 background:#f5f3ef; padding:3px 8px; border-radius:6px;">
                        {{ $lecture->livre->isbn ?: '—' }}
                    </span>
                </td>

                {{-- Pages --}}
                <td style="padding:14px 20px; text-align:center;">
                    @if($lecture->livre->pages)
                        <span style="display:inline-flex; align-items:center; gap:4px;
                                     font-size:12px; font-weight:600; color:#0C1F1F;
                                     background:#f5f3ef; padding:4px 10px; border-radius:20px;">
                            {{ $lecture->livre->pages }}
                            <span style="font-size:10px; font-weight:400; color:#9ca3af;">p.</span>
                        </span>
                    @else
                        <span style="font-size:12px; color:#d1d5db;">—</span>
                    @endif
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="5" style="padding:56px; text-align:center;">
                    <div style="display:flex; flex-direction:column; align-items:center; gap:12px;">
                        <svg width="36" height="36" fill="none" stroke="#d1d5db" stroke-width="1.3" viewBox="0 0 24 24">
                            <path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p style="font-size:14px; font-weight:500; color:#9ca3af;">Aucune lecture enregistrée</p>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- ── PAGINATION ── --}}
@if($lectures->hasPages())
<div style="display:flex; align-items:center; justify-content:space-between; padding:4px 0;">
    <p style="font-size:12.5px; color:#9ca3af;">
        Affichage de <strong style="color:#374151;">{{ $lectures->firstItem() }}</strong>
        à <strong style="color:#374151;">{{ $lectures->lastItem() }}</strong>
        sur <strong style="color:#374151;">{{ $lectures->total() }}</strong> résultats
    </p>
    <div style="display:flex; align-items:center; gap:6px;">
        @if($lectures->onFirstPage())
            <span style="display:inline-flex; align-items:center; justify-content:center;
                         width:34px; height:34px; border-radius:8px; border:1px solid #f0ede8;
                         background:#fafaf9; color:#d1d5db; cursor:not-allowed;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </span>
        @else
            <a href="{{ $lectures->previousPageUrl() }}"
               style="display:inline-flex; align-items:center; justify-content:center;
                      width:34px; height:34px; border-radius:8px; border:1px solid #e5e7eb;
                      background:white; color:#6b7280; text-decoration:none; transition:all 0.15s;"
               onmouseover="this.style.borderColor='#f97316';this.style.color='#f97316'"
               onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#6b7280'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
        @endif

        @foreach($lectures->getUrlRange(max(1, $lectures->currentPage()-2), min($lectures->lastPage(), $lectures->currentPage()+2)) as $page => $url)
            @if($page == $lectures->currentPage())
                <span style="display:inline-flex; align-items:center; justify-content:center;
                             width:34px; height:34px; border-radius:8px;
                             background:#0C1F1F; color:white; font-size:13px; font-weight:600;">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $url }}"
                   style="display:inline-flex; align-items:center; justify-content:center;
                          width:34px; height:34px; border-radius:8px; border:1px solid #e5e7eb;
                          background:white; color:#6b7280; font-size:13px; text-decoration:none;
                          transition:all 0.15s;"
                   onmouseover="this.style.borderColor='#f97316';this.style.color='#f97316'"
                   onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#6b7280'">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        @if($lectures->hasMorePages())
            <a href="{{ $lectures->nextPageUrl() }}"
               style="display:inline-flex; align-items:center; justify-content:center;
                      width:34px; height:34px; border-radius:8px; border:1px solid #e5e7eb;
                      background:white; color:#6b7280; text-decoration:none; transition:all 0.15s;"
               onmouseover="this.style.borderColor='#f97316';this.style.color='#f97316'"
               onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#6b7280'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </a>
        @else
            <span style="display:inline-flex; align-items:center; justify-content:center;
                         width:34px; height:34px; border-radius:8px; border:1px solid #f0ede8;
                         background:#fafaf9; color:#d1d5db; cursor:not-allowed;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </span>
        @endif
    </div>
</div>
@endif

<script>
function filterRows(q) {
    q = q.toLowerCase();
    document.querySelectorAll('#table-body tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>

@endsection