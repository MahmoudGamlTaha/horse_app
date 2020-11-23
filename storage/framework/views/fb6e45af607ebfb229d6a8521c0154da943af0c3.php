<?php
  $carts = \Helper::getListCart();
?>
<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale(), false); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo e($description??'', false); ?>">
    <meta name="keyword" content="<?php echo e($keyword??'', false); ?>">
    <meta property="fb:app_id" content="<?php echo e($configsGlobal['site_fb_appID'], false); ?>" />
    <title><?php echo e($title??'', false); ?></title>
    <meta property="og:image" content="<?php echo e(!empty($og_image)?$og_image:asset('images/org.jpg'), false); ?>" />
    <meta property="og:url" content="<?php echo e(\Request::fullUrl(), false); ?>" />
    <meta property="og:type" content="Website" />
    <meta property="og:title" content="<?php echo e($title??'', false); ?>" />
    <meta property="og:description" content="<?php echo e($description??'', false); ?>" />
<!--Module meta -->
  <?php if(isset($layouts['meta'])): ?>
      <?php $__currentLoopData = $layouts['meta']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($layout->page == null ||  $layout->page =='*' || $layout->page =='' || (isset($layout_page) && in_array($layout_page, $layout->page) ) ): ?>
          <?php if($layout->page =='html'): ?>
            <?php echo e($layout->text, false); ?>

          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endif; ?>
<!--//Module meta -->
    <link href="<?php echo e(asset($theme_asset.'/css/bootstrap.min.css'), false); ?>" rel="stylesheet">
    <link href="<?php echo e(asset($theme_asset.'/css/font-awesome.min.css'), false); ?>" rel="stylesheet">
    <link href="<?php echo e(asset($theme_asset.'/css/prettyPhoto.css'), false); ?>" rel="stylesheet">
    <link href="<?php echo e(asset($theme_asset.'/css/animate.css'), false); ?>" rel="stylesheet">
    <link href="<?php echo e(asset($theme_asset.'/css/main.css'), false); ?>" rel="stylesheet">
    <link href="<?php echo e(asset($theme_asset.'/css/responsive.css'), false); ?>" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="<?php echo e(asset($theme_asset.'/js/html5shiv.js'), false); ?>"></script>
    <script src="<?php echo e(asset($theme_asset.'/js/respond.min.js'), false); ?>"></script>
    <![endif]-->
    <link rel="shortcut icon" href="<?php echo e(asset($theme_asset.'/images/ico/favicon.ico'), false); ?>">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo e(asset($theme_asset.'/images/ico/apple-touch-icon-144-precomposed.png'), false); ?>">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo e(asset($theme_asset.'/images/ico/apple-touch-icon-114-precomposed.png'), false); ?>">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo e(asset($theme_asset.'/images/ico/apple-touch-icon-72-precomposed.png'), false); ?>">
    <link rel="apple-touch-icon-precomposed" href="<?php echo e(asset($theme_asset.'/images/ico/apple-touch-icon-57-precomposed.png'), false); ?>">
<!--Module header -->
  <?php if(isset($layouts['header'])): ?>
      <?php $__currentLoopData = $layouts['header']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($layout->page == null ||  $layout->page =='*' || $layout->page =='' || (isset($layout_page) && in_array($layout_page, $layout->page) ) ): ?>
          <?php if($layout->page =='html'): ?>
            <?php echo e($layout->text, false); ?>

          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endif; ?>
<!--//Module header -->

</head><!--/head-->
<body>

