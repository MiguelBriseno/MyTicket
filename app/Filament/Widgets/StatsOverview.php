<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user  = Auth::user();
        $query = Ticket::query();

        if ($user->hasRole('agent')) {
            $query->where('assigned_to', $user->id);
        }

        $total      = (clone $query)->count();
        $open       = (clone $query)->where('status', 'open')->count();
        $inProgress = (clone $query)->where('status', 'in_progress')->count();
        $resolvedToday = (clone $query)
            ->where('status', 'resolved')
            ->whereDate('resolved_at', today())
            ->count();

        $avgHours = (clone $query)
            ->whereNotNull('resolved_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours')
            ->value('avg_hours');

        return [
            Stat::make('Total tickets', $total)
                ->description('Todos los tickets')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('gray'),

            Stat::make('Abiertos', $open)
                ->description('Esperando atención')
                ->descriptionIcon('heroicon-m-envelope-open')
                ->color('info'),

            Stat::make('En progreso', $inProgress)
                ->description('Siendo atendidos')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),

            Stat::make('Resueltos hoy', $resolvedToday)
                ->description('Cerrados el día de hoy')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Tiempo promedio', $avgHours ? round($avgHours) . ' hrs' : 'N/A')
                ->description('Horas para resolver')
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary'),
        ];
    }
}
