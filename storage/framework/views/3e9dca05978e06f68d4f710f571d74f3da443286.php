<?php $__env->startSection('center'); ?>
          <div class="features_items"><!--features_items-->
            <h2 class="title text-center"><?php echo e(trans('language.features_items'), false); ?></h2>
                <?php $__currentLoopData = $products_new; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product_new): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="col-sm-4">
                    <div class="product-image-wrapper product-single">
                      <div class="single-products product-box-<?php echo e($product_new->id, false); ?>">
                          <div class="productinfo text-center">
                            <a href="<?php echo e($product_new->getUrl(), false); ?>"><img src="<?php echo e(asset($product_new->getThumb()), false); ?>" alt="<?php echo e($product_new->name, false); ?>" /></a>
                            <?php echo $product_new->showPrice(); ?>

                            <a href="<?php echo e($product_new->getUrl(), false); ?>"><p><?php echo e($product_new->name, false); ?></p></a>
                            <a class="btn btn-default add-to-cart" onClick="addToCart('<?php echo e($product_new->id, false); ?>','default',$(this))"><i class="fa fa-shopping-cart"></i><?php echo e(trans('language.add_to_cart'), false); ?></a>
                          </div>
                      <?php if($product_new->price != $product_new->getPrice()): ?>
                      <img src="<?php echo e(asset($theme_asset.'/images/home/sale.png'), false); ?>" class="new" alt="" />
                      <?php elseif($product_new->type == 1): ?>
                      <img src="<?php echo e(asset($theme_asset.'/images/home/new.png'), false); ?>" class="new" alt="" />
                      <?php endif; ?>
                      </div>
                      <div class="choose">
                        <ul class="nav nav-pills nav-justified">
                          <li><a onClick="addToCart('<?php echo e($product_new->id, false); ?>','wishlist',$(this))"><i class="fa fa-plus-square"></i><?php echo e(trans('language.add_to_wishlist'), false); ?></a></li>
                          <li><a onClick="addToCart('<?php echo e($product_new->id, false); ?>','compare',$(this))"><i class="fa fa-plus-square"></i><?php echo e(trans('language.add_to_compare'), false); ?></a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div><!--features_items-->

          <div class="recommended_items"><!--recommended_items-->
            <h2 class="title text-center"><?php echo e(trans('language.products_hot'), false); ?></h2>

            <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
              <div class="carousel-inner">
                <?php $__currentLoopData = $products_hot; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product_hot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($key % 3 == 0): ?>
                  <div class="item <?php echo e(($key ==0)?'active':'', false); ?>">
                <?php endif; ?>
                  <div class="col-sm-4">
                    <div class="product-image-wrapper product-single">
                      <div class="single-products   product-box-<?php echo e($product_hot->id, false); ?>">
                          <div class="productinfo text-center">
                            <a href="<?php echo e($product_hot->getUrl(), false); ?>"><img src="<?php echo e(asset($product_hot->getThumb()), false); ?>" alt="<?php echo e($product_hot->name, false); ?>" /></a>
                            <?php echo $product_hot->showPrice(); ?>

                            <a href="<?php echo e($product_hot->getUrl(), false); ?>"><p><?php echo e($product_hot->name, false); ?></p></a>
                            <a class="btn btn-default add-to-cart" onClick="addToCart('<?php echo e($product_hot->id, false); ?>','default',$(this))"><i class="fa fa-shopping-cart"></i><?php echo e(trans('language.add_to_cart'), false); ?></a>
                          </div>
                          <?php if($product_hot->price != $product_hot->getPrice()): ?>
                          <img src="<?php echo e(asset($theme_asset.'/images/home/sale.png'), false); ?>" class="new" alt="" />
                          <?php elseif($product_hot->type == 1): ?>
                          <img src="<?php echo e(asset($theme_asset.'/images/home/new.png'), false); ?>" class="new" alt="" />
                          <?php endif; ?>
                      </div>
                      <div class="choose">
                        <ul class="nav nav-pills nav-justified">
                          <li><a onClick="addToCart('<?php echo e($product_hot->id, false); ?>','wishlist',$(this))"><i class="fa fa-plus-square"></i><?php echo e(trans('language.add_to_wishlist'), false); ?></a></li>
                          <li><a onClick="addToCart('<?php echo e($product_hot->id, false); ?>','compare',$(this))"><i class="fa fa-plus-square"></i><?php echo e(trans('language.add_to_compare'), false); ?></a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                <?php if($key % 3 == 2 || $key+1 == $products_hot->count()): ?>
                  </div>
                <?php endif; ?>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

              </div>
               <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                <i class="fa fa-angle-left"></i>
                </a>
                <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                <i class="fa fa-angle-right"></i>
                </a>
            </div>
          </div><!--/recommended_items-->

          <div class="category-tab"><!--category-tab-->
            <div class="col-sm-12">
              <ul class="nav nav-tabs">
                <?php $__currentLoopData = $categories[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <li <?php echo e(($key ==0)?'class="active"':'', false); ?>><a href="#cate<?php echo e($key, false); ?>" data-toggle="tab"><?php echo e($category->name, false); ?></a></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </ul>
            </div>
            <div class="tab-content">
              <?php $__currentLoopData = $categories[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="tab-pane fade <?php echo e(($key ==0)?'active in':'', false); ?>" id="cate<?php echo e($key, false); ?>" >
                  <?php $__currentLoopData = $category->getProductsToCategory($category->id,4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-sm-3">
                      <div class="product-image-wrapper product-single">
                        <div class="single-products  product-box-<?php echo e($product->id, false); ?>">
                          <div class="productinfo text-center">
                            <a href="<?php echo e($product->getUrl(), false); ?>"><img src="<?php echo e(asset($product->getThumb()), false); ?>" alt="<?php echo e($product->name, false); ?>" /></a>
                            <?php echo $product->showPrice(); ?>

                            <a href="<?php echo e($product->getUrl(), false); ?>"><p><?php echo e($product->name, false); ?></p></a>
                            <a class="btn btn-default add-to-cart" onClick="addToCart('<?php echo e($product->id, false); ?>','default',$(this))"><i class="fa fa-shopping-cart"></i><?php echo e(trans('language.add_to_cart'), false); ?></a>
                          </div>
                          <?php if($product->price != $product->getPrice()): ?>
                          <img src="<?php echo e(asset($theme_asset.'/images/home/sale.png'), false); ?>" class="new" alt="" />
                          <?php elseif($product->type == 1): ?>
                          <img src="<?php echo e(asset($theme_asset.'/images/home/new.png'), false); ?>" class="new" alt="" />
                          <?php endif; ?>

                        </div>
                      </div>
                    </div>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div><!--/category-tab-->


<?php $__env->stopSection(); ?>



<?php $__env->startPush('styles'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'.shop_layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>