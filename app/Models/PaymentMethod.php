<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property boolean $active
 * @property string $created_at
 * @property string $updated_at
 */
class PaymentMethod extends Model
{

    const NOT_ACTIVE = 0;
    const ACTIVE = 1;

    /**
     * @var array
     */
    protected $fillable = ['name', 'active', 'created_at', 'updated_at'];

}
