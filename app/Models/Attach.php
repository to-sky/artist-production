<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $files_id
 * @property int $entity_id
 * @property string $entity
 * @property File $file
 * @property Dish[] $dishes
 */
class Attach extends Model
{

    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['files_id', 'entity_id', 'entity'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo('App\File', 'files_id');
    }
}
