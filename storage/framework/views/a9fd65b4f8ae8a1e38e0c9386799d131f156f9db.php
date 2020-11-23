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
                  <th><?php echo e(trans('language.modules.code'), false); ?></th>
                  <th><?php echo e(trans('language.modules.name'), false); ?></th>
                  <th><?php echo e(trans('language.modules.sort'), false); ?></th>
                  <th><?php echo e(trans('language.modules.status'), false); ?></th>
                  <th><?php echo e(trans('language.modules.action'), false); ?></th>
                </tr>
                </thead>
                <tbody>
                  <?php if(!$modules): ?>
                    <tr>
                      <td colspan="5" style="text-align: center;color: red;">Empty module!</td>
                    </tr>
                  <?php else: ?>
                  <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php
                    $moduleClass = $namespace.'\\'.$module;
                    if(!array_key_exists($module, $modulesInstalled->toArray())){
                      $moduleStatus = null;
                      $moduleStatusTitle = trans('language.modules.not_install');
                      $moduleAction = '<span onClick="installModule($(this),\''.$module.'\');" title="'.trans('language.modules.install').'" type="button" class="btn btn-flat btn-success"><i class="fa fa-plus-circle"></i></span>';
                    }else{
                      if($modulesInstalled[$module]['value']){
                        $moduleStatus = 1;
                        $moduleStatusTitle = trans('language.modules.actived');
                        $moduleAction ='<span onClick="disableModule($(this),\''.$module.'\');" title="'.trans('language.modules.disable').'" type="button" class="btn btn-flat btn-warning btn-flat"><i class="fa fa-power-off"></i></span>&nbsp;
                              <span onClick="uninstallModule($(this),\''.$module.'\');" title="'.trans('language.modules.remove').'" class="btn btn-flat btn-danger"><i class="fa fa-trash"></i></span>';
                      }else{
                        $moduleStatus = 0;
                        $moduleStatusTitle = trans('language.modules.disabled');
                        $moduleAction = '<span onClick="enableModule($(this),\''.$module.'\');" title="'.trans('language.modules.enable').'" type="button" class="btn btn-flat btn-primary"><i class="fa fa-paper-plane"></i></span>&nbsp;
                              <span onClick="uninstallModule($(this),\''.$module.'\');" title="'.trans('language.modules.remove').'" class="btn btn-flat btn-danger"><i class="fa fa-trash"></i></span>';
                      }
                    }
                  ?>
                    <tr>
                      <td><?php echo e($module, false); ?></td>
                      <td><?php echo e((new $moduleClass)->title, false); ?></td>
                      <td><?php echo e(isset($modulesInstalled[$module]['sort'])?$modulesInstalled[$module]['sort']:'', false); ?></td>
                      <td><?php echo e($moduleStatusTitle, false); ?></td>
                      <td><?php echo $moduleAction; ?></td>
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
  function enableModule(obj,key) {
      obj.button('loading');
      $.ajax({
        type: 'POST',
        dataType:'json',
        url: '<?php echo e(route('enableModule'), false); ?>',
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
  function disableModule(obj,key) {
      obj.button('loading');
      $.ajax({
        type: 'POST',
        dataType:'json',
        url: '<?php echo e(route('disableModule'), false); ?>',
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
  function installModule(obj,key) {
      obj.button('loading');
      $.ajax({
        type: 'POST',
        dataType:'json',
        url: '<?php echo e(route('installModule'), false); ?>',
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
  function uninstallModule(obj,key) {
    var checkstr =  confirm('are you sure you want to uninstall this?');
      if(checkstr == true){
            obj.button('loading');
            $.ajax({
              type: 'POST',
              dataType:'json',
              url: '<?php echo e(route('uninstallModule'), false); ?>',
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
