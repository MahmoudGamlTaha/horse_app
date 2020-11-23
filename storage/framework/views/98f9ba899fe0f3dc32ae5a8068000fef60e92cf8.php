<style>
    .nav-tabs > li:hover > i{
        display: inline;
    }
    .close-tab {
        position: absolute;
        font-size: 10px;
        top: 2px;
        right: 5px;
        color: #94A6B0;
        cursor: pointer;
        display: none;
    }
</style>
<div id="has-many-<?php echo e($column, false); ?>" class="nav-tabs-custom has-many-<?php echo e($column, false); ?>">
    <div class="row header">
        <div class="col-md-<?php echo e($viewClass['label'], false); ?>"><h4 class="pull-right"><?php echo e($label, false); ?></h4></div>
        <div class="col-md-<?php echo e($viewClass['field'], false); ?>">
            <button type="button" class="btn btn-default btn-sm add"><i class="fa fa-plus-circle" style="font-size: large;"></i></button>
        </div>
    </div>

    <hr style="margin-top: 0px;">

    <ul class="nav nav-tabs">
        <?php $__currentLoopData = $forms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pk => $form): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="<?php if($form == reset($forms)): ?> active <?php endif; ?> ">
                <a href="#<?php echo e($relationName . '_' . $pk, false); ?>" data-toggle="tab">
                    <?php echo e($pk, false); ?> <i class="fa fa-exclamation-circle text-red hide"></i>
                </a>
                <i class="close-tab fa fa-times" ></i>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </ul>
    
    <div class="tab-content has-many-<?php echo e($column, false); ?>-forms">

        <?php $__currentLoopData = $forms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pk => $form): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="tab-pane fields-group has-many-<?php echo e($column, false); ?>-form <?php if($form == reset($forms)): ?> active <?php endif; ?>" id="<?php echo e($relationName . '_' . $pk, false); ?>">
                <?php $__currentLoopData = $form->fields(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $field->render(); ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <template class="nav-tab-tpl">
        <li class="new">
            <a href="#<?php echo e($relationName . '_new_' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME, false); ?>" data-toggle="tab">
                &nbsp;New <?php echo e(\Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME, false); ?> <i class="fa fa-exclamation-circle text-red hide"></i>
            </a>
            <i class="close-tab fa fa-times" ></i>
        </li>
    </template>
    <template class="pane-tpl">
        <div class="tab-pane fields-group new" id="<?php echo e($relationName . '_new_' . \Encore\Admin\Form\NestedForm::DEFAULT_KEY_NAME, false); ?>">
            <?php echo $template; ?>

        </div>
    </template>

</div>