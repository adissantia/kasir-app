<!DOCTYPE html>
<html>
<head>
    <title>Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#F0F0EC]">

<div class="max-w-md mx-auto min-h-screen flex flex-col">

    <!-- HEADER -->
    <div class="bg-[#7F796A] text-white p-5 text-center rounded-b-3xl">
        <p class="text-xs tracking-widest text-gray-300">
            MEJA {{ $meja }}
        </p>

        <h1 class="text-xl font-semibold">
            Bellissimo
        </h1>

        <p class="text-xs text-gray-300">
            Pesan langsung, tanpa antri
        </p>
    </div>


    <!-- KATEGORI -->
    <div class="flex gap-3 overflow-x-auto px-4 mt-4">

        @php
            $kategoriList = [
                '' => 'Semua',
                'kopi' => 'Kopi',
                'nonkopi' => 'Non-Kopi',
                'makanan' => 'Makanan'
            ];
        @endphp

        @foreach($kategoriList as $key => $label)

        <a href="?kategori={{ $key }}"
           class="px-5 py-2 rounded-full text-sm whitespace-nowrap
           {{ request('kategori')==$key
              ? 'bg-[#53635C] text-white'
              : 'bg-gray-200 text-gray-700' }}">

            {{ $label }}

        </a>

        @endforeach

    </div>



    <!-- LIST MENU -->
    <div class="px-4 mt-5 space-y-4 flex-1">

        @foreach($produk as $p)

        @if(request('kategori')=='' || $p->kategori==request('kategori'))

        @php
            $qty = session('cart')[$p->id]['qty'] ?? 0;
        @endphp

        <div class="bg-white rounded-3xl p-5 shadow flex items-center gap-4">

            <!-- GAMBAR DIBESARKAN -->
            <div class="w-20 h-20 rounded-2xl bg-gray-200 overflow-hidden">

                @if($p->gambar)
                    <img src="{{ asset('storage/'.$p->gambar) }}"
                         class="w-full h-full object-cover">
                @else

                <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                    No Img
                </div>

                @endif

            </div>


            <!-- INFO DIBESARKAN -->
            <div class="flex-1">

                <p class="font-bold text-base">
                    {{ $p->nama_produk }}
                </p>

                <p class="text-sm text-gray-400 mt-1">
                    {{ ucfirst($p->kategori) }}
                </p>

                <p class="font-semibold text-base mt-2">
                    Rp {{ number_format($p->harga,0,',','.') }}
                </p>

            </div>


            <!-- QTY BUTTON -->
            <div>

                @if($qty==0)

                    <!-- AWAL CUMA ICON + -->
                    <form action="{{ route('customer.addCart') }}" method="POST">
                        @csrf

                        <input type="hidden"
                               name="produk_id"
                               value="{{ $p->id }}">

                        <button class="w-11 h-11 rounded-full bg-[#53635C] text-white text-2xl font-bold">
                            +
                        </button>

                    </form>

                @else

                <!-- SETELAH DIKLIK JADI - 1 + -->
                <div class="flex items-center gap-2">

                    <!-- MINUS -->
                    <form action="{{ route('customer.removeCart') }}" method="POST">
                        @csrf

                        <input type="hidden"
                               name="produk_id"
                               value="{{ $p->id }}">

                        <button class="w-9 h-9 rounded-full border text-xl">
                            -
                        </button>

                    </form>


                    <span class="font-bold text-base w-5 text-center">
                        {{ $qty }}
                    </span>


                    <!-- PLUS -->
                    <form action="{{ route('customer.addCart') }}" method="POST">
                        @csrf

                        <input type="hidden"
                               name="produk_id"
                               value="{{ $p->id }}">

                        <button class="w-9 h-9 rounded-full bg-[#53635C] text-white text-xl">
                            +
                        </button>

                    </form>

                </div>

                @endif

            </div>

        </div>

        @endif
        @endforeach

    </div>



    <!-- FOOTER CART -->
    @php
        $cart=session('cart',[]);
        $total=0;

        foreach($cart as $c){
            $total += $c['harga']*$c['qty'];
        }
    @endphp


    @if(count($cart)>0)

    <div class="p-4 bg-white shadow-inner">

        <div class="bg-[#D0D0CB] text-black rounded-2xl p-4 flex justify-between items-center">

            <div>

                <p class="text-xs text-black-300">
                    {{ count($cart) }} item dipilih
                </p>

                <p class="font-semibold">
                    Rp {{ number_format($total,0,',','.') }}
                </p>

            </div>


            <a href="{{ route('customer.checkout') }}"
               class="bg-[#53635C] text-white px-5 py-2 rounded-xl text-sm font-semibold">

                Lihat Keranjang

            </a>

        </div>

    </div>

    @endif


</div>

</body>
</html>