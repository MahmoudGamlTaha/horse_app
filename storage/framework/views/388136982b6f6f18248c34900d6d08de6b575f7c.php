<!--main left-->
<div class="col-sm-3">
   <?php $__env->startSection('left'); ?>
        <div class="left-sidebar">
      <!--Module left -->
          <?php if(isset($layouts['left'])): ?>
              <?php $__currentLoopData = $layouts['left']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
      <!--//Module left -->
      </div>
    <?php echo $__env->yieldSection(); ?>
</div>
<!--//main left-->
