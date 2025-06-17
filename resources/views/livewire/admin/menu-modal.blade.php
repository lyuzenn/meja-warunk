<div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
      <form>
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
            {{ $menu_id ? 'Edit Menu' : 'Tambah Menu Baru' }}
          </h3>

          <div class="mt-4">
            <div class="mb-4">
              <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Menu:</label>
              <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="name" wire:model.defer="name">
              @error('name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <div class="mb-4">
              <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi:</label>
              <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="description" wire:model.defer="description"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Harga:</label>
                <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="price" wire:model.defer="price">
                @error('price')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
              </div>

              <div>
                <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Kategori:</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="category" wire:model.defer="category">
                @error('category')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
              </div>
            </div>

            <div class="mb-4">
              <label for="is_available" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
              <select id="is_available" wire:model.defer="is_available" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                <option value="1">Tersedia</option>
                <option value="0">Habis</option>
              </select>
            </div>
          </div>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
            Simpan
          </button>
          <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
            Batal
          </button>
        </div>
      </form>
    </div>

  </div>
</div>
