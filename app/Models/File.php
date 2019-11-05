<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $name
 * @property string $mime
 * @property string $original_name
 * @property string $thumbnail
 * @property-read string $file_path
 */
class File extends Model
{

    const THUMBNAIL_WIDTH = 150;

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
    protected $appends = ['file_url', 'thumb_url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
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
     * File url
     *
     * @return string
     */
    function getFileUrlAttribute()
    {
        $entity = $this->entity()->withTrashed()->first();

        if (!empty($entity) && !is_null($this->name)) {
            $path = FileHelper::storagePath($entity) . $this->name;
            if (Storage::exists($path)) {
                return asset('storage/' . $entity->entity_type . '/' . $entity->id . '/' . $this->name);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * File path
     *
     * @return null|string
     */
    function getFilePathAttribute()
    {
        $entity = $this->entity()->withTrashed()->first();

        if (!empty($entity) && !is_null($this->name)) {
            $path = FileHelper::storagePath($entity) . $this->name;
            return Storage::exists($path) ? Storage::path($path) : null;
        }
    }

    /**
     * File thumbnail url
     *
     * @return string
     */
    function getThumbUrlAttribute()
    {
        $entity = $this->entity()->withTrashed()->first();

        if (!empty($entity) && !is_null($this->name)) {
            $thumbPath = FileHelper::storageThumbPath($entity) . $this->name;
            if (Storage::exists($thumbPath)) {
                return asset('storage/' . $entity->entity_type . '/' . $entity->id . '/thumbnails/' . $this->name);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

}
