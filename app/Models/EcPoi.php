<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EcPoi extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'description'];

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
