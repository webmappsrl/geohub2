<?php

namespace App\Models;

use App\Models\EcPoi;
use App\Models\EcTrack;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TaxonomyTheme extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'identifier'
    ];

    public function ecTracks()
    {
        return $this->morphedByMany(EcTrack::class, 'themeable');
    }

    public function ecPois()
    {
        return $this->morphedByMany(EcPoi::class, 'themeable');
    }
}
