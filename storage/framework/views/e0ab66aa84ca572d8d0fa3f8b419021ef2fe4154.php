<?php $__env->startSection('main'); ?>
<section>
    <div class="container">
      <div class="row">
<h2 class="title text-center"><?php echo e($title, false); ?></h2>
<?php if(count($cart) ==0): ?>
    <div class="col-md-12 text-danger">
        Cart empty!
    </div>
<?php else: ?>
    <style>
    .shipping_address td{
        padding: 3px !important;
    }
    .shipping_address textarea,.shipping_address input[type="text"]{
        width: 100%;
        padding: 7px !important;
    }
    .row_cart>td{
        vertical-align: middle !important;
    }
    input[type="number"]{
        text-align: center;
        padding:2px;
    }
</style>
<div class="table-responsive">
<table class="table box table-bordered">
    <thead>
      <tr  style="background: #eaebec">
        <th style="width: 50px;">No.</th>
        <th style="width: 100px;"><?php echo e(trans('language.product.sku'), false); ?></th>
        <th><?php echo e(trans('language.product.name'), false); ?></th>
        <th><?php echo e(trans('language.product.price'), false); ?></th>
        <th ><?php echo e(trans('language.product.quantity'), false); ?></th>
        <th><?php echo e(trans('language.product.total_price'), false); ?></th>
        <th><?php echo e(trans('language.cart.delete'), false); ?></th>
      </tr>
    </thead>
    <tbody>

    <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $n = (isset($n)?$n:0);
            $n++;
            $product = App\Models\ShopProduct::find($item->id);
        ?>
    <tr class="row_cart">
        <td ><?php echo e($n, false); ?></td>
        <td><?php echo e($product->sku, false); ?></td>
        <td>
            <?php echo e($product->getName(), false); ?><br>

            <?php if($item->options->att): ?>
            (
                <?php $__currentLoopData = $item->options->att; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyAtt => $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <b><?php echo e($attributesGroup[$keyAtt]['name'], false); ?></b>: <i><?php echo e($att, false); ?></i> ;
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            )<br>
            <?php endif; ?>

            <a href="<?php echo e($product->getUrl(), false); ?>"><img width="100" src="<?php echo e(asset($product->getImage()), false); ?>" alt=""></a>
        </td>
        <td><?php echo $product->showPrice(); ?></td>
        <td><input style="width: 70px;" type="number" onChange="updateCart('<?php echo e($item->rowId, false); ?>',<?php echo e($item->id, false); ?>);" class="item-qty" id="item-<?php echo e($item->id, false); ?>" name="qty-<?php echo e($item->id, false); ?>" value="<?php echo e($item->qty, false); ?>"><span class="text-danger item-qty-<?php echo e($item->id, false); ?>" style="display: none;"></span></td>
        <td align="right"><?php echo e(\Helper::currencyRender($item->subtotal), false); ?></td>
        <td>
            <a onClick="return confirm('Confirm?')" title="Remove Item" alt="Remove Item" class="cart_quantity_delete" href="<?php echo e(route("removeItem",['id'=>$item->rowId]), false); ?>"><i class="fa fa-times"></i></a>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <tfoot>
        <tr  style="background: #eaebec">
            <td colspan="7">
                 <div class="pull-left">
                <button class="btn btn-default" type="button" style="cursor: pointer;padding:10px 30px" onClick="location.href='<?php echo e(route('home'), false); ?>'"><i class="fa fa-arrow-left"></i><?php echo e(trans('language.cart.back_to_shop'), false); ?></button>
                </div>
                 <div class="pull-right">
                <a onClick="return confirm('Confirm ?')" href="<?php echo e(route('clearCart'), false); ?>"><button class="btn" type="button" style="cursor: pointer;padding:10px 30px"><?php echo e(trans('language.cart.remove_all'), false); ?></button></a>
                </div>
            </td>
        </tr>
    </tfoot>
  </table>
  </div>
