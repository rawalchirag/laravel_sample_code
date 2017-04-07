<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone_no','logo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * User belongsto favoritecoupon.
     *
     * @return  relation
     */
    public function favorites()
    {
        return $this->belongsTo(FavoriteCoupon::class,'user_id','id');
    }

    /**
     * User belongsto favoriteStores.
     *
     * @return  relation
     */
    public function favoriteStores()
    {
        return $this->belongsTo(FavoriteStore::class,'user_id','id');
    }

    /**
     * User belongsto favoriteCategories.
     *
     * @return  relation
     */
    public function favoriteCategories()
    {
        return $this->belongsTo(FavoriteCategory::class,'user_id','id');
    }

    /**
     * User has many comments.
     *
     * @return  relation
     */
    public function comments()
    {
        return $this->hasMany(Comment::class,'id','user_id');
    }

}
