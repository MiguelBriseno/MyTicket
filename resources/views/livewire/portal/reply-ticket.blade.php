<div class="border-t border-gray-100 pt-4">
    <form wire:submit="submit" class="flex gap-3">
        <textarea wire:model="body" rows="2" placeholder="Escribe tu respuesta..."
                  class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none"></textarea>
        <button type="submit"
                class="self-end bg-amber-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-amber-600 shrink-0">
            Enviar
        </button>
    </form>
    @error('body')
    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
