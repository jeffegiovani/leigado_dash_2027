<?php

namespace App\Filament\Dash\Resources\UserResource\Pages;

use App\Filament\Dash\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
