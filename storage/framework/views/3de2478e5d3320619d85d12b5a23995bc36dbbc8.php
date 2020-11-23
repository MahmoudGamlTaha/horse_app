<style type="text/css">
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td,.table>thead:first-child>tr:first-child>th {
    border: 1px solid #d0bcbc;
}
      .history{
        max-height: 50px;
        max-width: 300px;
        overflow-y: auto;
      }
.margin10{
  margin: 10px auto;
}
.td-title{
  width: 35%;
  font-weight: bold;
}
.td-title-normal{
  width: 35%;
}
</style>
<div class="container box">
    <div class="box-header with-border">
        <h3 class="box-title"><span class="glyphicon glyphicon-list-alt"></span> <?php echo e(trans('language.order.order_detail'), false); ?> #<?php echo e($order->id, false); ?></h3>
        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 10px">
                <a href="<?php echo e(URL::previous(), false); ?>" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;<?php echo e(trans('admin.list'), false); ?></a>
            </div>
            <div class="btn-group pull-right" style="margin-right: 10px">
                <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;<?php echo e(trans('admin.back'), false); ?></a>
            </div>
        </div>
    </div>
    <div>
      <div class="row">
        <div class="col-sm-6">
             <table class="table box table-bordered">
                <tr>
                  <td class="td-title"><?php echo e(trans('language.order.shipping_name'), false); ?>:</td><td><a href="#" class="updateInfoRequired" data-name="toname" data-type="text" data-pk="<?php echo e($order->id, false); ?>" data-url="<?php echo e(route("order_update"), false); ?>" data-title="<?php echo e(trans('language.order.shipping_name'), false); ?>" ><?php echo e($order->toname, false); ?></a></td>
                </tr>
                <tr>
                  <td class="td-title"><?php echo e(trans('language.order.shipping_phone'), false); ?>:</td><td><a href="#" class="updateInfoRequired" data-name="phone" data-type="text" data-pk="<?php echo e($order->id, false); ?>" data-url="<?php echo e(route("order_update"), false); ?>" data-title="<?php echo e(trans('language.order.shipping_phone'), false); ?>" ><?php echo e($order->phone, false); ?></a></td>
                </tr>
                <tr>
                  <td class="td-title">Email:</td><td><?php echo e(empty($order->email)?'N/A':$order->email, false); ?></td>
                </tr>
                <tr>
                  <td class="td-title"><?php echo e(trans('language.order.shipping_address1'), false); ?>:</td><td><a href="#" class="updateInfoRequired" data-name="address1" data-type="text" data-pk="<?php echo e($order->id, false); ?>" data-url="<?php echo e(route("order_update"), false); ?>" data-title="Địa chỉ 1" ><?php echo e($order->address1, false); ?></a></td>
                </tr>
                        <tr>
                  <td class="td-title"><?php echo e(trans('language.order.shipping_address2'), false); ?>:</td><td><a href="#" class="updateInfoRequired" data-name="address2" data-type="text" data-pk="<?php echo e($order->id, false); ?>" data-url="<?php echo e(route("order_update"), false); ?>" data-title="Địa chỉ 2" ><?php echo e($order->address2, false); ?></a></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6">
            <table  class="table table-bordered">
                <tr><td  class="td-title"><?php echo e(trans('language.order.order_status'), false); ?>:</td><td><a href="#" class="updateStatus" data-name="status" data-type="select" data-source ="<?php echo e(json_encode($statusOrder2), false); ?>"  data-pk="<?php echo e($order->id, false); ?>" data-value="<?php echo e($order->status, false); ?>" data-url="<?php echo e(route("order_update"), false); ?>" data-title="<?php echo e(trans('language.order.order_status'), false); ?>"><?php echo e($statusOrder[$order->status], false); ?></a></td></tr>
                <tr><td><?php echo e(trans('language.order.order_shipping_status'), false); ?>:</td><td><a href="#" class="updateStatus" data-name="shipping_status" data-type="select" data-source ="<?php echo e(json_encode($statusShipping2), false); ?>"  data-pk="<?php echo e($order->id, false); ?>" data-value="<?php echo e($order->shipping_status, false); ?>" data-url="<?php echo e(route("order_update"), false); ?>" data-title="<?php echo e(trans('language.order.order_shipping_status'), false); ?>"><?php echo e($statusShipping[$order->shipping_status], false); ?></a></td></tr>
                <tr><td><?php echo e(trans('language.order.payment_method'), false); ?>:</td><td><a href="#" class="updateStatus" data-name="payment_method" data-type="select" data-source ="<?php echo e(json_encode($paymentMethod), false); ?>"  data-pk="<?php echo e($order->id, false); ?>" data-value="<?php echo e($order->payment_method, false); ?>" data-url="<?php echo e(route("order_update"), false); ?>" data-title="<?php echo e(trans('language.order.payment_method'), false); ?>"><?php echo e($order->payment_method, false); ?></a></td></tr>
              </table>
             <table class="table box table-bordered">
                <tr>
                  <td class="td-title"><?php echo e(trans('language.order.currency'), false); ?>:</td><td><?php echo e($order->currency, false); ?></td>
                </tr>
                <tr>
                  <td class="td-title"><?php echo e(trans('language.order.exchange_rate'), false); ?>:</td><td><?php echo e(($order->exchange_rate)??1, false); ?></td>
                </tr>
            </table>
        </div>

      </div>

    </div>
