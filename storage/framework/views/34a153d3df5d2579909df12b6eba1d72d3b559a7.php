<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php echo e($title, false); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <table id="main-table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th><?php echo e(trans('language.templates.name'), false); ?></th>
                  <th><?php echo e(trans('language.templates.auth'), false); ?></th>
                  <th><?php echo e(trans('language.templates.email'), false); ?></th>
                  <th><?php echo e(trans('language.templates.website'), false); ?></th>
                  <th><?php echo e(trans('language.templates.status'), false); ?></th>
                </tr>
                </thead>
                <tbody>
                  <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                     <td><?php echo e($template['config']['name']??'', false); ?></td>
                     <td><?php echo e($template['config']['auth']??'', false); ?></td>
                     <td><?php echo e($template['config']['email']??'', false); ?></td>
                     <td><?php echo e($template['config']['website']??'', false); ?></td>
                      <td><?php echo ($templateCurrent == $key)?'<button title="'.trans('language.templates.active').'"  class="btn">'.trans('language.templates.active').'</button >':'<button  onClick="enableTemplate($(this),\''.$key.'\');" title="'.trans('language.templates.inactive').'" data-loading-text="'.trans('language.templates.installing').'" class="btn btn-primary">'.trans('language.templates.inactive').'</button >'; ?></td>
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
  function enableTemplate(obj,key) {
      obj.button('loading');
      $.ajax({
        type: 'POST',
        dataType:'json',
        url: '<?php echo e(route('changeTemplate'), false); ?>',
        data: {
          "_token": "<?php echo e(csrf_token(), false); ?>",
          "key":key,
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
</script>
