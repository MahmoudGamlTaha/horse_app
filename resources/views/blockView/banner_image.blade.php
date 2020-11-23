  @php
    $banners = \App\Models\Banner::where('status', 1)->sort()->get()
  @endphp
 @if (!empty($banners))
 
 <link rel="stylesheet" href="{{asset('js/dist/jquery.bxslider.css')}}"/> 
 <script src="{{asset('js/jquery.min.js')}}"></script>
  <script src="{{asset('js/dist/jquery.bxslider.js')}}">
     $(document).ready(function(){
        $('.bxslider').bxSlider({
          auto: true,
          autoControls: true,
          stopAutoOnClick: true,
          pager: true,
          slideWidth: 600
          });
    });
  </script>
 <section id="slider"><!--slider-->
    <div class="container">
    <div class="row">
    <div class="col-sm-12">
    <img src="{{ asset($path_file.'')}}/testbanner/test.png" class="top3img"></img>
    <img src="{{ asset($path_file.'')}}/testbanner/test.png" class="top3img"></img>
    <img src="{{ asset($path_file.'')}}/testbanner/test.png" class="top3img"></img>
    </div>
    </div>
    </div>
      <div class="row">
     
        <div class="col-sm-12">
          <div class="bxslider">
	           @foreach ($banners as $key => $banner) 
		     	   <div>
                <img data-u="image" src="{{ asset($path_file.'') }}/{{ $banner->image }}"></img>
            </div>
			@endforeach
          </div>   

        </div>
      </div>
    
  </section><!--/slider-->
@endif
