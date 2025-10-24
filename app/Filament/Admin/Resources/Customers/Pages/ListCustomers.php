<?php

namespace App\Filament\Admin\Resources\Customers\Pages;

use App\Filament\Admin\Resources\Customers\CustomerResource;
use App\Models\Customer;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static \UnitEnum|string|null $navigationGroup = 'İçerik Yönetimi';
    protected static ?string $navigationLabel = 'Randevular';
    protected static ?string $pluralNavigationLabel = 'Randevular';
    protected static ?string $label = 'Randevu';
    protected static ?string $pluralLabel = 'Randevular';
    protected static ?int $navigationSort = 3;


    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
