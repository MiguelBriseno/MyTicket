@extends('portal.layout')

@section('content')
    <div class="mb-4">
        <a href="{{ route('portal.tickets') }}"
           class="text-sm text-gray-500 hover:text-gray-700">
            ← Volver a mis tickets
        </a>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <div class="flex items-start justify-between gap-4 mb-4">
            <div>
                <p class="text-xs text-gray-400 mb-1">#{{ $ticket->id }}</p>
                <h2 class="text-xl font-semibold text-gray-800">{{ $ticket->title }}</h2>
            </div>
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
                $priorityColors = [
                    'low'      => 'bg-green-100 text-green-700',
                    'medium'   => 'bg-blue-100 text-blue-700',
                    'high'     => 'bg-yellow-100 text-yellow-700',
                    'critical' => 'bg-red-100 text-red-700',
                ];
                $priorityLabels = [
                    'low'      => 'Baja',
                    'medium'   => 'Media',
                    'high'     => 'Alta',
                    'critical' => 'Crítica',
                ];
            @endphp
            <div class="flex gap-2 shrink-0">
                <span class="text-xs px-2 py-1 rounded-full font-medium {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                </span>
                <span class="text-xs px-2 py-1 rounded-full font-medium {{ $priorityColors[$ticket->priority] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $priorityLabels[$ticket->priority] ?? $ticket->priority }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
            <div>
                <span class="text-gray-500">Departamento:</span>
                <span class="text-gray-800 ml-1">{{ $ticket->department?->name ?? 'Sin asignar' }}</span>
            </div>
            <div>
                <span class="text-gray-500">Agente:</span>
                <span class="text-gray-800 ml-1">{{ $ticket->assignee?->name ?? 'Sin asignar' }}</span>
            </div>
            <div>
                <span class="text-gray-500">Creado:</span>
                <span class="text-gray-800 ml-1">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
            </div>
            @if($ticket->resolved_at)
                <div>
                    <span class="text-gray-500">Resuelto:</span>
                    <span class="text-gray-800 ml-1">{{ $ticket->resolved_at->format('d/m/Y H:i') }}</span>
                </div>
            @endif
        </div>

        <div class="border-t border-gray-100 pt-4">
            <p class="text-sm text-gray-500 mb-1">Descripción</p>
            <p class="text-gray-800 text-sm leading-relaxed">{{ $ticket->description }}</p>
        </div>

        {{-- Adjuntos --}}
        @if($ticket->getMedia('attachments')->count() > 0)
            <div class="border-t border-gray-100 pt-4 mt-4">
                <p class="text-sm text-gray-500 mb-3">Archivos adjuntos</p>
                <div class="flex flex-wrap gap-3">
                    @foreach($ticket->getMedia('attachments') as $media)
                        <a href="{{ $media->getUrl() }}" target="_blank"
                           class="flex items-center gap-2 text-sm text-amber-600 hover:text-amber-700 border border-amber-200 rounded-lg px-3 py-2 hover:bg-amber-50">
                            @if(str_contains($media->mime_type, 'image'))
                                <img src="{{ $media->getUrl('thumb') }}" class="w-8 h-8 object-cover rounded">
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @endif
                            {{ $media->file_name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Comentarios --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h3 class="font-medium text-gray-800 mb-4">
            Conversación
            <span class="text-gray-400 font-normal text-sm ml-1">
                ({{ $ticket->comments->where('is_internal', false)->count() }} mensajes)
            </span>
        </h3>

        @if($ticket->comments->where('is_internal', false)->isEmpty())
            <p class="text-sm text-gray-400 text-center py-6">
                Aún no hay mensajes. Un agente te responderá pronto.
            </p>
        @else
            <div class="space-y-4 mb-6">
                @foreach($ticket->comments->where('is_internal', false) as $comment)
                    @php $isOwn = $comment->user_id === auth()->id(); @endphp
                    <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-lg">
                            <p class="text-xs text-gray-400 mb-1 {{ $isOwn ? 'text-right' : 'text-left' }}">
                                {{ $comment->user->name }} · {{ $comment->created_at->format('d/m/Y H:i') }}
                            </p>
                            <div class="px-4 py-3 rounded-lg text-sm {{ $isOwn ? 'bg-amber-50 text-amber-900' : 'bg-gray-100 text-gray-800' }}">
                                {{ $comment->body }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Formulario de respuesta --}}
        @if(!in_array($ticket->status, ['resolved', 'closed']))
            <livewire:portal.reply-ticket :ticket="$ticket" />
        @else
            <p class="text-sm text-center text-gray-400 border-t border-gray-100 pt-4">
                Este ticket está cerrado. Si el problema persiste crea un nuevo ticket.
            </p>
        @endif
    </div>
@endsection
