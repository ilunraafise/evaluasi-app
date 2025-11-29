<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $form->title }}</h1>
        <p class="text-gray-600 mb-6">Anda sudah mengisi form ini. Terima kasih atas partisipasinya.</p>
        <a href="{{ url('/') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
            Kembali ke Beranda
        </a>
    </div>
</x-app-layout>
