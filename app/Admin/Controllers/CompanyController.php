<?php
 namespace App\Admin\Controllers;
 use App\Http\Controllers\Controller;
 use App\Models\Company;
 use App\Models\ShopActivity;
 use App\Models\CompanyContact;
use App\Models\Language;
use App\Models\ShopProduct;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form\Field\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Support\Collection;
use App\Models\City;
use App\Models\Country;

class CompanyController extends Controller
{
  use HasResourceActions;
  public function index(){
    
    return Admin::content(function (Content $content) {
      $content->header(trans('language.admin.companies_manager'));
      $content->description(' ');  
      $content->body($this->grid());

  });
   }
   public function create()
   {
       return Admin::content(function (Content $content) {

           $content->header(trans('admin.company'));
           $content->description(trans('admin.edit'));

           $content->body($this->form());
       });
   }

   public function getAllshopActiviy(Request $request)
   {
       $allActivity = ShopActivity::where('active', 1)
                      ->where('id', '!=', CompanyType::ALL);//->get();
                      if($request->cat_type != null){
                        $allActivity  =  $allActivity->where('cat_type', $request->cat_type );
                      }
                      $allActivity = $allActivity->get(); 
       return $this->sendResponse($allActivity, 200);
   }

   public function getAllShopWithActivityId(Request $request,$activityId)
   {
     
      $allShopsWithActivity = Company::where("activity_id", $activityId)
                           ->get();
     return $this->sendResponse($allShopsWithActivity, 200 );
   }
   public function getAllShopWithCatType(Request $request)
   {
      $cat_type = $request->cat_type;
      $activityList = ShopActivity::where('cat_type', $cat_type)->pluck("id");
      $allShopsWithActivity = Company::whereIn("activity_id", $activityList);
      
      if($request->param != null){
        $allShopsWithActivity = $allShopsWithActivity->whereRaw('name LIKE "'.$request->param.'%"');
      }
      $allShopsWithActivity = $allShopsWithActivity->get();
     return $this->sendResponse($allShopsWithActivity, 200 );
   }

