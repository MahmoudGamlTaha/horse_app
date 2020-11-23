<div class="form-group <?php echo !$errors->has($errorKey) ?: 'has-error'; ?>">

    <label for="<?php echo e($id, false); ?>" class="col-sm-2 control-label"><?php echo e($label, false); ?></label>

    <div class="col-sm-8">

        <?php echo $__env->make('admin::form.error', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <textarea class="form-control <?php echo e($class, false); ?>" id="<?php echo e($id, false); ?>" name="<?php echo e($name, false); ?>" placeholder="<?php echo e($placeholder, false); ?>" <?php echo $attributes; ?> ><?php echo e(old($column, $value), false); ?></textarea>
        <?php echo $__env->make('admin::form.help-block', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    </div>
</div>
