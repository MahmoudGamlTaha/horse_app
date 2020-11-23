<?php $__env->startSection('main'); ?>
<section >
<div class="container">
    <div class="row">
        <h2 class="title text-center"><?php echo e($title, false); ?></h2>
<?php if(count($orders) ==0): ?>
    <div class="col-md-12 text-danger">
        <?php echo e(trans('language.profile.empty_order'), false); ?>

    </div>
<?php else: ?>
<table class="table box  table-bordered table-responsive">
    <thead>
      <tr>
        <th style="width: 50px;">No.</th>
        <th style="width: 100px;">SKU</th>
        <th><?php echo e(trans('language.profile.total'), false); ?></th>
        <th><?php echo e(trans('language.profile.status'), false); ?></th>
        <th><?php echo e(trans('language.profile.date_add'), false); ?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $n = (isset($n)?$n:0);
            $n++;
            // $order = App\Models\ShopProduct::find($item->id);
        ?>
    <tr>
        <td><span class="item_21_id"><?php echo e($n, false); ?></span></td>
        <td><span class="item_21_sku">#<?php echo e($order->id, false); ?></span></td>
        <td align="right">
            <?php echo e(number_format($order->total), false); ?>

        </td>
        <td><?php echo e($statusOrder[$order->status], false); ?></td>
        <td><?php echo e($order->created_at, false); ?></td>
        <td>
            <a data-toggle="modal" data-target="#order-<?php echo e($order->id, false); ?>" href="#"><i class="glyphicon glyphicon-list-alt"></i> <?php echo e(trans('language.profile.detail_order'), false); ?></a>
        </td>
    </tr>

    <!-- Modal -->
    <div id="order-<?php echo e($order->id, false); ?>" class="modal fade" role="dialog" style="z-index: 9999;">
      <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php echo e(trans('language.profile.detail_order'), false); ?> #<?php echo e($order->id, false); ?></h4>
          </div>
          <div class="modal-body">
                <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="row">
                        <div class="col-md-4"><?php echo e($detail->name, false); ?> (<b>SKU:</b> <?php echo e($detail->sku, false); ?>)</div>
                        <div class="col-md-3" align="right"><?php echo e(number_format($detail->price), false); ?> </div>
                        <div class="col-md-2"><?php echo e($detail->attribute, false); ?></div>
                        <div class="col-md-1">x <?php echo e($detail->qty, false); ?></div>
                        <div class="col-md-2"   align="right"><?php echo e(number_format($detail->total_price), false); ?> </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<hr>
                <?php $__currentLoopData = $order->orderTotal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($total->value !=0): ?>
                    <div class="row">
                        <div class="col-md-10" align="right">
                            <?php echo $total->title; ?>

                        </div>
                        <div class="col-md-2"  align="right"><?php echo e(number_format($total->value), false); ?> </div>
                    </div>
                <?php endif; ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>


    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <style>
    .shipping_address td{
        padding: 3px;
    }
    .shipping_address textarea,.shipping_address input{
        width: 100%;
    }
</style>
    </tbody>
  </table>

<?php endif; ?>
</div>
</div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'.shop_layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>