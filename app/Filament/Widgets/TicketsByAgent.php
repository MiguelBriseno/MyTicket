<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use App\Models\User;
use Filament\Widgets\ChartWidget;

class TicketsByAgent extends ChartWidget
{
    protected static ?string $heading = 'Tickets por agente';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $agents = User::role('agent')->get();

        $labels = [];
        $data   = [];

        foreach ($agents as $agent) {
            $labels[] = $agent->name;
            $data[]   = Ticket::where('assigned_to', $agent->id)
                ->whereIn('status', ['open', 'in_progress'])
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Tickets activos',
                    'data'            => $data,
                    'backgroundColor' => '#6366F1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
