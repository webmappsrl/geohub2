<?php

namespace App\Models;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class EcTrack extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'description'];

    protected static function booted()
    {
        static::creating(function ($ectrack) {
            if (Auth::check()) {
                $ectrack->user_id = Auth::user()->id;
            }
        });
    }

    protected $fillable = [
        'name',
        'user_id',
        'description'
    ];

    // Relationship with user

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
