<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Pembayaran;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PembayaranResource\Pages;
use App\Filament\Resources\PembayaranResource\RelationManagers;
use App\Models\Tagihan;
use Filament\Tables\Actions\Action;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tagihan.kode_tagihan')
                    ->sortable()
                    ->searchable()
                    ->label('Kode Tagihan'),
                TextColumn::make('pelanggan.nama')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('total_bayar')
                    ->label('Total'),
                TextColumn::make('metode_bayar')
                    ->label('Metode'),
                TextColumn::make('jumlah_meter'),

                ImageColumn::make('bukti_bayar')
                    ->disk('public')
                    ->width('180px')
                    ->height('320px'),
                TextColumn::make('tagihan.status')
                    ->label('Status')
                    ->badge(),
            ])
            ->recordUrl(null)
            ->filters([])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Action::make('updateStatus')
                    ->label('Lunas')
                    ->action(function (Pembayaran $record) {
                        $record->tagihan()->update([
                            'status' => 'lunas'
                        ]);
                    })
                    ->color('warning')
                    ->visible(function (Pembayaran $record) {
                        return $record->tagihan->status !== 'lunas';
                    })
                    ->icon('heroicon-o-check')
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
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}
