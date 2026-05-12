@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
        
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            ✏️ Edit Produk
        </h2>

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('produk.update', $produk->id) }}" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- NAMA --}}
            <div class="mb-4">
                <label class="block font-semibold">Nama Produk</label>
                <input type="text" 
                       name="nama_produk" 
                       value="{{ old('nama_produk', $produk->nama_produk) }}"
                       class="w-full mt-1 px-4 py-2 border rounded-lg"
                       required>
            </div>

            {{-- HARGA --}}
            <div class="mb-4">
                <label class="block font-semibold">Harga</label>
                <input type="number" 
                       name="harga" 
                       value="{{ old('harga', $produk->harga) }}"
                       class="w-full mt-1 px-4 py-2 border rounded-lg"
                       required>
            </div>

            {{-- STOK --}}
            <div class="mb-4">
                <label class="block font-semibold">Stok</label>
                <input type="number" 
                       name="stok" 
                       value="{{ old('stok', $produk->stok) }}"
                       class="w-full mt-1 px-4 py-2 border rounded-lg"
                       required>
            </div>

            {{-- ✅ KATEGORI --}}
            <div class="mb-4">
                <label class="block font-semibold">Kategori</label>
                <select name="kategori" 
                        class="w-full mt-1 px-4 py-2 border rounded-lg"
                        required>

                    <option value="kopi" {{ $produk->kategori == 'kopi' ? 'selected' : '' }}>
                        Kopi
                    </option>

                    <option value="nonkopi" {{ $produk->kategori == 'nonkopi' ? 'selected' : '' }}>
                        Non Kopi
                    </option>

                    <option value="makanan" {{ $produk->kategori == 'makanan' ? 'selected' : '' }}>
                        Makanan
                    </option>

                </select>
            </div>

            {{-- GAMBAR SAAT INI --}}
            <div class="mb-4">
                <label class="block font-semibold mb-2">Gambar Saat Ini</label>

                @if($produk->gambar)
                    <img 
                        src="{{ asset('storage/produk/'.$produk->gambar) }}" 
                        class="w-32 h-32 object-cover rounded shadow"
                    >
                @else
                    <p class="text-gray-400">Tidak ada gambar</p>
                @endif
            </div>

            {{-- UPLOAD GAMBAR BARU --}}
            <div class="mb-4">
                <label class="block font-semibold">Ganti Gambar</label>
                <input 
                    type="file" 
                    name="gambar"
                    class="w-full mt-1 px-4 py-2 border rounded-lg"
                >
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-between mt-6">
                <a href="{{ route('produk.index') }}"
                   class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">
                    ← Kembali
                </a>

                <button type="submit"
                    class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">
                    Update
                </button>
            </div>

        </form>

    </div>

</div>
@endsection