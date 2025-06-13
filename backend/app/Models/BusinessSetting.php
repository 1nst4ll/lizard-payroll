<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BusinessSetting extends Model
{
    use HasUuids;

    protected $fillable = [
        'business_name',
        'business_phone',
        'business_email',
        'business_address',
        'time_zone',
    ];
}
