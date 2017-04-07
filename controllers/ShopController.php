<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CouponCode;
use App\Merchant;
use Auth;

class ShopController extends Controller
{
    /**
     * Obtain the all shops data.
     *
     * @return view
     *
     */
    public function index()
    {
    	$shops = CouponCode::with('favoriteStores')->groupBy('merchant')->where('merchant','REGEXP','^[0-9]+')->get();
        $char  = "0-9";
        
    	return view('shops.index',compact('shops','char'));
    }
    /**
     * Search by keywords.
     *
     * @param $keyword
     *
     * @return view
     *
     */
    public function show($keyword)
    {
    	$search = str_replace('-', ' ', $keyword);
    	$shops  = CouponCode::like("$search")->with('category','favorites')->paginate(config('constants.SEARCH_PAGINATE'));
        if(!count($shops))
        {
            $shops  = CouponCode::like("$keyword")->with('category')->paginate(config('constants.SEARCH_PAGINATE'));
            $search = $keyword;
        }
        $title  = "Search Results";
    	return view('shops.search',compact('shops','search','title'));
    }
    /**
     * Get coupon from id.
     *
     * @param $id
     *
     * @return view
     *
     */

    public function getCoupon($id)
    {
        $coupon = CouponCode::where('id',$id)->with('category','favorites')->first();
        return view('shops.coupon_modal_body',compact('coupon'));
    }

    /**
     * Get top 20 offers.
     * 
     * @return view
     *
     */

    public function getTopOffers()
    {
        $shops  = CouponCode::with('category','favorites')->where('type','Coupon')->orderBy('updated_at','DESC')->limit(config('constants.TOP_PAGINATE'))->get();
        $title  = "Top 20 Offers";
        $top_20 = true;
        return view('shops.top_20',compact('shops','search','title','top_20'));
    }

    /**
     * Get daily offers.
     * 
     * @return view
     *
     */

    public function getDailyOffers()
    {
        $date   = date('Y-m-d');
        $shops  = CouponCode::with('category','favorites')->where('type','Offer')->where("start_date",'like',"%$date%")->paginate(config('constants.TOP_PAGINATE'));
        $title  = "Daily Offers";
        return view('shops.top_20',compact('shops','title'));
    }

    /**
     * Get offers which are expiring today.
     * 
     * @return view
     *
     */

    public function getExpiryOffers()
    {
        $date   = date('Y-m-d');
        $shops  = CouponCode::with('category','favorites')->where("expiry_date",'like',"%$date%")->paginate(config('constants.TOP_PAGINATE'));
        $title  = "Offers Expiring today";
        return view('shops.top_20',compact('shops','title'));
    }

    /**
     * Get coupons of particular shop.
     *
     * @param $id,$request
     *
     * @return view
     *
     */

    public function viewShop($id,Request $request)
    {   
        $url_name   = $id;
        $shops      = CouponCode::with('category','favorites')->where('url_name',$url_name)->paginate(8);
        $title      = "Search By Shop";
        
        if ($request->ajax()) {
            if(!empty($shops))
            $view   = view('top20data',compact('shops'))->render();
            return response()->json(['html'=>$view]);
        }
        $last_page  = $shops->toarray()['last_page'];

        return view('shops.search_shop',compact('shops','title','last_page'));
    }

     /**
     * Get coupon details.
     *
     * @param $id
     *
     * @return view
     *
     */

    public function detail($id)
    {
        $shop = CouponCode::with('category','favorites')->where('id',$id)->first();
        
        $title = "Coupon Detail";
        
        $star = ($shop!=null) ? $shop->userSumRating : 0;

        return view('shops.detail',compact('shop','title','star'));
    }

     /**
     * Save ratings
     *
     * @param $id,$star
     *
     * @return $result
     *
     */

    public function saveRating($id,$star)
    {
        if(!Auth::user())
        {
            $result['success'] = '0';
        }
        else
        {
            $coupon = CouponCode::where('id',$id)->first();
            $rating = new \willvincent\Rateable\Rating;
            $rating->rating = (int)$star;
            $rating->user_id = \Auth::id();
            $count = $rating->where('user_id',\Auth::id())->where('rateable_id',$id)->count();
            if($count == 0)
                $coupon->ratings()->save($rating);
            else
                $coupon->ratings()->update(['rating'=>$star]);
            $result['success'] = '1';
        }
        return $result;
    }

     /**
     * Get shops by alphabets
     *
     * @param $char
     *
     * @return view
     *
     */
    public function shopFilter($char)
    {
        if($char == 'number')
        {
            $shops = CouponCode::with('favoriteStores')->groupBy('merchant')->where('merchant','REGEXP','^[0-9]+')->get();
            $char = "0-9";
        }
        else
        {
            $shops = CouponCode::with('favoriteStores')->groupBy('merchant')->where('merchant','like',"$char%")->get();
        }
        
        return view('parts.shop_filters',compact('shops','char'));
    }
}
