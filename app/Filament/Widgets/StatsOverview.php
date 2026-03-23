<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $user = Auth::user();
        $query = Ticket::query();

        if ($user->hasRole('agent')) {
            $query->where('assigned_to', $user->id);
        }

        $startDate = $this->filters['start_date'] ?? null;
        $endDate = $this->filters['end_date'] ?? null;

        $baseQuery = (clone $query);

        $total = (clone $query)->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate))
            ->count();

        $open = (clone $baseQuery)->where('status', 'open')
            ->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate))
            ->count();

        $inProgress = (clone $baseQuery)->where('status', 'in_progress')
            ->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate))
            ->count();

        $resolved = (clone $baseQuery)->where('status', 'resolved')
            ->when($startDate, fn ($q) => $q->whereDate('resolved_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('resolved_at', '<=', $endDate))
            ->count();

        $queryAvg = (clone $baseQuery)->whereNotNull('resolved_at')
            ->when($startDate, fn ($q) => $q->whereDate('resolved_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('resolved_at', '<=', $endDate));

        $avgHours = $queryAvg->count() > 0
            ? $queryAvg->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours')->value('avg_hours')
            : null;

        $dateLabel = 'Todos';
        if ($startDate && $endDate) {
            $dateLabel = "{$startDate} - {$endDate}";
        } elseif ($startDate) {
            $dateLabel = "Desde {$startDate}";
        } elseif ($endDate) {
            $dateLabel = "Hasta {$endDate}";
        }

        return [
            Stat::make('Total tickets', $total)
                ->description($dateLabel)
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

            Stat::make('Resueltos', $resolved)
                ->description('En el período seleccionado')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Tiempo promedio', $avgHours ? round($avgHours).' hrs' : 'N/A')
                ->description('Horas para resolver')
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary'),
        ];
    }
}
