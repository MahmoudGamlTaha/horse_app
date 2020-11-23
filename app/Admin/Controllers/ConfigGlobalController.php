<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ConfigGlobal;
use App\Models\ConfigGlobalDescription;
use App\Models\Language;
use App\Models\ShopCurrency;
//use App\Models\ShopCategory;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Form\Field\Image;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfigGlobalController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('language.admin.info_global'));
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

            $content->header(trans('language.admin.info_global'));
            $content->description(' ');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    // public function create()
    // {
    //     return Admin::content(function (Content $content) {

    //         $content->header('header');
    //         $content->description('description');

    //         $content->body($this->form());
    //     });
    // }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(ConfigGlobal::class, function (Grid $grid) {

            $grid->html('&nbsp;');

            $grid->logo(trans('language.config.logo'))->image('', 50);
            if (\Helper::configs()['watermark']) {
                $grid->watermark(trans('language.config.watermark'))->image('', 50);
            }

            $languages = Language::getLanguages();
            $grid->descriptions(trans('language.config.description'))->expand(function () use ($languages) {
                $html = '<table width="100%" class="table-padding padding5" border=1 style="border: 1px solid #d0bcbc;"><tr>
            <td>' . trans('language.config.language') . '</td>
            <td>' . trans('language.config.title') . '</td>
            <td>' . trans('language.config.description') . '</td>
            <td>' . trans('language.config.keyword') . '</td>
            </tr>';
                foreach ($languages as $key => $lang) {
                    $langDescriptions = ConfigGlobalDescription::where('config_id', $this->id)->where('lang_id', $key)->first();
                    $html .= '<tr>
            <td>' . $lang['name'] . '</td>
            <td>' . $langDescriptions['title'] . '</td>
            <td>' . $langDescriptions['description'] . '</td>
            <td>' . $langDescriptions['keyword'] . '</td>
            </tr>';
                }
                $html .= '</table>';
                return $html;
            }, trans('language.admin.detail'));

            $grid->phone(trans('language.config.phone'));
            $grid->long_phone(trans('language.config.long_phone'))->display(function ($text) {
                return '<div style="max-width:150px; overflow:auto;">' . $text . '</div>';
            });
            $grid->time_active(trans('language.config.time_active'))->display(function ($text) {
                return '<div style="max-width:150px; overflow:auto;">' . $text . '</div>';
            });

            $grid->address(trans('language.config.address'))->display(function ($text) {
                return '<div style="max-width:150px; overflow:auto;">' . $text . '</div>';
            });
            $grid->email(trans('language.config.email'))->display(function ($text) {
                return '<div style="max-width:150px; overflow:auto;">' . $text . '</div>';
            });
            $grid->locale(trans('language.config.language'))->display(function ($locale) {
                $languages = Language::pluck('name', 'code')->all();
                return $languages[$locale];
            });
            $grid->currency(trans('language.config.currency'))->display(function ($currency) {
                $currencies = ShopCurrency::pluck('name', 'code')->all();
                return $currencies[$currency];
            });
            $grid->disableCreation();
            $grid->disableExport();
            $grid->disableRowSelector();
            $grid->disableFilter();
            $grid->disablePagination();
            $grid->actions(function ($actions) {
                $actions->disableView();
                $actions->disableDelete();
            });
            $grid->tools(function ($tools) {
                $tools->disableRefreshButton();
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id = null)
    {
        $currencies = ShopCurrency::where('status', 1)->pluck('name', 'code')->all();
        $form       = new Form(new ConfigGlobal);
        $form->image('logo', trans('language.config.logo'))->removable();
        if (\Helper::configs()['watermark']) {
            $form->image('watermark', trans('language.config.watermark'))->removable();
        }
//Language
        $languages   = Language::getLanguages();
        $arrLanguage = [];
        foreach ($languages as $key => $value) {
            $arrLanguage[$value->code] = $value->name;
        }
        $arrParameters = request()->route()->parameters();
        $idCheck       = (int) end($arrParameters);

        $arrFields = array();
        foreach ($languages as $key => $language) {
            if ($idCheck) {
                $langDescriptions = ConfigGlobalDescription::where('config_id', $idCheck)->where('lang_id', $language->id)->first();
            }
            if ($languages->count() > 1) {
                $form->html('<b>' . $language->name . '</b> <img style="height:25px" src="/' . config('filesystems.disks.path_file') . '/' . $language->icon . '">');
            }

            $form->text($language->code . '__title', trans('language.config.title'))->rules('required', ['required' => trans('validation.required')])->default(!empty($langDescriptions->title) ? $langDescriptions->title : null);
            $form->text($language->code . '__keyword', trans('language.config.keyword'))->default(!empty($langDescriptions->keyword) ? $langDescriptions->keyword : null);
            $form->textarea($language->code . '__description', trans('language.config.description'))->rules('max:300', ['max' => trans('validation.max')])->default(!empty($langDescriptions->description) ? $langDescriptions->description : null);
            $arrFields[] = $language->code . '__title';
            $arrFields[] = $language->code . '__keyword';
            $arrFields[] = $language->code . '__description';
            $form->divide();
        }
        $form->ignore($arrFields);
//end language spring 3
        $form->text('phone', trans('language.config.phone'));
        $form->text('long_phone', trans('language.config.long_phone'));
        $form->text('time_active', trans('language.config.time_active'));
        $form->text('address', trans('language.config.address'));
        $form->text('email', trans('language.config.email'));
        $form->textarea('polices_and_condition', trans('language.polices'));
        $form->textarea('about_app', trans('language.config.about_app') );
        
        $form->select('locale', trans('language.config.language'))->options($arrLanguage);
        $form->select('currency', trans('language.config.currency'))->options($currencies)->rules('required');
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
            $tools->disableDelete();
        });

        $arrData = array();
        $form->saving(function (Form $form) use ($languages, &$arrData) {
            //Lang
            foreach ($languages as $key => $language) {
                $arrData[$language->code]['title']       = request($language->code . '__title');
                $arrData[$language->code]['keyword']     = request($language->code . '__keyword');
                $arrData[$language->code]['description'] = request($language->code . '__description');
            }
            //end lang
        });

        //saved
        $form->saved(function (Form $form) use ($languages, &$arrData) {
            $id = $form->model()->id;
            //Lang
            foreach ($languages as $key => $language) {
                if (array_filter($arrData[$language->code], function ($v, $k) {
                    return $v != null;
                }, ARRAY_FILTER_USE_BOTH)) {
                    $arrData[$language->code]['config_id'] = $id;
                    $arrData[$language->code]['lang_id']   = $language->id;
                    ConfigGlobalDescription::where('lang_id', $arrData[$language->code]['lang_id'])->where('config_id', $arrData[$language->code]['config_id'])->delete();
                    ConfigGlobalDescription::insert($arrData[$language->code]);
                }
            }
        });
        return $form;
    }

    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('');
            $content->description('');
            print_r($id);
            //die;
            $content->body(Admin::show(ConfigGlobal::findOrFail($id), function ($show) {
                $show->id('ID');
            }));
        });
    }

    public function getPolices(){
        $polices = ConfigGlobal::all('polices_and_condition');
        return $this->sendResponse($polices, 200);
    }
    public function aboutUs(){
        $about = ConfigGlobal::first()
        //->with('description')
        ->get(['about_app']);
        return $this->sendResponse($about, 200);
    }
    public function config(){
        $config = ConfigGlobal::all(['polices_and_condition', 'about_app']);
        return $this->sendResponse($config, 200);
    }
    public function update($id, Request $request)
    {
        $config = ConfigGlobal::findOrFail($id);
     if(isset($request->watermark) && $request->watermark == '_file_del_'){
        
         $config->watermark = null;
         $config->save();
         return $this->sendResponse("sucess", 200);
     }
     if(isset($request->logo) && $request->logo == '_file_del_'){
        $config->logo = null;
        $config->save();
        return $this->sendResponse("sucess", 200);
    }

    if(isset($request->logo)){
         $image = new Image($request->logo);
         $image->uniqueName();
         $image->move('global-config');
         $config->path = Storage::disk(config('admin.upload.disk'))->url('');
         $config->logo = $image->prepare($request->logo);
    }
    if(isset($request->watermark)){
        $image = new Image($request->watermark);
        $image->uniqueName();
        $image->move('global-config');
        $config->path = Storage::disk(config('admin.upload.disk'))->url('');
        $config->logo = $image->prepare($request->watermark);
   }
    $config->address = $request['address']??null;
    $config->about_app = $request['about_app']??null;
    $config->phone = $request['phone']??null;
    $config->email = $request['email']??null;
    $config->polices_and_condition = $request['polices_and_condition']??null;
    $config->save();
    }

}
