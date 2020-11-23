  <header id="header"><!--header-->
    <div class="header_top"><!--header_top-->
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <div class="contactinfo">
              <ul class="nav nav-pills">
                <li><a href="#"><i class="fa fa-phone"></i> <?php echo e($configsGlobal['phone'], false); ?></a></li>
                <li><a href="#"><i class="fa fa-envelope"></i> <?php echo e($configsGlobal['email'], false); ?></a></li>
              </ul>
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

                <li><a href="<?php echo e(route('wishlist'), false); ?>"><span  class="cart-qty  shopping-wishlist" id="shopping-wishlist"><?php echo e(Cart::instance('wishlist')->count(), false); ?></span><i class="fa fa-star"></i> <?php echo e(trans('language.wishlist'), false); ?></a></li>
                <li><a href="<?php echo e(route('compare'), false); ?>"><span  class="cart-qty shopping-compare" id="shopping-compare"><?php echo e(Cart::instance('compare')->count(), false); ?></span><i class="fa fa-crosshairs"></i> <?php echo e(trans('language.compare'), false); ?></a></li>
                <li><a href="<?php echo e(route('cart'), false); ?>"><span class="cart-qty shopping-cart" id="shopping-cart"><?php echo e($carts['count'], false); ?></span><i class="fa fa-shopping-cart"></i> <?php echo e(trans('language.cart_title'), false); ?></a>
                </li>
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

              </ul>
            </div>
          </div>
        </div>
      </div>
    </div><!--/header-middle-->

    <div class="header-bottom"><!--header-bottom-->
      <div class="container">
        <div class="row">
          <div class="col-sm-9">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>
            <div class="mainmenu pull-left">
              <ul class="nav navbar-nav collapse navbar-collapse">
                <li><a href="<?php echo e(route('home'), false); ?>" class="active"><?php echo e(trans('language.home'), false); ?></a></li>
                <li class="dropdown"><a href="#"><?php echo e(trans('language.shop'), false); ?><i class="fa fa-angle-down"></i></a>
                    <ul role="menu" class="sub-menu">
                        <?php $__currentLoopData = $Activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li> <a href="#"><?php echo e($activity->type, false); ?></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <li><a href="<?php echo e(route('products'), false); ?>"><?php echo e(trans('language.all_product'), false); ?></a></li> 
                 <!--       <li><a href="<?php echo e(route('cart'), false); ?>"><?php echo e(trans('language.cart_title'), false); ?></a></li>-->
                        <li><a href="<?php echo e(route('brands'), false); ?>"><?php echo e(trans('language.brands'), false); ?></a></li>
                        <li><a href="<?php echo e(route('vendors'), false); ?>"><?php echo e(trans('language.vendors'), false); ?></a></li>
                    </ul>
                </li>
                <li><a href="<?php echo e(route('categories'), false); ?>"><?php echo e(trans('language.categories'), false); ?></a></li>
                <li><a href="<?php echo e(route('compare'), false); ?>"><?php echo e(trans('language.compare'), false); ?></a></li>   
                <?php if(!empty($configs['News'])): ?>
                <li><a href="<?php echo e(route('news'), false); ?>"><?php echo e(trans('language.blog'), false); ?></a></li>
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
          </div>
          <div class="col-sm-3">
            <div class="search_box pull-right">
              <form id="searchbox" method="get" action="<?php echo e(route('search'), false); ?>" >
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="<?php echo e(trans('language.search_form.keyword'), false); ?>..." name="keyword">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div><!--/header-bottom-->
  </header><!--/header-->
