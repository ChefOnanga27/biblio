<!DOCTYPE html>
<html lang="fr">
<head>
    @include('partials.head')
</head>
<body class="font-sans">

<div class="relative z-10 min-h-dvh grid lg:grid-cols-2">

    <div class="flex items-center justify-center px-4 py-10 sm:px-8">
        <div class="w-full max-w-[540px] rounded-[20px] bg-[#11141d]/95 border border-ligne shadow-2xl shadow-black/40 px-6 py-9 sm:px-10 sm:py-11
                    @hasSection('centre') text-center @endif">

            <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                <img src="{{ asset('Digital.png') }}" alt="" class="w-9 h-9 rounded-full object-cover">
                <span class="text-[26px] font-semibold tracking-tight">{{ $siteNom }}</span>
            </a>

            <h1 class="mt-7 text-[26px] sm:text-[28px] font-semibold leading-tight">@yield('auth-title')</h1>
            @hasSection('auth-subtitle')
                <p class="mt-2 text-[15.5px] text-brume leading-relaxed">@yield('auth-subtitle')</p>
            @endif

            @if(session('error'))
                <p role="alert" class="mt-6 rounded-lg bg-red-500/10 border border-red-400/25 text-red-200 text-[13.5px] px-4 py-3 text-left">
                    {{ session('error') }}
                </p>
            @endif

            @if(session('status') && session('status') !== 'verification-link-sent')
                <p role="status" class="mt-6 rounded-lg bg-menthe/10 border border-menthe/25 text-green-200 text-[13.5px] px-4 py-3 text-left">
                    {{ session('status') }}
                </p>
            @endif

            <div class="mt-8 @hasSection('centre') text-center @else text-left @endif">
                @yield('auth')
            </div>
        </div>
    </div>

    <div class="hidden lg:block relative overflow-hidden">
        <img src="{{ asset('hero.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-[#070b14]/50 via-transparent to-transparent"></div>
    </div>
</div>

</body>
</html>
