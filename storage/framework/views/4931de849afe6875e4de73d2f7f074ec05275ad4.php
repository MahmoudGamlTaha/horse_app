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
        <td><?php echo e($item->qty, false); ?></td>
        <td align="right"><?php echo e(\Helper::currencyRender($item->subtotal), false); ?></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>
  </div>
<form class="shipping_address" id="form-order" role="form" method="POST" action="<?php echo e(route('storeOrder'), false); ?>">
    <?php echo e(csrf_field(), false); ?>

    <input type="hidden" name="address" value="<?php echo e(json_encode($address), false); ?>">
    <input type="hidden" name="dataTotal" value="<?php echo e(json_encode($dataTotal), false); ?>">
    <input type="hidden" name="payment" value="<?php echo e($payment, false); ?>">
    <input type="hidden" name="shipping" value="<?php echo e($shipping, false); ?>">
    <div class="row">
    <div class="col-md-6">
        <h3 class="control-label"><i class="fa fa-credit-card-alt"></i> <?php echo e(trans('language.cart.shipping_address'), false); ?>:<br></h3>
        <table class="table box table-bordered" id="showTotal">
            <tr>
                <th><?php echo e(trans('language.cart.to_name'), false); ?>:</td>
                <td><?php echo e($address['toname'], false); ?></td>
            </tr>
            <tr>
                <th><?php echo e(trans('language.cart.phone'), false); ?>:</td>
                <td><?php echo e($address['phone'], false); ?></td>
            </tr>
             <tr>
                <th><?php echo e(trans('language.cart.email'), false); ?>:</td>
                <td><?php echo e($address['email'], false); ?></td>
            </tr>
             <tr>
                <th><?php echo e(trans('language.cart.address'), false); ?>:</td>
                <td><?php echo e($address['address1'].' '.$address['address2'], false); ?></td>
            </tr>
             <tr>
                <th><?php echo e(trans('language.cart.note'), false); ?>:</td>
                <td><?php echo e($address['comment'], false); ?></td>
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
        
            <div class="row">
                <div class="col-md-12">
                        <div class="form-group">
                            <h3 class="control-label"><i class="fa fa-credit-card-alt"></i> <?php echo e(trans('language.cart.payment_method'), false); ?>:<br></h3>
                        </div>
                        <div class="form-group">
                                <div>
                                    <label class="radio-inline">
                                     <img title="<?php echo e($paymentMethod['title'], false); ?>" alt="<?php echo e($paymentMethod['title'], false); ?>" src="<?php echo e(asset($paymentMethod['image']), false); ?>" style="width: 120px;">
                                    </label>
                                </div>
                        </div>
                </div>
            </div>
        
            </div>
        </div>


        <div class="row" style="padding-bottom: 20px;">
            <div class="col-md-12 text-center">
             <div class="pull-left">
                <button class="btn btn-default" type="button" style="cursor: pointer;padding:10px 30px" onClick="location.href='<?php echo e(route('cart'), false); ?>'"><i class="fa fa-arrow-left"></i><?php echo e(trans('language.cart.back_to_cart'), false); ?></button>
                </div>
                    <div class="pull-right">
                        <button class="btn btn-success" id="submit-order" type="submit" style="cursor: pointer;padding:10px 30px"><i class="fa fa-check"></i> <?php echo e(trans('language.cart.confirm'), false); ?></button>
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
          <li><a href="<?php echo e(route('cart'), false); ?>"><?php echo e(trans('language.cart_title'), false); ?></a></li>
          <li class="active"><?php echo e($title, false); ?></li>
        </ol>
      </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme.'.shop_layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>