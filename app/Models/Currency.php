<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function countries()
    {
        return $this->hasMany(Country::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}
