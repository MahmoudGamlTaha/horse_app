<link href="{{admin_asset('vendor/laravel-admin/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}" rel="stylesheet">
<script src="{{admin_asset('vendor/laravel-admin/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
	<div class="row">
		
        
        <div class="col-md-12">
        <div class="table-responsive">

                
              <table id="mytable" class="table table-bordred table-striped">
                   
                   <thead>
                   
                   <th><input type="checkbox" id="checkall" /></th>
                   <th>ID</th>
                     <th>{{trans('language.admin.image')}}</th>
                     <th>{{trans('language.product.sku')}}</th>
                     <th>{{trans('language.product.product_name')}}</th>
                     <th>{{trans('language.categories')}}</th>
                     <th>{{trans('language.product.price_cost')}}</th>                      
                     <th>{{trans('language.product.price')}}</th>
                     <th>{{trans('language.product.product_type')}}</th>
                     <th> {{trans('language.admin.status')}} </th>
                     <th>{{trans('language.admin.created_at')}}</th>
                   </thead>
    <tbody>
    @foreach($products as $product)
    <tr>
    <td><input type="checkbox" class="checkthis" /></td>
    <td>{{$product->id}}</td>
    <td><image src="{{$product->path??$base}}/{{$product->image}}" style="max-width:50px;max-height:200px" class="img img-thumbnail"></image></td>
    <td>{{$product->sku}}</td>
    <td>{{$product->name}}</td>
    <td>{{$product->category()->first()->name}}</td>
    <td>{{$product->cost}}</td>
    <td>{{$product->price}}</td>
    <td>{{$product->type}}</td>

   <td> 
     @if($product->state)
     <input type="checkbox" name="my-checkbox" checked>
     @else
     <input type="checkbox" name="my-checkbox">
     @endif
    </td>
   <td>{{$product->created_at}}</td>
    <td><p data-placement="top" data-toggle="tooltip" title="Edit"><a class="btn btn-primary btn-xs" href="/system_admin/shop_product/{{$product->id}}/edit" data-title="Edit" ><span class="glyphicon glyphicon-pencil"></span></a></p></td>
    <td><p data-placement="top" data-toggle="tooltip" title="Delete"><a class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></a></p></td>
    </tr>  
    @endforeach 
    </tbody>
        
</table>
{{$products->links()}}                
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
    