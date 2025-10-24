<?php

namespace App\Filament\Admin\Resources\Opportunities\Pages;

use App\Filament\Admin\Resources\Opportunities\OpportunityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOpportunity extends CreateRecord
{
    protected static string $resource = OpportunityResource::class;
}
