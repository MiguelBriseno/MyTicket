<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Tickets';

    protected static ?string $modelLabel = 'Ticket';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Gestión';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Información del ticket')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Título')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('description')
                        ->label('Descripción')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Clasificación')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Estado')
                        ->options([
                            'open' => 'Abierto',
                            'in_progress' => 'En progreso',
                            'waiting_response' => 'Esperando respuesta',
                            'resolved' => 'Resuelto',
                            'closed' => 'Cerrado',
                        ])
                        ->default('open')
                        ->required(),
                    Forms\Components\Select::make('priority')
                        ->label('Prioridad')
                        ->options([
                            'low' => 'Baja',
                            'medium' => 'Media',
                            'high' => 'Alta',
                            'critical' => 'Crítica',
                        ])
                        ->default('medium')
                        ->required(),
                    Forms\Components\Select::make('department_id')
                        ->label('Departamento')
                        ->options(Department::where('is_active', true)->pluck('name', 'id'))
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('assigned_to')
                        ->label('Asignar a')
                        ->options(
                            User::role('agent')->pluck('name', 'id')
                        )
                        ->searchable()
                        ->preload(),
                    SpatieMediaLibraryFileUpload::make('attachments')
                        ->label('Archivos adjuntos')
                        ->collection('attachments')
                        ->multiple()
                        ->acceptedFileTypes([
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp',
                            'application/pdf',
                        ])
                        ->maxSize(10240)
                        ->downloadable()
                        ->columnSpanFull(),
                    Forms\Components\Hidden::make('created_by')
                        ->default(fn () => Auth::id()),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->limit(40)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'info',
                        'in_progress' => 'warning',
                        'waiting_response' => 'gray',
                        'resolved' => 'success',
                        'closed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open' => 'Abierto',
                        'in_progress' => 'En progreso',
                        'waiting_response' => 'Esperando respuesta',
                        'resolved' => 'Resuelto',
                        'closed' => 'Cerrado',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioridad')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'success',
                        'medium' => 'info',
                        'high' => 'warning',
                        'critical' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'low' => 'Baja',
                        'medium' => 'Media',
                        'high' => 'Alta',
                        'critical' => 'Crítica',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Departamento')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->label('Asignado a')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Creado por')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'open' => 'Abierto',
                        'in_progress' => 'En progreso',
                        'waiting_response' => 'Esperando respuesta',
                        'resolved' => 'Resuelto',
                        'closed' => 'Cerrado',
                    ]),
                Tables\Filters\SelectFilter::make('priority')
                    ->label('Prioridad')
                    ->options([
                        'low' => 'Baja',
                        'medium' => 'Media',
                        'high' => 'Alta',
                        'critical' => 'Crítica',
                    ]),
                Tables\Filters\SelectFilter::make('department')
                    ->label('Departamento')
                    ->relationship('department', 'name'),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->label('Agente')
                    ->options(User::role('agent')->pluck('name', 'id')),
                Filter::make('created_at')
                    ->label('Fecha de creación')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Desde'),
                        Forms\Components\DatePicker::make('to')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['from'] ?? null,
                            fn (Builder $query, $date) => $query->whereDate('created_at', '>=', $date)
                        )->when(
                            $data['to'] ?? null,
                            fn (Builder $query, $date) => $query->whereDate('created_at', '<=', $date)
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        // Admin ve absolutamente todo
        if ($user->hasRole('admin')) {
            return $query;
        }

        // Agente ve tickets asignados a él O tickets de su departamento
        if ($user->hasRole('agent')) {
            return $query->where(function (Builder $q) use ($user) {
                $q->where('assigned_to', $user->id)
                    ->orWhereIn('department_id', function ($sub) use ($user) {
                        $sub->select('department_id')
                            ->from('tickets')
                            ->where('assigned_to', $user->id)
                            ->whereNotNull('department_id');
                    });
            });
        }

        // Usuario normal solo ve sus propios tickets
        return $query->where('created_by', $user->id);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
            'view' => Pages\ViewTicket::route('/{record}'),
        ];
    }
}
