<link href="<?php echo e(admin_asset('vendor/laravel-admin/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css'), false); ?>" rel="stylesheet">
<script src="<?php echo e(admin_asset('vendor/laravel-admin/bootstrap-switch/dist/js/bootstrap-switch.min.js'), false); ?>"></script>
	<div class="row">
		
        
        <div class="col-md-12">
        <div class="table-responsive">

                
              <table id="mytable" class="table table-bordred table-striped">
                   
                   <thead>
                   
                   <th><input type="checkbox" id="checkall" /></th>
                   <th>ID</th>
                     <th><?php echo e(trans('language.admin.image'), false); ?></th>
                     <th><?php echo e(trans('language.product.sku'), false); ?></th>
                     <th><?php echo e(trans('language.product.product_name'), false); ?></th>
                     <th><?php echo e(trans('language.categories'), false); ?></th>
                     <th><?php echo e(trans('language.product.price_cost'), false); ?></th>                      
                     <th><?php echo e(trans('language.product.price'), false); ?></th>
                     <th><?php echo e(trans('language.product.product_type'), false); ?></th>
                     <th> <?php echo e(trans('language.admin.status'), false); ?> </th>
                     <th><?php echo e(trans('language.admin.created_at'), false); ?></th>
                   </thead>
    <tbody>
    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
    <td><input type="checkbox" class="checkthis" /></td>
    <td><?php echo e($product->id, false); ?></td>
    <td><image src="<?php echo e($product->path??$base, false); ?>/<?php echo e($product->image, false); ?>" style="max-width:50px;max-height:200px" class="img img-thumbnail"></image></td>
    <td><?php echo e($product->sku, false); ?></td>
    <td><?php echo e($product->name, false); ?></td>
    <td><?php echo e($product->category()->first()->name, false); ?></td>
    <td><?php echo e($product->cost, false); ?></td>
    <td><?php echo e($product->price, false); ?></td>
    <td><?php echo e($product->type, false); ?></td>

   <td> 
     <?php if($product->state): ?>
     <input type="checkbox" name="my-checkbox" checked>
     <?php else: ?>
     <input type="checkbox" name="my-checkbox">
     <?php endif; ?>
    </td>
   <td><?php echo e($product->created_at, false); ?></td>
    <td><p data-placement="top" data-toggle="tooltip" title="Edit"><a class="btn btn-primary btn-xs" href="/system_admin/shop_product/<?php echo e($product->id, false); ?>/edit" data-title="Edit" ><span class="glyphicon glyphicon-pencil"></span></a></p></td>
    <td><p data-placement="top" data-toggle="tooltip" title="Delete"><a class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></a></p></td>
    </tr>  
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
    </tbody>
        
</table>
<?php echo e($products->links(), false); ?>                
            </div>    
        </div>
	</div>
</div>


<div class="modal fade in" id="delete" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
      <div class="modal-dialog">
    <div class="modal-content">
          <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
        <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
      </div>
          <div class="modal-body">
       
       <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Record?</div>
       
      </div>
        <div class="modal-footer ">
        <button type="button" class="btn btn-success" ><span class="glyphicon glyphicon-ok-sign"></span> Yes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
      </div>
        </div>
		</div>
		</div>
    
    <!-- /.modal-content --> 
  </div>
  <script type="text/javascript">
  $(document).ready(function(){
    $("[name='my-checkbox']").bootstrapSwitch();
$("#mytable #checkall").click(function () {
        if ($("#mytable #checkall").is(':checked')) {
            $("#mytable input[type=checkbox]").each(function () {
                $(this).prop("checked", true);
            });

        } else {
            $("#mytable input[type=checkbox]").each(function () {
                $(this).prop("checked", false);
            });
        }
    });
    
    $("[data-toggle=tooltip]").tooltip();
});
</script>
      <!-- /.modal-dialog --> 
    