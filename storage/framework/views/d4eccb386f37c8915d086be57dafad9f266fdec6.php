  <?php
    $banners = \App\Models\Banner::where('status', 1)->sort()->get()
  ?>
 <?php if(!empty($banners)): ?>
 
 <link rel="stylesheet" href="<?php echo e(asset('js/dist/jquery.bxslider.css'), false); ?>"/> 
 <script src="<?php echo e(asset('js/jquery.min.js'), false); ?>"></script>
  <script src="<?php echo e(asset('js/dist/jquery.bxslider.js'), false); ?>">
     $(document).ready(function(){
        $('.bxslider').bxSlider({
          auto: true,
          autoControls: true,
          stopAutoOnClick: true,
          pager: true,
          slideWidth: 600
          });
    });
  </script>
 <section id="slider"><!--slider-->
    <div class="container">
    <div class="row">
    <div class="col-sm-12">
    <img src="<?php echo e(asset($path_file.''), false); ?>/testbanner/test.png" class="top3img"></img>
    <img src="<?php echo e(asset($path_file.''), false); ?>/testbanner/test.png" class="top3img"></img>
    <img src="<?php echo e(asset($path_file.''), false); ?>/testbanner/test.png" class="top3img"></img>
    </div>
    </div>
    </div>
      <div class="row">
     
        <div class="col-sm-12">
          <div class="bxslider">
	           <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
		     	   <div>
                <img data-u="image" src="<?php echo e(asset($path_file.''), false); ?>/<?php echo e($banner->image, false); ?>"></img>
            </div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>   

        </div>
      </div>
    
  </section><!--/slider-->
<?php endif; ?>
