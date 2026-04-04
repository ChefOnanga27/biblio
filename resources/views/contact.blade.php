@extends('base')

@section('title', 'Contact')

@section('content')
    <div class="max-w-5xl mx-auto my-12 p-6 bg-white shadow rounded-lg">
        <h1 class="text-4xl font-bold mb-4">Contactez-nous</h1>

        <p class="text-gray-700 leading-relaxed mb-6">
            Pour toute question, suggestion ou demande d'assistance, utilisez le formulaire suivant ou envoyez-nous un e-mail.
        </p>

        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block font-medium text-gray-700">Nom</label>
                <input type="text" id="name" name="name" required
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            </div>

            <div>
                <label for="email" class="block font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            </div>

            <div>
                <label for="message" class="block font-medium text-gray-700">Message</label>
                <textarea id="message" name="message" rows="5" required
                          class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">Envoyer</button>
        </form>
    </div>
@endsection