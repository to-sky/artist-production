<?php

namespace App\Models;

use App\Traits\FilesMorphTrait;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Observers\UserActionsObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use FilesMorphTrait, SoftDeletes;

    const ACTIVE = 1;
    const NOT_ACTIVE = 0;

    const ENTITY_TYPE = 'events';

    function __construct(array $attributes = [])
    {
        $this->entity_type = static::ENTITY_TYPE;

        parent::__construct($attributes);
    }

    protected $fillable = [
        'name',
        'date',
        'is_active',
        'hall_id',
        'event_image_id',
        'free_pass_logo_id',
        'kartina_id',
        'hall_blueprint_id',
    ];

    protected $appends = ['image_url'];

    public static function boot()
    {
        parent::boot();

        Event::observe(new UserActionsObserver);
    }

    public function hall()
    {
        return $this->belongsTo('App\Models\Hall');
    }

    public function prices()
    {
        return $this->hasMany('App\Models\Price');
    }

    public function priceGroups()
    {
        return $this->hasMany('App\Models\PriceGroup');
    }

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket');
    }

    public function eventImage()
    {
        return $this->belongsTo('App\Models\File', 'event_image_id');
    }

    public function getImageUrlAttribute()
    {
        return $this->eventImage->file_url ?? asset('images/no-image.jpg');
    }

    public function freePassLogo()
    {
        return $this->belongsTo('App\Models\File', 'free_pass_logo_id');
    }

    /**
     * Set attribute to datetime format
     * @param $input
     */
    public function setDateAttribute($input)
    {
        if($input != '') {
            $this->attributes['date'] = Carbon::createFromFormat(
                config('admin.date_format') . ' ' . config('admin.time_format_hm'), $input
            )->format('Y-m-d H:i:s');
        }else{
            $this->attributes['date'] = '';
        }
    }

    /**
     * Get attribute from datetime format
     * @param $input
     *
     * @return string
     */
    public function getDateAttribute($input)
    {
        if($input != '0000-00-00') {
            return Carbon::createFromFormat('Y-m-d H:i:s', $input);
        }else{
            return '';
        }
    }

    /**
     * Set is_active attribute
     *
     * @param $input
     */
    public function setIsActiveAttribute($input)
    {
        $this->attributes['is_active'] = empty($input)
            ? self::NOT_ACTIVE
            : self::ACTIVE;
    }

    public function getCanOrderAttribute()
    {
        $tz = new \DateTimeZone('UTC');
        
        $eventDate = $this->date->timezone($tz)->format('Y-m-d H:i');
        $now = Carbon::now()->timezone($tz)->format('Y-m-d H:i');

        return $now < $eventDate;
    }

    public static function buildHallFromBlueprint($blueprint_id)
    {
        $blueprint = HallBlueprint::with(['placeBlueprints', 'zoneBlueprints', 'labelBlueprints'])->whereId($blueprint_id)->first();

        $hall = self::storeHall($blueprint);
        $zoneBindings = self::storeZones($blueprint->zoneBlueprints, $hall);
        self::storePlaces($blueprint->placeBlueprints, $hall, $zoneBindings);
        self::storeLabels($blueprint->labelBlueprints, $hall);

        return $hall;
    }

    /**
     * Store hall
     *
     * @param $blueprint
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected static function storeHall($blueprint)
    {
        return Hall::create([
            'name' => $blueprint->name,
            'building_id' => $blueprint->building_id,
            'kartina_id' => 0,
        ]);
    }

    /**
     * Store zones
     *
     * @param $zones
     * @param $hall
     * @return array
     */
    protected static function storeZones($zones, $hall)
    {
        $bindings = [];
        foreach ($zones as $zoneBlueprint) {
            $zone = Zone::create([
                'hall_id' => $hall->id,
                'name' => $zoneBlueprint->name,
                'kartina_id' => 0,
            ]);

            $bindings[$zoneBlueprint['id']] = $zone->id;
        }

        return $bindings;
    }

    /**
     * Store places
     *
     * @param $places
     * @param $hall
     * @param $zoneBinds
     */
    protected static function storePlaces($places, $hall, $zoneBinds)
    {
        $data = [];
        foreach ($places as $placeBlueprint) {
            $data[] = [
                'row' => $placeBlueprint->row,
                'num' => $placeBlueprint->num,
                'text' => $placeBlueprint->text,
                'template' => $placeBlueprint->template,
                'x' => (double)$placeBlueprint->x,
                'y' => (double)$placeBlueprint->y,
                'width' => (double)$placeBlueprint->width,
                'height' => (double)$placeBlueprint->height,
                'path' => $placeBlueprint->path,
                'rotate' => (double)$placeBlueprint->rotate,
                'zone_id' => $zoneBinds[$placeBlueprint->zone_blueprint_id] ?? null,
                'hall_id' => $hall->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'kartina_id' => 0,
            ];
        }

        collect($data)->chunk(500)->each(function($chunk) {
            Place::insert($chunk->toArray());
        });
    }

    /**
     * Store labels
     *
     * @param $labels
     * @param $hall
     */
    protected static function storeLabels($labels, $hall)
    {
        $data = [];
        foreach ($labels as $labelBlueprints) {
            $data[] = [
                'x' => (double)$labelBlueprints->x,
                'y' => (double)$labelBlueprints->y,
                'hall_id' => $hall->id,
                'is_bold' => $labelBlueprints->is_bold,
                'is_italic' => $labelBlueprints->is_italic,
                'text' => $labelBlueprints->text,
                'layer' => $labelBlueprints->layer,
                'rotation' => (double)$labelBlueprints->rotation,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        collect($data)->chunk(500)->each(function ($chunk) {
            Label::insert($chunk->toArray());
        });
    }
}
