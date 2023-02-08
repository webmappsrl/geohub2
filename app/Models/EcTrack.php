<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    // Relationship with user

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
