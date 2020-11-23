
       <table class="table box table-bordered">
        <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td style="width: 200px;"><?php echo trans(htmlentities($data['title'])); ?>:</td>
          <td>
            <a href="#" class="<?php echo e(!empty($data['disabled'])?'editable-disabled':'', false); ?> <?php echo e(($data['required'])?'fied-required':'no-required', false); ?>" data-name="<?php echo e($data['field'], false); ?>" data-type="<?php echo e($data['type'], false); ?>" data-pk="<?php echo e($data['key'], false); ?>" data-source ="<?php echo e($data['source'], false); ?>" data-url="<?php echo e($data['url'], false); ?>" data-title="<?php echo trans(htmlentities($data['title'])); ?>" data-value="<?php echo e($data['value'], false); ?>"></a>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </table>



<script type="text/javascript">
$(document).ready(function() {
        $('.no-required').editable({});
        $('.fied-required').editable({
        validate: function(value) {
            if (value == '') {
                return '<?php echo e(trans('language.admin.not_empty'), false); ?>';
            }
        }
    });
});
</script>
