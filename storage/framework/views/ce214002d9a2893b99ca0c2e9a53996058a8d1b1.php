
       <table class="table box table-bordered">
         <tr>
           <td>code</td>
           <td>name</td>
           <td>amount_in_base</td>
         </tr>
        <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td>
            <a href="#" class="<?php echo e(!empty($data['disabled'])?'editable-disabled':'', false); ?> <?php echo e(($data['required'])?'fied-required':'no-required', false); ?>" data-name="<?php echo e($data['codeField'], false); ?>" data-type="<?php echo e($data['type'], false); ?>" data-pk="<?php echo e($data['key'], false); ?>" data-source ="<?php echo e($data['source'], false); ?>" data-url="<?php echo e($data['url'], false); ?>" data-title="<?php echo trans(htmlentities($data['title'])); ?>" data-value="<?php echo e($data['codeValue'], false); ?>"></a>
          </td>
          <td>
            <a href="#" class="<?php echo e(!empty($data['disabled'])?'editable-disabled':'', false); ?> <?php echo e(($data['required'])?'fied-required':'no-required', false); ?>" data-name="<?php echo e($data['nameField'], false); ?>" data-type="<?php echo e($data['type'], false); ?>" data-pk="<?php echo e($data['key'], false); ?>" data-source ="<?php echo e($data['source'], false); ?>" data-url="<?php echo e($data['url'], false); ?>" data-title="<?php echo trans(htmlentities($data['title'])); ?>" data-value="<?php echo e($data['value'], false); ?>"></a>
          </td>
          <td>
            <a href="#" class="<?php echo e(!empty($data['disabled'])?'editable-disabled':'', false); ?> <?php echo e(($data['required'])?'fied-required':'no-required', false); ?>" data-name="<?php echo e($data['amountField'], false); ?>" data-type="number" data-pk="<?php echo e($data['key'], false); ?>" data-source ="<?php echo e($data['source'], false); ?>" data-url="<?php echo e($data['url'], false); ?>" data-title="<?php echo trans(htmlentities($data['title'])); ?>" data-value="<?php echo e($data['amount'], false); ?>"></a>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr id="addnew-' .code. '">
          <td>  <button type="button" class="btn btn-sm btn-success"  onclick="morItem(' . $key . ');" rel="tooltip" data-original-title="" title="Add new item"><i class="fa fa-plus"></i> ' . trans('language.attribute.add_more') . '</button>
          </td>
          <td>  <button type="button" class="btn btn-sm btn-success"  onclick="morItem(' . $key . ');" rel="tooltip" data-original-title="" title="Add new item"><i class="fa fa-plus"></i> ' . trans('language.attribute.add_more') . '</button>
          </td>
          <td>  <button type="button" class="btn btn-sm btn-success"  onclick="morItem(' . $key . ');" rel="tooltip" data-original-title="" title="Add new item"><i class="fa fa-plus"></i> ' . trans('language.attribute.add_more') . '</button>
          </td>

        </tr>
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
<script>
                function morItem(id){
                        $("#no-item-"+id).remove();
                    $("tr#addnew-"+id).before("<tr><td><span><span class=\"input-group\"><input  type=\"text\" name=\"group["+id+"][name][]\" value=\"\" class=\"form-control\" placeholder=\"$detail_name\"></span></span></td><td><button onclick=\"removeItemForm(this);\" class=\"btn btn-danger btn-xs\" data-title=\"Delete\" data-toggle=\"modal\"  data-placement=\"top\" rel=\"tooltip\" data-original-title=\"\" title=\"Remove item\"><span class=\"glyphicon glyphicon-remove\"></span> $remove</button></td></tr>");
                    }

                    function removeItemForm(elmnt){
                      elmnt.closest("tr").remove();
                    }

</script>
