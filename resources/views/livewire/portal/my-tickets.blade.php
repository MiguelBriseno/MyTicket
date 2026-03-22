<div>
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Mis tickets</h2>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($tickets->isEmpty())
        <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
            <p class="text-gray-500 mb-4">No tienes tickets todavía.</p>
            <a href="{{ route('portal.create') }}"
               class="bg-amber-500 text-white px-6 py-2 rounded-lg hover:bg-amber-600">
                Crear mi primer ticket
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($tickets as $ticket)
                <a href="{{ route('portal.show', $ticket) }}"
                   class="block bg-white rounded-lg border border-gray-200 p-5 hover:border-amber-300 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">#{{ $ticket->id }}</p>
                            <h3 class="font-medium text-gray-800">{{ $ticket->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $ticket->department?->name ?? 'Sin departamento' }}
                                @if($ticket->assignee)
                                    · Atendido por {{ $ticket->assignee->name }}
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            @php
                                $statusColors = [
                                    'open'             => 'bg-blue-100 text-blue-700',
                                    'in_progress'      => 'bg-yellow-100 text-yellow-700',
                                    'waiting_response' => 'bg-gray-100 text-gray-600',
                                    'resolved'         => 'bg-green-100 text-green-700',
                                    'closed'           => 'bg-red-100 text-red-700',
                                ];
                                $statusLabels = [
                                    'open'             => 'Abierto',
                                    'in_progress'      => 'En progreso',
                                    'waiting_response' => 'Esperando respuesta',
                                    'resolved'         => 'Resuelto',
                                    'closed'           => 'Cerrado',
                                ];
                            @endphp
                            <span class="text-xs px-2 py-1 rounded-full font-medium {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ $ticket->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
