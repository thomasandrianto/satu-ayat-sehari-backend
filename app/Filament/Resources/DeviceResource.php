<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceResource\Pages;
use App\Models\Device;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static ?string $navigationGroup = 'Satu Ayat Sehari';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Device';

    protected static ?string $pluralModelLabel = 'Devices';

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('created_at', 'desc')

            ->columns([

                TextColumn::make('nomor')
                    ->rowIndex(),

                TextColumn::make('device_id')
                    ->label('Device ID')
                    ->searchable()
                    ->copyable()
                    ->limit(20),

                TextColumn::make('platform')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d-m-Y H:i'),

                TextColumn::make('last_seen_at')
                    ->label('Last Seen')
                    ->since(),
            ])

            ->actions([])

            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDevices::route('/'),
        ];
    }
}