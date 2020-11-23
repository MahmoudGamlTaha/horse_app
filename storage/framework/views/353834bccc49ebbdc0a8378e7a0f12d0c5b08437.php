<?php $__env->startSection('center'); ?>
  <div class="features_items">
    <h2 class="title text-center"><?php echo e($title, false); ?></h2>

    <?php if(isset($itemsList)): ?>
      <?php if($itemsList->count()): ?>
      <div class="item-folder">
            <?php $__currentLoopData = $itemsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-sm-3 col-xs-4">
                <div class="item-folder-wrapper product-single">
                  <div class="single-products">
                    <div class="productinfo text-center product-box-<?php echo e($item->id, false); ?>">
                      <a href="<?php echo e($item->getUrl(), false); ?>"><img src="<?php echo e(asset($item->getThumb()), false); ?>" alt="<?php echo e($item->name, false); ?>" /></a>
                      <a href="<?php echo e($item->getUrl(), false); ?>"><p><?php echo e($item->name, false); ?></p></a>
                    </div>
                  </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div style="clear: both; ">
        </div>
      </div>
      <?php endif; ?>
    <?php endif; ?>

      <?php if(count($products) ==0): ?>
        <?php echo e(trans('language.empty_product'), false); ?>

      <?php else: ?>
          <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-sm-4 col-xs-6">
              <div class="product-image-wrapper product-single">
                <div class="single-products">
                  <div class="productinfo text-center product-box-<?php echo e($product->id, false); ?>">
                    <a href="<?php echo e($product->getUrl(), false); ?>"><img src="<?php echo e(asset($product->getThumb()), false); ?>" alt="<?php echo e($product->name, false); ?>" /></a>

                    <?php echo $product->showPrice(); ?>


                    <a href="<?php echo e($product->getUrl(), false); ?>"><p><?php echo e($product->name, false); ?></p></a>
                    <a  class="btn btn-default add-to-cart" onClick="addToCart(<?php echo e($product->id, false); ?>,'default',$(this))"><i class="fa fa-shopping-cart"></i><?php echo e(trans('language.add_to_cart'), false); ?></a>
                  </div>
                  <?php if($product->price != $product->getPrice()): ?>
                  <img src="<?php echo e(asset($theme_asset.'/images/home/sale.png'), false); ?>" class="new" alt="" />
                  <?php elseif($product->type == 1): ?>
                  <img src="<?php echo e(asset($theme_asset.'/images/home/new.png'), false); ?>" class="new" alt="" />
                  <?php endif; ?>
                </div>
                <div class="choose">
                  <ul class="nav nav-pills nav-justified">
                    <li><a  onClick="addToCart(<?php echo e($product->id, false); ?>,'wishlist',$(this))"><i class="fa fa-plus-square"></i><?php echo e(trans('language.add_to_wishlist'), false); ?></a></li>
                    <li><a  onClick="addToCart(<?php echo e($product->id, false); ?>,'compare',$(this))"><i class="fa fa-plus-square"></i><?php echo e(trans('language.add_to_compare'), false); ?></a></li>
                  </ul>
                </div>
              </div>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>

    <div style="clear: both; ">
        <ul class="pagination">
          <?php echo e($products->appends(request()->except(['page','_token']))->links(), false); ?>

      </ul>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('breadcrumb'); ?>
    <div class="breadcrumbs pull-left">
        <ol class="breadcrumb">
          <li><a href="<?php echo e(route('home'), false); ?>">Home</a></li>
          <li class="active"><?php echo e($title, false); ?></li>
        </ol>
      </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('filter'); ?>
  <form action="" method="GET" id="filter_sort">
        <div class="pull-right">
        <div>
            <?php
              $queries = request()->except(['filter_sort','page']);
            ?>
            <?php $__currentLoopData = $queries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $query): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <input type="hidden" name="<?php echo e($key, false); ?>" value="<?php echo e($query, false); ?>">
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <select class="custom-select" name="filter_sort">
            <option value=""><?php echo e(trans('language.filters.sort'), false); ?></option>
            <option value="price_asc" <?php echo e(($filter_sort =='price_asc')?'selected':'', false); ?>><?php echo e(trans('language.filters.price_asc'), false); ?></option>
            <option value="price_desc" <?php echo e(($filter_sort =='price_desc')?'selected':'', false); ?>><?php echo e(trans('language.filters.price_desc'), false); ?></option>
            <option value="sort_asc" <?php echo e(($filter_sort =='sort_asc')?'selected':'', false); ?>><?php echo e(trans('language.filters.sort_asc'), false); ?></option>
            <option value="sort_desc" <?php echo e(($filter_sort =='sort_desc')?'selected':'', false); ?>><?php echo e(trans('language.filters.sort_desc'), false); ?></option>
            <option value="id_asc" <?php echo e(($filter_sort =='id_asc')?'selected':'', false); ?>><?php echo e(trans('language.filters.id_asc'), false); ?></option>
            <option value="id_desc" <?php echo e(($filter_sort =='id_desc')?'selected':'', false); ?>><?php echo e(trans('language.filters.id_desc'), false); ?></option>
          </select>
        </div>
      </div>
  </form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?>
  <script type="text/javascript">
    $('[name="filter_sort"]').change(function(event) {
      $('#filter_sort').submit();
    });
  </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'.shop_layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>