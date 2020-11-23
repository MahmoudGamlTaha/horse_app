<?php if((sizeof($files) > 0) || (sizeof($directories) > 0)): ?>
<table class="table table-responsive table-condensed table-striped hidden-xs table-list-view">
  <thead>
    <th style='width:50%;'><?php echo e(Lang::get('laravel-filemanager::lfm.title-item'), false); ?></th>
    <th><?php echo e(Lang::get('laravel-filemanager::lfm.title-size'), false); ?></th>
    <th><?php echo e(Lang::get('laravel-filemanager::lfm.title-type'), false); ?></th>
    <th><?php echo e(Lang::get('laravel-filemanager::lfm.title-modified'), false); ?></th>
    <th><?php echo e(Lang::get('laravel-filemanager::lfm.title-action'), false); ?></th>
  </thead>
  <tbody>
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
      <td>
        <i class="fa <?php echo e($item->icon, false); ?>"></i>
        <a class="<?php echo e($item->is_file ? 'file' : 'folder', false); ?>-item clickable" data-id="<?php echo e($item->is_file ? $item->url : $item->path, false); ?>" title="<?php echo e($item->name, false); ?>">
          <?php echo e(str_limit($item->name, $limit = 40, $end = '...'), false); ?>

        </a>
      </td>
      <td><?php echo e($item->size, false); ?></td>
      <td><?php echo e($item->type, false); ?></td>
      <td><?php echo e($item->time, false); ?></td>
      <td class="actions">
        <?php if($item->is_file): ?>
          <a href="javascript:download('<?php echo e($item->name, false); ?>')" title="<?php echo e(Lang::get('laravel-filemanager::lfm.menu-download'), false); ?>">
            <i class="fa fa-download fa-fw"></i>
          </a>
          <?php if($item->thumb): ?>
            <a href="javascript:fileView('<?php echo e($item->url, false); ?>', '<?php echo e($item->updated, false); ?>')" title="<?php echo e(Lang::get('laravel-filemanager::lfm.menu-view'), false); ?>">
              <i class="fa fa-image fa-fw"></i>
            </a>
            <a href="javascript:cropImage('<?php echo e($item->name, false); ?>')" title="<?php echo e(Lang::get('laravel-filemanager::lfm.menu-crop'), false); ?>">
              <i class="fa fa-crop fa-fw"></i>
            </a>
            <a href="javascript:resizeImage('<?php echo e($item->name, false); ?>')" title="<?php echo e(Lang::get('laravel-filemanager::lfm.menu-resize'), false); ?>">
              <i class="fa fa-arrows fa-fw"></i>
            </a>
          <?php endif; ?>
        <?php endif; ?>
        <a href="javascript:rename('<?php echo e($item->name, false); ?>')" title="<?php echo e(Lang::get('laravel-filemanager::lfm.menu-rename'), false); ?>">
          <i class="fa fa-edit fa-fw"></i>
        </a>
        <a href="javascript:trash('<?php echo e($item->name, false); ?>')" title="<?php echo e(Lang::get('laravel-filemanager::lfm.menu-delete'), false); ?>">
          <i class="fa fa-trash fa-fw"></i>
        </a>
      </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tbody>
</table>

<table class="table visible-xs">
  <tbody>
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
      <td>
        <div class="media" style="height: 70px;">
          <div class="media-left">
            <div class="square <?php echo e($item->is_file ? 'file' : 'folder', false); ?>-item clickable"  data-id="<?php echo e($item->is_file ? $item->url : $item->path, false); ?>">
              <?php if($item->thumb): ?>
              <img src="<?php echo e($item->thumb, false); ?>">
              <?php else: ?>
              <i class="fa <?php echo e($item->icon, false); ?> fa-5x"></i>
              <?php endif; ?>
            </div>
          </div>
          <div class="media-body" style="padding-top: 10px;">
            <div class="media-heading">
              <p>
                <a class="<?php echo e($item->is_file ? 'file' : 'folder', false); ?>-item clickable" data-id="<?php echo e($item->is_file ? $item->url : $item->path, false); ?>">
                  <?php echo e(str_limit($item->name, $limit = 20, $end = '...'), false); ?>

                </a>
                &nbsp;&nbsp;
                
              </p>
            </div>
            <p style="color: #aaa;font-weight: 400"><?php echo e($item->time, false); ?></p>
          </div>
        </div>
      </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tbody>
</table>

<?php else: ?>
<p><?php echo e(trans('laravel-filemanager::lfm.message-empty'), false); ?></p>
<?php endif; ?>
