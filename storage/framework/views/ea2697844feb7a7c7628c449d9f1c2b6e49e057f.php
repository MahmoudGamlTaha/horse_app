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
              <tr>
                <th width="40%"><?php echo e(trans('Extensions/Payment/Paypal.paypal_mode'), false); ?></th>
                <td><a href="#" class="updateData" data-name="paypal_mode" data-type="select" data-pk="<?php echo e($data['id'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>$group,'key'=>$key]), false); ?>" data-title="<?php echo e(trans('Extensions/Payment/Paypal.paypal_mode'), false); ?>" data-value="<?php echo e($data['paypal_mode'], false); ?>" data-source ='[{"value":"sandbox","text":"Sandbox"},{"value":"live","text":"Live"}]'</a></td>
              </tr>

              <tr>
                <th width="40%"><?php echo e(trans('Extensions/Payment/Paypal.paypal_log'), false); ?></th>
                <td><a href="#" class="updateData_num" data-name="paypal_log" data-type="select" data-pk="<?php echo e($data['id'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>$group,'key'=>$key]), false); ?>" data-title="<?php echo e(trans('Extensions/Payment/Paypal.paypal_log'), false); ?>" data-value="<?php echo e($data['paypal_log'], false); ?>" data-source ='[{"value":0,"text":"OFF"},{"value":1,"text":"ON"}]'</a></td>
              </tr>

              <tr>
                <th width="40%"><?php echo e(trans('Extensions/Payment/Paypal.paypal_client_id'), false); ?></th>
                <td><a href="#" class="updateData_can_empty" data-name="paypal_client_id" data-type="text" data-pk="<?php echo e($data['id'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>$group,'key'=>$key]), false); ?>" data-value="<?php echo e($data['paypal_client_id'], false); ?>" data-title="<?php echo e(trans('Extensions/Payment/Paypal.paypal_client_id'), false); ?>"></a></td>
              </tr>
              <tr>
                <th width="40%"><?php echo e(trans('Extensions/Payment/Paypal.paypal_secrect'), false); ?></th>
                <td><a href="#" class="updateData_can_empty" data-name="paypal_secrect" data-type="password" data-pk="<?php echo e($data['id'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>$group,'key'=>$key]), false); ?>" data-value="<?php echo e($data['paypal_secrect'], false); ?>" data-title="<?php echo e(trans('Extensions/Payment/Paypal.paypal_secrect'), false); ?>"></a></td>
              </tr>
              <tr>
                <th width="40%"><?php echo e(trans('Extensions/Payment/Paypal.paypal_path_log'), false); ?></th>
                <td><a href="#" class="updateData" data-name="paypal_path_log" data-type="text" data-pk="<?php echo e($data['id'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>$group,'key'=>$key]), false); ?>" data-title="<?php echo e(trans('Extensions/Payment/Paypal.paypal_path_log'), false); ?>"><?php echo e($data['paypal_path_log'], false); ?></a></td>
              </tr>
              <tr>
                <th width="40%"><?php echo e(trans('Extensions/Payment/Paypal.paypal_currency'), false); ?></th>
                <td><a href="#" class="updateData" data-name="paypal_currency" data-type="text" data-pk="<?php echo e($data['id'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>$group,'key'=>$key]), false); ?>" data-title="<?php echo e(trans('Extensions/Payment/Paypal.paypal_currency'), false); ?>"><?php echo e($data['paypal_currency'], false); ?></a></td>
              </tr>
              <tr>
                <th width="40%"><?php echo e(trans('Extensions/Payment/Paypal.paypal_order_status_success'), false); ?></th>
                <td><a href="#" class="updateData_num" data-name="paypal_order_status_success" data-type="select" data-pk="<?php echo e($data['id'], false); ?>" data-source="<?php echo e($jsonStatusOrder, false); ?>" data-value="<?php echo e($data['paypal_order_status_success'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>$group,'key'=>$key]), false); ?>" data-title="<?php echo e(trans('Extensions/Payment/Paypal.paypal_order_status_success'), false); ?>"></a></td>
              </tr>
              <tr>
                <th width="40%"><?php echo e(trans('Extensions/Payment/Paypal.paypal_order_status_faild'), false); ?></th>
                <td><a href="#" class="updateData_num" data-name="paypal_order_status_faild" data-type="select" data-pk="<?php echo e($data['id'], false); ?>" data-source="<?php echo e($jsonStatusOrder, false); ?>" data-value="<?php echo e($data['paypal_order_status_faild'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>$group,'key'=>$key]), false); ?>" data-title="<?php echo e(trans('Extensions/Payment/Paypal.paypal_order_status_faild'), false); ?>"></a></td>
              </tr>
              <tr>
                <th width="40%"><?php echo e(trans('Extensions/Payment/Paypal.paypal_logLevel'), false); ?></th>
                <td><a href="#" class="updateData" data-name="paypal_logLevel" data-type="select" data-pk="<?php echo e($data['id'], false); ?>" data-url="<?php echo e(route('processExtension',['group'=>$group,'key'=>$key]), false); ?>" data-title="<?php echo e(trans('Extensions/Payment/Paypal.paypal_logLevel'), false); ?>" data-value="<?php echo e($data['paypal_logLevel'], false); ?>" data-source ='[{"value":"DEBUG","text":"DEBUG (not allow live)"},{"value":"INFO","text":"INFO"},{"value":"ERROR","text":"ERROR"},{"value":"WARNING","text":"WARNING"}]'</a></td>
              </tr>

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

    $('.updateData').editable({
    ajaxOptions: {
    type: 'put',
    dataType: 'json'
    },
    validate: function(value) {
        if (value == '') {
            return '<?php echo e(trans('language.admin.not_empty'), false); ?>';
        }
    }
    });

    $('.updateData_can_empty').editable({
    ajaxOptions: {
    type: 'put',
    dataType: 'json'
    }
    });
});

</script>
