<!--Footer-->

<!--Module top footer -->
  <?php if(isset($layouts['footer'])): ?>
      <?php $__currentLoopData = $layouts['footer']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($layout->page == null ||  $layout->page =='*' || $layout->page =='' || (isset($layout_page) && in_array($layout_page, $layout->page) ) ): ?>
          <?php if($layout->type =='html'): ?>
            <?php echo $layout->text; ?>

          <?php elseif($layout->type =='view'): ?>
            <?php if(view()->exists('blockView.'.$layout->text)): ?>
             <?php echo $__env->make('blockView.'.$layout->text, \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>
          <?php elseif($layout->type =='module'): ?>
            <?php echo (new $layout->text)->render(); ?>

          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endif; ?>
<!--//Module top footer -->

  <footer id="footer"><!--Footer-->
    <div class="footer-widget">
      <div class="container">
        <div class="row">
          <div class="col-sm-3">
            <div class="single-widget">
              <h2><a href="<?php echo e(route('home'), false); ?>"><img style="max-width: 150px;" src="<?php echo e(asset($logo), false); ?>"></a></h2>
             <ul class="nav nav-pills nav-stacked">
               <li><?php echo e($configsGlobal['title'], false); ?></li>
             </ul>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="single-widget">
              <h2><?php echo e(trans('language.my_account'), false); ?></h2>
              <ul class="nav nav-pills nav-stacked">
                <?php if(!empty($layoutsUrl['footer'])): ?>
                  <?php $__currentLoopData = $layoutsUrl['footer']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a <?php echo e(($url->target =='_blank')?'target=_blank':'', false); ?> href="<?php echo e(url($url->url), false); ?>"><?php echo e(trans($url->name), false); ?></a></li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
              </ul>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="single-widget">
              <h2><?php echo e(trans('language.about'), false); ?></h2>
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#"><?php echo e(trans('language.shop_info.address'), false); ?>: <?php echo e($configsGlobal['address'], false); ?></a></li>
                <li><a href="#"><?php echo e(trans('language.shop_info.hotline'), false); ?>: <?php echo e($configsGlobal['long_phone'], false); ?></a></li>
                <li><a href="#"><?php echo e(trans('language.shop_info.email'), false); ?>: <?php echo e($configsGlobal['email'], false); ?></a></li>
            </ul>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="single-widget">
              <h2><?php echo e(trans('language.subscribe.title'), false); ?></h2>
              <form action="<?php echo e(route('subscribe'), false); ?>" method="post" class="searchform">
                <?php echo csrf_field(); ?>

                <input type="email" name="subscribe_email" required="required" placeholder="<?php echo e(trans('language.subscribe.subscribe_email'), false); ?>">
                <button type="submit" class="btn btn-default"><i class="fa fa-arrow-circle-o-right"></i></button>
                <p><?php echo e(trans('language.subscribe.subscribe_des'), false); ?></p>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container">
        <div class="row">
          <p class="pull-left">Copyright © 2019 <a href="<?php echo e(config('scart.homepage'), false); ?>"><?php echo e(config('scart.name'), false); ?> <?php echo e(config('scart.version'), false); ?></a> Inc. All rights reserved.</p>
          <p class="pull-right">Hosted by  <span><a target="_blank" href="https://highcoder.com">highcoder</a></span></p>
            <!--
            S-Cart is free open source and you are free to remove the powered by S-cart if you want, but its generally accepted practise to make a small donation.
            Please donate via PayPal to https://www.paypal.me/LeLanh or Email: fastle.ktc@gmail.com
            //-->
        </div>
      </div>
    </div>
  </footer>
<!--//Footer-->
