<?php

namespace App\Models;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\TaxonomyTheme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EcTrack extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'description', 'excerpt'];

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
        'description',
        'excerpt',
        'geohub_id',
        'geometry'

    ];

    // Relationship with user

    public function user()
    {
        return $this->belongsTo(User::class)->where('role', UserRole::Editor);
    }

    public function taxonomyThemes()
    {
        return $this->morphToMany(TaxonomyTheme::class, 'themeable', 'taxonomy_themeables');
    }
}