<?php
    if($order->balance == 0){
        $style = 'style="color:#0e9e33;font-weight:bold;"';
    }else
        if($order->balance < 0){
        $style = 'style="color:#ff2f00;font-weight:bold;"';
    }else{
        $style = 'style="font-weight:bold;"';
    }
?>
  <div class="row">
    <div class="col-sm-6">
      <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
              <tr>
                <th style="width: 100px;"><?php echo e(trans('language.product.sku'), false); ?></th>
                <th><?php echo e(trans('language.product.name'), false); ?></th>
                <th><?php echo e(trans('language.product.price'), false); ?></th>
                <th style="width: 100px;"><?php echo e(trans('language.product.quantity'), false); ?></th>
                <th><?php echo e(trans('language.product.total_price'), false); ?></th>
                <th><?php echo e(trans('admin.action'), false); ?></th>
              </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr>
                        <span style="display: none;"  class="item_<?php echo e($item->id, false); ?>_id"><?php echo e($item->id, false); ?></span>
                        <td><span class="item_<?php echo e($item->id, false); ?>_sku"><?php echo e($item->sku, false); ?></span></td>
                        <td><span class="item_<?php echo e($item->id, false); ?>_name"><?php echo e($item->name, false); ?>

                          <?php
                          $html = '';
                            if($item->attribute && is_array(json_decode($item->attribute,true))){
                              $array = json_decode($item->attribute,true);
                                  foreach ($array as $key => $element){
                                    $html .= '<br><b>'.$attributesGroup[$key].'</b> : <i>'.$element.'</i>';
                                  }
                            }
                          ?>
                        <?php echo $html; ?>

                        </span></td>
                        <td align="right"><span><?php echo e(\Helper::currencyOnlyRender($item->price,$order->currency), false); ?></span></td>
                        <td>x <span class="item_<?php echo e($item->id, false); ?>_qty"><?php echo e(number_format($item->qty), false); ?></span></td>
                        <td align="right"><span ><?php echo e(\Helper::currencyOnlyRender($item->total_price,$order->currency), false); ?></span></td>
                        <td>
                          <span style="display: none"  class="item_<?php echo e($item->id, false); ?>_price"><?php echo e($item->price, false); ?></span>
                          <span style="display: none"  class="item_<?php echo e($item->id, false); ?>_total_price"><?php echo e($item->total_price, false); ?></span>
                            <button onclick="dataEdit(<?php echo e($item->id, false); ?>);" class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#editItem" data-placement="top" rel="tooltip" data-original-title="" title="Edit item"><span class="glyphicon glyphicon-pencil"></span></button>
                             &nbsp;
                            <button  onclick="dataRemove(<?php echo e($item->id, false); ?>);" class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#removeItem" data-placement="top" rel="tooltip" data-original-title="" title="Remove item"><span class="glyphicon glyphicon-remove"></span></button>
                        </td>
                      </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>

    <div class="margin10" id="add-item">
        <button  type="button" class="btn btn-sm btn-success" id="add-item-button"  title="<?php echo e(trans('language.product.add_product'), false); ?>"><i class="fa fa-plus"></i> <?php echo e(trans('language.product.add_product'), false); ?></button>
    </div>


      <table class="table box table-bordered">
        <tr>
          <td  class="td-title"><?php echo e(trans('language.order.order_note'), false); ?>:</td>
          <td>
            <a href="#" class="updateInfo" data-name="comment" data-type="textarea" data-pk="<?php echo e($order->id, false); ?>" data-url="<?php echo e(route("order_update"), false); ?>" data-title="" ><?php echo e($order->comment, false); ?>

            </a>
        </td>
        </tr>
      </table>

      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
              <div class="panel-heading" role="tab" id="headingOne">
                  <h4 class="panel-title">
                          <?php echo e(trans('language.order.order_history'), false); ?>

                      <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          <i class="more-less glyphicon glyphicon-plus"></i>
                          </a>
                  </h4>
              </div>
              <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                      <?php if(count($order->history)): ?>
                        <table  class="table table-bordered" id="history">
                          <tr>
                            <th><?php echo e(trans('language.order.history_staff'), false); ?></th>
                            <th><?php echo e(trans('language.order.history_content'), false); ?></th>
                            <th><?php echo e(trans('language.order.history_time'), false); ?></th>
                          </tr>
                        <?php $__currentLoopData = $order->history->sortKeysDesc()->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e(\Encore\Admin\Auth\Database\Administrator::find($history['admin_id'])->name??'', false); ?></td>
                            <td><div class="history"><?php echo $history['content']; ?></div></td>
                            <td><?php echo e($history['add_date'], false); ?></td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                      <?php endif; ?>
              </div>
          </div>
      </div>

    </div>

    <div class="col-sm-6">
          <table   class="table table-bordered">
