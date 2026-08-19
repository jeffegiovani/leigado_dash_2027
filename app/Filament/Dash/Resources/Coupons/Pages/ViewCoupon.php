<?php

namespace App\Filament\Dash\Resources\Coupons\Pages;

use App\Filament\Dash\Resources\Coupons\CouponResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCoupon extends ViewRecord
{
    protected static string $resource = CouponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
