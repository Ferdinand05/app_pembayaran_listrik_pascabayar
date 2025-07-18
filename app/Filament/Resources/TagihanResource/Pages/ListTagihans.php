<?php

namespace App\Filament\Resources\TagihanResource\Pages;

use App\Filament\Exports\TagihanExporter;
use App\Filament\Resources\TagihanResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListTagihans extends ListRecords
{
    protected static string $resource = TagihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            ExportAction::make('export')
                ->label('Cetak Laporan')
                ->exporter(TagihanExporter::class)
        ];
    }
}
