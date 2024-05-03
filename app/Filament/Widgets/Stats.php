<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class Stats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Всего заказов', Order::count()),
            Stat::make('Заказы в обработке', Order::all()->where('status', 'pending')->count()),
            Stat::make('Выполненные заказы', Order::all()->where('status', 'completed')->count())
        ];
    }
}
