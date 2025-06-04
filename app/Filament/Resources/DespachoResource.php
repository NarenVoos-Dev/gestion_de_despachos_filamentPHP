<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DespachoResource\Pages;
use App\Filament\Resources\DespachoResource\RelationManagers;
use App\Models\Despacho;
use App\Models\Ciudades;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;

use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Support\Facades\Storage;

class DespachoResource extends Resource
{
    protected static ?string $model = Despacho::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('administrador');
        return $form
        ->schema([
                DatePicker::make('fecha')->label('Fecha')
                    ->disabled(!$isAdmin),

                DatePicker::make('fecha_entrega')->label('Fecha de Entrega')
                    ->disabled(!$isAdmin),

                TextInput::make('orden_compra')->label('Orden de Compra')
                    ->required()
                    ->disabled(!$isAdmin && request()->routeIs('filament.admin.resources.despachos.edit')),

                TextInput::make('orden_pedido')->label('Numero de Pedido'),
                    //->disabled(!$isAdmin),

                TextInput::make('factura')->label('Factura'),
                    //->disabled(!$isAdmin),

                Select::make('cliente_id')->label('Cliente')
                    ->relationship('cliente', 'nombre')
                    ->searchable()
                    ->disabled(!$isAdmin),

                Select::make('ciudad')
                    ->label('Ciudad')
                    ->options(
                        Ciudades::all()->pluck('nombre', 'nombre')
                    )
                    ->searchable()
                    ->required()
                    ->disabled(!$isAdmin),

                TextInput::make('cantidad_pedido')->label('Cantidad de Pedido')
                    ->numeric()
                    ->disabled(!$isAdmin),

                Select::make('producto_id')->label('Producto')
                    ->relationship('producto', 'nombre')
                    //->searchable()
                    ->required()
                    ->disabled(!$isAdmin),

                TextInput::make('empresa')->label('Empresa')
                    ->disabled(!$isAdmin),

                Select::make('transportadora_id')->label('Transportadora')
                    ->relationship('transportadora', 'nombre')
                    //->searchable()
                    ->required(),
                    //->disabled(!$isAdmin && request()->routeIs('filament.admin.resources.despachos.edit')),
                TextInput::make('guia')
                    ->label('Guía'),
                    //->disabled(!$isAdmin),
                TextInput::make('valor_unitario')
                    ->label('Valor Unitario')
                    ->numeric()
                    ->prefix('$')
                    ->disabled(!$isAdmin),
                TextInput::make('valor_total')
                    ->label('Valor Total')
                    ->numeric()
                    ->prefix('$')
                    ->disabled(!$isAdmin),
                Select::make('mes')
                    ->label('Mes')
                    ->options([
                        'Enero' => 'Enero',
                        'Febrero' => 'Febrero',
                        'Marzo' => 'Marzo',
                        'Abril' => 'Abril',
                        'Mayo' => 'Mayo',
                        'Junio' => 'Junio',
                        'Julio' => 'Julio',
                        'Agosto' => 'Agosto',
                        'Septiembre' => 'Septiembre',
                        'Octubre' => 'Octubre',
                        'Noviembre' => 'Noviembre',
                        'Diciembre' => 'Diciembre',
                    ])
                    ->searchable()
                    ->disabled(!$isAdmin),
                Select::make('estado')->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'enviado' => 'Enviado',
                        'entregado' => 'Entregado',
                        'cancelado' => 'Cancelado',
                    ])
                    ->required(),

                Textarea::make('novedad_factura')->label('Novedad en Factura')
                    ->rows(6),
                    //->columnSpanFull(),
                    //->disabled(!$isAdmin),
                FileUpload::make('adjunto')
                    ->label('Adjuntar archivo')
                    ->disk('public') // Asegúrate que el disco esté configurado
                    ->directory('adjuntos-despachos')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                    ->enableDownload()
                    ->enableOpen()
                    //->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fecha')
                    ->label('Fecha de creación')
                    ->date()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_entrega')
                    ->date()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('orden_compra')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('orden_pedido')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('factura')
                    ->label('Factura')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->alignCenter()
                    ->searchable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('ciudad')
                    ->label('Ciudad')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad_pedido')
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('producto.nombre')
                    ->label('Producto')
                    ->alignCenter()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('empresa')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('transportadora.nombre')
                    ->label('Transportadora')
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),
                BadgeColumn::make('estado')
                    ->label('Estado')
                    ->alignCenter()
                    ->colors([
                        'warning' => 'pendiente',    // 🟡 Amarillo
                        'info'    => 'enviado',      // 🔵 Azul
                        'success' => 'entregado',    // 🟢 Verde
                        'danger'  => 'cancelado',    // 🔴 Rojo
                    ])
                    ->formatStateUsing(function (string $state) {
                        return ucfirst($state); // convierte "pendiente" en "Pendiente"
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('guia')
                    ->label('Guía')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('valor_unitario')
                    ->label('Valor Unitario')
                    ->alignCenter()
                    ->money('COP'),
                Tables\Columns\TextColumn::make('valor_total')
                    ->label('Valor Total')
                    ->alignCenter()
                    ->money('COP'),
                Tables\Columns\TextColumn::make('mes')
                    ->label('Mes')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('creador.name')
                    ->label('Creado por')
                    ->alignCenter()
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('editor.name')
                    ->label('Actualizado por')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('estado')
                ->label('Filtrar por estado')
                ->options([
                    'pendiente' => 'Pendiente',
                    'enviado' => 'Enviado',
                    'entregado' => 'Entregado',
                    'cancelado' => 'Cancelado',
                ])
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->visible(fn () => auth()->user()?->hasRole('administrador')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn () => auth()->user()?->hasRole('administrador')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDespachos::route('/'),
            'create' => Pages\CreateDespacho::route('/create'),
            'edit' => Pages\EditDespacho::route('/{record}/edit'),
        ];
    }
}