<?php $__currentLoopData = $dataTotal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php if($element['code'] =='subtotal'): ?>
    <tr><td  class="td-title-normal"><?php echo $element['title']; ?>:</td><td align="right" class="data-<?php echo e($element['code'], false); ?>"><?php echo e(\Helper::currencyFormat($element['value']), false); ?></td></tr>
  <?php endif; ?>
  <?php if($element['code'] =='shipping'): ?>
    <tr><td><?php echo $element['title']; ?>:</td><td align="right"><a href="#" class="updatePrice data-<?php echo e($element['code'], false); ?>"  data-name="<?php echo e($element['code'], false); ?>" data-type="text" data-pk="<?php echo e($element['id'], false); ?>" data-url="<?php echo e(route("order_update"), false); ?>" data-title="<?php echo e(trans('language.order.shipping_price'), false); ?>"><?php echo e($element['value'], false); ?></a></td></tr>
  <?php endif; ?>
  <?php if($element['code'] =='discount'): ?>
    <tr><td><?php echo $element['title']; ?>(-):</td><td align="right"><a href="#" class="updatePrice data-<?php echo e($element['code'], false); ?>" data-name="<?php echo e($element['code'], false); ?>" data-type="text" data-pk="<?php echo e($element['id'], false); ?>" data-url="<?php echo e(route("order_update"), false); ?>" data-title="<?php echo e(trans('language.order.discount'), false); ?>"><?php echo e($element['value'], false); ?></a></td></tr>
  <?php endif; ?>

   <?php if($element['code'] =='total'): ?>
    <tr style="background:#f5f3f3;font-weight: bold;"><td><?php echo $element['title']; ?>:</td><td align="right" class="data-<?php echo e($element['code'], false); ?>"><?php echo e(\Helper::currencyFormat($element['value']), false); ?></td></tr>
  <?php endif; ?>

  <?php if($element['code'] =='received'): ?>
    <tr><td><?php echo $element['title']; ?>(-):</td><td align="right"><a href="#" class="updatePrice data-<?php echo e($element['code'], false); ?>" data-name="<?php echo e($element['code'], false); ?>" data-type="text" data-pk="<?php echo e($element['id'], false); ?>" data-url="<?php echo e(route("order_update"), false); ?>" data-title="<?php echo e(trans('language.order.received'), false); ?>"><?php echo e($element['value'], false); ?></a></td></tr>
  <?php endif; ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

  <tr  <?php echo $style; ?>  class="data-balance"><td><?php echo e(trans('language.order.balance'), false); ?>:</td><td align="right"><?php echo e(($order->balance === NULL)?\Helper::currencyFormat($order->total):\Helper::currencyFormat($order->balance), false); ?></td></tr>
  <tr id="update-status" style="display: none;"></tr>
        </table>

    </div>

  </div>
</div>



