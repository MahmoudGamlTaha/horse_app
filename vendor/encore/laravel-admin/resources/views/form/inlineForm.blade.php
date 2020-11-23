<div class="{{$viewClass['row']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label for="{{$id}}" class="{{$viewClass['xs']}}-2 control-label">{{$label}}</label>
   

        @include('admin::form.error')
        @if($groups)
                @foreach($groups as $group)
                <div class="{{$viewClass['sm-col']}}-1">
        <span style="margin-right: 12px;right:0px;"><b>{{ trans($group['label'])}}</b></span>                
        <select class="form-control {{$class}}" style="width: 100%;" name="{{$group['name']}}" {!! $attributes !!} >
                 
                        @foreach($group['options'] as $select => $option)
                            <option value="{{$select}}" {{ $select == old($column, $group['column']) ?'selected':'' }}>{{$option}}</option>
                        @endforeach      
        </select>
        </div>
                @endforeach
            @endif
            </div>
<br/>
        @include('admin::form.help-block')