   public function getCompanyProfile($companyId)
   {
     try{
       if(!is_numeric($companyId)){
        return $this->sendError("data error ", 400); 
       }
     $profile = CompanyContact::where("company_id", $companyId)
                                ->selectRaw('address, phone_contact, email, about_us,
                                 site, longitude, latitude, fax, manager, general_manager, published_date, youtube_url,country, city')
                                 ->get();
     return $this->sendResponse($profile, 200);
     }catch(\Exception $e){
        return $this->sendError([], 400);
     }
   }

   public function getAllCompanyProduct(Request $request, $companyId)
   {
     try{
      if(!is_numeric($companyId) || !is_numeric($request->category_id)){
        return $this->sendError([], 400);
    }
    if($request->category_id  != 0){
       $companyProducts = Company::findOrFail($companyId)
       ->Products()
       ->where('category_id', $request->category_id)
       ->where('company_id',$companyId)
       ->paginate(20);
       return $this->sendResponse($companyProducts, 200);
    }else{
      $companyProducts = Company::findOrFail($companyId)
      ->Products()
      ->where('company_id',$companyId)
      ->paginate(20);
      return $this->sendResponse($companyProducts, 200);
    }
     }catch(\Exception $e){
       return $this->sendError([], 400);
     }
   }
   protected function grid()
    {
        return Admin::grid(Company::class, function (Grid $grid) {
            //if($this->checkSuperUser())
          //  $grid->model()->company_id = 1;
            $grid->id('ID')->sortable();
          //  $grid->image(trans('language.admin.image'))->image('', 50);
            $grid->name(trans('admin.company'))->sortable();
         //   $grid->model()->name(trans('language.categories'));
         
          //  $arrType = $this->arrType;
            $grid->activity()->type(trans('language.admin.type'));
            $grid->active(trans('language.admin.status'))->switch();
            $grid->seller(trans('admin.seller'))->switch();
            $grid->created_at(trans('language.admin.created_at'));
            $grid->notes(trans('language.notes'));
            $grid->model()->orderBy('id', 'desc');
            //$grid->disableExport();
            $grid->actions(function ($actions) {
                $actions->disableView();
            });

            $grid->tools(function ($tools) {
                $tools->append('<div class="pull-right">
            <div class="btn-group pull-right" style="margin-right: 10px">
             <a href="' . route('productImport') . '" class="btn btn-sm btn-success" title="New">
                <i class="fa fa-save"></i><span class="hidden-xs">&nbsp;&nbsp;&nbsp;' . trans('language.product.import_multi') . '</span>
            </a>
          </div>
        </div>');
            });
             if(!$this->checkSuperUser())
            {
            //  $grid->model()
              //   ->where('id',$this->getUserCompany()[0]->id);
            }
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('name', trans('admin.company'));
              

            });
        });
    }
    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('');
            $content->description('');
            $content->body(Admin::show(Company::findOrFail($id), function (Show $show) {
                $show->id('ID');
            }));
        });
    }
    
   public function edit($id = null){
    return Admin::content(function (Content $content) use ($id) {
      $content->header(trans('admin.companies'));
      $content->description(trans('admin.edit'));
      
      $content->body($this->form($id)->edit($id));
  });
   }
   //from allah 
   public function update(Request $request, $id ){
     $arr = $request->all();
     $model = Company::findOrFail($id);
     DB::beginTransaction();
     $model->activity_id = $arr['activity_id'];
     $model->name = $arr['name'];
     $model->notes = $arr['notes'];
     $model->path =  Storage::disk(config('admin.upload.disk'))->url('');
     $model->iban = $arr['iban'];
     if(isset($request->logo)){
      $uploadedImage = new Image($arr['logo']) ;
      $uploadedImage->uniqueName();
      $uploadedImage->move('company_logos');
      $model->logo = $uploadedImage->prepare($arr['logo']);
      }
     $model->save();
     $companyDataArr = null;
     if(isset($arr['company_contact']))
        $companyDataArr =new Collection($arr['company_contact']);
        else 
        $companyDataArr =new Collection([]);
     if(isset($companyDataArr)){
       
      $companyData = $companyDataArr->first();
      $data = CompanyContact::where('company_id', $id)->get();
      $companyContact = $data->first();
       if(!isset($companyContact)){
        $companyContact = new CompanyContact();
       }
      
        $companyContact->phone_contact =  $companyData['phone_contact']??null;
        $companyContact->address =  $companyData['address']??null; 
        $companyContact->about_us =  $companyData['about_us']??null;
        $companyContact->company_id = $id;
        $companyContact->general_manager = $companyData['general_manager']??null;
        $companyContact->published_date = $companyData['published_date']??null;
        $companyContact->manager = $companyData['manager']??null;
        $companyContact->email = $companyData['email']??null;
        $companyContact->fax = $companyData['fax']??null;
        $companyContact->site = $companyData['site']??null;
        $companyContact->youtube_url = $companyData['youtube_url']??null;
        $companyContact->longitude = $companyData['longitude']??null;
        $companyContact->latitude = $companyData['latitude']??null;
        $companyContact->city = $companyData['city']??null;
        $companyContact->country = $companyData['country']??null;
        $companyContact->save();
       
      }
     DB::commit();
    return $this->edit($id);
   }
   protected function form($id = null)
    {
        return Admin::form(Company::class, function (Form $form) use ($id) {
            $languages = Language::getLanguages();
            $form->tab(trans('admin.company'), function ($form) use ($languages, $id) {
//Language      
                $name = '';
                $notes = '';
                $selected = '0';
                $arrData = array();
                 if($id != null){
                      $entity = Company::find($id);
                      $name = $entity->name;
                      $notes = $entity->notes;
                      $selected = strval($entity->activity_id);
                      $companyContacts = $entity->getContact()->get();
                      $config = $entity->configs();
                     
                 }
                 
              //  print_r($selected);
              //  $arrParameters = request()->route()->parameters();
            //    $idCheck       = (int) end($arrParameters);
                $allActivity =  ShopActivity::where('active', true)->pluck('type', 'id');
                $form->text('name', trans('admin.company'))->default(!empty($name) ? $name : null);
                $form->text('notes', trans('language.notes'))->default(!empty($notes) ? $notes : null);
                $form->select('activity_id', trans('admin.activity'))->options($allActivity)->default($selected);
                $form->text('iban', trans('admin.iban'));
                $form->image('logo', trans('admin.logo'))->uniqueName()->move('company_logos')->removable();
                $form->saving(function (Form $form) use ($languages, &$arrData) {
                  $form->model()->path =  Storage::disk(config('admin.upload.disk'))->url('');
                    //Lang
                    //if($this->isValidIBAN($form->model()->iban))
                    if(sizeof($arrData) > 0){
                    foreach ($languages as $key => $language) {
                        $arrData[$language->code]['name']        = request($language->code . '__name');
                        $arrData[$language->code]['keyword']     = request($language->code . '__keyword');
                        $arrData[$language->code]['description'] = request($language->code . '__description');
                    }
    
                    }
                  
                });
                $form->saved(function (Form $form) use ($languages, &$arrData) {
                $id = $form->model()->id;
                //Lang
                foreach ($languages as $key => $language) {
                    if (sizeof($arrData) > 0 && array_filter($arrData[$language->code], function ($v, $k) {
                        return $v != null;
                    }, ARRAY_FILTER_USE_BOTH)) {
                       //config save
                    }
                }
                //end lang
            
            $form->disableViewCheck();
        //    $form->disableEditingCheck();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
         
        });//sprint 2
            })->tab(trans('admin.setting'), function($form) {
            $form->hasMany('company_contact','',function(Form\NestedForm $form){ // should be same tablel name releation
               // $contactForm->html('&nbsp;');
               $Cites = City::pluck('name','name');
               $Countries = Country::pluck('name', 'name');
               $form->text('address',(trans('language.config.address')));
                $form->email('email' ,trans('language.contact_form.email'));
                $form->text('fax',(trans('language.fax')));
                $form->mobile('phone_contact',trans('language.phone'));
                $form->text('longitude',(trans('language.longitude')));
                $form->text('latitude',(trans('language.latitude')));
                $form->text('general_manager', trans('language.general_manager'));
                $form->text('manager', trans('language.manager'));
                $form->select('city', trans('language.city'))->options($Cites);
                $form->select('country', trans('language.city'))->options($Countries);
                $form->date('published_date', trans('language.publish_date'));
                $form->textarea('about_us', trans('language.about_us'));
               
              })->disableCreate();

            })->tab(trans('language.admin.product_manager'), function($form)  use($id){
              $products = ShopProduct::where('company_id', $id)
              ->paginate(20);    
              $path = Storage::disk(config('admin.upload.disk'))->url('');          
              $form->html(view('admin::grid.grid-table')->with([
               'products' => $products,
               'base' => $path
             ]));
        
            });
          });
      }

     
