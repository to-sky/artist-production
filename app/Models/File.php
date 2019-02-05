<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\FileHelper;

/**
 * @property int $id
 * @property string $name
 * @property string $mime
 * @property string $original_name
 * @property string $thumbnail
 */
class File extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'mime', 'original_name', 'thumbnail'];

    protected $hidden = ['entity_id', 'entity_type'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['file_url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attach()
    {
        return $this->hasOne('App\Models\Attach', 'files_id');
    }

    public function entity()
    {
        return $this->morphTo();
    }

    /**
     * File storage path
     *
     * @return string
     */
    function getFileUrlAttribute()
    {
        $entity = $this->entity()->withTrashed()->first();

        if (!empty($entity)) {
            return asset('storage/' . $entity->entity_type . '/' . $entity->id . '/' . $this->name);
        } else {
            return null;
        }
    }

}
