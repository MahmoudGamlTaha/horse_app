<?php $__env->startSection('main'); ?>
<div class="row">
        <div class="container">
                <h2 class="title text-center"><?php echo e($title, false); ?></h2>
                  <?php if(!empty($itemsList)): ?>
                    <?php $__currentLoopData = $itemsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-sm-3 col-xs-4">
                            <div class="product-image-wrapper product-single">
                              <div class="single-products">
                                <div class="productinfo text-center product-box-<?php echo e($item->id, false); ?>">
                                  <a href="<?php echo e($item->getUrl(), false); ?>"><img src="<?php echo e(asset($item->getImage()), false); ?>" alt="<?php echo e($item->name, false); ?>" /></a>
                                  <a href="<?php echo e($item->getUrl(), false); ?>"><p><?php echo e($item->name, false); ?></p></a>
                                </div>
                              </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php endif; ?>
<div style="clear: both; ">
    <ul class="pagination">
      <?php echo e($itemsList->appends(request()->except(['page','_token']))->links(), false); ?>

  </ul>
</div>

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
            <option value="name_asc" <?php echo e(($filter_sort =='name_asc')?'selected':'', false); ?>><?php echo e(trans('language.filters.name_asc'), false); ?></option>
            <option value="name_desc" <?php echo e(($filter_sort =='name_desc')?'selected':'', false); ?>><?php echo e(trans('language.filters.name_desc'), false); ?></option>
            <option value="sort_asc" <?php echo e(($filter_sort =='sort_asc')?'selected':'', false); ?>><?php echo e(trans('language.filters.sort_asc'), false); ?></option>
            <option value="sort_desc" <?php echo e(($filter_sort =='sort_desc')?'selected':'', false); ?>><?php echo e(trans('language.filters.sort_desc'), false); ?></option>
            <option value="id_asc" <?php echo e(($filter_sort =='id_asc')?'selected':'', false); ?>><?php echo e(trans('language.filters.id_asc'), false); ?></option>
            <option value="id_desc" <?php echo e(($filter_sort =='id_desc')?'selected':'', false); ?>><?php echo e(trans('language.filters.id_desc'), false); ?></option>
          </select>
        </div>
      </div>
  </form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
  <script type="text/javascript">
    $('[name="filter_sort"]').change(function(event) {
      $('#filter_sort').submit();
    });
  </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'.shop_layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>