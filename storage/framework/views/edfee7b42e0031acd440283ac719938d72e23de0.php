<?php $__env->startSection('main'); ?>

    <section >
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-danger text-center">
                   <h1><?php echo e($msg, false); ?></h1>
                </div>
                </div>
            </div>
        </section>
<!-- /.col -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'.shop_layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>