<?php echo $__env->make($theme.'.header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<!--Module banner -->
  <?php if(isset($layouts['banner_top'])): ?>
      <?php $__currentLoopData = $layouts['banner_top']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($layout->page == null ||  $layout->page =='*' || $layout->page =='' || (isset($layout_page) && in_array($layout_page, $layout->page) ) ): ?>
          <?php if($layout->type =='html'): ?>
            <?php echo $layout->text; ?>

          <?php elseif($layout->type =='view'): ?>
            <?php if(view()->exists('blockView.'.$layout->text)): ?>
             <?php echo $__env->make('blockView.'.$layout->text, \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>
          <?php elseif($layout->type =='module'): ?>
            <?php echo (new $layout->text)->render(); ?>

          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endif; ?>
<!--//Module banner -->


<?php if($configs['site_status']): ?>

<!--Module top -->
  <?php if(isset($layouts['top'])): ?>
      <?php $__currentLoopData = $layouts['top']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($layout->page == null ||  $layout->page =='*' || $layout->page =='' || (isset($layout_page) && in_array($layout_page, $layout->page) ) ): ?>
          <?php if($layout->type =='html'): ?>
            <?php echo $layout->text; ?>

          <?php elseif($layout->type =='view'): ?>
            <?php if(view()->exists('blockView.'.$layout->text)): ?>
             <?php echo $__env->make('blockView.'.$layout->text, \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>
          <?php elseif($layout->type =='module'): ?>
            <?php echo (new $layout->text)->render(); ?>

          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endif; ?>
<!--//Module top -->


  <section>
    <div class="container">
      <div class="row">
        <div class="col-sm-12" id="breadcrumb">
          <!--breadcrumb-->
          <?php echo $__env->yieldContent('breadcrumb'); ?>
          <!--//breadcrumb-->

          <!--//fillter-->
          <?php echo $__env->yieldContent('filter'); ?>
          <!--//fillter-->
        </div>

        <!--body-->
        <?php $__env->startSection('main'); ?>
          <?php echo $__env->make($theme.'.left', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
          <?php echo $__env->make($theme.'.center', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
          <?php echo $__env->make($theme.'.right', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php echo $__env->yieldSection(); ?>
        <!--//body-->

      </div>
    </div>
  </section>

<?php else: ?>
  <section>
    <div class="container">
      <div class="row">
        <div id="columns" class="container"  style="color:red;text-align: center;">
          <img src="<?php echo e(asset('images/maintenance.png'), false); ?>"><br>
          <h3><i class="fas fa-exclamation"></i><?php echo e(trans('language.maintenance'), false); ?></h3>
            <!-- /.col -->
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php echo $__env->make($theme.'.footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<script src="<?php echo e(asset($theme_asset.'/js/jquery.js'), false); ?>"></script>
<script src="<?php echo e(asset($theme_asset.'/js/jquery-ui.min.js'), false); ?>"></script>
<script src="<?php echo e(asset($theme_asset.'/js/bootstrap.min.js'), false); ?>"></script>
<script src="<?php echo e(asset($theme_asset.'/js/jquery.scrollUp.min.js'), false); ?>"></script>
<script src="<?php echo e(asset($theme_asset.'/js/jquery.prettyPhoto.js'), false); ?>"></script>
<script src="<?php echo e(asset($theme_asset.'/js/main.js'), false); ?>"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/mouse0270-bootstrap-notify/3.1.7/bootstrap-notify.min.js"></script>


<?php echo $__env->yieldPushContent('scripts'); ?>

    <script type="text/javascript">
      function formatNumber (num) {
          return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
      }
      $('#shipping').change(function(){
          $('#total').html(formatNumber(parseInt(<?php echo e(Cart::subtotal(), false); ?>)+ parseInt($('#shipping').val())));
      });
    </script>

    <script type="text/javascript">
        function addToCart(id,instance = null,element = null){
        $.ajax({
            url: '<?php echo e(route('addToCart'), false); ?>',
            type: 'POST',
            dataType: 'json',
            data: {id: id,instance:instance, _token:'<?php echo e(csrf_token(), false); ?>'},
            async: false,
            success: function(data){
              // console.log(data);
                error= parseInt(data.error);
                if(error ==0)
                {
                //animate
                if(instance == null || instance =='' || instance =='default'){
                  var cart = $('#shopping-cart');
                }else{
                  var cart = $('#shopping-'+instance);
                }
                var imgtodrag = element.closest('.product-single').find("img").eq(0);
                if (imgtodrag) {
                    var imgclone = imgtodrag.clone()
                        .offset({
                        top: imgtodrag.offset().top,
                        left: imgtodrag.offset().left
                    })
                        .css({
                        'opacity': '0.5',
                            'position': 'absolute',
                            'width': '150px',
                            'z-index': '9999'
                    })
                        .appendTo($('body'))
                        .animate({
                        'top': cart.offset().top,
                            'left': cart.offset().left,
                            'width': 75,
                            'height': 75
                    });
                    imgclone.animate({
                        'width': 0,
                            'height': 0
                    }, function () {
                        $(this).detach()
                    });
                }
                //End animate
                  setTimeout(function () {
                    if(data.instance =='default'){
                      $('.shopping-cart').html(data.count_cart);
                      // $('.shopping-cart-subtotal').html(data.subtotal);
                      // $('#shopping-cart-show').html(data.html);
                    }else{
                      $('.shopping-'+data.instance).html(data.count_cart);
                    }
                  }, 1000);

                    $.notify({
                      icon: 'glyphicon glyphicon-star',
                      message: data.msg
                    },{
                      type: 'success'
                    });
                // $('#cart-alert').html('<div class="cart-alert alert alert-success">'+data.msg+'</div>').fadeIn(100).delay(2000).fadeOut('slow');
                }else{
                  $.notify({
                  icon: 'glyphicon glyphicon-warning-sign',
                    message: data.msg
                  },{
                    type: 'danger'
                  });
                  // $('#cart-alert').html('<div class="cart-alert alert alert-danger">'+data.msg+'</div>').fadeIn(100).delay(2000).fadeOut('slow');
                }

                }
        });
    }
</script>

<!--message-->
    <?php if(Session::has('message')): ?>
    <script type="text/javascript">
        $.notify({
          icon: 'glyphicon glyphicon-star',
          message: "<?php echo Session::get('message'); ?>"
        },{
          type: 'success'
        });
    </script>
    <?php endif; ?>
    <?php if(Session::has('error')): ?>
    <script type="text/javascript">
        $.notify({
        icon: 'glyphicon glyphicon-warning-sign',
          message: "<?php echo Session::get('error'); ?>"
        },{
          type: 'danger'
        });
    </script>
    <?php endif; ?>
    <?php if(Session::has('warning')): ?>
    <script type="text/javascript">
        $.notify({
        icon: 'glyphicon glyphicon-warning-sign',
          message: "<?php echo Session::get('warning'); ?>"
        },{
          type: 'warning'
        });
    </script>
    <?php endif; ?>
<!--//message-->


<!--Module bottom -->
  <?php if(isset($layouts['bottom'])): ?>
      <?php $__currentLoopData = $layouts['bottom']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($layout->page == null ||  $layout->page =='*' || $layout->page =='' || (isset($layout_page) && in_array($layout_page, $layout->page) ) ): ?>
          <?php if($layout->type =='html'): ?>
            <?php echo $layout->text; ?>

          <?php elseif($layout->type =='view'): ?>
            <?php if(view()->exists('blockView.'.$layout->text)): ?>
             <?php echo $__env->make('blockView.'.$layout->text, \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>
          <?php elseif($layout->type =='module'): ?>
            <?php echo (new $layout->text)->render(); ?>

          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endif; ?>
<!--//Module bottom -->

</body>
</html>
