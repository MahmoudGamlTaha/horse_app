  <?php
    $categories = (new \App\Models\ShopCategory)->getCategoriesAll();
    $categoriesTop = (new \App\Models\ShopCategory)->getCategoriesTop();
  ?>
  <?php if($categoriesTop->count()): ?>
              <h2><?php echo e(trans('language.categories'), false); ?></h2>
              <div class="panel-group category-products" id="accordian">
              <?php $__currentLoopData = $categoriesTop; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>  $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(!empty($categories[$category->id])): ?>
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordian" href="#<?php echo e($key, false); ?>">
                        <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                      </a>
                      <a href="<?php echo e($category->getUrl(), false); ?>">  <?php echo e($category->name, false); ?></a>
                    </h4>
                  </div>
                  <div id="<?php echo e($key, false); ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                      <ul>
                        <?php $__currentLoopData = $categories[$category->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cateChild): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                - <a href="<?php echo e($cateChild->getUrl(), false); ?>"><?php echo e($cateChild->name, false); ?></a>
                                <ul>
                                  <?php if(!empty($categories[$cateChild->id])): ?>
                                    <?php $__currentLoopData = $categories[$cateChild->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cateChild2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            -- <a href="<?php echo e($cateChild2->getUrl(), false); ?>"><?php echo e($cateChild2->name, false); ?></a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  <?php endif; ?>
                                </ul>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </ul>
                    </div>
                  </div>
                </div>
                <?php else: ?>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <a href="<?php echo e($category->getUrl(), false); ?>"><h4 class="panel-title"><a href="<?php echo e($category->getUrl(), false); ?>"><?php echo e($category->name, false); ?></a></h4></a>
                    </div>
                  </div>
               <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
  <?php endif; ?>
