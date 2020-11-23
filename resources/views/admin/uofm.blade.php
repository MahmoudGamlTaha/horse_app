
       <table class="table box table-bordered">
         <thead>
         <tr>
           <th>code</th>
           <th>name</th>
           <th>amount_in_base</th>
         </tr>
         </thead>
         <tbody>
        @foreach ($datas as $data)
        <tr>
          <td>
            <a href="#" class="{{ !empty($data['disabled'])?'editable-disabled':'' }} {{ ($data['required'])?'fied-required':'no-required' }}" data-name="{{ $data['codeField'] }}" data-type="{{ $data['type'] }}" data-pk="{{ $data['key'] }}" data-source ="{{ $data['source'] }}" data-url="{{ $data['url'] }}" data-title="{!! trans(htmlentities($data['title'])) !!}" data-value="{{ $data['codeValue'] }}"></a>
          </td>
          <td>
            <a href="#" class="{{ !empty($data['disabled'])?'editable-disabled':'' }} {{ ($data['required'])?'fied-required':'no-required' }}" data-name="{{ $data['nameField'] }}" data-type="{{ $data['type'] }}" data-pk="{{ $data['key'] }}" data-source ="{{ $data['source'] }}" data-url="{{ $data['url'] }}" data-title="{!! trans(htmlentities($data['title'])) !!}" data-value="{{ $data['value'] }}"></a>
          </td>
          <td>
            <a href="#" class="{{ !empty($data['disabled'])?'editable-disabled':'' }} {{ ($data['required'])?'fied-required':'no-required' }}" data-name="{{ $data['amountField'] }}" data-type="number" data-pk="{{ $data['key'] }}" data-source ="{{ $data['source'] }}" data-url="{{ $data['url'] }}" data-title="{!! trans(htmlentities($data['title'])) !!}" data-value="{{ $data['amount'] }}"></a>
          </td>
        </tr>
        @endforeach
        </tbody>
         <tfoot>
        <tr>
          <td colspan="3">  
            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addNewUOF"  rel="tooltip" data-original-title="" title="Add new item"><i class="fa fa-plus"></i> {{trans('language.attribute.add_more')}}</button>
          
          </td>

        </tr>
         </tfoot>
      </table>
      <div class="modal fade" id="addNewUOF" tabindex="-1" role="dialog" aria-labelledby="addNewUOF" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
           <div class="modal-header">
             <h5> add new Item</h5>
           </div>
         
           <div class="model-body">
              <form  method="POST" action="/"  id="uofMCode"> 
                  <div class="container-fluid">
                <span style="margin-left: 68px" >code</span><input type="text" name="ucode" ></input>
                  <br/><br/>
                <span style="margin-left: 64px">name</span><input type="text"  name="uname"></input>
                <br/><br/>
                <span>amount in base </span> <input type="number" name="amount_base" ></input>
                <br/><br/>
                <span id="errMsg" style="color:red"> </span>
           
        </div>
        </div>
          
        <div class="modal-footer">
           <button type="button" class="btn btn-primary" onclick="uofMeasureSubmit()">Save changes</button>
           <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
           </form>
      </div>
      </div>
   
    </div>
  </div>
<script type="text/javascript">
$(document).ready(function() {
        $('.no-required').editable({});
        $('.fied-required').editable({
        validate: function(value) {
            if (value == '') {
                return '{{  trans('language.admin.not_empty') }}';
            }
        }
    });
   // uofMeasureSubmit();
}
);
function uofMeasureSubmit(){
  $("#errMsg").text('');
  code = $("input[name='ucode']").val();
  name = $("input[name='uname']").val();
  amount_base = $("input[name='amount_base']").val();
  if(!code.trim().length || !name.trim().length || !amount_base.length){
    $("#errMsg").append("data required");
 //    return;
  }
  $("#errMsg").text('');
  form = new FormData();
  form.append('ucode',code);
  form.append('uname', name);
  form.append('amount_base', amount_base);
  $.ajax({
    url:"addNewUofm",
    data:{ucode:code,uname:name,inbase:amount_base},
    dataType:"json",
    method:"post",
    beforeSend:function(xhr, type) {
              if (!type.crossDomain) {
                 xhr.setRequestHeader('X-CSRF-Token','{{csrf_token()}}');
               }},
   success:function(data){
    location.reload()
   },
   error:function(res, status, error){
    $("#errMsg").append(res.responseJSON.message);
    // console.log(error);
     $("input[name='ucode']").val('');
     $("input[name='uname']").val('');
     $("input[name='amount_base']").val('');
   }
  });
}
</script>
<script>
                function morItem(id){
                 
                        $("#no-item-"+id).remove();
                    $("tr#addnew-"+id).before("<tr><td><span><span class=\"input-group\"><input  type=\"text\" name=\"group["+id+"][name][]\" value=\"\" class=\"form-control\" placeholder=\"$detail_name\"></span></span></td><td><button onclick=\"removeItemForm(this);\" class=\"btn btn-danger btn-xs\" data-title=\"Delete\" data-toggle=\"modal\"  data-placement=\"top\" rel=\"tooltip\" data-original-title=\"\" title=\"Remove item\"><span class=\"glyphicon glyphicon-remove\"></span> $remove</button></td></tr>");
                    }

                    function removeItemForm(elmnt){
                      elmnt.closest("tr").remove();
                    }

</script>
