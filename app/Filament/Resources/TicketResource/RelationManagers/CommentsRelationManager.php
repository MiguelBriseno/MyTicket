<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';
    protected static ?string $title = 'Comentarios';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Textarea::make('body')
                ->label('Comentario')
                ->required()
                ->rows(3)
                ->columnSpanFull(),
            Forms\Components\Toggle::make('is_internal')
                ->label('Nota interna (solo visible para agentes y admins)')
                ->default(false),
            Forms\Components\Hidden::make('user_id')
                ->default(fn() => Auth::id()),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->sortable(),
                Tables\Columns\TextColumn::make('body')
                    ->label('Comentario')
                    ->limit(80)
                    ->wrap(),
                Tables\Columns\IconColumn::make('is_internal')
                    ->label('Interno')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'asc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Agregar comentario'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
