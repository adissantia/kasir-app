@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
        
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            ➕ Tambah Produk
        </h2>

        {{-- ALERT ERROR --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- NAMA --}}
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Nama Produk</label>
                <input 
                    type="text" 
                    name="nama_produk" 
                    class="w-full border p-2 rounded"
                    value="{{ old('nama_produk') }}"
                    required
                >
            </div>

            {{-- HARGA --}}
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Harga</label>
                <input 
                    type="number" 
                    name="harga" 
                    class="w-full border p-2 rounded"
                    value="{{ old('harga') }}"
                    required
                >
            </div>

            {{-- STOK --}}
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Stok</label>
                <input 
                    type="number" 
                    name="stok" 
                    class="w-full border p-2 rounded"
                    value="{{ old('stok') }}"
                    required
                >
            </div>

            {{-- ✅ KATEGORI (BARU) --}}
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Kategori</label>
                <select 
                    name="kategori" 
                    class="w-full border p-2 rounded"
                    required
                >
                    <option value="">-- Pilih Kategori --</option>
                    <option value="kopi" {{ old('kategori') == 'kopi' ? 'selected' : '' }}>Kopi</option>
                    <option value="nonkopi" {{ old('kategori') == 'nonkopi' ? 'selected' : '' }}>Non Kopi</option>
                    <option value="makanan" {{ old('kategori') == 'makanan' ? 'selected' : '' }}>Makanan</option>
                </select>
            </div>

            {{-- GAMBAR --}}
            <div class="mb-6">
                <label class="block mb-1 font-semibold">Gambar Produk</label>
                <input 
                    type="file" 
                    name="gambar" 
                    class="w-full border p-2 rounded"
                >
            </div>

            {{-- BUTTON --}}
            <div class="flex gap-2">
                <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Simpan
                </button>

                <a href="{{ route('produk.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">
                    Batal
                </a>
            </div>

        </form>

    </div>

</div>
@endsection