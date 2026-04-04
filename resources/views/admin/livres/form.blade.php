@extends('admin.admin')

@section('title', $livre->exists ? 'Éditer le livre' : 'Ajouter un livre')
@section('page-title', $livre->exists ? 'Éditer le livre' : 'Ajouter un livre')
@section('page-subtitle', $livre->exists ? 'Modifier les informations de « ' . $livre->titre . ' »' : 'Renseigner les informations du nouvel ouvrage')

@section('content')

<style>
    .form-label {
        display: block;
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #6b7280;
        margin-bottom: 7px;
    }
    .form-input {
        width: 100%;
        font-size: 13.5px;
        color: #1f2937;
        background: #fafaf9;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 14px;
        outline: none;
        transition: border-color 0.15s, background 0.15s;
        box-sizing: border-box;
        font-family: 'DM Sans', sans-serif;
    }
    .form-input:focus {
        border-color: #f97316;
        background: white;
    }
    .form-input::placeholder { color: #d1d5db; }
    select.form-input { cursor: pointer; }
    textarea.form-input { resize: vertical; min-height: 110px; line-height: 1.6; }

    .field-group { margin-bottom: 22px; }

    .upload-zone {
        border: 1.5px dashed #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        background: #fafaf9;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s;
        position: relative;
    }
    .upload-zone:hover { border-color: #f97316; background: #fff7ed; }
    .upload-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }

    .section-title {
        font-family: 'Syne', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: #0C1F1F;
        letter-spacing: 0.02em;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0ede8;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title span.dot {
        width: 6px; height: 6px; border-radius: 50%; background: #f97316; flex-shrink: 0;
    }

    .toggle-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #fafaf9;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        cursor: pointer;
    }
    .toggle-wrap:hover { border-color: #f97316; }

    .toggle {
        width: 40px; height: 22px;
        background: #e5e7eb;
        border-radius: 20px;
        position: relative;
        transition: background 0.2s;
        flex-shrink: 0;
    }
    .toggle::after {
        content: '';
        width: 16px; height: 16px;
        background: white;
        border-radius: 50%;
        position: absolute;
        top: 3px; left: 3px;
        transition: transform 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }
    input#disponible:checked ~ label .toggle { background: #f97316; }
    input#disponible:checked ~ label .toggle::after { transform: translateX(18px); }

    @error_style: color: #dc2626; font-size: 11.5px; margin-top: 5px;
</style>

<form action="{{ $livre->exists ? route('admin.livres.update', $livre) : route('admin.livres.store') }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    @if($livre->exists) @method('PUT') @endif

    <div style="display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start;">

        {{-- ── COLONNE PRINCIPALE ── --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- Informations générales --}}
            <div style="background:white; border-radius:16px; border:1px solid #f0ede8; padding:28px;">
                <p class="section-title"><span class="dot"></span> Informations générales</p>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                    <div class="field-group" style="margin-bottom:0; grid-column:1/-1;">
                        <label for="titre" class="form-label">Titre du livre</label>
                        <input type="text" name="titre" id="titre"
                               value="{{ old('titre', $livre->titre) }}"
                               placeholder="Ex : Les Bouts de bois de Dieu"
                               class="form-input">
                        @error('titre')<p style="color:#dc2626;font-size:11.5px;margin-top:5px;">{{ $message }}</p>@enderror
                    </div>

                    <div class="field-group" style="margin-bottom:0;">
                        <label for="auteur" class="form-label">Auteur</label>
                        <input type="text" name="auteur" id="auteur"
                               value="{{ old('auteur', $livre->auteur) }}"
                               placeholder="Prénom Nom"
                               class="form-input">
                        @error('auteur')<p style="color:#dc2626;font-size:11.5px;margin-top:5px;">{{ $message }}</p>@enderror
                    </div>

                    <div class="field-group" style="margin-bottom:0;">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" name="isbn" id="isbn"
                               value="{{ old('isbn', $livre->isbn) }}"
                               placeholder="978-3-16-148410-0"
                               class="form-input">
                        @error('isbn')<p style="color:#dc2626;font-size:11.5px;margin-top:5px;">{{ $message }}</p>@enderror
                    </div>

                    <div class="field-group" style="margin-bottom:0;">
                        <label for="categorie_id" class="form-label">Catégorie</label>
                        <select name="categorie_id" id="categorie_id" class="form-input">
                            <option value="">— Choisir —</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}"
                                    {{ old('categorie_id', $livre->categorie_id) == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie_id')<p style="color:#dc2626;font-size:11.5px;margin-top:5px;">{{ $message }}</p>@enderror
                    </div>

                    <div class="field-group" style="margin-bottom:0;">
                        <label for="date_publication" class="form-label">Date de publication</label>
                        <input type="date" name="date_publication" id="date_publication"
                               value="{{ old('date_publication', $livre->date_publication ?? '') }}"
                               class="form-input">
                        @error('date_publication')<p style="color:#dc2626;font-size:11.5px;margin-top:5px;">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="field-group" style="margin-top:18px; margin-bottom:0;">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description"
                              placeholder="Résumé du livre, contexte, thèmes abordés…"
                              class="form-input">{{ old('description', $livre->description) }}</textarea>
                    @error('description')<p style="color:#dc2626;font-size:11.5px;margin-top:5px;">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Fichier PDF --}}
            <div style="background:white; border-radius:16px; border:1px solid #f0ede8; padding:28px;">
                <p class="section-title"><span class="dot"></span> Fichier PDF</p>

                <div class="upload-zone" id="pdf-zone">
                    <input type="file" name="pdf" id="pdf" accept="application/pdf"
                           onchange="showFileName(this, 'pdf-name')">
                    <svg width="28" height="28" fill="none" stroke="#d1d5db" stroke-width="1.4" viewBox="0 0 24 24"
                         style="margin:0 auto 10px;">
                        <path d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                    <p id="pdf-name" style="font-size:13px; color:#6b7280; font-weight:500;">
                        Glisser un PDF ou cliquer pour parcourir
                    </p>
                    <p style="font-size:11.5px; color:#9ca3af; margin-top:4px;">Format PDF uniquement</p>
                </div>

                @if($livre->pdf_path)
                <div style="display:flex; align-items:center; gap:10px; margin-top:12px;
                            padding:12px 14px; background:#f5f3ef; border-radius:10px;">
                    <svg width="16" height="16" fill="none" stroke="#f97316" stroke-width="1.7" viewBox="0 0 24 24">
                        <path d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                    <span style="font-size:12.5px; color:#374151; flex:1;">Fichier PDF actuel</span>
                    <a href="{{ asset('storage/' . $livre->pdf_path) }}" target="_blank"
                       style="font-size:12px; color:#f97316; text-decoration:none; font-weight:500;">
                        Voir le fichier →
                    </a>
                </div>
                @endif
            </div>

        </div>

        {{-- ── COLONNE LATÉRALE ── --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- Image --}}
            <div style="background:white; border-radius:16px; border:1px solid #f0ede8; padding:24px;">
                <p class="section-title"><span class="dot"></span> Couverture</p>

                {{-- Preview --}}
                <div style="width:100%; aspect-ratio:2/3; border-radius:12px; overflow:hidden;
                            background:#f5f3ef; margin-bottom:14px; position:relative;">
                    @if($livre->image)
                    <img id="cover-preview"
                         src="{{ asset('storage/' . $livre->image) }}"
                         style="width:100%; height:100%; object-fit:cover;">
                    @else
                    <div id="cover-placeholder" style="width:100%; height:100%; display:flex; flex-direction:column;
                                                       align-items:center; justify-content:center; gap:8px;">
                        <svg width="32" height="32" fill="none" stroke="#d1d5db" stroke-width="1.3" viewBox="0 0 24 24">
                            <path d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                        </svg>
                        <p style="font-size:12px; color:#9ca3af;">Aucune image</p>
                    </div>
                    @endif
                </div>

                <div class="upload-zone" style="padding:14px;">
                    <input type="file" name="image" id="image" accept="image/*"
                           onchange="previewImage(this)">
                    <p style="font-size:12.5px; color:#6b7280;">Changer l'image</p>
                    <p style="font-size:11px; color:#9ca3af; margin-top:2px;">JPG, PNG, WebP</p>
                </div>

                @error('image')<p style="color:#dc2626;font-size:11.5px;margin-top:8px;">{{ $message }}</p>@enderror
            </div>

            {{-- Disponibilité --}}
            <div style="background:white; border-radius:16px; border:1px solid #f0ede8; padding:24px;">
                <p class="section-title"><span class="dot"></span> Disponibilité</p>

                <input type="hidden" name="disponible" value="0">
                <label style="display:flex; align-items:center; gap:12px; cursor:pointer;
                              padding:14px 16px; background:#fafaf9; border:1px solid #e5e7eb;
                              border-radius:10px; transition:border-color 0.15s;"
                       id="toggle-label"
                       onmouseover="this.style.borderColor='#f97316'"
                       onmouseout="this.style.borderColor=document.getElementById('disponible').checked?'#f97316':'#e5e7eb'">
                    <input type="checkbox" name="disponible" id="disponible" value="1"
                           {{ old('disponible', $livre->disponible ?? false) ? 'checked' : '' }}
                           style="display:none;"
                           onchange="updateToggle(this)">
                    <div id="toggle-pill" style="width:40px; height:22px; border-radius:20px;
                                                  background:{{ old('disponible', $livre->disponible ?? false) ? '#f97316' : '#e5e7eb' }};
                                                  position:relative; flex-shrink:0; transition:background 0.2s;">
                        <span id="toggle-knob" style="width:16px; height:16px; background:white; border-radius:50%;
                                                       position:absolute; top:3px;
                                                       left:{{ old('disponible', $livre->disponible ?? false) ? '21px' : '3px' }};
                                                       transition:left 0.2s; box-shadow:0 1px 3px rgba(0,0,0,0.15);"></span>
                    </div>
                    <div>
                        <p style="font-size:13.5px; font-weight:500; color:#1f2937;" id="toggle-text">
                            {{ old('disponible', $livre->disponible ?? false) ? 'Disponible' : 'Non disponible' }}
                        </p>
                        <p style="font-size:11.5px; color:#9ca3af; margin-top:1px;">Visible dans le catalogue</p>
                    </div>
                </label>
            </div>

            {{-- Actions --}}
            <div style="background:white; border-radius:16px; border:1px solid #f0ede8; padding:24px;">
                <p class="section-title"><span class="dot"></span> Actions</p>

                <button type="submit"
                        style="width:100%; padding:12px; background:#0C1F1F; color:white; border:none;
                               border-radius:10px; font-size:13.5px; font-weight:600; cursor:pointer;
                               font-family:'DM Sans',sans-serif; letter-spacing:0.01em;
                               transition:background 0.15s; display:flex; align-items:center;
                               justify-content:center; gap:8px; margin-bottom:10px;"
                        onmouseover="this.style.background='#f97316'"
                        onmouseout="this.style.background='#0C1F1F'">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $livre->exists ? 'Enregistrer les modifications' : 'Publier le livre' }}
                </button>

                <a href="{{ route('admin.livres.index') }}"
                   style="width:100%; padding:11px; background:transparent; color:#6b7280;
                          border:1px solid #e5e7eb; border-radius:10px; font-size:13px;
                          font-weight:500; text-decoration:none; display:flex; align-items:center;
                          justify-content:center; gap:8px; transition:all 0.15s; box-sizing:border-box;"
                   onmouseover="this.style.borderColor='#9ca3af';this.style.color='#374151'"
                   onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#6b7280'">
                    Annuler
                </a>
            </div>

        </div>
    </div>

</form>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            let preview = document.getElementById('cover-preview');
            const placeholder = document.getElementById('cover-placeholder');
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'cover-preview';
                preview.style.cssText = 'width:100%;height:100%;object-fit:cover;';
                input.closest('.upload-zone').parentNode.querySelector('div[style*="aspect-ratio"]').appendChild(preview);
            }
            if (placeholder) placeholder.style.display = 'none';
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function showFileName(input, targetId) {
    if (input.files && input.files[0]) {
        document.getElementById(targetId).textContent = input.files[0].name;
    }
}

function updateToggle(checkbox) {
    const pill = document.getElementById('toggle-pill');
    const knob = document.getElementById('toggle-knob');
    const text = document.getElementById('toggle-text');
    const label = document.getElementById('toggle-label');
    if (checkbox.checked) {
        pill.style.background = '#f97316';
        knob.style.left = '21px';
        text.textContent = 'Disponible';
        label.style.borderColor = '#f97316';
    } else {
        pill.style.background = '#e5e7eb';
        knob.style.left = '3px';
        text.textContent = 'Non disponible';
        label.style.borderColor = '#e5e7eb';
    }
}
</script>

@endsection