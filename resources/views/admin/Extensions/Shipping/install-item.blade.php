<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">{{ $title }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th width="40%">{{ trans('language.extensions.install') }}</th>
                  <th width="40%">{{ trans('language.extensions.install_condition') }}</th>
                  <th width="40%">{{ trans('language.company') }}</th>
                </tr>
                </thead>
                <tbody>
                @if(is_a($dataList, 'Illuminate\Database\Eloquent\Collection'))
                  @foreach($dataList as $data)
                    <tr>
                      <td><a href="#" class="updateData_num" data-name="fee" data-type="number" data-pk="{{ $data['id'] }}" data-url="{{ route('processExtension',['group'=>'Installment','key'=>'ShippingBasic']) }}" data-title="{{ trans('Extensions/Shipping/ShippingStandard.fee') }}">{{ $data['fee'] }}</a></td>
                      <td><a href="#" class="updateData_num" data-name="condition_fee" data-type="number" data-pk="{{ $data['id'] }}" data-url="{{ route('processExtension',['group'=>$group,'key'=>$key]) }}" data-title="{{ trans('Extensions/Shipping/ShippingStandard.shipping_free') }}">{{ $data['condition_fee'] }}</a></td>
                      <td><a href="#" class="updateData_num" data-name="company_id" data-type="number" data-pk="{{ $data['id'] }}" data-url="{{ route('processExtension',['group'=>$group,'key'=>$key]) }}" data-title=" {{trans('language.company')}}">{{ $data['company_id'] }}</a></td>
                    </tr>
                    @endforeach
                    @else 
                    <tr>
                      <td ><a href="#" class="updateData_num"   data-name="fee" data-type="number" data-pk="{{ $dataList['id'] }}" data-url="{{ route('processExtension',['group'=>'Installment','key'=>'ShippingBasic']) }}" data-title="{{ trans('Extensions/Shipping/ShippingStandard.fee') }}">{{ $dataList['fee'] }}</a></td>
                      <td><a href="#" class="updateData_num" data-name="condition_fee" data-type="number" data-pk="{{ $dataList['id'] }}" data-url="{{ route('processExtension',['group'=>$group,'key'=>$key]) }}" data-title="{{ trans('Extensions/Shipping/ShippingStandard.shipping_free') }}">{{ $dataList['condition_fee'] }}</a></td>
                      <td><a href="#" class="updateData_num" data-name="company_id" data-type="number" data-pk="{{ $dataList['id'] }}" data-url="{{ route('processExtension',['group'=>$group,'key'=>$key]) }}" data-title=" {{trans('language.company')}}">{{ $dataList['company_id'] }}</a></td>
                    </tr>
                    @endif
                </tbody>
                <tfoot>
                </tfoot>
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
$(document).ready(function() {
    $(".updateData_num").on("shown", function(e, editable) {
      var value = $(this).text().replace(/,/g, "");
      editable.input.$input.val(parseInt(value));
    });
    $('.updateData_num').editable({
    ajaxOptions: {
    type: 'put',
    dataType: 'json'
    },
    validate: function(value) {
        if (value == '') {
            return '{{  trans('language.admin.not_empty') }}';
        }
        if (!$.isNumeric(value)) {
            return '{{  trans('language.admin.only_numeric') }}';
        }
    }
    });

});

</script>
