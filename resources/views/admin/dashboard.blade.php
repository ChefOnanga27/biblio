@extends('admin.admin')

@section('title', 'Admin - Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue d\'ensemble de la plateforme MBULU')

@section('content')

{{-- ── STAT CARDS ── --}}
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:28px;">

    {{-- Utilisateurs --}}
    <div style="background:white; border-radius:14px; border:1px solid #f0ede8; padding:24px 26px; position:relative; overflow:hidden;">
        <div style="position:absolute; top:-18px; right:-18px; width:80px; height:80px;
                    border-radius:50%; background:#fff7ed; pointer-events:none;"></div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <span style="font-size:11px; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; color:#9ca3af;">
                Utilisateurs
            </span>
            <div style="width:34px; height:34px; border-radius:9px; background:#fff7ed;
                        display:flex; align-items:center; justify-content:center;">
                <svg width="16" height="16" fill="none" stroke="#f97316" stroke-width="1.7" viewBox="0 0 24 24">
                    <path d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
            </div>
        </div>
        <p style="font-family:'Poppins',sans-serif; font-size:36px; font-weight:800; color:#0C1F1F; line-height:1;">
            {{ $users->count() }}
        </p>
        <p style="font-size:12px; color:#9ca3af; margin-top:6px;">inscrits sur la plateforme</p>
    </div>

    {{-- Visiteurs uniques --}}
    <div style="background:white; border-radius:14px; border:1px solid #f0ede8; padding:24px 26px; position:relative; overflow:hidden;">
        <div style="position:absolute; top:-18px; right:-18px; width:80px; height:80px;
                    border-radius:50%; background:#f0fdf4; pointer-events:none;"></div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <span style="font-size:11px; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; color:#9ca3af;">
                Visiteurs uniques
            </span>
            <div style="width:34px; height:34px; border-radius:9px; background:#f0fdf4;
                        display:flex; align-items:center; justify-content:center;">
                <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="1.7" viewBox="0 0 24 24">
                    <path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
        <p style="font-family:'Poppins',sans-serif; font-size:36px; font-weight:800; color:#0C1F1F; line-height:1;">
            {{ $visitorsCount }}
        </p>
        <p style="font-size:12px; color:#9ca3af; margin-top:6px;">visiteurs distincts</p>
    </div>

    {{-- Visites totales --}}
    <div style="background:#0C1F1F; border-radius:14px; padding:24px 26px; position:relative; overflow:hidden;">
        <div style="position:absolute; top:-18px; right:-18px; width:80px; height:80px;
                    border-radius:50%; background:rgba(249,115,22,0.12); pointer-events:none;"></div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <span style="font-size:11px; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; color:rgba(255,255,255,0.35);">
                Visites totales
            </span>
            <div style="width:34px; height:34px; border-radius:9px; background:rgba(249,115,22,0.15);
                        display:flex; align-items:center; justify-content:center;">
                <svg width="16" height="16" fill="none" stroke="#fb923c" stroke-width="1.7" viewBox="0 0 24 24">
                    <path d="M3 13.5h18M3 10.5h18M3 7.5h18M3 16.5h18"/>
                </svg>
            </div>
        </div>
        <p style="font-family:'Poppins',sans-serif; font-size:36px; font-weight:800; color:white; line-height:1;">
            {{ $totalVisits }}
        </p>
        <p style="font-size:12px; color:rgba(255,255,255,0.35); margin-top:6px;">pages vues au total</p>
    </div>

</div>

{{-- ── TABLE UTILISATEURS ── --}}
<div style="background:white; border-radius:16px; border:1px solid #f0ede8; overflow:hidden;">

    {{-- Table header --}}
    <div style="display:flex; align-items:center; justify-content:space-between;
                padding:20px 24px; border-bottom:1px solid #f5f3ef;">
        <div>
            <p style="font-family:'Poppins',sans-serif; font-size:15px; font-weight:700; color:#0C1F1F;">
                Utilisateurs inscrits
            </p>
            <p style="font-size:12px; color:#9ca3af; margin-top:2px;">
                {{ $users->count() }} compte{{ $users->count() > 1 ? 's' : '' }} au total
            </p>
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
            <div style="position:relative;">
                <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); pointer-events:none;"
                     width="13" height="13" fill="none" stroke="#9ca3af" stroke-width="1.7" viewBox="0 0 24 24">
                    <path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0015.803 15.803z"/>
                </svg>
                <input type="text" placeholder="Rechercher..."
                       style="font-size:12.5px; padding:7px 12px 7px 30px; border:1px solid #e5e7eb;
                              border-radius:8px; color:#374151; outline:none; width:180px;"
                       oninput="filterTable(this.value)"
                       onfocus="this.style.borderColor='#f97316'"
                       onblur="this.style.borderColor='#e5e7eb'">
            </div>
        </div>
    </div>

    {{-- Table --}}
    <table style="width:100%; border-collapse:collapse;" id="users-table">
        <thead>
            <tr style="background:#fafaf9;">
                <th style="padding:11px 24px; font-size:11px; font-weight:600; letter-spacing:0.08em;
                           text-transform:uppercase; color:#9ca3af; text-align:left; width:48px;">#</th>
                <th style="padding:11px 16px; font-size:11px; font-weight:600; letter-spacing:0.08em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">Nom</th>
                <th style="padding:11px 16px; font-size:11px; font-weight:600; letter-spacing:0.08em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">Email</th>
                <th style="padding:11px 24px; font-size:11px; font-weight:600; letter-spacing:0.08em;
                           text-transform:uppercase; color:#9ca3af; text-align:left;">Inscrit le</th>
            </tr>
        </thead>
        <tbody id="table-body">
            @forelse($users as $user)
            <tr style="border-top:1px solid #f5f3ef; transition:background 0.1s;"
                onmouseover="this.style.background='#fafaf9'"
                onmouseout="this.style.background='transparent'">

                <td style="padding:14px 24px; font-size:12px; color:#d1d5db; font-weight:500;">
                    {{ $loop->iteration }}
                </td>

                <td style="padding:14px 16px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:32px; height:32px; border-radius:8px; background:#fff7ed; flex-shrink:0;
                                    display:flex; align-items:center; justify-content:center;
                                    font-size:12px; font-weight:700; color:#f97316;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span style="font-size:13.5px; font-weight:500; color:#1f2937;">{{ $user->name }}</span>
                    </div>
                </td>

                <td style="padding:14px 16px; font-size:13px; color:#6b7280;">
                    {{ $user->email }}
                </td>

                <td style="padding:14px 24px;">
                    <span style="font-size:12px; background:#f5f3ef; color:#6b7280;
                                 padding:4px 10px; border-radius:20px; font-weight:500;">
                        {{ $user->created_at->format('d/m/Y') }}
                    </span>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:48px; text-align:center; color:#9ca3af; font-size:13.5px;">
                    <div style="display:flex; flex-direction:column; align-items:center; gap:10px;">
                        <svg width="32" height="32" fill="none" stroke="#d1d5db" stroke-width="1.3" viewBox="0 0 24 24">
                            <path d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                        </svg>
                        Aucun utilisateur trouvé.
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

<script>
function filterTable(query) {
    const rows = document.querySelectorAll('#table-body tr');
    const q = query.toLowerCase();
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>

@endsection