<form class="shipping_address" id="form-order" role="form" method="POST" action="<?php echo e(route('processCart'), false); ?>">
<div class="row">
    <div class="col-md-6">
            <?php echo e(csrf_field(), false); ?>

            <table class="table  table-bordered table-responsive">
                <tr>
                    <td class="form-group<?php echo e($errors->has('toname') ? ' has-error' : '', false); ?>">
                        <label for="phone" class="control-label"><i class="fa fa-user"></i> <?php echo e(trans('language.cart.to_name'), false); ?>:</label> <input name="toname" type="text" placeholder="<?php echo e(trans('language.cart.to_name'), false); ?>" value="<?php echo e((old('toname'))?old('toname'):$shippingAddress['toname'], false); ?>">
                            <?php if($errors->has('toname')): ?>
                                <span class="help-block"><?php echo e($errors->first('toname'), false); ?></span>
                            <?php endif; ?>
                        </td>
                    <td class="form-group<?php echo e($errors->has('phone') ? ' has-error' : '', false); ?>">
                        <label for="phone" class="control-label"><i class="fa fa-volume-control-phone"></i> <?php echo e(trans('language.cart.phone'), false); ?>:</label> <input name="phone" type="text" placeholder="<?php echo e(trans('language.cart.phone'), false); ?>" value="<?php echo e((old('phone'))?old('phone'):$shippingAddress['phone'], false); ?>">
                            <?php if($errors->has('phone')): ?>
                                <span class="help-block"><?php echo e($errors->first('phone'), false); ?></span>
                            <?php endif; ?>
                        </td>
                </tr>
                <tr>
                    <td colspan="2" class="form-group<?php echo e($errors->has('email') ? ' has-error' : '', false); ?>">
                        <label for="email" class="control-label"><i class="fa fa-user"></i> <?php echo e(trans('language.cart.email'), false); ?>:</label> <input name="email" type="text" placeholder="<?php echo e(trans('language.cart.email'), false); ?>" value="<?php echo e((old('email'))?old('email'):$shippingAddress['email'], false); ?>">
                            <?php if($errors->has('email')): ?>
                                <span class="help-block"><?php echo e($errors->first('email'), false); ?></span>
                            <?php endif; ?>
                    </td>

                </tr>

                <tr>
                    <td class="form-group<?php echo e($errors->has('address1') ? ' has-error' : '', false); ?>"><label for="address1" class="control-label"><i class="fa fa-home"></i> <?php echo e(trans('language.cart.address1'), false); ?>:</label> <input name="address1" type="text" placeholder="<?php echo e(trans('language.cart.address1'), false); ?>" value="<?php echo e((old('address1'))?old('address1'):$shippingAddress['address1'], false); ?>">
                            <?php if($errors->has('address1')): ?>
                                <span class="help-block"><?php echo e($errors->first('address1'), false); ?></span>
                            <?php endif; ?></td>
                    <td class="form-group<?php echo e($errors->has('address2') ? ' has-error' : '', false); ?>"><label for="address2" class="control-label"><i class="fa fa-university"></i> <?php echo e(trans('language.cart.address2'), false); ?></label><input name="address2" type="text" placeholder="<?php echo e(trans('language.cart.address2'), false); ?>" value="<?php echo e((old('address2'))?old('address2'):$shippingAddress['address2'], false); ?>">
                            <?php if($errors->has('address2')): ?>
                                <span class="help-block"><?php echo e($errors->first('address2'), false); ?></span>
                            <?php endif; ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label  class="control-label"><i class="fa fa-file-image-o"></i> <?php echo e(trans('language.cart.note'), false); ?>:</label>
                        <textarea rows="5" name="comment" placeholder="<?php echo e(trans('language.cart.note'), false); ?>...."><?php echo e((old('comment'))?old('comment'):$shippingAddress['comment'], false); ?></textarea>
                    </td>

                </tr>
            </table>

    </div>
    <div class="col-md-6">




        <div class="row">
            <div class="col-md-12">
                <table class="table box table-bordered" id="showTotal">
                    <?php $__currentLoopData = $dataTotal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($element['value'] !=0): ?>

                     <?php if($element['code']=='total'): ?>
                         <tr class="showTotal" style="background:#f5f3f3;font-weight: bold;">
                     <?php else: ?>
                        <tr class="showTotal">
                     <?php endif; ?>
                             <th><?php echo $element['title']; ?></th>
                            <td style="text-align: right" id="<?php echo e($element['code'], false); ?>"><?php echo e($element['text'], false); ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>


        <?php if($extensionDiscount): ?>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label class="control-label" for="inputGroupSuccess3"><i class="fa fa-exchange" aria-hidden="true"></i> <?php echo e(trans('language.cart.coupon'), false); ?>

                        <span style="display:inline; cursor: pointer; display: <?php echo e(($hasCoupon)?'inline':'none', false); ?>" class="text-danger" id="removeCoupon">(<?php echo e(trans('language.cart.remove_coupon'), false); ?> <i class="fa fa fa-times"></i>)</span>
                    </label>
                    <div id="coupon-group" class="input-group <?php echo e(Session::has('error_discount')?'has-error':'', false); ?>">
                      <input type="text" <?php echo e(($extensionDiscount['permission'])?'':'disabled', false); ?> placeholder="Your coupon" class="form-control" id="coupon-value" aria-describedby="inputGroupSuccess3Status">
                      <span class="input-group-addon <?php echo e(($extensionDiscount['permission'])?'':'disabled', false); ?>"  <?php echo ($extensionDiscount['permission'])?'id="coupon-button"':''; ?> style="cursor: pointer;" data-loading-text="<i class='fa fa-spinner fa-spin'></i> checking"><?php echo e(trans('language.cart.apply'), false); ?></span>
                    </div>
                    <span class="status-coupon" style="display: none;" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
                    <div class="coupon-msg  <?php echo e(Session::has('error_discount')?'text-danger':'', false); ?>" style="text-align: left;padding-left: 10px;"><?php echo e(Session::has('error_discount')?Session::get('error_discount'):'', false); ?></div>
                  </div>
              </div>
        <?php endif; ?>





        <div class="row">
            <div class="col-md-12">
                    <div class="form-group <?php echo e($errors->has('shippingMethod') ? ' has-error' : '', false); ?>">
                        <h3 class="control-label"><i class="fa fa-credit-card-alt"></i> <?php echo e(trans('language.cart.shipping_method'), false); ?>:<br></h3>
                        <?php if($errors->has('shippingMethod')): ?>
                            <span class="help-block"><?php echo e($errors->first('shippingMethod'), false); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <?php $__currentLoopData = $shippingMethod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $shipping): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <label class="radio-inline">
                                 <input type="radio" name="shippingMethod" value="<?php echo e($shipping['code'], false); ?>"  <?php echo e((old('shippingMethod') == $key)?'checked':'', false); ?> style="position: relative;" <?php echo e(($shipping['permission'])?'':'disabled', false); ?>>
                                 <?php echo e($shipping['title'], false); ?> (<?php echo e(\Helper::currencyRender($shipping['value']), false); ?>)
                                </label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
            </div>
        </div>




        <div class="row">
            <div class="col-md-12">
                    <div class="form-group <?php echo e($errors->has('paymentMethod') ? ' has-error' : '', false); ?>">
                        <h3 class="control-label"><i class="fa fa-credit-card-alt"></i> <?php echo e(trans('language.cart.payment_method'), false); ?>:<br></h3>
                        <?php if($errors->has('paymentMethod')): ?>
                            <span class="help-block"><?php echo e($errors->first('paymentMethod'), false); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <?php $__currentLoopData = $paymentMethod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <label class="radio-inline">
                                 <input type="radio" name="paymentMethod" value="<?php echo e($payment['code'], false); ?>"  <?php echo e((old('paymentMethod') == $key)?'checked':'', false); ?> style="position: relative;" <?php echo e(($payment['permission'])?'':'disabled', false); ?>>
                                 <img title="<?php echo e($payment['title'], false); ?>" alt="<?php echo e($payment['title'], false); ?>" src="<?php echo e(asset($payment['image']), false); ?>" style="width: 120px;">
                                </label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
            </div>
        </div>

            </div>
        </div>



        <div class="row" style="padding-bottom: 20px;">
            <div class="col-md-12 text-center">
                    <div class="pull-right">
                        <button class="btn btn-success" id="submit-order" type="button" style="cursor: pointer;padding:10px 30px"><i class="fa fa-check"></i> <?php echo e(trans('language.cart.checkout'), false); ?></button>
                    </div>
            </div>
        </div>



    </div>
