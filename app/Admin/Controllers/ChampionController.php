<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Champion;
use App\Models\ChampionDescription;
use App\Models\ChampionDetails;
use App\Models\ChampionDetailsDescription;
use App\Models\Country;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use App\Models\Language;
use Illuminate\Support\Facades\Storage;
use DB;
use Encore\Admin\Form\Field\Image;
use PayPal\Api\Details;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChampionController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @return Content
     */
    public $grades = ['GOLD'=>'Gold', 'SILVER'=>'Silver', 'BRONZE' => 'Bronze'];
    public $types  = ['CHAMPION' => 'Champion ', 'CLASS' => 'Class '];
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('language.champoines.champoines'));
            $content->description(' ');
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('language.champoines.champoines'));
            $content->description(' ');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('language.champoines.create'));
            $content->description(' ');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Champion::class, function (Grid $grid) {

            $grid->id(trans('language.admin.sort'))->sortable();
            $grid->name(trans('language.champoines.champoine'));
            $grid->image(trans('language.champoines.image'))->image('', 50);
            $grid->short_description(trans('language.champoine.short_desc'));
            $grid->active(trans('language.admin.status'))->switch();
            $grid->date(trans('language.champoines.date'));
            $grid->created_at(trans('language.admin.created_at'));
            $grid->model()->orderBy('id', 'desc');
            $grid->disableExport();
            $grid->disableFilter();
            $grid->actions(function ($actions) {
                $actions->disableView();
            });
            $grid->tools(function ($tools) {
                $tools->disableRefreshButton();
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Champion
     */
    public function getChampiones(Request $request){
        try{
            $type = $request->type??['CLASS','CHAMPION'];
            $date = $request->date?? null;
            $lang_id = $request->lang_id ?? 1;
            if($date != null && !is_numeric($date)){
               return $this->sendError("error champion date");
            }
            $champion = new Champion();
           if(!is_array($type)){
             $type = [$type];
            }
            $selected = $champion->getChampions($lang_id, $type, $date);
            return $this->sendResponse($selected, 200);
                        
     }catch(\Exception $ex){
         return $this->sendError("champion error ", 400);
      }
  }
  public function getChampionesYears(Request $request){
    $champions = new Champion(); 
    $years = $champions->getChampionsYears();
    return $this->sendResponse($years, 200);
  }
  public function getChampioneFilterNameCountry(Request $request){
    try{
      $champion= new Champion();
      $lang_id    = $request->lang_id ?? 1;
      $country = $request->country?? null;
      $title   = $request->title ?? ''; 
      $data = $champion->getChampionsWithCriteria($lang_id, $country, $title);
      return $this->sendResponse($data, 200);
    }catch(\Exception $ex){
        return $this->sendError($ex->getMessage(), 400);
    }
  }
  public function getCountryList(Request $request){
      $lang_id  = $request->lang_id ?? 1;
     //$championDesc = new ChampionDescription();
     //$result = $championDesc->getCountries($lang_id);
     $result = Country::select('name','id')->where("active", 1)
                        ->where('lang_id', $lang_id)
                        ->get();
     return $this->sendResponse($result, 200);
  }
  public function getChampionesByName(Request $request){

  }
  
    protected function form( $id = null)
    {
     //   Admin::script($this->jsProcess());
        return Admin::form(Champion::class, function (Form $form) {
            $languages = Language::getLanguages();
        $form->tab(trans("language.champoines.champoines"), function ($form) use($languages){
   
            $company = $this->getUserCompany()[0]->id;
            if(!$this->checkSuperUser()){
                 abort(404); 
            }
            $arrParameters = request()->route()->parameters();
            $idCheck       = (int) end($arrParameters);
            $langDescriptions = new ChampionDescription();
           // $championDetails = new ChampionDetails();
            $ChampionDetailsDescription = new ChampionDetailsDescription();
          if($idCheck){
           //   $championDetails = ChampionDetails::where("champion_id", $idCheck)->first();
             
          }
            $form->image('image', trans('language.champoines.image'));
            $form->date('date');
         //   dd($championDetails->horse_image);
         //   $form->html("<div class='form-group'><img src='".$championDetails->path.''.$championDetails->horse_image."'><img></div>");
            $form->radio('type', trans('language.champoines.type'))->options($this->types)->default('CHAMPOINE');
            $form->model()->company_id =  $company ;
            $form->model()->path = Storage::disk(config('admin.upload.disk'))->url('');
            $form->switch('active', trans('language.admin.status'));
            $form->disableViewCheck();
            $form->disableEditingCheck();

            $arrFields = array();
            if(!$this->checkSuperUser())
            {
              $form->model()->company_id = $this->getUserCompany()[0]->id;
            }
            foreach ($languages as $key => $language) {
               // dd($ChampionDetailsDescription);
               if($idCheck){
                $langDescriptions =  ChampionDescription::where("champion_id", $idCheck)->where("lang_id", $language->id)->first();
               }
                $form->html('<span>'.$language->name.'</span>');
                $form->text($language->code . '__name', trans('language.champoines.champoine'))->rules('required', ['required' => trans('validation.required')])->default(!empty($langDescriptions->name) ? $langDescriptions->name : null);
                $form->ckeditor($language->code . '__description', trans('language.admin.description'))->default($langDescriptions->description);//->rules('max:300', ['max' => trans('validation.max')])->default(!empty($langDescriptions->description) ? $langDescriptions->description : null);
                $form->hidden($language->code ."__champion_desc_id")->default($langDescriptions->id);
                $arrFields[] = $language->code . '__name';
                $arrFields[] = $language->code . '__description';
                $arrFields[] = 'horse_image';
                $arrFields[] = 'grade';
                $arrFields[] = $language->code ."__champion_desc_id";
                $arrFields[] = $language->code . '__horse_name';
                $arrFields[] = $language->code.'__detail_desc_id';
                $form->divide();
            }
            $form->ignore($arrFields);
            //
            $arrData = array();
            $arrayDetails = array();
            $form->saving(function (Form $form) use ($languages, &$arrData, $arrayDetails) {
                $form->model()->path = Storage::disk(config('admin.upload.disk'))->url('');
                //Lang
                foreach ($languages as $key => $language) {
                    $arrData[$language->code]['name']        = request($language->code . '__name');
                    $arrData[$language->code]['description'] = request($language->code . '__description');
                    $arrData['details']['grade'] = request('grade');
                    $image = new Image(request('horse_image'));
                    $arrData['details']['path'] =  Storage::disk(config('admin.upload.disk'))->url('');
                    $arrData['details']['horse_image'] = $image->prepare(request('horse_image'));
                    $arrayDetails[$language->code]['horse_name'] =  request($language->code . '__horse_name');
                    
                }
            });
           
            $form->saved(function (Form $form) use ($languages, &$arrData, &$arrayDetails) {
                $id = $form->model()->id;
                $once = 1;
                $detail_id = 0;
               // $detail_desc_id = 0;
                foreach ($languages as $key => $language) {
                    if (array_filter($arrData[$language->code], function ($v, $k) {
                        return $v != null;
                    }, ARRAY_FILTER_USE_BOTH)) {
                        $arrData[$language->code]['champion_id'] = $id;
                        $arrData[$language->code]['lang_id']    = $language->id;
                        $arrData[$language->code]['company_id'] = $form->model()->company_id; // sprint 1
                        $arrData['details']['champion_id'] = $id;
                        $arrData['details']['company_id'] = $form->model()->company_id;
                              
                        $arrayDetails[$language->code]['company_id'] = $form->model()->company_id;
                        $arrayDetails[$language->code]['champion_id'] = $id;
                        $arrayDetails[$language->code]['lang_id']    = $language->id;
                        ChampionDescription::where('lang_id', $arrData[$language->code]['lang_id'])->where('champion_id', $arrData[$language->code]['champion_id'])->delete();
                        ChampionDescription::insert($arrData[$language->code]);
                        if($once){
                            $detail_id = ChampionDetails::insertGetId($arrData['details']);
                            $once = 0;
                        }
                        $arrayDetails[$language->code]['champion_detail_id'] =  $detail_id;
                    //    dd($arrayDetails[$language->code]);
                        ChampionDetailsDescription::where('lang_id', $arrData[$language->code]['lang_id'])->where('champion_id', $arrData[$language->code]['champion_id'])->delete();
                        ChampionDetailsDescription::insert($arrayDetails[$language->code]);     
                        //
                    }
                }
        });
                //end lang
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });

        })->tab(trans('language.champoines.horse_win'), function($form) use($languages){
            $arrParameters = request()->route()->parameters();
            $idCheck       = (int) end($arrParameters);
          $form->hasMany('details', function (Form\NestedForm $form) use($languages, $idCheck){
            $arrayDetails = array();
            $championDetails =  ChampionDetails::where('champion_id', $idCheck)->first();
            $ChampionDetailsDescription = new ChampionDetailsDescription();
            $form->image('horse_image', trans('language.champoines.horse_image'));//->uniqueName()->move('horse_images');
            $form->radio('grade', trans('language.champoines.grade'))->options($this->grades);//->default($championDetails->grade);
            foreach ($languages as $key => $language) {
                if ($idCheck) {
                    $langDescriptions = ChampionDescription::where('champion_id', $idCheck)->where('lang_id', $language->id)->first();
                    $ChampionDetailsDescription = ChampionDetailsDescription::where('champion_id', $idCheck)->where("lang_id", $language->id)->first();
                    $form->hidden($language->code.'__detail_desc_id', '')->default($ChampionDetailsDescription->id);
                    $form->hidden($language->code.'__detail_id', '')->default($championDetails->id); //  remove and dependacny 
                }
                 $form->html('<span>'.$language->name.'</span>');
                 $form->text($language->code . '__horse_name', trans('language.champoines.horse_name'))->rules('required', ['required' => trans('validation.required')])->default(!empty($ChampionDetailsDescription->horse_name) ? $ChampionDetailsDescription->horse_name : null);
                
            }            
            $form->saving(function (Form $form) use ($languages, &$arrData, $arrayDetails) {
                $form->model()->path = Storage::disk(config('admin.upload.disk'))->url('');
                //Lang
                foreach ($languages as $key => $language) {
                    $arrData['details']['grade'] = request('grade');
                    $image = new Image(request('horse_image'));
                    $arrData['details']['path'] =  Storage::disk(config('admin.upload.disk'))->url('');
                    $arrData['details']['horse_image'] = $image->prepare(request('horse_image'));
                    $arrayDetails[$language->code]['horse_name'] =  request($language->code . '__horse_name');
                }
            });
         //   $form->
        });
        });
    }); 
    }

  
  public function  update(Request $request, $id){
    try{
      
       $championModel = Champion::findOrFail($id);
       $company_id = $this->getUserCompany()[0]->id;
       if(isset($request->champion_id)){
          if($championModel != null &&$championModel->company_id != $company_id ){
            if(!$this->checkSuperUser()){
                abort(404);
            }

            if($championModel == null){
                abort(404);
            }
         }
       }
       
       $updateData = $request->all();
       $idCheck = $id;
       $detail_id = null;
       $ch_desc_id = 0;
       //
       $languages = Language::getLanguages();
            $arrFields = array();
            $arrDetails = array();
            $arrDetailsDesc = array();
            DB::BeginTransaction();
            foreach ($languages as $key => $language) {
                if ($idCheck) {
                    $championDescription = ChampionDescription::where('champion_id', $idCheck)->where('lang_id', $language->id)->first();
                   
                }
                if ($languages->count() > 1) { // no need if one language used
                }
                if(!$this->checkSuperUser())
                {
                    $championModel->company_id = $this->getUserCompany()[0]->id;
                }
              //  dd($updateData);
                if(!isset($updateData[$language->code . '__name'])){
                //    continue;
                }
                
              //  dd($updateData);
                 $arrFields[$language->code]['name'] =  $updateData[$language->code . '__name'];
                 $arrFields[$language->code]['description'] =  $updateData[$language->code . '__description'];
                
                 if(isset($updateData['horse_image'])){
                 $arrFields[$language->code]['horse_image'] = $updateData['horse_image'];
                 }
                 $arrFields[$language->code]['champion_id'] = $id;
                 $arrFields[$language->code]['created_at']= $championDescription->created_at;
                 $arrFields[$language->code]['company_id'] = $company_id;
                
                 $arrDetails[$language->code]['champion_id'] = $id;
            
                 $arrDetailsDesc[$language->code]['lang_id'] = $language->id;
                 $arrDetailsDesc[$language->code]['company_id'] = $company_id;
                 if(isset($updateData['details'])){
                    $list = $updateData['details'];
                     foreach($list as $key=> $detail){
                     //    dd($list);
                    $arrDetails[$language->code]['grade'] = $detail['grade']; 
                   // $arrDetailsDesc[$language->code]['description'] = $detail[$language->code . '__description'];
                    $arrDetailsDesc[$language->code]['horse_name'] =   $detail[$language->code . '__horse_name'];
                    $arrDetailsDesc[$language->code]['champion_id'] =  $id;
                  
                    $detail_desc_id =  $detail[$language->code . '__detail_desc_id'];
                    $detail_id = $detail[$language->code . '__detail_id'];
                    if($detail_desc_id != null){
                        
                        ChampionDetailsDescription::where("champion_id", $id)->where("id", $detail_desc_id )
                       ->update($arrDetailsDesc[$language->code]);
                       }else{
                         ChampionDetailsDescription::insert($arrDetailsDesc[$language->code]);
                       }
                  }
                 }
            //     dd($updateData);
                 // ChampionDescription::where("champion_id", $id)->delete();
                 $ch_desc_id = $updateData[$language->code .'__champion_desc_id']; 
               //  dd($updateData);
                  ChampionDescription::where("id", $ch_desc_id)->update($arrFields[$language->code]);
                 // ChampionDetailsDescription::where("champion_id", $id)->delete();
                  if($detail_id != null){
                    ChampionDetails::where("champion_id", $id)->where("id", $detail_id)->update($arrDetails[$language->code]);      
                  }else{
                    ChampionDetails::insert($arrDetails[$language->code]);
                  }
                
            }
            //
      
       if(isset($request->status)){
           $championModel->status = $request->status == "Off"?0:1;
       }
       $championModel->save();
       DB::commit();
    }catch(\Exception $e){
        dd($e->getMessage());
        return $this->sendError($e->getMessage(), 400);
    }

  }

    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('');
            $content->description('');
            $content->body(Admin::show(Champion::findOrFail($id), function (Show $show) {
                $show->id('ID');
            }));
        });
    }

}
