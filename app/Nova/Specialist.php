<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Specialist extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Specialist::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make('User'),
            BelongsTo::make('Activity kind')
                ->sortable(),
            Text::make('Name')
                ->creationRules('required')
                ->updateRules('required,{{resourceId}}'),
            Text::make('Surname')
                ->creationRules('required')
                ->updateRules('required,{{resourceId}}'),
            Avatar::make('Avatar')
                ->prunable(),
            Text::make('Card Title')
                ->creationRules('required')
                ->updateRules('required,{{resourceId}}')
                ->hideFromIndex(),
            Text::make('About')
                ->creationRules('required')
                ->updateRules('required,{{resourceId}}')
                ->hideFromIndex(),
            Text::make('Address')
                ->creationRules('required')
                ->updateRules('required,{{resourceId}}')
                ->hideFromIndex(),
            Text::make('Placement')
                ->hideFromIndex(),
            Text::make('Floor')
                ->hideFromIndex(),
            Text::make('Instagram Account')
                ->hideFromIndex(),
            Text::make('VK Account')
                ->hideFromIndex(),
            Text::make('Youtube Account')
                ->hideFromIndex(),
            Text::make('TikTok Account')
                ->hideFromIndex(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
