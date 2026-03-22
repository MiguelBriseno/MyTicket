<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TicketsByStatus extends ChartWidget
{
    protected static ?string $heading = 'Tickets por estado';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $user = Auth::user();

        $query = Ticket::query();
        if ($user->hasRole('agent')) {
            $query->where('assigned_to', $user->id);
        }

        $statuses = [
            'open'             => 'Abierto',
            'in_progress'      => 'En progreso',
            'waiting_response' => 'Esperando respuesta',
            'resolved'         => 'Resuelto',
            'closed'           => 'Cerrado',
        ];

        $data   = [];
        $labels = [];

        foreach ($statuses as $key => $label) {
            $count = (clone $query)->where('status', $key)->count();
            if ($count > 0) {
                $data[]   = $count;
                $labels[] = $label;
            }
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Tickets',
                    'data'            => $data,
                    'backgroundColor' => [
                        '#3B82F6',
                        '#F59E0B',
                        '#6B7280',
                        '#10B981',
                        '#EF4444',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
