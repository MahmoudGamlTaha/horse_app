  <style>
  @import  url(http://fonts.googleapis.com/css?family=Open+Sans:400,700);

.navbar-nav>li>.dropdown-menu {
  margin-top: 20px;
  border-top-left-radius: 4px;
  border-top-right-radius: 4px;
}
.navbar-default .navbar-nav>li>a {
  font-weight: bold;
}

.mega-dropdown {
  position: static !important;
}

.mega-dropdown-menu {
  padding: 20px 0px;
  width: 100%;
  box-shadow: none;
  -webkit-box-shadow: none;
}

.mega-dropdown-menu:before {
  content: "";
  border-bottom: 15px solid #fff;
  border-right: 17px solid transparent;
  border-left: 17px solid transparent;
  position: absolute;
  top: -15px;
  right: 10%;
  z-index: 10;
}

.mega-dropdown-menu:after {
  content: "";
  border-bottom: 17px solid #ccc;
  border-right: 19px solid transparent;
  border-left: 19px solid transparent;
  position: absolute;
  top: -17px;
  right: 10%;
  z-index: 8;
}

.mega-dropdown-menu > li > ul {
  padding: 0;
  margin: 0;
}

.mega-dropdown-menu > li > ul > li {
  list-style: none;
}

.mega-dropdown-menu > li > ul > li > a {
  display: block;
  padding: 3px 20px;
  clear: both;
  font-weight: normal;
  line-height: 1.428571429;
  color: #999;
  white-space: normal;
}

.mega-dropdown-menu > li ul > li > a:hover,
.mega-dropdown-menu > li ul > li > a:focus {
  text-decoration: none;
  color: #444;
  background-color: #f5f5f5;
}
.top-head-backcolor{
  background: none repeat scroll 0 0 black !important;
}
.top-head-forecolor{
  color: white !important;
}
.has-search {
  position: relative;
  width: 100% !important;
  padding-left: 2.375rem;
  left: 54%;
  top:-30px;
  margin-bottom: -60px;
}
.radius22{
  border-radius: 22px !important;
}
.zindex2{
  position: relative;
  z-index: 2;
  right: 88%;
  top:-36px;
  margin-top: 2px;
  background-color: #ffb52ce6;
  border-bottom-left-radius: 22px !important;
  border-top-left-radius: 22px !important;
  width: 12%;
  text-align: center;
}
.mega-dropdown-menu .dropdown-header {
  color: #428bca;
  font-size: 18px;
  font-weight: bold;
}

.mega-dropdown-menu form {
  margin: 3px 20px;
}

.mega-dropdown-menu .form-group {
  margin-bottom: 3px;
}
  </style>
  <header id="header"><!--header-->
    <div class="header_top top-head-backcolor"><!--header_top-->
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <div class="contactinfo">
              <ul class="nav nav-pills">
              <li><a href="<?php echo e(route('wishlist'), false); ?>"><span  class="cart-qty shopping-wishlist top-head-forecolor" id="shopping-wishlist"><?php echo e(Cart::instance('wishlist')->count(), false); ?></span><i class="glyphicon glyphicon-heart"></i> <?php echo e(trans('language.wishlist'), false); ?></a></li>   
            <!--  <li><a href="<?php echo e(route('compare'), false); ?>"><span  class="cart-qty shopping-compare" id="shopping-compare"><?php echo e(Cart::instance('compare')->count(), false); ?></span><i class="fa fa-crosshairs"></i> <?php echo e(trans('language.compare'), false); ?></a></li> -->
              <li><a href="<?php echo e(route('cart'), false); ?>"><span class="cart-qty shopping-cart" id="shopping-cart"><?php echo e($carts['count'], false); ?></span><i class="fa fa-shopping-cart"></i> <?php echo e(trans('language.cart_title'), false); ?></a>
              </ul>     
            </div>
            <div class="has-search">
 <!-- Another variation with a button -->
                   <input type="text" class="form-control radius22" placeholder="Search">
                      
                        <button class="btn btn-secondary zindex2" type="button">
                             <i class="fa fa-search"></i>
                        </button>
                   </div>
          </div>
          <div class="col-sm-6">
            <div class="btn-group pull-right">
              <div class="btn-group locale">
                <?php if(count($languages)>1): ?>
                <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown"><img src="<?php echo e(asset($path_file.'/'.$languages[app()->getLocale()]['icon']), false); ?>" style="height: 25px;">
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="<?php echo e(url('locale/'.$key), false); ?>"><img src="<?php echo e(asset($path_file.'/'.$language['icon']), false); ?>" style="height: 25px;"></a></li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <?php endif; ?>
              </div>
              <?php if(count($currencies)>1): ?>
               <div class="btn-group locale">
                <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">
                  <?php echo e(\Helper::getCurrency()['name'], false); ?>

                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="<?php echo e(url('currency/'.$currency->code), false); ?>"><?php echo e($currency->name, false); ?></a></li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div><!--/header_top-->
    <div class="header-middle"><!--header-middle-->
      <div class="container">
        <div class="row">
          <div class="col-sm-4">
            <div class="logo pull-left">
              <a href="<?php echo e(route('home'), false); ?>"><img style="width: 150px;" src="<?php echo e(asset($logo), false); ?>" alt="" /></a>
            </div>
          </div>
          <div class="col-sm-8">
            <div class="shop-menu pull-right">
			
              <ul class="nav navbar-nav">
             <!--   <li><a href="<?php echo e(route('compare'), false); ?>"><span  class="cart-qty shopping-compare" id="shopping-compare"><?php echo e(Cart::instance('compare')->count(), false); ?></span><i class="fa fa-crosshairs"></i> <?php echo e(trans('language.compare'), false); ?></a></li>
                <li><a href="<?php echo e(route('cart'), false); ?>"><span class="cart-qty shopping-cart" id="shopping-cart"><?php echo e($carts['count'], false); ?></span><i class="fa fa-shopping-cart"></i> <?php echo e(trans('language.cart_title'), false); ?></a> -->
                </li>
               

              </ul>
            </div>
          </div>
        </div>
      </div>
    </div><!--/header-middle-->

    <div class=""><!--header-bottom-->
      <div class="container">
  <nav class="navbar navbar-default">
    <div class="navbar-header">
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo e(route('home'), false); ?>"><?php echo e(trans('language.home'), false); ?></a>
    </div>


    <div class="collapse navbar-collapse js-navbar-collapse">
      <ul class="nav navbar-nav">
      <li class="dropdown mega-dropdown">
          <a href="#" class="dropdown-toggle orange-bk" data-toggle="dropdown"><?php echo e(trans('language.shops'), false); ?> <span class="glyphicon glyphicon-align-justify pull-right"></span></a>
          <ul class="dropdown-menu mega-dropdown-menu row">
            <li class="col-sm-3">
              <ul>
                <li class="dropdown-header">New in Stores</li>
                
                <!-- /.carousel -->
                <li class="divider"></li>
                <li><a href="#">View all Collection <span class="glyphicon glyphicon-chevron-right pull-right"></span></a></li>
              </ul>
            </li>
			 <?php $__currentLoopData = $Activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			 <li class="col-sm-3">
			   <ul>
			     <li class="dropdown-header"><?php echo e($activity->type, false); ?></li>
				     <?php $__currentLoopData = $Companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			      	 <?php if($activity->id==$company->activity_id): ?>
                        <li> <a href="#"><?php echo e($company->name, false); ?></a></li>
					<?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				 </ul>
						</li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						
            <li class="col-sm-3">
              <ul>
                <li class="dropdown-header">Other</li>
                <li><a href="<?php echo e(route('products'), false); ?>"><?php echo e(trans('language.all_product'), false); ?></a></li> 
                 <!--       <li><a href="<?php echo e(route('cart'), false); ?>"><?php echo e(trans('language.cart_title'), false); ?></a></li>-->
                        <li><a href="<?php echo e(route('brands'), false); ?>"><?php echo e(trans('language.brands'), false); ?></a></li>
                        <li><a href="<?php echo e(route('vendors'), false); ?>"><?php echo e(trans('language.vendors'), false); ?></a></li>
               
              </ul>
            </li>
                    </ul>
        </li>
	
      <li class="dropdown">
		<a href="<?php echo e(route('categories'), false); ?>"><?php echo e(trans('language.categories'), false); ?></a>
		</li>
      <!--   <li class="dropdown"><a href="<?php echo e(route('compare'), false); ?>"><?php echo e(trans('language.compare'), false); ?></a></li> -->
         <?php if(auth()->guard()->guest()): ?>
                <li><a href="<?php echo e(route('login'), false); ?>"><i class="fa fa-lock"></i> <?php echo e(trans('language.login'), false); ?></a></li>
                <?php else: ?>
                <li><a href="<?php echo e(route('profile'), false); ?>"><i class="fa fa-user"></i> <?php echo e(trans('language.account'), false); ?></a></li>
                <li><a href="<?php echo e(route('logout'), false); ?>" rel="nofollow" onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> <?php echo e(trans('language.logout'), false); ?></a></li>
                <form id="logout-form" action="<?php echo e(route('logout'), false); ?>" method="POST" style="display: none;">
                <?php echo e(csrf_field(), false); ?>

                </form>
                <?php endif; ?>   
                <?php if(!empty($configs['News'])): ?>
                <li class="dropdown"><a href="<?php echo e(route('news'), false); ?>"><?php echo e(trans('language.blog'), false); ?></a></li>
                <?php endif; ?>
         <?php if(!empty($configs['Content'])): ?>
                <li class="dropdown"><a href="#"><?php echo e(trans('language.cms_category'), false); ?><i class="fa fa-angle-down"></i></a>
                    <ul role="menu" class="sub-menu">
                      <?php
                        $cmsCategories = (new \App\Modules\Cms\Models\CmsCategory)->where('status',1)->get();
                      ?>
                      <?php $__currentLoopData = $cmsCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cmsCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><a href="<?php echo e($cmsCategory->getUrl(), false); ?>"><?php echo e($cmsCategory->title, false); ?></a></li>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </li>
                <?php endif; ?>
              
                  <?php if(!empty($layoutsUrl['menu'])): ?>
                    <?php $__currentLoopData = $layoutsUrl['menu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <li><a <?php echo e(($url->target =='_blank')?'target=_blank':'', false); ?> href="<?php echo e(url($url->url), false); ?>"><?php echo e(trans($url->name), false); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php endif; ?>
        
      </ul>

    </div>
    <!-- /.nav-collapse -->
  </nav>
      </div>
    </div><!--/header-bottom-->
  </header><!--/header-->
