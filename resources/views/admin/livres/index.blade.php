@extends('admin.admin')

@section('title', 'Liste des livres')
@section('page-title', 'Catalogue')
@section('page-subtitle', 'Gérez l\'ensemble des livres publiés sur la plateforme')

@section('content')

<style>
    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: white;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
        flex-shrink: 0;
    }
    .btn-icon:hover { border-color: #d1d5db; background: #f9fafb; }
    .btn-icon.danger:hover { border-color: #fca5a5; background: #fef2f2; }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        letter-spacing: 0.02em;
    }
    .status-badge.available { background: #f0fdf4; color: #16a34a; }
    .status-badge.unavailable { background: #fef2f2; color: #dc2626; }

    .table-row { transition: background 0.1s; }
    .table-row:hover { background: #fafaf9; }

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
    }
    .search-input:focus { border-color: #f97316; }
</style>

{{-- ── TOPBAR ACTIONS ── --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:22px;">

    <div style="display:flex; align-items:center; gap:10px;">
        {{-- Search --}}
        <div style="position:relative;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);pointer-events:none;"
                 width="14" height="14" fill="none" stroke="#9ca3af" stroke-width="1.7" viewBox="0 0 24 24">
                <path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0015.803 15.803z"/>
            </svg>
            <input type="text" class="search-input" placeholder="Rechercher un livre…"
                   oninput="filterRows(this.value)">
        </div>

        {{-- Count badge --}}
        <span style="font-size:12px; color:#9ca3af; background:#f5f3ef;
                     padding:5px 12px; border-radius:20px; font-weight:500;">
            {{ $livres->count() }} ouvrage{{ $livres->count() > 1 ? 's' : '' }}
        </span>
    </div>

    <a href="{{ route('admin.livres.create') }}"
       style="display:inline-flex; align-items:center; gap:7px; background:#0C1F1F; color:white;
              font-size:13px; font-weight:600; padding:9px 18px; border-radius:10px;
              text-decoration:none; transition:background 0.15s; font-family:'DM Sans',sans-serif;"
       onmouseover="this.style.background='#f97316'"
       onmouseout="this.style.background='#0C1F1F'">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Ajouter un livre
    </a>
</div>

{{-- ── TABLE ── --}}
<div style="background:white; border-radius:16px; border:1px solid #f0ede8; overflow:hidden;">

    <table style="width:100%; border-collapse:collapse;" id="livres-table">
        <thead>
            <tr style="background:#fafaf9; border-bottom:1px solid #f0ede8;">
                <th style="padding:12px 20px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:left; width:56px;">Cover</th>
                <th style="padding:12px 16px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">Titre / Auteur</th>
                <th style="padding:12px 16px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">ISBN</th>
                <th style="padding:12px 16px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:left; max-width:200px;">Description</th>
                <th style="padding:12px 16px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">Publication</th>
                <th style="padding:12px 16px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">Statut</th>
                <th style="padding:12px 20px; font-size:10.5px; font-weight:600; letter-spacing:0.1em;
                           text-transform:uppercase; color:#9ca3af; text-align:right;">Actions</th>
            </tr>
        </thead>

        <tbody id="table-body">
        @forelse($livres as $livre)
            <tr class="table-row" style="border-top:1px solid #f5f3ef;">

                {{-- Cover --}}
                <td style="padding:14px 20px;">
                    @if($livre->image)
                        <div style="width:40px; height:54px; border-radius:7px; overflow:hidden;
                                    box-shadow:0 2px 8px rgba(0,0,0,0.1); flex-shrink:0;">
                            <img src="{{ asset('storage/' . $livre->image) }}"
                                 style="width:100%; height:100%; object-fit:cover;">
                        </div>
                    @else
                        <div style="width:40px; height:54px; border-radius:7px; background:#f5f3ef;
                                    display:flex; align-items:center; justify-content:center;">
                            <svg width="16" height="16" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                            </svg>
                        </div>
                    @endif
                </td>

                {{-- Titre / Auteur --}}
                <td style="padding:14px 16px; max-width:220px;">
                    <p style="font-size:13.5px; font-weight:600; color:#1f2937; white-space:nowrap;
                              overflow:hidden; text-overflow:ellipsis; max-width:200px;">
                        {{ $livre->titre }}
                    </p>
                    <p style="font-size:12px; color:#9ca3af; margin-top:2px;">
                        {{ $livre->auteur }}
                    </p>
                </td>

                {{-- ISBN --}}
                <td style="padding:14px 16px;">
                    <span style="font-size:12px; color:#6b7280; font-family:monospace;
                                 background:#f5f3ef; padding:3px 8px; border-radius:6px;">
                        {{ $livre->isbn ?: '—' }}
                    </span>
                </td>

                {{-- Description --}}
                <td style="padding:14px 16px; max-width:200px;">
                    <p style="font-size:12.5px; color:#9ca3af; white-space:nowrap;
                              overflow:hidden; text-overflow:ellipsis; max-width:190px;">
                        {{ \Illuminate\Support\Str::limit($livre->description, 50) }}
                    </p>
                </td>

                {{-- Date --}}
                <td style="padding:14px 16px;">
                    <p style="font-size:12.5px; color:#6b7280;">
                        {{ $livre->date_publication
                            ? \Carbon\Carbon::parse($livre->date_publication)->format('d/m/Y')
                            : '—' }}
                    </p>
                </td>

                {{-- Statut --}}
                <td style="padding:14px 16px;">
                    @if($livre->disponible)
                        <span class="status-badge available">
                            <span style="width:5px;height:5px;border-radius:50%;background:#16a34a;flex-shrink:0;"></span>
                            Disponible
                        </span>
                    @else
                        <span class="status-badge unavailable">
                            <span style="width:5px;height:5px;border-radius:50%;background:#dc2626;flex-shrink:0;"></span>
                            Indisponible
                        </span>
                    @endif
                </td>

                {{-- Actions --}}
                <td style="padding:14px 20px;">
                    <div style="display:flex; align-items:center; gap:6px; justify-content:flex-end;">

                        <a href="{{ route('admin.livres.edit', $livre) }}"
                           class="btn-icon" title="Modifier">
                            <svg width="14" height="14" fill="none" stroke="#6b7280" stroke-width="1.7" viewBox="0 0 24 24">
                                <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                            </svg>
                        </a>

                        <form action="{{ route('admin.livres.destroy', $livre) }}" method="POST"
                              onsubmit="return confirm('Supprimer « {{ addslashes($livre->titre) }} » ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon danger" title="Supprimer"
                                    style="border:none; padding:0;">
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
                <td colspan="7" style="padding:56px; text-align:center;">
                    <div style="display:flex; flex-direction:column; align-items:center; gap:12px;">
                        <svg width="36" height="36" fill="none" stroke="#d1d5db" stroke-width="1.3" viewBox="0 0 24 24">
                            <path d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                        </svg>
                        <p style="font-size:14px; font-weight:500; color:#9ca3af;">Aucun livre trouvé</p>
                        <a href="{{ route('admin.livres.create') }}"
                           style="font-size:12.5px; color:#f97316; text-decoration:none; font-weight:500;">
                            Ajouter le premier livre →
                        </a>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>

<script>
function filterRows(q) {
    q = q.toLowerCase();
    document.querySelectorAll('#table-body tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>

@endsection