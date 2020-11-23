<div class="<?php echo e($viewClass['row'], false); ?> <?php echo !$errors->has($errorKey) ? '' : 'has-error'; ?>">
    <label for="<?php echo e($id, false); ?>" class="<?php echo e($viewClass['xs'], false); ?>-2 control-label"><?php echo e($label, false); ?></label>
   

        <?php echo $__env->make('admin::form.error', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php if($groups): ?>
                <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="<?php echo e($viewClass['sm-col'], false); ?>-1">
        <span style="margin-right: 12px;right:0px;"><b><?php echo e(trans($group['label']), false); ?></b></span>                
        <select class="form-control <?php echo e($class, false); ?>" style="width: 100%;" name="<?php echo e($group['name'], false); ?>" <?php echo $attributes; ?> >
                 
                        <?php $__currentLoopData = $group['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $select => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($select, false); ?>" <?php echo e($select == old($column, $group['column']) ?'selected':'', false); ?>><?php echo e($option, false); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>      
        </select>
        </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </div>
<br/>
        <?php echo $__env->make('admin::form.help-block', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>