<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{

    use SoftDeletes;

    const TYPE_INDIVIDUAL = 0;
    const TYPE_DISTRIBUTOR = 1;

    const DEFAULT_COMMISSION = 10;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'profiles';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'street',
        'house',
        'city',
        'country_id',
        'post_code',
        'commission',
        'type',
        'code',
        'comment',
        'user_id'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['fullname', 'types'];


    public static function boot()
    {
        parent::boot();

        Profile::observe(new UserActionsObserver);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany('App\Models\Address');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getTypesAttribute()
    {
        return static::getTypes();
    }

    public function getTypeLabelAttribute()
    {
        return self::getTypeLabel($this->type);
    }

    public static function getTypes()
    {
        return [
            static::TYPE_INDIVIDUAL => static::getTypeLabel(static::TYPE_INDIVIDUAL),
            static::TYPE_DISTRIBUTOR => static::getTypeLabel(static::TYPE_DISTRIBUTOR)
        ];
    }

    public static function getTypeLabel($type)
    {
        switch ($type) {
            case static::TYPE_INDIVIDUAL:
                return __('Individual');
                break;
            case static::TYPE_DISTRIBUTOR:
                return __('Distributor');
                break;
        }
    }

    /**
     * Get type attribute
     *
     * @return array|string|null
     */
    public function getTypeLabelAttribute()
    {
        return self::getTypeLabel($this->type);
    }
}
