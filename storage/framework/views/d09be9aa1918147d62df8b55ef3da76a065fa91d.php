<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php echo e($title, false); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th><?php echo e(trans('language.extensions.code'), false); ?></th>
                  <th><?php echo e(trans('language.extensions.name'), false); ?></th>
                  <th><?php echo e(trans('language.extensions.sort'), false); ?></th>
                  <th><?php echo e(trans('language.extensions.status'), false); ?></th>
                  <th><?php echo e(trans('language.extensions.action'), false); ?></th>
                </tr>
                </thead>
                <tbody>
                  <?php if(!$extensions): ?>
                    <tr>
                      <td colspan="5" style="text-align: center;color: red;">Empty extension!</td>
                    </tr>
                  <?php else: ?>
                  <?php $__currentLoopData = $extensions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $extension): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php
                    $extensionClass = $namespace.'\\'.$extension;
                    if(!array_key_exists($extension, $extensionsInstalled->toArray())){
                      $extensionStatus = null;
                      $extensionStatusTitle = trans('language.extensions.not_install');
                      $extensionAction = '<span onClick="installExtension($(this),\''.$extension.'\');" title="'.trans('language.extensions.install').'" type="button" class="btn btn-flat btn-success"><i class="fa fa-plus-circle"></i></span>';
                    }else{
                      if($extensionsInstalled[$extension]['value']){
                        $extensionStatus = 1;
                        $extensionStatusTitle = trans('language.extensions.actived');
                        $extensionAction ='<span onClick="disableExtension($(this),\''.$extension.'\');" title="'.trans('language.extensions.disable').'" type="button" class="btn btn-flat btn-warning btn-flat"><i class="fa fa-power-off"></i></span>&nbsp;
                              <a href="'.url()->current().'?action=config&extensionKey='.$extension.'"><span title="'.trans('language.extensions.config').'" class="btn btn-flat btn-primary"><i class="fa fa-gears"></i></span>&nbsp;</a>
                              <span onClick="uninstallExtension($(this),\''.$extension.'\');" title="'.trans('language.extensions.remove').'" class="btn btn-flat btn-danger"><i class="fa fa-trash"></i></span>';
                      }else{
                        $extensionStatus = 0;
                        $extensionStatusTitle = trans('language.extensions.disabled');
                        $extensionAction = '<span onClick="enableExtension($(this),\''.$extension.'\');" title="'.trans('language.extensions.enable').'" type="button" class="btn btn-flat btn-primary"><i class="fa fa-paper-plane"></i></span>&nbsp;
                              <a href="'.url()->current().'?action=config&extensionKey='.$extension.'"><span title="'.trans('language.extensions.config').'" class="btn btn-flat btn-primary"><i class="fa fa-gears"></i></span>&nbsp;</a>
                              <span onClick="uninstallExtension($(this),\''.$extension.'\');" title="'.trans('language.extensions.remove').'" class="btn btn-flat btn-danger"><i class="fa fa-trash"></i></span>';
                      }
                    }
                  ?>
                    <tr>
                      <td><?php echo e($extension, false); ?></td>
                      <td><?php echo e((new $extensionClass)->title, false); ?></td>
                      <td><?php echo e(isset($extensionsInstalled[$extension]['sort'])?$extensionsInstalled[$extension]['sort']:'', false); ?></td>
                      <td><?php echo e($extensionStatusTitle, false); ?></td>
                      <td><?php echo $extensionAction; ?></td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    <div>
</div>
</section>
<script type="text/javascript">
  function enableExtension(obj,key) {
      obj.button('loading');
      $.ajax({
        type: 'POST',
        dataType:'json',
        url: '<?php echo e(route('enableExtension'), false); ?>',
        data: {
          "_token": "<?php echo e(csrf_token(), false); ?>",
          "key":key,
          "group":"<?php echo e($group, false); ?>"
        },
        success: function (response) {
          console.log(response);
          if(parseInt(response.error) ==0){
              location.reload();
          }else{
              obj.button('reset');
              alert(response.msg);
          }
        }
      });

  }
  function disableExtension(obj,key) {
      obj.button('loading');
      $.ajax({
        type: 'POST',
        dataType:'json',
        url: '<?php echo e(route('disableExtension'), false); ?>',
        data: {
          "_token": "<?php echo e(csrf_token(), false); ?>",
          "key":key,
          "group":"<?php echo e($group, false); ?>"
        },
        success: function (response) {
          console.log(response);
          if(parseInt(response.error) ==0){
              location.reload();
          }else{
              obj.button('reset');
              alert(response.msg);
          }
        }
      });
  }
  function installExtension(obj,key) {
      obj.button('loading');
      $.ajax({
        type: 'POST',
        dataType:'json',
        url: '<?php echo e(route('installExtension'), false); ?>',
        data: {
          "_token": "<?php echo e(csrf_token(), false); ?>",
          "key":key,
          "group":"<?php echo e($group, false); ?>"
        },
        success: function (response) {
          console.log(response);
          if(parseInt(response.error) ==0){
              location.reload();
          }else{
              obj.button('reset');
              alert(response.msg);
          }
        }
      });
  }
  function uninstallExtension(obj,key) {
    var checkstr =  confirm('are you sure you want to uninstall this?');
      if(checkstr == true){
            obj.button('loading');
            $.ajax({
              type: 'POST',
              dataType:'json',
              url: '<?php echo e(route('uninstallExtension'), false); ?>',
              data: {
                "_token": "<?php echo e(csrf_token(), false); ?>",
                "key":key,
                "group":"<?php echo e($group, false); ?>"
              },
              success: function (response) {
                console.log(response);
                if(parseInt(response.error) ==0){
                    location.reload();
                }else{
                    obj.button('reset');
                    alert(response.msg);
                }
              }
            });
      }else{
      return false;
      }
  }
</script>
