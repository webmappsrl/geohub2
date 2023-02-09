<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcPoi extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::creating(function ($ecpoi) {
            if (Auth::check()) {
                $ecpoi->user_id = Auth::user()->id;
            }
        });
    }

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