<div class="modal fade" id="removeItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel"><?php echo e(trans('admin.delete'), false); ?></h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body ">
        <p class="text-danger"><span class="glyphicon glyphicon-warning-sign"></span> <?php echo e(trans('admin.delete_confirm'), false); ?></p>
      </div>
      <form>
          <input  type="hidden" name="form_id" value="">
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('admin.close'), false); ?></button>
        <button id="removeItem-button" type="button" class="btn btn-primary"><?php echo e(trans('admin.delete'), false); ?></button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="editItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel"><?php echo e(trans('admin.edit'), false); ?></h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
            <input type="hidden" name="edit_order" value="">
          <table width="100%">
            <tr>
              <th style="width: 100px;"><?php echo e(trans('language.product.sku'), false); ?></th>
              <th><?php echo e(trans('language.product.name'), false); ?></th>
              <th style="width: 70px;"><?php echo e(trans('language.product.quantity'), false); ?></th>
              <th><?php echo e(trans('language.product.price'), false); ?></th>
              <th><?php echo e(trans('language.product.total_price'), false); ?></th>
              <th><?php echo e(trans('language.product.attribute'), false); ?></th>
            </tr>
            <tr>
              <input  type="hidden" class="edit_id" name="edit_id" value="">
              <td><input   type="text" disabled class="edit_sku form-control" name="edit_sku" value=""></td>
              <td><input  type="text" class="edit_name form-control" name="edit_name" value=""></td>
              <td><input type="number" class="edit_qty form-control" name="edit_qty" value=""></td>
              <td><input  type="number" class="edit_price form-control" name="edit_price" value=""></td>
              <td><input  type="number" disabled class="edit_total_price form-control" name="edit_total_price" value=""></td>
              <td><input  type="text" class="edit_attr form-control" name="edit_attr" value=""></td>
            </tr>
          </table>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('admin.close'), false); ?></button>
        <button type="button" class="btn btn-primary" id="editItem-button" ><?php echo e(trans('admin.save'), false); ?></button>
      </div>
    </div>
  </div>
</div>


<?php
  $selectProduct = '<form id="addItem-form">
      '.csrf_field().'
          <table width="100%">
          <input type="hidden" name="add_order" value="'.$order->id.'">
            <tr>
              <th>'.trans('language.product.name').'</th>
              <th style="width: 150px;">'.trans('language.product.sku').'</th>
              <th style="width: 70px;">'.trans('language.product.quantity').'</th>
              <th>'.trans('language.product.price').'</th>
            </tr>
            <tr>
              <td>
                <select required onChange="selectProduct($(this));" class="add_id form-control" name="add_id">
                <option value="0">'.trans('language.order.select_product').'</option>';
                foreach ($products as $key => $value){
  $selectProduct .='<option  value="'.$key.'" >'.$value.'</option>';
                }
  $selectProduct .='
              </select>
            </td>
              <td><input type="text" disabled class="add_sku form-control" name="add_sku" value=""></td>
              <td><input required type="number" class="add_qty form-control" name="add_qty" value=""></td>
              <td><input required type="text" class="add_price form-control" name="add_price" value=""></td>
              <td></td>
            </tr>
          <tr>
            <td colspan="4" class="add_attr"></td>
          </tr>
          </table></form>';
        $selectProduct = str_replace("\n", '', $selectProduct);
        $selectProduct = str_replace("\t", '', $selectProduct);
        $selectProduct = str_replace("\r", '', $selectProduct);
?>





<script type="text/javascript">


//Edit item
//
  function dataEdit(id){
        $.ajax({
            url:'<?php echo e(route("getInfoItem"), false); ?>',
            type:'get',
            dataType:'json',
            data:{
                'id':id,
            },
            success: function(data){
              $('#editItem [name="edit_order"]').val(data.order_id);
              $('#editItem [name="edit_id"]').val(data.id);
              $('#editItem [name="edit_sku"]').val(data.sku);
              $('#editItem [name="edit_name"]').val(data.name);
              $('#editItem [name="edit_qty"]').val(data.qty);
              $('#editItem [name="edit_price"]').val(data.price);
              $('#editItem [name="edit_attr"]').val(data.attribute);
              $('#editItem [name="edit_total_price"]').val(data.total_price);
            }
        });

    $('#editItem [name="edit_price"],#editItem [name="edit_qty"]').change(function(){
      $('#editItem [name="edit_total_price"]').val(
        parseInt($('#editItem [name="edit_qty"]').val()) * parseInt($('#editItem [name="edit_price"]').val())
        );
    });
  }

    $('#editItem-button').click(function(){
        $.ajax({
            url:'<?php echo e(route("order_edit_item"), false); ?>',
            type:'post',
            dataType:'json',
            data:{
                'pOrder':$('#editItem [name="edit_order"]').val(),
                'pId':$('#editItem [name="edit_id"]').val(),
                'pName':$('#editItem [name="edit_name"]').val(),
                'pQty':$('#editItem [name="edit_qty"]').val(),
                'pPrice':$('#editItem [name="edit_price"]').val(),
                'pAttr':$('#editItem [name="edit_attr"]').val(),
                '_token': "<?php echo e(csrf_token(), false); ?>",
            },
            success: function(result){
                if(parseInt(result.error) ==0){
                    location.reload();
                }else{
                    alert(result.msg);
                }
            }
        });
    });
