<?php

namespace App\Filament\Widgets;

use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pelanggan', Pelanggan::count()),
            Stat::make('Tagihan', Tagihan::count()),
            Stat::make('Pembayaran', Pembayaran::count())
        ];
    }
}
