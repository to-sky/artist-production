<?php

namespace App\Helpers;


use App\Models\Price as PriceModel;
use App\Models\PriceGroup;

class PriceHelper
{
    /**
     * Get resulting price for Price with $price_id and PriceGroup with $group_id
     *
     * @param int $price_id
     * @param int|null $group_id
     * @return float
     */
    public static function getPriceWithGroup($price_id, $group_id = null)
    {
        return self::getPriceById($price_id) / (1 - self::getPriceGroupDiscountById($group_id));
    }

    /**
     * Returns price value of Price with $price_id
     *
     * @param int $price_id
     * @return float
     */
    public static function getPriceById($price_id)
    {
        return PriceModel::whereId($price_id)->value('price') ?: 0;
    }

    /**
     * @param int|null $price_group_id
     * @return float
     */
    public static function getPriceGroupDiscountById($price_group_id)
    {
        return PriceGroup::whereId($price_group_id)->value('discount') ?: 0;
    }
}