//End edit item

//Remove item order
       function dataRemove(id){
        $('#removeItem [name="form_id"]').val(id);
      }

        $('#removeItem-button').click(function(){
        $.ajax({
            url:'<?php echo e(route("order_delete_item"), false); ?>',
            type:'post',
            dataType:'json',
            data:{
                'pId':$('#removeItem [name="form_id"]').val(),
                '_token': "<?php echo e(csrf_token(), false); ?>",
            },
            success: function(result){
                if(parseInt(result.error) ==0){
                    location.reload();
                }else{
                    alert('Error');
                }
            }
        });
    });
//End remove item order


//Add item
    function selectProduct(element){
        node = element.closest('tr');
        var id = parseInt(node.find('option:selected').eq(0).val());
        if(id == 0){
            node.find('[name="add_sku"]').val('');
            node.find('[name="add_qty"]').eq(0).val('');
            node.find('[name="add_price"]').eq(0).val('');
            node.next().find('.add_attr').html('');
        }else{
                $.ajax({
                url : '<?php echo e(route('getInfoProduct'), false); ?>',
                type : "get",
                dateType:"application/json; charset=utf-8",
                data : {
                     id : id
                },
                success: function(result){
                    var returnedData = JSON.parse(result);
                    node.find('[name="add_sku"]').val(returnedData.sku);
                    node.find('[name="add_qty"]').eq(0).val(1);
                    node.find('[name="add_price"]').eq(0).val(returnedData.price * <?php echo ($order->exchange_rate)??1; ?>);
                    node.next().find('.add_attr').eq(0).html(returnedData.renderAttDetails);
                    }
                });
        }

    }

$('#add-item-button').click(function() {
  var checkForm = $('#addItem-form').length;
  if(checkForm){
              $.ajax({
                url:'<?php echo e(route("order_add_item"), false); ?>',
                type:'post',
                dataType:'json',
                data:$('form#addItem-form').serialize(),
                success: function(result){
                    if(parseInt(result.error) ==0){
                        location.reload();
                    }else{
                        alert(result.msg);
                    }
                }
            });
        }else{
          var html = '<?php echo $selectProduct; ?>';
          $('#add-item').before(html);
        }

});
//End add item
//

$(document).ready(function() {
    $('.updateInfo').editable({});

    $(".updatePrice").on("shown", function(e, editable) {
      var value = $(this).text().replace(/,/g, "");
      editable.input.$input.val(parseInt(value));
    });
    $('.updateStatus').editable({
        validate: function(value) {
            if (value == '') {
                return '<?php echo e(trans('language.admin.not_empty'), false); ?>';
            }
        }
    });
        $('.updateInfoRequired').editable({
        validate: function(value) {
            if (value == '') {
                return '<?php echo e(trans('language.admin.not_empty'), false); ?>';
            }
        }
    });
    $('.updatePrice').editable({
    ajaxOptions: {
    type: 'post',
    dataType: 'json'
    },
    validate: function(value) {
        if (value == '') {
            return '<?php echo e(trans('language.admin.not_empty'), false); ?>';
        }
        if (!$.isNumeric(value)) {
            return '<?php echo e(trans('language.admin.only_numeric'), false); ?>';
        }
    },

        success: function(response, newValue) {
            // var rs = JSON.parse(response);
            console.log(response);
            var rs = response;
            if(rs.stt ==1){
                $('.data-shipping').html(rs.msg.shipping);
                $('.data-received').html(rs.msg.received);
                $('.data-total').html(rs.msg.total);
                $('.data-shipping').html(rs.msg.shipping);
                $('.data-discount').html(rs.msg.discount);
                $('.data-balance').remove();
                $('#update-status').before(rs.msg.balance);
                $('.payment_status').html(rs.msg.payment_status);
            }
    }
    });
});

</script>
