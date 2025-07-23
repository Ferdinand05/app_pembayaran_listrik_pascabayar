<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use Filament\Forms;
use Filament\Tables;
use App\Models\Tagihan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TagihanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TagihanResource\RelationManagers;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_tagihan')
                    ->searchable()
                    ->copyable()
                    ->label('Kode'),
                TextColumn::make('pelanggan.nama')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('bulan')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Carbon::create()->month($state)->monthName),
                TextColumn::make('tahun')
                    ->sortable(),
                TextColumn::make('jumlah_meter'),
                TextColumn::make('biaya_admin')
                    ->label('Biaya adm.')
                    ->numeric(),
                TextColumn::make('total_tagihan')
                    ->label('Total')
                    ->sortable()
                    ->numeric(),
                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'belum dibayar' => 'danger',
                        'sudah dibayar' => 'success',
                        default => 'gray',
                    })
            ])
            ->recordUrl(null)
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'belum dibayar' => 'belum dibayar',
                        'lunas' => 'lunas'
                    ]),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->defaultFormat('pdf')
                    ->disablePreview()
                    ->label('Cetak Laporan')
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(null);
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
            'index' => Pages\ListTagihans::route('/'),
            'create' => Pages\CreateTagihan::route('/create'),
            'edit' => Pages\EditTagihan::route('/{record}/edit'),
        ];
    }
}
