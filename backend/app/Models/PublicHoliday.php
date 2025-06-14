<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicHoliday extends Model
{
    protected $fillable = [
        'holiday_name',
        'holiday_date',
        'business_id',
    ];
}
