<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrativeUnits extends Model
{
    use HasFactory;
    protected $table = 'administrative_units';
    protected $fillable = [
        'full_name',
        'full_name_en',
        'short_name',
        'short_name_en',
        'code_name',
        'code_name_en',
    ];
}
