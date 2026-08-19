<?php

namespace App\Filament\Dash\Resources\Coupons;

use App\Filament\Dash\Resources\Coupons\Schemas\CouponForm;
use App\Filament\Dash\Resources\Coupons\Tables\CouponsTable;
use App\Models\Coupon;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Cupons de Desconto'; // Para Menus e para o Spotlight

    // protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Cupom de Desconto';

    protected static ?string $pluralModelLabel = 'Cupons de Desconto';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'author.name'];
    }

    public static function form(Schema $schema): Schema
    {
        return CouponForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CouponsTable::configure($table);
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'view' => Pages\ViewCoupon::route('/{record}'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