//sprint 1
      function isValidIBAN ($iban) {

        $iban = strtolower($iban);
        $Countries = array(
          'al'=>28,'ad'=>24,'at'=>20,'az'=>28,'bh'=>22,'be'=>16,'ba'=>20,'br'=>29,'bg'=>22,'cr'=>21,'hr'=>21,'cy'=>28,'cz'=>24,
          'dk'=>18,'do'=>28,'ee'=>20,'fo'=>18,'fi'=>18,'fr'=>27,'ge'=>22,'de'=>22,'gi'=>23,'gr'=>27,'gl'=>18,'gt'=>28,'hu'=>28,
          'is'=>26,'ie'=>22,'il'=>23,'it'=>27,'jo'=>30,'kz'=>20,'kw'=>30,'lv'=>21,'lb'=>28,'li'=>21,'lt'=>20,'lu'=>20,'mk'=>19,
          'mt'=>31,'mr'=>27,'mu'=>30,'mc'=>27,'md'=>24,'me'=>22,'nl'=>18,'no'=>15,'pk'=>24,'ps'=>29,'pl'=>28,'pt'=>25,'qa'=>29,
          'ro'=>24,'sm'=>27,'sa'=>24,'rs'=>22,'sk'=>24,'si'=>19,'es'=>24,'se'=>24,'ch'=>21,'tn'=>24,'tr'=>26,'ae'=>23,'gb'=>22,'vg'=>24
        );
        $Chars = array(
          'a'=>10,'b'=>11,'c'=>12,'d'=>13,'e'=>14,'f'=>15,'g'=>16,'h'=>17,'i'=>18,'j'=>19,'k'=>20,'l'=>21,'m'=>22,
          'n'=>23,'o'=>24,'p'=>25,'q'=>26,'r'=>27,'s'=>28,'t'=>29,'u'=>30,'v'=>31,'w'=>32,'x'=>33,'y'=>34,'z'=>35
        );
      
        if (strlen($iban) != $Countries[ substr($iban,0,2) ]) { return false; }
      
        $MovedChar = substr($iban, 4) . substr($iban,0,4);
        $MovedCharArray = str_split($MovedChar);
        $NewString = "";
      
        foreach ($MovedCharArray as $k => $v) {
      
          if ( !is_numeric($MovedCharArray[$k]) ) {
            $MovedCharArray[$k] = $Chars[$MovedCharArray[$k]];
          }
          $NewString .= $MovedCharArray[$k];
        }
        if (function_exists("bcmod")) { return bcmod($NewString, '97') == 1; }
        $x = $NewString; $y = "97";
        $take = 5; $mod = "";
      
        do {
          $a = (int)$mod . substr($x, 0, $take);
          $x = substr($x, $take);
          $mod = $a % $y;
        }
        while (strlen($x));
      
        return (int)$mod == 1;
      }
     
    }