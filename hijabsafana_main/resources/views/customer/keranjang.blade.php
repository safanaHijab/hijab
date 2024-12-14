<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (Session::has('keranjang') && count(Session::get('keranjang')) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach (Session::get('keranjang') as $key => $item)
                            <div class="relative bg-white shadow-lg rounded-lg overflow-hidden border-t-4 border-[#8B4513] hover:shadow-xl transition">
                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-[#8B4513]">{{ $item['name'] }}</h3>
                                    <p class="text-gray-500 mt-2">Harga: <span class="font-semibold">Rp {{ number_format($item['price'], 2) }}</span></p>
                                    <p class="text-gray-500">Jumlah: 
                                        <form action="{{ route('customer.update', $key) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 text-center border rounded" onchange="this.form.submit()" />
                                        </form>
                                    </p>
                                    <p class="text-gray-500">Total: <span class="font-semibold">Rp {{ number_format($item['price'] * $item['quantity'], 2) }}</span></p>
                                    <div class="mt-4 flex justify-between">
                                        <form action="{{ route('customer.destroy', $key) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 text-center">
                        <h3 class="font-semibold text-lg">Total Keseluruhan:</h3>
                        <p class="text-xl font-bold">
                            Rp {{ number_format(array_sum(array_map(function($item) {
                                return $item['price'] * $item['quantity'];
                            }, Session::get('keranjang'))), 2) }}
                        </p>
                        <form action="{{ route('customer.buy') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">Beli</button>
                        </form>
                    </div>
                @else
                    <p class="text-gray-500 text-center">Keranjang Anda kosong.</p>
                @endif
            </div>
        </div>
    </div>

    <style>
        .bg-brown-200 {
            background-color: #D7B49A;
        }
        .bg-brown-600 {
            background-color: #8B4513;
        }
    </style>
</x-app-layout>
