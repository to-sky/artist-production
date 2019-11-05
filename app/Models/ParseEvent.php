<?php

namespace App\Models;

use App\Events\ParseEventSaved;
use Illuminate\Database\Eloquent\Model;

class ParseEvent extends Model
{
    protected $fillable = ['kartina_id', 'is_parsed'];

    public $timestamps = false;

    protected $dispatchesEvents = [
        'created' => ParseEventSaved::class
    ];

    /**
     * Set status to parsed
     *
     * @param $kartinaId
     * @return $this
     */
    public static function parsed($kartinaId)
    {
        return self::where('kartina_id', $kartinaId)
            ->update(['is_parsed' => true]);
    }


    /**
     * Get not parsed events
     *
     * @return mixed
     */
    public static function getNotParsedEvents()
    {
        return self::where('is_parsed', false)->get();
    }
}
