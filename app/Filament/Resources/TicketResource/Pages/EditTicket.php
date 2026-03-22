<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Si el status cambia a resolved y no tiene resolved_at, lo asignamos
        if (
            isset($data['status']) &&
            $data['status'] === 'resolved' &&
            empty($data['resolved_at'])
        ) {
            $data['resolved_at'] = now();
        }

        // Si el status vuelve a open o in_progress, limpiamos resolved_at
        if (
            isset($data['status']) &&
            in_array($data['status'], ['open', 'in_progress', 'waiting_response'])
        ) {
            $data['resolved_at'] = null;
        }

        return $data;
    }
}
