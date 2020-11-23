<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
    <style>
        @media  only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media  only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>

    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="header">
                            <?php echo e(trans('language.email.order.title_1',['website'=>config('app.name')]), false); ?>


                        </td>
                    </tr>

                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0">
                                <!-- Body content -->
                                <tr>
                                    <td>
                                        <b><?php echo e(trans('language.email.order.order_id'), false); ?></b>: <?php echo e($id, false); ?><br>
                                        <b><?php echo e(trans('language.email.order.toname'), false); ?></b>: <?php echo e($toname, false); ?><br>
                                        <b><?php echo e(trans('language.email.order.address'), false); ?></b>: <?php echo e($address1.' '.$address2, false); ?><br>
                                        <b><?php echo e(trans('language.email.order.phone'), false); ?></b>: <?php echo e($phone, false); ?><br>
                                        <b><?php echo e(trans('language.email.order.note'), false); ?></b>: <?php echo e($comment, false); ?>

                                    </td>
                                </tr>
                            </table>
                            <hr>
                            <p style="text-align: center;"><?php echo e(trans('language.email.order.order_detail'), false); ?>:<br>
                            ===================================<br></p>
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" border="1">
                                <tr>
                                    <td><?php echo e(trans('language.email.order.sort'), false); ?></td>
                                    <td><?php echo e(trans('language.email.order.sku'), false); ?></td>
                                    <td><?php echo e(trans('language.email.order.name'), false); ?></td>
                                    <td><?php echo e(trans('language.email.order.note'), false); ?></td>
                                    <td><?php echo e(trans('language.email.order.qty'), false); ?></td>
                                    <td><?php echo e(trans('language.email.order.total'), false); ?></td>
                                </tr>
                                <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key, false); ?></td>
                                    <td><?php echo e($detail['sku'], false); ?></td>
                                    <td><?php echo e($detail['name'], false); ?></td>
                                    <td><?php echo e(\Helper::currencyRender($detail['price']), false); ?></td>
                                    <td><?php echo e(number_format($detail['qty']), false); ?></td>
                                    <td align="right"><?php echo e(\Helper::currencyRender($detail['total_price']), false); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2" style="font-weight: bold;"><?php echo e(trans('language.email.order.sub_total'), false); ?></td>
                                    <td colspan="2" align="right"><?php echo e(\Helper::currencyRender($subtotal), false); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2" style="font-weight: bold;"><?php echo e(trans('language.email.order.shipping_fee'), false); ?></td>
                                    <td colspan="2" align="right"><?php echo e(\Helper::currencyRender($shipping), false); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2" style="font-weight: bold;"><?php echo e(trans('language.email.order.discount'), false); ?></td>
                                    <td colspan="2" align="right"><?php echo e(\Helper::currencyRender($discount), false); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2" style="font-weight: bold;"><?php echo e(trans('language.email.order.order_total'), false); ?></td>
                                    <td colspan="2" align="right"><?php echo e(\Helper::currencyRender($total), false); ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="content-cell" align="center">
                                        <p>&nbsp;</p>
                                         &copy; <?php echo e(date('Y'), false); ?> <a href="<?php echo e(url('/'), false); ?>"><?php echo e(config('app.name'), false); ?></a>. All rights reserved.
                                        
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
