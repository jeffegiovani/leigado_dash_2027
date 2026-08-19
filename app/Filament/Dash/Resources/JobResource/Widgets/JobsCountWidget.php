<?php

namespace App\Filament\Dash\Resources\JobResource\Widgets;

use App\Models\Job;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class JobsCountWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static bool $isLazy = true;

    public function getColumns(): int
    {
        return 1;
    }

    protected int|string|array $columnSpan = [
        'default' => 1,
        // 'md' => 1,
    ];

    protected function getStats(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;

        return [
            Stat::make(
                label: 'Vagas de Emprego',
                value: Job::query()
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count(),
            ),
        ];
    }
}
