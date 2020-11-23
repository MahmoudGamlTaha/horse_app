<?php $__env->startSection('center'); ?>

<div class="features_items">
<h2 class="title text-center"><?php echo e($title, false); ?></h2>
<?php if(count($wishlist) ==0): ?>
    <div class="col-md-12 text-danger">
        Not found products!
    </div>
<?php else: ?>
<div class="table-responsive">
<table class="table box table-bordered">
    <thead>
      <tr  style="background: #eaebec">
        <th style="width: 50px;">No.</th>
        <th style="width: 100px;">SKU</th>
        <th>Name</th>
        <th>Price</th>
        <th>Remove</th>
      </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $wishlist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $n = (isset($n)?$n:0);
            $n++;
            $product = App\Models\ShopProduct::find($item->id);
        ?>
    <tr class="row_cart">
        <td ><?php echo e($n, false); ?></td>
        <td><?php echo e($product->sku, false); ?></td>
        <td>
            <?php echo e($product->name, false); ?><br>
            <a href="<?php echo e($product->getUrl(), false); ?>"><img width="100" src="<?php echo e(asset($product->getImage()), false); ?>" alt=""></a>
        </td>
        <td><?php echo $product->showPrice(); ?></td>
        <td>
            <a onClick="return confirm('Confirm')" title="Remove Item" alt="Remove Item" class="cart_quantity_delete" href="<?php echo e(url("removeItemWishlist/$item->rowId"), false); ?>"><i class="fa fa-times"></i></a>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>
  </div>
<?php endif; ?>
            </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <div class="breadcrumbs">
        <ol class="breadcrumb">
          <li><a href="<?php echo e(route('home'), false); ?>">Home</a></li>
          <li class="active"><?php echo e($title, false); ?></li>
        </ol>
      </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'.shop_layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>