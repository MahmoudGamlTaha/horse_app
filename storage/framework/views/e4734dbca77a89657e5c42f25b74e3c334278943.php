<?php if(Session::has('import_success')): ?>
    <div class="alert alert-success">
        <b>List product success:</b><br>
        <?php $__currentLoopData = Session::get('import_success'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e($detail, false); ?><br>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php if(Session::has('import_error')): ?>
    <div class="alert alert-danger">
        <b>List product success:</b><br>
        <?php $__currentLoopData = Session::get('import_error'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e($detail, false); ?><br>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>

<form method="post" action="" enctype="multipart/form-data">
<div>
<?php echo e(trans('language.process.productImport_text'), false); ?>: <a href="<?php echo e(asset('format/importProduct.xlsx'), false); ?>">Download HERE</a>
</div>
<div class="col-md-6 input-group file-caption-main ">
  <div class="form-group <?php echo !$errors->has('import_file') ?: 'has-error'; ?>">
    <input type="file" name="import_file" class="form-control-file">
    <?php if($errors->has('import_file')): ?>
        <span class="help-block"><?php echo e($errors->first('import_file'), false); ?></span>
    <?php endif; ?>
  </div>
</div>
<?php echo e(csrf_field(), false); ?>

<div class="btn-group">
    <button type="submit" class="btn btn-primary" style="width: 100px;">Submit</button>
</div>
</form>
