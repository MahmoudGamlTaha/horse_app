  <?php
    $products_special = (new \App\Models\ShopProduct)->getProductsSpecial($limit = 1, $random = true)
  ?>
  <?php if(!empty($products_special)): ?>
              <div class="brands_products"><!--product special-->
                <h2><?php echo e(trans('language.products_special'), false); ?></h2>
                <div class="products-name">
                  <ul class="nav nav-pills nav-stacked">
                    <?php $__currentLoopData = $products_special; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product_special): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <li>
                        <div class="product-image-wrapper product-single">
                          <div class="single-products product-box-<?php echo e($key, false); ?>">
                              <div class="productinfo text-center">
                                <a href="<?php echo e($product_special->product->getUrl(), false); ?>"><img src="<?php echo e(asset($product_special->product->getThumb()), false); ?>" alt="<?php echo e($product_special->product->name, false); ?>" /></a>
                                <?php echo $product_special->product->showPrice(); ?>

                                <a href="<?php echo e($product_special->product->getUrl(), false); ?>"><p><?php echo e($product_special->product->name, false); ?></p></a>
                              </div>
                          <?php if($product_special->product->price != $product_special->product->getPrice()): ?>
                          <img src="<?php echo e(asset($theme_asset.'/images/home/sale.png'), false); ?>" class="new" alt="" />
                          <?php elseif($product_special->product->type == 1): ?>
                          <img src="<?php echo e(asset($theme_asset.'/images/home/new.png'), false); ?>" class="new" alt="" />
                          <?php endif; ?>
                          </div>
                        </div>
                      </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </ul>
                </div>
              </div><!--/product special-->
  <?php endif; ?>
