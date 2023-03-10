<?php

namespace App\Models;

use App\Models\User;
use App\Models\TaxonomyTheme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EcPoi extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'description', 'excerpt'];

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
        'description',
        'excerpt',
        'geohub_id',
        'geometry',
        'out_source_feature_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taxonomyThemes()
    {
        return $this->morphToMany(TaxonomyTheme::class, 'themeable');
    }
}
