@props(['route', 'label' => 'item ini'])

<div x-data="{ open: false }">
    <button @click="open = true" type="button" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5">
        Hapus
    </button>

    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
        <div @click="open = false" class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6 mx-4">
            <div class="text-center">
                <svg class="mx-auto mb-4 text-red-500 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-2">
                    Yakin ingin menghapus <span class="font-semibold">{{ $label }}</span>? Tindakan ini tidak bisa dibatalkan.
                </p>
            </div>
            <div class="flex justify-center gap-3 mt-6">
                <button @click="open = false" type="button" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">
                    Batal
                </button>
                <form action="{{ $route }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>