@extends('espace.layout')

@section('title', 'Paramètres du compte — ' . $siteNom)

@section('espace')
@php
    $ongletInitial = ($errors->updatePassword->isNotEmpty() || $errors->userDeletion->isNotEmpty() || session('status') === 'password-updated')
        ? 'securite' : 'compte';
    $initiales = collect(explode(' ', trim($user->name)))->filter()->take(2)->map(fn ($m) => mb_strtoupper(mb_substr($m, 0, 1)))->join('');
    $lus = $user->lectures()->count();
    $termines = $user->lectures()->whereNotNull('termine_at')->count();
@endphp

<div x-data="{ onglet: '{{ $ongletInitial }}' }" class="rounded-[14px] bg-surface/50 border border-ligne px-5 sm:px-8 lg:px-10 py-8">

    <div class="grid xl:grid-cols-[minmax(0,1fr)_400px] gap-10">
        <div class="min-w-0">
            {{-- Onglets --}}
            <div role="tablist" aria-label="Paramètres" class="no-scrollbar flex gap-3 overflow-x-auto">
                @foreach(['compte' => 'Mon compte', 'securite' => 'Connexion et sécurité'] as $cle => $libelle)
                    <button type="button" role="tab" id="onglet-{{ $cle }}" aria-controls="panneau-{{ $cle }}"
                            @click="onglet = '{{ $cle }}'" :aria-selected="(onglet === '{{ $cle }}').toString()"
                            class="flex-none px-3 pb-2.5 text-[17px] sm:text-[19px] font-medium border-b-[3px] transition-colors cursor-pointer"
                            :class="onglet === '{{ $cle }}' ? 'text-encre border-corail' : 'text-voile border-white/15 hover:text-brume'">
                        {{ $libelle }}
                    </button>
                @endforeach
            </div>

            {{-- ═════════ MON COMPTE ═════════ --}}
            <section id="panneau-compte" role="tabpanel" aria-labelledby="onglet-compte" x-show="onglet === 'compte'" class="mt-9">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                      x-data="{ apercu: null }" class="space-y-7">
                    @csrf
                    @method('patch')

                    <div>
                        <p class="text-[16px] font-medium">Votre photo de profil</p>
                        <div class="mt-4 flex items-center gap-6">
                            <span class="relative w-28 h-28 rounded-full overflow-hidden bg-surface-2 ring-4 ring-white/5 shadow-[0_10px_30px_rgba(0,0,0,0.5)] flex items-center justify-center">
                                <template x-if="apercu"><img :src="apercu" alt="" class="w-full h-full object-cover"></template>
                                @if($user->avatar)
                                    <img x-show="! apercu" src="{{ media_url($user->avatar) }}" alt="Votre photo de profil" class="w-full h-full object-cover">
                                @else
                                    <span x-show="! apercu" class="text-[30px] font-semibold text-corail">{{ $initiales }}</span>
                                @endif
                            </span>
                            <label for="avatar" class="text-[13.5px] underline underline-offset-4 text-brume hover:text-encre cursor-pointer">Changer la photo</label>
                            <input id="avatar" name="avatar" type="file" accept="image/png,image/jpeg,image/webp" class="sr-only"
                                   @change="const f = $event.target.files[0]; apercu = f ? URL.createObjectURL(f) : null">
                        </div>
                        @include('partials.erreur', ['champ' => 'avatar'])
                    </div>

                    <div class="grid md:grid-cols-2 gap-x-10 gap-y-6">
                        <div>
                            <label for="name" class="champ-label !text-[15.5px] !text-encre">Nom complet</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name"
                                   class="champ !bg-transparent !border-white/60 focus:!border-corail">
                            @include('partials.erreur', ['champ' => 'name'])
                        </div>
                        <div>
                            <label for="email" class="champ-label !text-[15.5px] !text-encre">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                                   class="champ !bg-transparent !border-white/60 focus:!border-corail">
                            <p class="mt-1.5 text-[12px] text-voile">Si vous changez d'adresse, un code de vérification y sera envoyé.</p>
                            @include('partials.erreur', ['champ' => 'email'])
                        </div>
                        <div>
                            <label for="telephone" class="champ-label !text-[15.5px] !text-encre">Téléphone <span class="text-voile text-[13px]">(facultatif)</span></label>
                            <input id="telephone" name="telephone" type="tel" value="{{ old('telephone', $user->telephone) }}" autocomplete="tel" placeholder="+241 …"
                                   class="champ !bg-transparent !border-white/60 focus:!border-corail">
                            @include('partials.erreur', ['champ' => 'telephone'])
                        </div>
                    </div>

                    <div>
                        <label for="bio" class="champ-label !text-[15.5px] !text-encre">Bio <span class="text-voile text-[13px]">(facultatif)</span></label>
                        <textarea id="bio" name="bio" rows="5" maxlength="1000" placeholder="Quelques mots sur vous et vos lectures…"
                                  class="champ !bg-white/[0.03] resize-y">{{ old('bio', $user->bio) }}</textarea>
                        @include('partials.erreur', ['champ' => 'bio'])
                    </div>

                    <div class="flex items-center gap-6">
                        <button type="submit" class="btn btn-blanc !px-10 !py-4 !text-[16px]">Mettre à jour</button>
                        <button type="reset" @click="apercu = null" class="text-[16px] font-medium text-encre hover:text-corail cursor-pointer">Annuler</button>
                    </div>
                </form>
            </section>

            {{-- ═════════ CONNEXION ET SÉCURITÉ ═════════ --}}
            <section id="panneau-securite" role="tabpanel" aria-labelledby="onglet-securite" x-show="onglet === 'securite'" x-cloak class="mt-9 space-y-10 max-w-xl">
                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    @method('put')
                    <div>
                        <h2 class="text-[18px] font-semibold">Changer de mot de passe</h2>
                        <p class="text-[14px] text-brume mt-1">Utilisez un mot de passe long, que vous n'utilisez pas ailleurs.</p>
                    </div>

                    @foreach([
                        ['current_password', 'update_password_current_password', 'Mot de passe actuel', 'current-password'],
                        ['password', 'update_password_password', 'Nouveau mot de passe', 'new-password'],
                        ['password_confirmation', 'update_password_password_confirmation', 'Confirmer le nouveau mot de passe', 'new-password'],
                    ] as [$nom, $id, $libelle, $auto])
                        <div>
                            <label for="{{ $id }}" class="champ-label">{{ $libelle }}</label>
                            @include('partials.champ-mot-de-passe', ['nom' => $nom, 'id' => $id, 'autocomplete' => $auto, 'placeholder' => ''])
                            @foreach($errors->updatePassword->get($nom) as $message)
                                <p class="mt-2 text-[13px] text-[#ff8a73]" role="alert">{{ $message }}</p>
                            @endforeach
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-blanc">Enregistrer le mot de passe</button>
                </form>

                <div class="rounded-[12px] border border-red-400/25 bg-red-500/[0.06] p-6"
                     x-data="{ confirmer: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
                    <h2 class="text-[18px] font-semibold">Supprimer mon compte</h2>
                    <p class="text-[14px] text-brume mt-1">Vos lectures, favoris et notes seront définitivement effacés.</p>

                    <button type="button" x-show="! confirmer" @click="confirmer = true; $nextTick(() => $refs.mdp.focus())"
                            class="btn mt-5 bg-red-600 hover:bg-red-500 text-white">Supprimer mon compte</button>

                    <form method="POST" action="{{ route('profile.destroy') }}" x-show="confirmer" x-cloak class="mt-5 space-y-4">
                        @csrf
                        @method('delete')
                        <label for="suppression_password" class="champ-label">Saisissez votre mot de passe pour confirmer</label>
                        <input x-ref="mdp" id="suppression_password" name="password" type="password" required autocomplete="current-password" class="champ">
                        @foreach($errors->userDeletion->get('password') as $message)
                            <p class="text-[13px] text-[#ff8a73]" role="alert">{{ $message }}</p>
                        @endforeach
                        <div class="flex gap-3">
                            <button type="submit" class="btn bg-red-600 hover:bg-red-500 text-white">Supprimer définitivement</button>
                            <button type="button" @click="confirmer = false" class="btn btn-discret">Annuler</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>

        {{-- ═════════ CARTE DE LECTEUR ═════════ --}}
        <aside class="order-first xl:order-none">
            <div class="rounded-[12px] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.45)] bg-gradient-to-br from-[#16324a] via-[#1d2b4f] to-[#3a2350] border border-white/10">
                <div class="flex items-center gap-3.5 px-5 py-4 border-b border-white/10">
                    <img src="{{ asset('Digital.png') }}" alt="" class="w-11 h-11 rounded-full object-cover ring-2 ring-white/20">
                    <div>
                        <p class="text-[17px] font-semibold leading-tight">{{ $siteNom }}</p>
                        <p class="text-[11.5px] text-white/60">Carte de lecteur</p>
                    </div>
                </div>
                <div class="flex gap-4 p-5">
                    <span class="flex-none w-[86px] h-[104px] rounded-md overflow-hidden bg-white/10 flex items-center justify-center">
                        @if($user->avatar)
                            <img src="{{ media_url($user->avatar) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-[26px] font-semibold text-corail">{{ $initiales }}</span>
                        @endif
                    </span>
                    <dl class="min-w-0 grid grid-cols-[auto_minmax(0,1fr)] gap-x-3 gap-y-1.5 text-[12px]">
                        <dt class="text-white/55">Nom</dt><dd class="font-semibold truncate">{{ $user->name }}</dd>
                        <dt class="text-white/55">Email</dt><dd class="font-semibold truncate">{{ $user->email }}</dd>
                        <dt class="text-white/55">Membre depuis</dt><dd class="font-semibold">{{ $user->created_at?->locale('fr')->translatedFormat('F Y') }}</dd>
                        <dt class="text-white/55">Livres ouverts</dt><dd class="font-semibold">{{ $lus }}</dd>
                        <dt class="text-white/55">Terminés</dt><dd class="font-semibold">{{ $termines }}</dd>
                    </dl>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
