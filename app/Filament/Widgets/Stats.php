<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class Stats extends BaseWidget
{
    public function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Всего заказов', Order::count()),
            Stat::make('Заказы в обработке', Order::all()->where('status', 'pending')->count()),
            Stat::make('Выполненные заказы', Order::all()->where('status', 'completed')->count()),
            Stat::make('Сумма заказов', number_format(Order::all()->sum('total_price'), 0, ',', ' ') . ' ₽'),
            Stat::make('Сумма заказов в обработке', number_format(Order::all()->where('status', 'pending')->sum('total_price'), 0, ',', ' ') . ' ₽'),
            Stat::make('Сумма выполненных заказов', number_format(Order::all()->where('status', 'completed')->sum('total_price'), 0, ',', ' ') . ' ₽'),
        ];
    }
}
