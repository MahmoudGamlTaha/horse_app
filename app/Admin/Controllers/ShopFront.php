<?php
#app/Http/Controller/ShopFront.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ShopAttributeGroup;
use App\Models\ShopBrand;
use App\Models\ShopCategory;
use App\Models\ShopOrder;
use App\Models\ShopOrderStatus;
use App\Models\ShopPage;
use App\Models\ShopProduct;
use App\Models\ShopVendor;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopFront extends Controller
{
   protected $theme = "default";
/**
 * [index description]
 * @return [type] [description]
 */
    public function index(Request $request)
    {
        return view('welcome');
    }

/**
 * [getCategories description]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
   
  
/**
 * [productToBrand description]
 * @param  [type] $name [description]
 * @param  [type] $id   [description]
 * @return [type]       [description]
 */
  
    /**
     * [profile description]
     * @return [type] [description]
     */
    public function profile()
    {
        $user        = Auth::user();
        $id          = $user->id;
        $orders      = ShopOrder::with('orderTotal')->where('user_id', $id)->sort()->get();
        $statusOrder = ShopOrderStatus::pluck('name', 'id')->all();
        return view($this->theme . '.shop_profile')->with(array(
            'title'       => trans('language.my_profile'),
            'user'        => $user,
            'orders'      => $orders,
            'statusOrder' => $statusOrder,
            'layout_page' => 'shop_profile',
        ));
    }

/**
 * [search description]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
    public function search(Request $request)
    {
        $sortBy      = null;
        $sortOrder   = 'asc';
        $filter_sort = request('filter_sort') ?? '';
        $filterArr   = [
            'price_desc' => ['price', 'desc'],
            'price_asc'  => ['price', 'asc'],
            'sort_desc'  => ['sort', 'desc'],
            'sort_asc'   => ['sort', 'asc'],
            'id_desc'    => ['id', 'desc'],
            'id_asc'     => ['id', 'asc'],
        ];
        if (array_key_exists($filter_sort, $filterArr)) {
            $sortBy    = $filterArr[$filter_sort][0];
            $sortOrder = $filterArr[$filter_sort][1];
        }
        $keyword = request('keyword') ?? '';
        return view($this->theme . '.shop_products_list',
            array(
                'title'       => trans('language.search') . ': ' . $keyword,
                'products'    => (new ShopProduct)->getSearch($keyword, $limit = $this->configs['product_list'], $sortBy, $sortOrder),
                'layout_page' => 'product_list',
                'filter_sort' => $filter_sort,
            ));
    }

/**
 * [getContact description]
 * @return [type] [description]
 */
    public function getContact()
    {
        $page = $this->getPage('contact');
        return view($this->theme . '.shop_contact',
            array(
                'title'       => trans('language.contact'),
                'description' => '',
                'page'        => $page,
                'keyword'     => $this->configsGlobal['keyword'],
                'og_image'    => $this->logo,
            )
        );
    }

/**
 * [postContact description]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
    public function postContact(Request $request)
    {
        $validator = $request->validate([
            'name'    => 'required',
            'title'   => 'required',
            'content' => 'required',
            'email'   => 'required|email',
            'phone'   => 'required|regex:/^0[^0][0-9\-]{7,13}$/',
        ], [
            'name.required'    => trans('validation.required'),
            'content.required' => trans('validation.required'),
            'title.required'   => trans('validation.required'),
            'email.required'   => trans('validation.required'),
            'email.email'      => trans('validation.email'),
            'phone.required'   => trans('validation.required'),
            'phone.regex'      => trans('validation.phone'),
        ]);
        //Send email
        try {
            $data            = $request->all();
            $data['content'] = str_replace("\n", "<br>", $data['content']);
            Mail::send('vendor.mail.contact', $data, function ($message) use ($data) {
                $message->to($this->configsGlobal['email'], $this->configsGlobal['title']);
                $message->replyTo($data['email'], $data['name']);
                $message->subject($data['title']);
            });
            return redirect()->route('contact')->with('message', trans('language.thank_contact'));

        } catch (\Exception $e) {
            echo $e->getMessage();
        } //

    }

/**
 * [pages description]
 * @param  [type] $key [description]
 * @return [type]      [description]
 */
    public function pages($key = null)
    {
        $page = $this->getPage($key);
        if ($page) {
            return view($this->theme . '.shop_page',
                array(
                    'title'       => $page->title,
                    'description' => '',
                    'keyword'     => $this->configsGlobal['keyword'],
                    'page'        => $page,
                ));
        } else {
            return $this->pageNotFound();
        }
    }

/**
 * [getPage description]
 * @param  [type] $key [description]
 * @return [type]      [description]
 */
    public function getPage($key = null)
    {
        return ShopPage::where('uniquekey', $key)->where('status', 1)->first();
    }

}
