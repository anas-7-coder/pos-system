<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'short_name',
        'code',
        'allows_decimal',
        'precision',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'allows_decimal' => 'boolean',
            'precision' => 'decimal:4',
        ];
    }
}
