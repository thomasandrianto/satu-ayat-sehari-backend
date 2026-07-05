<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DevotionResource\Pages;
use App\Models\Devotion;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class DevotionResource extends Resource
{
    protected static ?string $model = Devotion::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Satu Ayat Sehari';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Renungan';

    protected static ?string $pluralModelLabel = 'Renungan Harian';

    public static function form(Form $form): Form
    {
        return $form->schema([

            DatePicker::make('devotion_date')
                ->label('Tanggal')
                ->required(),

            TextInput::make('book')
                ->label('Kitab')
                ->required(),

            TextInput::make('chapter')
                ->numeric()
                ->required(),

            TextInput::make('verse_start')
                ->numeric()
                ->required(),

            TextInput::make('verse_end')
                ->numeric(),

            Textarea::make('verse_text_id')
                ->label('Ayat (Indonesia)')
                ->rows(4)
                ->required(),

            Textarea::make('verse_text_en')
                ->label('Ayat (English)')
                ->rows(4),

            TextInput::make('devotion_title')
                ->label('Judul Renungan')
                ->required(),

            Textarea::make('devotion_text')
                ->label('Isi Renungan')
                ->rows(8)
                ->required(),

            TextInput::make('theme')
                ->label('Tema'),

            Toggle::make('is_published')
                ->label('Publish')
                ->default(true),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('devotion_date', 'desc')

            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Renungan')
                    ->slideOver(),
            ])

            ->columns([

                TextColumn::make('nomor')
                    ->rowIndex(),

                TextColumn::make('devotion_date')
                    ->label('Tanggal')
                    ->date('d-m-Y')
                    ->sortable(),

                TextColumn::make('reference')
                    ->label('Referensi')
                    ->getStateUsing(
                        fn ($record) =>
                        $record->book . ' ' .
                        $record->chapter . ':' .
                        $record->verse_start .
                        ($record->verse_end
                            ? '-' . $record->verse_end
                            : '')
                    )
                    ->searchable(),

                TextColumn::make('devotion_title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('theme')
                    ->label('Tema')
                    ->badge(),

                IconColumn::make('is_published')
                    ->label('Publish')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime('d-m-Y'),
            ])

            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->slideOver(),

                Tables\Actions\DeleteAction::make()
                    ->iconButton(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDevotions::route('/'),
        ];
    }
}