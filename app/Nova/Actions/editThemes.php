<?php

namespace App\Nova\Actions;

use App\Models\TaxonomyTheme;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Nova\Http\Requests\NovaRequest;

class editThemes extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Edit Themes';


    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            //attach new taxonomyThemes to the model
            if (isset($fields['attach'])) {
                $taxonomyTheme = TaxonomyTheme::where('identifier', $fields['attach'])->first();
                $model->taxonomyThemes()->attach($taxonomyTheme);
            }

            //detach taxonomyThemes from the model
            if (isset($fields['detach'])) {
                $taxonomyTheme = TaxonomyTheme::where('identifier', $fields['detach'])->first();
                $model->taxonomyThemes()->detach($taxonomyTheme);
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            //select field to attach taxonomy theme. Generate options from the taxonomy themes not attached to the model 
            Select::make('Attach')
                ->options($request->resource == 'ec-tracks' ? TaxonomyTheme::whereHas('ecTracks', function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                }, '<', 1)->get()->pluck('identifier', 'identifier') : TaxonomyTheme::whereHas('ecPois', function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                }, '<', 1)->get()->pluck('identifier', 'identifier'))
                ->displayUsingLabels()
                ->searchable(),

            //select field to detach taxonomy theme. Generate options from the taxonomy themes attached to the model
            Select::make('Detach')
                ->options($request->resource == 'ec-tracks' ? TaxonomyTheme::whereHas('ecTracks', function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                })->get()->pluck('identifier', 'identifier') : TaxonomyTheme::whereHas('ecPois', function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                })->get()->pluck('identifier', 'identifier'))
                ->displayUsingLabels()
                ->searchable(),



        ];
    }

    public function name()
    {
        return __('Edit Themes');
    }
}
