<div class="bg-white rounded-lg border border-gray-200 p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Nuevo ticket</h2>

    <form wire:submit="submit" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Título <span class="text-red-500">*</span>
            </label>
            <input wire:model="title" type="text"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                   placeholder="Describe brevemente el problema">
            @error('title')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Descripción <span class="text-red-500">*</span>
            </label>
            <textarea wire:model="description" rows="4"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                      placeholder="Explica el problema con el mayor detalle posible"></textarea>
            @error('description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                <select wire:model="priority"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <option value="low">Baja</option>
                    <option value="medium">Media</option>
                    <option value="high">Alta</option>
                    <option value="critical">Crítica</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                <select wire:model="department_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <option value="">Sin departamento</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('portal.tickets') }}"
               class="text-sm text-gray-500 hover:text-gray-700">
                Cancelar
            </a>
            <button type="submit"
                    class="bg-amber-500 text-white px-6 py-2 rounded-lg text-sm hover:bg-amber-600">
                Enviar ticket
            </button>
        </div>
    </form>
</div>
