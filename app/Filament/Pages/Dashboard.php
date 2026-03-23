<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filtro de fechas')
                    ->description('Selecciona un rango de fechas para las estadísticas')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Desde')
                            ->maxDate(now())
                            ->default(now()->startOfMonth()),
                        DatePicker::make('end_date')
                            ->label('Hasta')
                            ->maxDate(now())
                            ->default(now()),
                    ])
                    ->columns(2),
            ]);
    }
}
