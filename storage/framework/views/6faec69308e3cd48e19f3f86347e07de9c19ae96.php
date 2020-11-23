<?php $__env->startSection('center'); ?>
          <div class="product-details"><!--product-details-->
            <div class="col-sm-5">


              <div id="similar-product" class="carousel slide" data-ride="carousel">
                  <!-- Wrapper for slides -->
                  <div class="carousel-inner">

                  <div id="similar-product" class="carousel slide" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                      <div class="view-product item active"  data-slide-number="0">
                        <img src="<?php echo e(asset($product->getImage()), false); ?>" alt="">
                      </div>
                    <?php if($product->images->count()): ?>
                       <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="view-product item"  data-slide-number="<?php echo e($key + 1, false); ?>">
                          <img src="<?php echo e(asset($image->getImage()), false); ?>" alt="">
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </div>
                  </div>
            <?php if($product->images->count()): ?>
                  <!-- Controls -->
                  <a class="left item-control" href="#similar-product" data-slide="prev">
                  <i class="fa fa-angle-left"></i>
                  </a>
                  <a class="right item-control" href="#similar-product" data-slide="next">
                  <i class="fa fa-angle-right"></i>
                  </a>
              <?php endif; ?>
                  </div>
              </div>

            </div>

        <form id="buy_block" action="<?php echo e(route('postCart'), false); ?>" method="post">
          <?php echo e(csrf_field(), false); ?>

          <input type="hidden" name="product_id" value="<?php echo e($product->id, false); ?>" />
            <div class="col-sm-7">
              <div class="product-information"><!--/product-information-->
                <?php if($product->price != $product->getPrice()): ?>
                <img src="<?php echo e(asset($theme_asset.'/images/home/sale2.png'), false); ?>" class="newarrival" alt="" />
                <?php elseif($product->type == 1): ?>
                <img src="<?php echo e(asset($theme_asset.'/images/home/new2.png'), false); ?>" class="newarrival" alt="" />
                <?php endif; ?>
                <h2><?php echo e($product->name, false); ?></h2>
                <p>SKU: <?php echo e($product->sku, false); ?></p>
                  <?php echo $product->showPrice(); ?>

                <span>
                  <label><?php echo e(trans('language.product.quantity'), false); ?>:</label>
                  <input type="number" name="qty" value="1" />
                  <button type="submit" class="btn btn-fefault cart">
                    <i class="fa fa-shopping-cart"></i>
                    <?php echo e(trans('language.add_to_cart'), false); ?>

                  </button>
                </span>
                <?php if($product->attGroupBy()): ?>
                <div class="form-group">
                  <?php $__currentLoopData = $product->attGroupBy(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyAtt => $attributes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($attributesGroup[$keyAtt]['type'] =='select'): ?>
                    <div class="input-group">
                      <label><?php echo e($attributesGroup[$keyAtt]['name'], false); ?>:</label>
                       <select class="form-control" style="max-width: 100px;" name="attribute[<?php echo e($keyAtt, false); ?>]">
                        <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($attribute->name, false); ?>" <?php echo e(($k ==0)?'selected':'', false); ?>> <?php echo e($attribute->name, false); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </select>
                    </div>
                    <?php elseif($attributesGroup[$keyAtt]['type'] =='radio'): ?>
                     <div class="input-group">
                      <label><?php echo e($attributesGroup[$keyAtt]['name'], false); ?>:</label><br>
                      <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="radio-inline"><input type="radio" name="attribute[<?php echo e($keyAtt, false); ?>]" value="<?php echo e($attribute->name, false); ?>" <?php echo e(($k ==0)?'checked':'', false); ?>> <?php echo e($attribute->name, false); ?></label>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
                <p><b><?php echo e(trans('language.product.availability'), false); ?>:</b>
                <?php if($configs['show_date_available'] && $product->date_available >= date('Y-m-d H:i:s')): ?>
                <?php echo e($product->date_available, false); ?>

                <?php else: ?>
                <?php echo e(trans('language.product.in_stock'), false); ?>

                <?php endif; ?>
              </p>
                <p><b><?php echo e(trans('language.product.type'), false); ?>:</b> New</p>
                <p><b><?php echo e(trans('language.product.brand'), false); ?>:</b> <?php echo e(empty($product->brand->name)?'None':$product->brand->name, false); ?></p>
                <div class="short-description">
                  <b><?php echo e(trans('language.product.overview'), false); ?>:</b>
                  <p><?php echo e($product->description, false); ?></p>
                </div>
              <div class="addthis_inline_share_toolbox_yprn"></div>
              </div><!--/product-information-->
            </div>
          </div><!--/product-details-->
        </form>

          <div class="category-tab shop-details-tab"><!--category-tab-->
            <div class="col-sm-12">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#details" data-toggle="tab"><?php echo e(trans('language.product.description'), false); ?></a></li>
                <li><a href="#reviews" data-toggle="tab"><?php echo e(trans('language.product.comment'), false); ?></a></li>
              </ul>
            </div>
            <div class="tab-content">
              <div class="tab-pane fade  active in" id="details" >
                <?php echo $product->content; ?>

              </div>

              <div class="tab-pane fade" id="reviews" >
                <div class="col-sm-12">
<div class="fb-comments" data-href="<?php echo e($product->getUrl(), false); ?>" data-numposts="5"></div>
                </div>
              </div>

            </div>
          </div><!--/category-tab-->

          <div class="recommended_items"><!--recommended_items-->
            <h2 class="title text-center"><?php echo e(trans('language.recommended_items'), false); ?></h2>

            <div id="recommended-item-carousel" class="carousel slide">
              <div class="carousel-inner">
               <?php $__currentLoopData = $productsToCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product_rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($key % 4 == 0): ?>
                  <div class="item <?php echo e(($key ==0)?'active':'', false); ?>">
                <?php endif; ?>
                  <div class="col-sm-3">
                    <div class="product-image-wrapper product-single">
                      <div class="single-products   product-box-<?php echo e($product_rel->id, false); ?>">
                          <div class="productinfo text-center">
                            <a href="<?php echo e($product_rel->getUrl(), false); ?>"><img src="<?php echo e(asset($product_rel->getThumb()), false); ?>" alt="<?php echo e($product_rel->name, false); ?>" /></a>
                        <?php echo $product_rel->showPrice(); ?>

                            <a href="<?php echo e($product_rel->getUrl(), false); ?>"><p><?php echo e($product_rel->name, false); ?></p></a>
                          </div>
                          <?php if($product_rel->price != $product_rel->getPrice()): ?>
                          <img src="<?php echo e(asset($theme_asset.'/images/home/sale.png'), false); ?>" class="new" alt="" />
                          <?php elseif($product_rel->type == 1): ?>
                          <img src="<?php echo e(asset($theme_asset.'/images/home/new.png'), false); ?>" class="new" alt="" />
                          <?php endif; ?>
                      </div>
                    </div>
                  </div>
                <?php if($key % 4 == 3): ?>
                  </div>
                <?php endif; ?>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </div>
          </div><!--/recommended_items-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5bd09e60b8c26cab"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'.shop_layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>