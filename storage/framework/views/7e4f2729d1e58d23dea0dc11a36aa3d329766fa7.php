<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php echo e($title, false); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th width="40%"><?php echo e(trans('language.extensions.install'), false); ?></th>
                  <th width="40%"><?php echo e(trans('language.extensions.install_condition'), false); ?></th>
                  <th width="40%"><?php echo e(trans('language.company'), false); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_a($dataList, 'Illuminate\Database\Eloquent\Collection')): ?>
                  <?php $__currentLoopData = $dataList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                      <td><a href="#" class="updateData_num" data-name="fee" data-type="number" data-pk="<?php echo e($data['id'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>'Installment','key'=>'ShippingBasic']), false); ?>" data-title="<?php echo e(trans('Extensions/Shipping/ShippingStandard.fee'), false); ?>"><?php echo e($data['fee'], false); ?></a></td>
                      <td><a href="#" class="updateData_num" data-name="condition_fee" data-type="number" data-pk="<?php echo e($data['id'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>$group,'key'=>$key]), false); ?>" data-title="<?php echo e(trans('Extensions/Shipping/ShippingStandard.shipping_free'), false); ?>"><?php echo e($data['condition_fee'], false); ?></a></td>
                      <td><a href="#" class="updateData_num" data-name="company_id" data-type="number" data-pk="<?php echo e($data['id'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>$group,'key'=>$key]), false); ?>" data-title=" <?php echo e(trans('language.company'), false); ?>"><?php echo e($data['company_id'], false); ?></a></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?> 
                    <tr>
                      <td ><a href="#" class="updateData_num"   data-name="fee" data-type="number" data-pk="<?php echo e($dataList['id'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>'Installment','key'=>'ShippingBasic']), false); ?>" data-title="<?php echo e(trans('Extensions/Shipping/ShippingStandard.fee'), false); ?>"><?php echo e($dataList['fee'], false); ?></a></td>
                      <td><a href="#" class="updateData_num" data-name="condition_fee" data-type="number" data-pk="<?php echo e($dataList['id'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>$group,'key'=>$key]), false); ?>" data-title="<?php echo e(trans('Extensions/Shipping/ShippingStandard.shipping_free'), false); ?>"><?php echo e($dataList['condition_fee'], false); ?></a></td>
                      <td><a href="#" class="updateData_num" data-name="company_id" data-type="number" data-pk="<?php echo e($dataList['id'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>$group,'key'=>$key]), false); ?>" data-title=" <?php echo e(trans('language.company'), false); ?>"><?php echo e($dataList['company_id'], false); ?></a></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    <div>
</div>
</section>
<script type="text/javascript">
$(document).ready(function() {
    $(".updateData_num").on("shown", function(e, editable) {
      var value = $(this).text().replace(/,/g, "");
      editable.input.$input.val(parseInt(value));
    });
    $('.updateData_num').editable({
    ajaxOptions: {
    type: 'put',
    dataType: 'json'
    },
    validate: function(value) {
        if (value == '') {
            return '<?php echo e(trans('language.admin.not_empty'), false); ?>';
        }
        if (!$.isNumeric(value)) {
            return '<?php echo e(trans('language.admin.only_numeric'), false); ?>';
        }
    }
    });

});

</script>
