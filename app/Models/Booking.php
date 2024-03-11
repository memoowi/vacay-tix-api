<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
    public function qrCode(): HasOne
    {
        return $this->hasOne(QRCode::class);
    }
}
