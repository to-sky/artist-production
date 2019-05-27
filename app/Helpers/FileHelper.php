<?php

namespace App\Helpers;

use App\Models\File;
use Illuminate\Support\Str;
use League\Flysystem\AdapterInterface;
use Illuminate\Support\Facades\Storage;

/**
 * Class FileHelper
 * @package App\Helpers
 */

class FileHelper
{
    /**
     * Supported types
     */
    const MIME_TYPES = [
        'application/pdf' => 'pdf',
        'image/png' => 'png',
        'image/jpg' => 'jpg',
        'image/jpeg' => 'jpeg',
        'audio/ogg' => 'ogg'
    ];

    /**
     * Decode encoded data with mime info
     *
     * @param $data
     * @return bool|string
     */
    public static function decode_base64($data)
    {
        list($type, $data) = explode(';', $data);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);
        return $data;
    }

    /**
     * Parse encoded data with mime on mime, extension & data
     *
     * @param $data
     * @return array
     */
    public static function parse_encoded_base64($data, $decode = false)
    {
        list($type, $data) = explode(';', $data);
        list(, $mime) = explode(':', $type);
        list(, $data) = explode(',', $data);

        $data = $decode ? base64_decode($data) : $data;

        return [
            'mime' => $mime,
            'extension' => self::MIME_TYPES[$mime] ?? '',
            'data' => $data
        ];
    }

    /**
     * Return encode data with mime info
     *
     * @param $data
     * @return string
     */
    public static function encode_base64($data, $mime)
    {
        return 'data:' . $mime . ';base64,' . base64_encode($data);
    }

    /**
     * Gets a random filename for the file.
     *
     * @param  string  $extension
     * @return string
     */
    public static function hashName($extension)
    {
        $hash = Str::random(40);

        return $hash.'.'.$extension;
    }

    /**
     *  Generates storage path for file
     *
     * @param $entity
     * @param string $visibility
     * @return string
     */
    public static function storagePath($entity, $visibility = AdapterInterface::VISIBILITY_PUBLIC)
    {
        return "$visibility/{$entity->entity_type}/{$entity->id}/";
    }

    public static function storageThumbPath($entity)
    {
        return static::storagePath($entity) . 'thumbnails/';
    }

    /**
     * Returns entity name.
     *
     * @param $entity
     * @return string
     */
    public static function entityName($entity)
    {
        return class_basename($entity);
    }

    /**
     * Gets full file name from its path
     *
     * @param $path
     * @return string
     */
    public static function name($path)
    {
        return pathinfo($path, PATHINFO_FILENAME) . '.' . pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * Removes file from disk
     *
     * @param File $file
     */
    public static function delete(File $file)
    {
        $path = static::storagePath($file->entity) . $file->name;

        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }

    /**
     * Removes image thumbnail from disk
     *
     * @param File $file
     */
    public static function deleteThumb(File $file)
    {
        $thumPath = static::storageThumbPath($file->entity) . $file->name;

        if (Storage::exists($thumPath)) {
            Storage::delete($thumPath);
        }
    }

    /**
     * Returns the maximum number of files can be added to a entity.
     *
     * @param $entity
     * @return int maximum number of images to add
     */
    public static function maxFilesAllowed($entity)
    {
        $class = get_class($entity);

        $diff = $class::MAX_FILES - $entity->files()->count();

        return $diff >= 0 ? $diff : 0;
    }
}
