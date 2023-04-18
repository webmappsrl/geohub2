<?php

namespace App\Nova\Filters;

use App\Models\TaxonomyTheme;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class ThemeFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->whereHas('taxonomyThemes', function ($query) use ($value) {
            $query->where('taxonomy_themes.identifier', $value);
        });
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        $options = [];
        //when the user is in the EcTracks index, the filter show only the themes of the user's related tracks
        if ($request->resource == 'ec-tracks') {
            $taxonomyThemes = TaxonomyTheme::whereHas('ecTracks', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })->get();
        }
        //when the user is in the EcPoi index, the filter show only the themes of the user's related pois
        if ($request->resource == 'ec-pois') {
            $taxonomyThemes = TaxonomyTheme::whereHas('ecPois', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })->get();
        }

        foreach ($taxonomyThemes as $taxonomyTheme) {
            $options[$taxonomyTheme->identifier] = $taxonomyTheme->identifier;
        }

        return $options;
    }
}
