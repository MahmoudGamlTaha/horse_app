<?php $__env->startSection('main'); ?>
<section >
<div class="container">
    <div class="row">
        <h2 class="title text-center"><?php echo e($title, false); ?></h2>
        <?php if(count($compare) ==0): ?>
            <div class="col-md-12 text-danger">
               Not found!
            </div>
        <?php else: ?>
<div class="table-responsive">
<table class="table box table-bordered">
    <tbody>
   <tr>
    <?php
        $n = 0;
    ?>
    <?php $__currentLoopData = $compare; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $n++;
            $product = App\Models\ShopProduct::find($item->id);
        ?>
       <td align="center">
           <?php echo e($product->name, false); ?>(<?php echo e($product->sku, false); ?>)
           <hr>
            <a href="<?php echo e($product->getUrl(), false); ?>"><img width="100" src="<?php echo e(asset($product->getImage()), false); ?>" alt=""></a>
            <hr>
            <?php echo $product->showPrice(); ?>

            <hr>
            <?php echo $product->description; ?>

            <hr>
            <a onClick="return confirm('Confirm')" title="Remove Item" alt="Remove Item" class="cart_quantity_delete" href="<?php echo e(route("removeItemCompare",['id'=>$item->rowId]), false); ?>"><i class="fa fa-times"></i></a>
       </td>
       <?php if($n % 4 == 0): ?>
      </tr>
       <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tr>
    </tbody>
  </table>
  </div>
        <?php endif; ?>
        </div>
    </div>
</section>
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