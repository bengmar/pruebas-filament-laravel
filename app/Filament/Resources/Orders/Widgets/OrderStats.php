<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order; // 🌟 IMPORTANTE: Importamos tu modelo Order
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // Ingresos Netos: Solo sumamos lo que realmente se cobró o se está procesando
        $ingresosNetos = Order::query()
            ->whereIn('status', ['processing', 'shipped', 'delivered'])
            ->sum('total');

        // Órdenes Activas: Cantidad de órdenes que requieren atención inmediata
        $ordenesActivas = Order::query()
            ->whereIn('status', ['pending', 'processing', 'shipped'])
            ->count();

        // Tasa de Cancelación: Cálculo porcentual histórico
        $totalOrdenes = Order::query()->count();
        $totalCanceladas = Order::query()->where('status', 'cancelled')->count();

        $tasaCancelacion = $totalOrdenes > 0
            ? round(($totalCanceladas / $totalOrdenes) * 100, 1)
            : 0;

        return [
            Stat::make('Ingresos Netos', '$' . number_format($ingresosNetos, 2, ',', '.'))
                ->description('Total recaudado (sin contar canceladas)')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Órdenes Activas', $ordenesActivas)
                ->description('Pendientes, en proceso o enviadas')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make('Tasa de Cancelación', $tasaCancelacion . '%')
                ->description('Órdenes anuladas del total histórico')
                ->descriptionIcon($tasaCancelacion > 15 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($tasaCancelacion > 15 ? 'danger' : 'gray'),
        ];
    }
}
