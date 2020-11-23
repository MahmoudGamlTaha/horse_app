<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php echo e($title, false); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div><button id="generate" class="btn btn-success" data-loading-text="<?php echo e(trans('language.backup.processing'), false); ?>"><span class="glyphicon glyphicon-plus"></span><?php echo e(trans('language.backup.generate_now'), false); ?></button></div>
             <table id="main-table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th><?php echo e(trans('language.backup.sort'), false); ?></th>
                  <th><?php echo e(trans('language.backup.date'), false); ?></th>
                  <th><?php echo e(trans('language.backup.name'), false); ?></th>
                  <th><?php echo e(trans('language.backup.size'), false); ?></th>
                  <th><?php echo e(trans('language.backup.download'), false); ?></th>
                  <th><?php echo e(trans('language.backup.remove'), false); ?></th>
                  <th><?php echo e(trans('language.backup.restore'), false); ?></th>
                </tr>
                </thead>
                <tbody>
                  <?php $__currentLoopData = $arrFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                     <td><?php echo e($key+1, false); ?></td>
                     <td><?php echo e($file['time'], false); ?></td>
                     <td><?php echo e($file['name'], false); ?></td>
                     <td><?php echo e($file['size'], false); ?></td>
                      <td><?php echo '<a href="?download='.$file['name'].'"><button title="'.trans('language.backup.download').'" data-loading-text="'.trans('language.backup.processing').'" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> '.trans('language.backup.download').'</button ></a>'; ?></td>
                      <td><?php echo '<button  onClick="processBackup($(this),\''.$file['name'].'\',\'remove\');" title="'.trans('language.backup.remove').'" data-loading-text="'.trans('language.backup.processing').'" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> '.trans('language.backup.remove').'</button >'; ?></td>
                      <td><?php echo '<button  onClick="processBackup($(this),\''.$file['name'].'\',\'restore\');" title="'.trans('language.backup.restore').'" data-loading-text="'.trans('language.backup.processing').'" class="btn btn-warning"><span class="glyphicon glyphicon-retweet"></span> '.trans('language.backup.restore').'</button >'; ?></td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
  function processBackup(obj,file,action) {
      var checkstr =  confirm('are you sure you want to do this?');
      if(checkstr == true){
      obj.button('loading');
      $.ajax({
        type: 'POST',
        dataType:'json',
        url: '<?php echo e(route('processBackupFile'), false); ?>',
        data: {
          "_token": "<?php echo e(csrf_token(), false); ?>",
          "file":file,
          "action":action,
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

  function generateBackup(obj) {
      obj.button('loading');
      $.ajax({
        type: 'POST',
        dataType:'json',
        url: '<?php echo e(route('generateBackup'), false); ?>',
        data: {
          "_token": "<?php echo e(csrf_token(), false); ?>",
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

$('#generate').click(function(){
  generateBackup($(this));
});
</script>
