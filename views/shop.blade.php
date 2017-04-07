@extends('layouts.theme')
@section('title') Coupons for Best Discount @endsection
@section('sub_title') {{$title}} in the store, enjoy! @endsection
@section('page_title') {{$title}} @endsection
@section('filters_featured')
<div class="filters col-md-10 col-md-offset-2 ">
	<!-- shop-search -->
	<div class="col-md-5">
	@include('parts.shop_search')
	</div>
	<!-- .shop-search -->
	<!-- categories-dropdown-buton -->
	<div class="col-md-5">
	@include('parts.category_search')
	</div>
	<!-- .categories-dropdown-buton -->
</div>
<section class="shop-single">
<div class="container">
<div class="row">
<div class="col-md-12 ">
	<div class="caption category-caption ">
		<h2>{{$title}}</h2>
		<p></p>
		<div class="line-divider">
			<span class="line-mask green-bg"></span>
		</div>
	</div>
</div>
</div>
@if($shops->isEmpty())
	<h2 class="col-md-offset-5">No records found.</h2>
@endif
@foreach($shops as $shop)
@section('meta')  
<meta property="og:image" content="{{$shop->merchant_logo_url}}" />
<link rel="image_src" href="{{$shop->merchant_logo_url}}"/>
<meta name="twitter:image" content="{{$shop->merchant_logo_url}}" />
<meta name="title" content="{{$shop->title}}" />
<meta name="description" content="{{$shop->description}}" />
@endsection
<div class="featured-item-container col-md-3">
	<div class="featured-item">
		<div class="logotype logotype-no-padding">
			<div class="logotype-image">
				<div class="">
					<a href="" target="_blank"></a>
				</div>
									
				<img  style="max-height: 80px;" src="{{$shop->merchant_logo_url}}" class="attachment-daily_offer size-daily_offer wp-post-image" alt="Fotolia_12377076_XXL" />
			</div>
		</div>
		
		<a href="{{url('coupon/'.$shop->id)}}" class="btn btn-custom btn-shop btn-full blue-bg btn-top btn-default btn-lg">
		<small>Show offer & Coupon Code</small></a>
		<div class="featured-item-content" style="min-height: 150px;max-height: 150px;overflow-y: auto;">
			<h4><a href="javascript:;">{{$shop->title}}</a></h4>
			<p>{{$shop->description}} </p>
			
		</div>
		<div class="item-meta daily-meta">
			<ul class="list-inline list-unstyled">
				<li>
					<a href="javascript:;">
						<span class="fa fa-clock-o"></span>
						{{date('Y-m-d',strtotime($shop->start_date))}}
					</a>
				</li>
				<li>
					<a href="javascript:;">
						<span class="fa fa-tag"></span>{{$shop->type}}</a>
				</li>

				<li>
					<a href="javascript:;" class="favorite" data-id="{{$shop->id}}" title="Favorite">
					@if(isset($shop->favorites) && $shop->favorites->user_id == Auth::id() && $shop->id == $shop->favorites->coupon_id)
					<span class="fa fa-heart" style="color:red;"></span>
					@else
					<span class="fa fa-heart fv" ></span>
					@endif
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>
@endforeach
</div>
</section>
@if(!isset($top_20))
<div class="pagination-wrapper col-md-offset-4"> {!! $shops->links() !!} </div>
@endif
@endsection