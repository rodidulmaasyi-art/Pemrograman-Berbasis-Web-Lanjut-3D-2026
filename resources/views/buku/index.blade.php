@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Daftar Buku Perpustakaan</h2>
    <p class="text-gray-600">Berikut adalah koleksi buku yang tersedia.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($buku as $item)
        <x-kartu-buku :id="$item['id']" :judul="$item['judul']" :penulis="$item['penulis']" :tahun="$item['tahun']">
            <span class="inline-block bg-gray-100 text-xs px-2 py-1 rounded text-gray-700">
                Kategori: {{ $item['kategori'] }}
            </span>
        </x-kartu-buku>
    @endforeach
</div>
@endsection