<?php
#app/Admin/Controller/GenealogyController.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Genealogy;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GeneologyController extends Controller
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

            $content->header(trans('language.horse.pedgrees'));
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

            $content->header(trans('language.horse.pedgrees'));
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

            $content->header(trans('language.brands'));
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
        return Admin::grid(Genealogy::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->parent_name(trans('language.admin.name'))->sortable();
            $grid->image(trans('language.admin.image'))->image('', 50);
            $grid->disableRowSelector();
            $grid->disableFilter();
            $grid->tools(function ($tools) {
                $tools->disableRefreshButton();
            });
            $grid->disableExport();
            $grid->actions(function ($actions) {
                $actions->disableView();
            });
            $grid->model()->orderBy('id', 'desc');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Genealogy::class, function (Form $form) {

            $form->text('parent_name', trans('language.horses.name'))->rules('required');
            $form->select('parent_id',trans('language.horses.grandfather'));
            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
        });
    }

    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('');
            $content->description('');
            $content->body(Admin::show(Genealogy::findOrFail($id), function (Show $show) {
                $show->id('ID');
            }));
        });
    }
}
