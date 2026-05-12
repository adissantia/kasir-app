@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">

    <div class="max-w-6xl mx-auto bg-white p-6 rounded-2xl shadow-lg">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">📦 Data Produk</h2>

            <a href="{{ route('produk.create') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                + Tambah Produk
            </a>
        </div>

        <!-- SUCCESS -->
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">

                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Gambar</th>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Kategori</th> <!-- ✅ TAMBAHAN -->
                        <th class="p-3 text-left">Harga</th>
                        <th class="p-3 text-left">Stok</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($produk as $item)
                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-3">{{ $loop->iteration }}</td>

                        <!-- GAMBAR -->
                        <td class="p-3">
                            @if($item->gambar)
                                <img 
                                    src="{{ asset('storage/'.$item->gambar) }}" 
                                    class="w-16 h-16 object-cover rounded shadow"
                                >
                            @else
                                <span class="text-gray-400">No Image</span>
                            @endif
                        </td>

                        <!-- NAMA -->
                        <td class="p-3 font-semibold">
                            {{ $item->nama_produk }}
                        </td>

                        <!-- ✅ KATEGORI -->
                        <td class="p-3">
                            @if($item->kategori)
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                    {{ $item->kategori }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>

                        <!-- HARGA -->
                        <td class="p-3">
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                        </td>

                        <!-- STOK -->
                        <td class="p-3">
                            {{ $item->stok }}
                        </td>

                        <!-- AKSI -->
                        <td class="p-3 text-center">
                            <div class="flex justify-center gap-3">

                                <a href="{{ route('produk.edit', $item->id) }}"
                                   class="bg-yellow-400 px-3 py-1 rounded text-white hover:bg-yellow-500">
                                    ✏️ Edit
                                </a>

                                <form action="{{ route('produk.destroy', $item->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button 
                                        onclick="return confirm('Yakin hapus?')"
                                        class="bg-red-500 px-3 py-1 rounded text-white hover:bg-red-600">
                                        🗑 Hapus
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center p-4 text-gray-500">
                            Belum ada produk
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

</div>
@endsection