</div>
</form>
<?php endif; ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <div class="breadcrumbs">
        <ol class="breadcrumb">
          <li><a href="<?php echo e(route('home'), false); ?>">Home</a></li>
          <li class="active"><?php echo e($title, false); ?></li>
        </ol>
      </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script type="text/javascript">
    function updateCart(rowId,id){
        var new_qty = $('#item-'+id).val();
            $.ajax({
            url: '<?php echo e(route('updateToCart'), false); ?>',
            type: 'POST',
            dataType: 'json',
            async: true,
            cache: false,
            data: {
                id: id,
                rowId: rowId,
                new_qty: new_qty,
                _token:'<?php echo e(csrf_token(), false); ?>'},
            success: function(data){
                error= parseInt(data.error);
                if(error ===0)
                {
                        window.location.replace(location.href);
                }else{
                    $('.item-qty-'+id).css('display','block').html(data.msg);
                }

                }
        });
    }

$('#submit-order').click(function(){
    $('#form-order').submit();
    $(this).prop('disabled',true);
});

<?php if($extensionDiscount): ?>
    $('#coupon-button').click(function() {
     var coupon = $('#coupon-value').val();
        if(coupon==''){
            $('#coupon-group').addClass('has-error');
            $('.coupon-msg').html('<?php echo e(trans('language.cart.coupon_empty'), false); ?>').addClass('text-danger').show();
        }else{
        $('#coupon-button').button('loading');
        setTimeout(function() {
            $.ajax({
                url: '<?php echo e(route('useDiscount'), false); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    code: coupon,
                    uID: <?php echo e($uID, false); ?>,
                    _token: "<?php echo e(csrf_token(), false); ?>",
                },
            })
            .done(function(result) {
                    $('#coupon-value').val('');
                    $('.coupon-msg').removeClass('text-danger');
                    $('.coupon-msg').removeClass('text-success');
                    $('#coupon-group').removeClass('has-error');
                    $('.coupon-msg').hide();
                if(result.error ==1){
                    $('#coupon-group').addClass('has-error');
                    $('.coupon-msg').html(result.msg).addClass('text-danger').show();
                }else{
                    $('#removeCoupon').show();
                    $('.coupon-msg').html(result.msg).addClass('text-success').show();
                    $('.showTotal').remove();
                    $('#showTotal').prepend(result.html);
                }
            })
            .fail(function() {
                console.log("error");
            })
           $('#coupon-button').button('reset');
       }, 2000);
        }

    });
    $('#removeCoupon').click(function() {
            $.ajax({
                url: '<?php echo e(route('removeDiscount'), false); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: "<?php echo e(csrf_token(), false); ?>",
                },
            })
            .done(function(result) {
                    $('#removeCoupon').hide();
                    $('#coupon-value').val('');
                    $('.coupon-msg').removeClass('text-danger');
                    $('.coupon-msg').removeClass('text-success');
                    $('.coupon-msg').hide();
                    $('.showTotal').remove();
                    $('#showTotal').prepend(result.html);
            })
            .fail(function() {
                console.log("error");
            })
            // .always(function() {
            //     console.log("complete");
            // });
    });
<?php endif; ?>

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'.shop_layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>