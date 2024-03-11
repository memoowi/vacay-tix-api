<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
