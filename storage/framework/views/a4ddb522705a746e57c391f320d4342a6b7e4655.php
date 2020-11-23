<div style="margin:10px;" class="btn-group">
    <button type="button" class="dropdown-toggle usa" data-toggle="dropdown" aria-expanded="false"><img src="<?php echo e(asset($path_file . '/' . $languages[session('locale')??app()->getLocale()]['icon']), false); ?>" style="height: 25px;"><span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li>
            <a href="<?php echo e(route('admin.locale', ['locale' => $key]), false); ?>"><img src="<?php echo e(asset($path_file . '/' . $language['icon']), false); ?>" style="height: 25px;"></a>
        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
