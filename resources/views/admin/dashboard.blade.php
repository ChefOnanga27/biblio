@extends('admin.admin')

@section('title', 'Admin - Tableau de bord')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold">Dashboard Admin</h1>
    <p class="text-gray-600">Statistiques globales</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="p-4 bg-white rounded shadow">
        <h2 class="text-lg font-semibold">Utilisateurs</h2>
        <p class="text-3xl font-bold mt-2">{{ $users->count() }}</p>
    </div>
    <div class="p-4 bg-white rounded shadow">
        <h2 class="text-lg font-semibold">Visiteurs uniques</h2>
        <p class="text-3xl font-bold mt-2">{{ $visitorsCount }}</p>
    </div>
    <div class="p-4 bg-white rounded shadow">
        <h2 class="text-lg font-semibold">Visites totales</h2>
        <p class="text-3xl font-bold mt-2">{{ $totalVisits }}</p>
    </div>
</div>

<div class="bg-white rounded shadow p-4">
    <h2 class="text-xl font-semibold mb-4">Liste des utilisateurs</h2>
    <table class="w-full text-left">
        <thead class="bg-orange-500 text-white">
            <tr>
                <th class="p-2">#</th>
                <th class="p-2">Email</th>
                <th class="p-2">Nom</th>
                <th class="p-2">Inscrit le</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($users as $user)
                <tr>
                    <td class="p-2">{{ $loop->iteration }}</td>
                    <td class="p-2">{{ $user->email }}</td>
                    <td class="p-2">{{ $user->name }}</td>
                    <td class="p-2">{{ $user->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-2 text-center text-gray-500">Aucun utilisateur trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection