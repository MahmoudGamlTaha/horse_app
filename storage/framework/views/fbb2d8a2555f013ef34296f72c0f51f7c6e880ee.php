<?php $__env->startSection('main'); ?>
<section >
<div class="container">
    <div class="row">
        <h2 class="title text-center"><?php echo e($title, false); ?></h2>
        <?php echo $page->content; ?>

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

<?php echo $__env->make($theme.'.shop_layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>