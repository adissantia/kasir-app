@php
use App\Models\Order;

$pendingCount = Order::where('order_status','pending')->count();
@endphp

<a href="{{ route('orders.index') }}"
class="block p-2 hover:bg-gray-700 rounded flex justify-between items-center">

    <span>
        Pesanan
    </span>

    @if($pendingCount > 0)
    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
        {{ $pendingCount }}
    </span>
    @endif

</a>