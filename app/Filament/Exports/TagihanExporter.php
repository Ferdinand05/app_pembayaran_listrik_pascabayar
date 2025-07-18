<?php

namespace App\Filament\Exports;

use App\Models\Tagihan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TagihanExporter extends Exporter
{
    protected static ?string $model = Tagihan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('kode_tagihan'),
            ExportColumn::make('pelanggan.nama'),
            ExportColumn::make('total_tagihan'),
            ExportColumn::make('status'),
            ExportColumn::make('jumlah_meter'),
            ExportColumn::make('bulan'),
            ExportColumn::make('tahun'),
            ExportColumn::make('created_at')
                ->label('Tanggal')
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your tagihan export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
