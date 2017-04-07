<?php

namespace App;

use willvincent\Rateable\Rateable;
use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model 
{   
    /* use of Rateable for giving ratings */
    use Rateable;

    /**
     * Couponcode belongsto category.
     *
     * @return  relation
     */
	public function category()
	{
		return $this->belongsTo(Category::class,'category_id','id');
	}
    
    /**
     * Couponcode belongsto favorites.
     *
     * @return  relation
     */
	public function favorites()
    {
        return $this->belongsTo(FavoriteCoupon::class,'id','coupon_id');
    }

    /**
     * Couponcode belongsto favoriteStores.
     *
     * @return  relation
     */
    public function favoriteStores()
    {
        return $this->belongsTo(FavoriteStore::class,'mid','store_id');
    }

    /**
     * Add scope for like query
     *
     * @param $query,$value
     *
     * @return  scope
     */
    public function scopeLike($query, $value)
    {
        return $query->Where('title', 'LIKE', "%$value%")->orWhere('description', 'LIKE', "%$value%")->orWhere('merchant', 'LIKE', "%$value%")->orWhere('network', 'LIKE', "%$value%");
    }

}
