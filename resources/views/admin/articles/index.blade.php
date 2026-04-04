@extends('admin.admin')

@section('title', 'Articles du Blog')
@section('page-title', 'Blog')
@section('page-subtitle', 'Gérez les articles publiés sur la plateforme')

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
    .btn-icon {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 8px;
        border: 1px solid #e5e7eb; background: white;
        cursor: pointer; transition: all 0.15s; text-decoration: none; flex-shrink: 0;
    }
    .btn-icon:hover { border-color: #d1d5db; background: #f9fafb; }
    .btn-icon.danger:hover { border-color: #fca5a5; background: #fef2f2; }

    .filter-tab {
        font-size: 12.5px; font-weight: 500; padding: 6px 14px;
        border-radius: 8px; border: 1px solid #e5e7eb; background: white;
        color: #6b7280; cursor: pointer; transition: all 0.15s; text-decoration: none;
    }
    .filter-tab.active, .filter-tab:hover {
        background: #0C1F1F; color: white; border-color: #0C1F1F;
    }
</style>

{{-- ── FLASH ── --}}
@if(session('success'))
<div style="display:flex; align-items:center; gap:10px; background:#f0fdf4; border:1px solid #bbf7d0;
            border-radius:10px; padding:12px 16px; margin-bottom:20px;">
    <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
        <path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p style="font-size:13px; color:#15803d; font-weight:500;">{{ session('success') }}</p>
</div>
@endif

{{-- ── TOPBAR ── --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:22px;">
    <div style="display:flex; align-items:center; gap:10px;">

        <div style="position:relative;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);pointer-events:none;"
                 width="14" height="14" fill="none" stroke="#9ca3af" stroke-width="1.7" viewBox="0 0 24 24">
                <path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0015.803 15.803z"/>
            </svg>
            <input type="text" class="search-input" placeholder="Rechercher un article…"
                   oninput="filterRows(this.value)">
        </div>

        <a href="?status=" class="filter-tab {{ !request('status') ? 'active' : '' }}">Tous</a>
        <a href="?status=published" class="filter-tab {{ request('status') === 'published' ? 'active' : '' }}">Publiés</a>
        <a href="?status=draft" class="filter-tab {{ request('status') === 'draft' ? 'active' : '' }}">Brouillons</a>

        <span style="font-size:12px; color:#9ca3af; background:#f5f3ef;
                     padding:5px 12px; border-radius:20px; font-weight:500;">
            {{ $articles->total() }} article{{ $articles->total() > 1 ? 's' : '' }}
        </span>
    </div>

    <a href="{{ route('admin.articles.create') }}"
       style="display:inline-flex; align-items:center; gap:7px; background:#0C1F1F; color:white;
              font-size:13px; font-weight:600; padding:9px 18px; border-radius:10px;
              text-decoration:none; transition:background 0.15s; font-family:'DM Sans',sans-serif;"
       onmouseover="this.style.background='#f97316'"
       onmouseout="this.style.background='#0C1F1F'">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Nouvel article
    </a>
</div>

{{-- ── TABLE ── --}}
<div style="background:white; border-radius:16px; border:1px solid #f0ede8; overflow:hidden; margin-bottom:20px;">
    <table style="width:100%; border-collapse:collapse;" id="articles-table">
        <thead>
            <tr style="background:#fafaf9; border-bottom:1px solid #f0ede8;">
                <th style="padding:12px 20px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">Titre</th>
                <th style="padding:12px 16px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">Auteur</th>
                <th style="padding:12px 16px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">Statut</th>
                <th style="padding:12px 16px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">Créé le</th>
                <th style="padding:12px 20px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:right;">Actions</th>
            </tr>
        </thead>

        <tbody id="table-body">
        @forelse($articles as $article)
            <tr class="table-row" style="border-top:1px solid #f5f3ef;">

                {{-- Titre --}}
                <td style="padding:14px 20px; max-width:280px;">
                    <p style="font-size:13.5px; font-weight:600; color:#1f2937;
                              white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:260px;">
                        {{ $article->titre }}
                    </p>
                    @if($article->published_at)
                    <p style="font-size:11.5px; color:#9ca3af; margin-top:2px;">
                        Publié le {{ $article->published_at->format('d/m/Y') }}
                    </p>
                    @endif
                </td>

                {{-- Auteur --}}
                <td style="padding:14px 16px;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div style="width:28px; height:28px; border-radius:7px; background:#fff7ed;
                                    display:flex; align-items:center; justify-content:center;
                                    font-size:11px; font-weight:700; color:#f97316; flex-shrink:0;">
                            {{ strtoupper(substr($article->user->name, 0, 1)) }}
                        </div>
                        <span style="font-size:13px; color:#374151;">{{ $article->user->name }}</span>
                    </div>
                </td>

                {{-- Statut --}}
                <td style="padding:14px 16px;">
                    @if($article->published_at)
                        <span style="display:inline-flex; align-items:center; gap:5px;
                                     font-size:11.5px; font-weight:600; padding:4px 10px;
                                     border-radius:20px; background:#f0fdf4; color:#16a34a;">
                            <span style="width:5px;height:5px;border-radius:50%;background:#16a34a;flex-shrink:0;"></span>
                            Publié
                        </span>
                    @else
                        <span style="display:inline-flex; align-items:center; gap:5px;
                                     font-size:11.5px; font-weight:600; padding:4px 10px;
                                     border-radius:20px; background:#fffbeb; color:#d97706;">
                            <span style="width:5px;height:5px;border-radius:50%;background:#d97706;flex-shrink:0;"></span>
                            Brouillon
                        </span>
                    @endif
                </td>

                {{-- Date --}}
                <td style="padding:14px 16px;">
                    <p style="font-size:13px; color:#6b7280;">
                        {{ $article->created_at->format('d M Y') }}
                    </p>
                </td>

                {{-- Actions --}}
                <td style="padding:14px 20px;">
                    <div style="display:flex; align-items:center; gap:6px; justify-content:flex-end;">
                        <a href="{{ route('admin.articles.edit', $article) }}"
                           class="btn-icon" title="Éditer">
                            <svg width="14" height="14" fill="none" stroke="#6b7280" stroke-width="1.7" viewBox="0 0 24 24">
                                <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                            </svg>
                        </a>

                        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST"
                              onsubmit="return confirm('Supprimer « {{ addslashes($article->titre) }} » ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon danger" style="border:none; padding:0;" title="Supprimer">
                                <svg width="14" height="14" fill="none" stroke="#9ca3af" stroke-width="1.7" viewBox="0 0 24 24">
                                    <path d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="5" style="padding:56px; text-align:center;">
                    <div style="display:flex; flex-direction:column; align-items:center; gap:12px;">
                        <svg width="36" height="36" fill="none" stroke="#d1d5db" stroke-width="1.3" viewBox="0 0 24 24">
                            <path d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        <p style="font-size:14px; font-weight:500; color:#9ca3af;">Aucun article trouvé</p>
                        <a href="{{ route('admin.articles.create') }}"
                           style="font-size:12.5px; color:#f97316; text-decoration:none; font-weight:500;">
                            Rédiger le premier article →
                        </a>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- ── PAGINATION ── --}}
@if($articles->hasPages())
<div style="display:flex; align-items:center; justify-content:space-between; padding:4px 0;">
    <p style="font-size:12.5px; color:#9ca3af;">
        Affichage de <strong style="color:#374151;">{{ $articles->firstItem() }}</strong>
        à <strong style="color:#374151;">{{ $articles->lastItem() }}</strong>
        sur <strong style="color:#374151;">{{ $articles->total() }}</strong> résultats
    </p>
    <div style="display:flex; align-items:center; gap:6px;">
        @if($articles->onFirstPage())
            <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;
                         border-radius:8px;border:1px solid #f0ede8;background:#fafaf9;color:#d1d5db;cursor:not-allowed;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            </span>
        @else
            <a href="{{ $articles->previousPageUrl() }}"
               style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;
                      border-radius:8px;border:1px solid #e5e7eb;background:white;color:#6b7280;text-decoration:none;transition:all 0.15s;"
               onmouseover="this.style.borderColor='#f97316';this.style.color='#f97316'"
               onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#6b7280'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            </a>
        @endif

        @foreach($articles->getUrlRange(max(1, $articles->currentPage()-2), min($articles->lastPage(), $articles->currentPage()+2)) as $page => $url)
            @if($page == $articles->currentPage())
                <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;
                             border-radius:8px;background:#0C1F1F;color:white;font-size:13px;font-weight:600;">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $url }}"
                   style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;
                          border-radius:8px;border:1px solid #e5e7eb;background:white;color:#6b7280;
                          font-size:13px;text-decoration:none;transition:all 0.15s;"
                   onmouseover="this.style.borderColor='#f97316';this.style.color='#f97316'"
                   onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#6b7280'">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        @if($articles->hasMorePages())
            <a href="{{ $articles->nextPageUrl() }}"
               style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;
                      border-radius:8px;border:1px solid #e5e7eb;background:white;color:#6b7280;text-decoration:none;transition:all 0.15s;"
               onmouseover="this.style.borderColor='#f97316';this.style.color='#f97316'"
               onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#6b7280'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </a>
        @else
            <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;
                         border-radius:8px;border:1px solid #f0ede8;background:#fafaf9;color:#d1d5db;cursor:not-allowed;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
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