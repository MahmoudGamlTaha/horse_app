  <?php
    $brands = (new \App\Models\ShopBrand)->getBrandsList()
  ?>
  <?php if(!empty($brands)): ?>
              <div class="brands_products"><!--brands_products-->
                <h2><?php echo e(trans('language.brands'), false); ?></h2>
                <div class="brands-name">
                  <ul class="nav nav-pills nav-stacked">
                    <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <li><a href="<?php echo e($brand->getUrl(), false); ?>"> <span class="pull-right">(<?php echo e($brand->products()->count(), false); ?>)</span><?php echo e($brand->name, false); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </ul>
                </div>
              </div><!--/brands_products-->
  <?php endif; ?>
