@extends('admin.admin')

@section('title', $article->exists ? 'Éditer l\'article' : 'Nouvel article')
@section('page-title', $article->exists ? 'Éditer l\'article' : 'Nouvel article')
@section('page-subtitle', $article->exists ? 'Modifier « ' . $article->titre . ' »' : 'Rédiger et publier un nouvel article')

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
    .form-input:focus { border-color: #f97316; background: white; }
    .form-input.error { border-color: #fca5a5; background: #fff8f8; }
    .form-input::placeholder { color: #d1d5db; }
    textarea.form-input { resize: vertical; line-height: 1.6; }
    .field-group { margin-bottom: 20px; }
    .section-title {
        font-family: 'Syne', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: #0C1F1F;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0ede8;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title .dot { width:6px; height:6px; border-radius:50%; background:#f97316; flex-shrink:0; }
    .upload-zone {
        border: 1.5px dashed #e5e7eb;
        border-radius: 12px;
        padding: 18px;
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
    .char-count { font-size: 11px; color: #9ca3af; text-align: right; margin-top: 4px; }
</style>

<form action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    @if($article->exists) @method('PUT') @endif

    {{-- ── ERREURS ── --}}
    @if($errors->any())
    <div style="display:flex; align-items:flex-start; gap:10px; background:#fef2f2; border:1px solid #fecaca;
                border-radius:10px; padding:14px 16px; margin-bottom:20px;">
        <svg style="flex-shrink:0;margin-top:1px;" width="16" height="16" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
        </svg>
        <div>
            <p style="font-size:13px; font-weight:600; color:#dc2626; margin-bottom:4px;">Veuillez corriger les erreurs suivantes :</p>
            <ul style="margin:0; padding-left:16px;">
                @foreach($errors->all() as $error)
                    <li style="font-size:12.5px; color:#b91c1c; margin-bottom:2px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div style="display:grid; grid-template-columns:1fr 320px; gap:20px; align-items:start;">

        {{-- ── COLONNE PRINCIPALE ── --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- Contenu rédactionnel --}}
            <div style="background:white; border-radius:16px; border:1px solid #f0ede8; padding:28px;">
                <p class="section-title"><span class="dot"></span> Contenu de l'article</p>

                <div class="field-group">
                    <label for="titre" class="form-label">Titre <span style="color:#f97316;">*</span></label>
                    <input type="text" name="titre" id="titre"
                           value="{{ old('titre', $article->titre) }}"
                           placeholder="Un titre accrocheur et clair…"
                           class="form-input {{ $errors->has('titre') ? 'error' : '' }}">
                    @error('titre')<p style="color:#dc2626;font-size:11.5px;margin-top:5px;">{{ $message }}</p>@enderror
                </div>

                <div class="field-group">
                    <label for="description" class="form-label">Description courte</label>
                    <textarea name="description" id="description" rows="3"
                              placeholder="Un résumé en 1 à 2 phrases visible dans les aperçus…"
                              class="form-input {{ $errors->has('description') ? 'error' : '' }}"
                              oninput="updateCount(this, 'desc-count', 200)">{{ old('description', $article->description) }}</textarea>
                    <p class="char-count" id="desc-count">
                        {{ strlen(old('description', $article->description ?? '')) }} / 200 caractères
                    </p>
                    @error('description')<p style="color:#dc2626;font-size:11.5px;margin-top:5px;">{{ $message }}</p>@enderror
                </div>

                <div class="field-group" style="margin-bottom:0;">
                    <label for="contenu" class="form-label">Contenu complet <span style="color:#f97316;">*</span></label>
                    <textarea name="contenu" id="contenu" rows="16"
                              placeholder="Rédigez votre article ici…"
                              class="form-input {{ $errors->has('contenu') ? 'error' : '' }}"
                              oninput="updateCount(this, 'contenu-count', null)">{{ old('contenu', $article->contenu) }}</textarea>
                    <p class="char-count" id="contenu-count">
                        {{ strlen(old('contenu', $article->contenu ?? '')) }} caractères
                    </p>
                    @error('contenu')<p style="color:#dc2626;font-size:11.5px;margin-top:5px;">{{ $message }}</p>@enderror
                </div>
            </div>

        </div>

        {{-- ── COLONNE LATÉRALE ── --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- Image de couverture --}}
            <div style="background:white; border-radius:16px; border:1px solid #f0ede8; padding:24px;">
                <p class="section-title"><span class="dot"></span> Image de couverture
                    @if(!$article->exists)
                        <span style="font-size:10px; font-weight:600; color:#f97316; background:#fff7ed;
                                     padding:2px 7px; border-radius:20px; margin-left:auto;">Requis</span>
                    @endif
                </p>

                {{-- Preview --}}
                <div style="width:100%; aspect-ratio:16/9; border-radius:10px; overflow:hidden;
                            background:#f5f3ef; margin-bottom:14px;">
                    @if($article->image)
                        <img id="cover-preview"
                             src="{{ asset('storage/' . $article->image) }}"
                             style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <div id="cover-placeholder" style="width:100%; height:100%; display:flex;
                                                           flex-direction:column; align-items:center;
                                                           justify-content:center; gap:8px;">
                            <svg width="28" height="28" fill="none" stroke="#d1d5db" stroke-width="1.3" viewBox="0 0 24 24">
                                <path d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                            </svg>
                            <p style="font-size:11.5px; color:#9ca3af;">Aucune image</p>
                        </div>
                    @endif
                </div>

                <div class="upload-zone">
                    <input type="file" name="image" id="image" accept="image/*"
                           {{ !$article->exists ? 'required' : '' }}
                           onchange="previewImage(this)">
                    <p style="font-size:12.5px; color:#6b7280; font-weight:500;">
                        {{ $article->image ? 'Changer l\'image' : 'Choisir une image' }}
                    </p>
                    <p style="font-size:11px; color:#9ca3af; margin-top:2px;">JPG, PNG, WebP · Format 16:9 recommandé</p>
                </div>
                @error('image')<p style="color:#dc2626;font-size:11.5px;margin-top:8px;">{{ $message }}</p>@enderror
            </div>

            {{-- Publication --}}
            <div style="background:white; border-radius:16px; border:1px solid #f0ede8; padding:24px;">
                <p class="section-title"><span class="dot"></span> Publication</p>

                <div class="field-group" style="margin-bottom:0;">
                    <label for="published_at" class="form-label">
                        Date de publication
                        <span style="font-size:10px; font-weight:500; color:#9ca3af; text-transform:none; letter-spacing:0; margin-left:4px;">
                            — vide = brouillon
                        </span>
                    </label>
                    <input type="datetime-local" name="published_at" id="published_at"
                           value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}"
                           class="form-input">

                    <div style="display:flex; align-items:center; gap:8px; margin-top:10px; padding:10px 12px;
                                border-radius:8px; background:#f5f3ef;">
                        <span id="status-dot" style="width:7px; height:7px; border-radius:50%; flex-shrink:0;
                              background:{{ old('published_at', $article->published_at) ? '#16a34a' : '#d97706' }};"></span>
                        <p id="status-text" style="font-size:12px; font-weight:500;
                           color:{{ old('published_at', $article->published_at) ? '#15803d' : '#b45309' }};">
                            {{ old('published_at', $article->published_at) ? 'Sera publié' : 'Brouillon' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div style="background:white; border-radius:16px; border:1px solid #f0ede8; padding:24px;">
                <p class="section-title"><span class="dot"></span> Actions</p>

                <button type="submit"
                        style="width:100%; padding:12px; background:#0C1F1F; color:white; border:none;
                               border-radius:10px; font-size:13.5px; font-weight:600; cursor:pointer;
                               font-family:'DM Sans',sans-serif; transition:background 0.15s;
                               display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:10px;"
                        onmouseover="this.style.background='#f97316'"
                        onmouseout="this.style.background='#0C1F1F'">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $article->exists ? 'Mettre à jour' : 'Publier l\'article' }}
                </button>

                <a href="{{ route('admin.articles.index') }}"
                   style="width:100%; padding:11px; background:transparent; color:#6b7280;
                          border:1px solid #e5e7eb; border-radius:10px; font-size:13px; font-weight:500;
                          text-decoration:none; display:flex; align-items:center; justify-content:center;
                          gap:8px; transition:all 0.15s; box-sizing:border-box;"
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
                input.closest('.upload-zone').previousElementSibling.appendChild(preview);
            }
            if (placeholder) placeholder.style.display = 'none';
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function updateCount(el, countId, max) {
    const len = el.value.length;
    const el2 = document.getElementById(countId);
    el2.textContent = max ? `${len} / ${max} caractères` : `${len} caractères`;
    el2.style.color = max && len > max ? '#dc2626' : '#9ca3af';
}

document.getElementById('published_at').addEventListener('input', function() {
    const dot = document.getElementById('status-dot');
    const text = document.getElementById('status-text');
    if (this.value) {
        dot.style.background = '#16a34a';
        text.style.color = '#15803d';
        text.textContent = 'Sera publié';
    } else {
        dot.style.background = '#d97706';
        text.style.color = '#b45309';
        text.textContent = 'Brouillon';
    }
});
</script>

@endsection