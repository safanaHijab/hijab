<!-- Modal Detail Order -->
<div id="orderDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 w-full">
    <div class="bg-white rounded-lg shadow-xl w-1/2 p-6 relative">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <h2 class="text-xl font-bold mb-4 text-[#8B4513]">Detail Pesanan</h2>

        <div id="orderDetailContent" class="space-y-2">
            <!-- Konten detail pesanan akan diisi secara dinamis -->
        </div>

        <div class="mt-4 border-t pt-4">
            <p class="font-bold text-right">Total: <span id="modalTotalOrder"></span></p>

            <!-- Tambahkan form cancel di sini -->
            @if ($order->status == 'Pending')
                <form action="{{ route('customer.transaksi.cancel', $order->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan?')"
                        class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition duration-300">
                        Batalkan Pesanan
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function openModal(orderId) {
                fetch(`/customer/transaksi/detail/${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        const content = document.getElementById('orderDetailContent');
                        const totalSpan = document.getElementById('modalTotalOrder');
                        const modalFooter = document.querySelector('#orderDetailModal .mt-4');

                        // Bersihkan konten sebelumnya
                        content.innerHTML = '';

                        // Hapus tombol cancel sebelumnya jika ada
                        const existingCancelButton = document.getElementById('cancelOrderButton');
                        if (existingCancelButton) {
                            existingCancelButton.remove();
                        }

                        // Tambahkan detail produk
                        data.details.forEach(detail => {
                            const detailItem = document.createElement('div');
                            detailItem.classList.add('flex', 'justify-between', 'border-b', 'pb-2');
                            detailItem.innerHTML = `
                    <div>
                        <p class="font-semibold">${detail.product.name}</p>
                        <p class="text-sm text-gray-500">
                            ${detail.quantity} x Rp ${detail.price.toLocaleString()}
                        </p>
                    </div>
                    <p class="font-semibold">
                        Rp ${(detail.quantity * detail.price).toLocaleString()}
                    </p>
                `;
                            content.appendChild(detailItem);
                        });

                        // Set total order
                        totalSpan.textContent = `Rp ${data.total.toLocaleString()}`;

                        // Tampilkan modal
                        document.getElementById('orderDetailModal').classList.remove('hidden');
                        document.getElementById('orderDetailModal').classList.add('flex');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memuat detail pesanan');
                    });
            }

            function closeModal() {
                document.getElementById('orderDetailModal').classList.remove('flex');
                document.getElementById('orderDetailModal').classList.add('hidden');
            }

            // Expose functions to global scope
            window.openModal = openModal;
            window.closeModal = closeModal;
        });
    </script>
@endpush
