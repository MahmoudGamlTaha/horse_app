<?php
#app\Extensions\Shipping\Controllers\ShippingBasic.php
namespace App\Extensions\Shipping\Controllers;

use App\Extensions\Shipping\Models\InstallItems;
use App\Models\Config;

class ShippingBasic extends \App\Http\Controllers\Controller
{
    protected $configType = 'Extensions';
    protected $configCode = 'Shipping';
    protected $configKey  = 'ShippingBasic';
    protected $configScreen = 'install-item';
    protected $name = 'InstallCost';
    public $title;
    public $image;
    const ALLOW  = 1;
    const DENIED = 0;
    const ON     = 1;
    const OFF    = 0;
    public function __construct()
    {
        $this->title = trans('language.extensions.install');
        $this->image = 'images/' . $this->configType . '/' . $this->configCode . '/' . $this->configKey . '.png';
    }

    public function getData($company_id)
    {
        return $this->processData($company_id);
    }

    public function processData($company_id)
    {
        $subtotal = \Cart::subtotal();
        $shipping = InstallItems::where('company_id', $company_id)->first();
        if($shipping == null){
            $shipping = new InstallItems();
        }
        if ($subtotal <= $shipping->shipping_free) {
            $arrData = [
                'code'       => $this->name,
                'value'      => $shipping->fee,
            ];
        } else {
            $arrData = [
                'code'       => $this->name,
                'value'      => $shipping->fee??0,
            ];

        }
        return $arrData;
    }

    public function install()
    {
        $return = ['error' => 0, 'msg' => ''];
        $check  = Config::where('key', $this->configKey)->first();
        if ($check) {
            $return = ['error' => 1, 'msg' => 'Module exist'];
        } else {
            $process = Config::insert(
                [
                    'code'   => $this->configCode,
                    'key'    => $this->configKey,
                    'type'   => $this->configType,
                    'sort'   => 0, // Sort extensions in group
                    'value'  => self::ON, //1- Enable extension; 0 - Disable
                    'detail' => $this->configType . '/' . $this->configCode . '/' . $this->configKey . '.title',
                ]
            );
            if (!$process) {
                $return = ['error' => 1, 'msg' => 'Error when install'];
            } else {
                $return = (new InstallItems())->installExtension();
            }
        }
        return $return;
    }

    public function uninstall()
    {
        $return  = ['error' => 0, 'msg' => ''];
        $process = (new Config)->where('key', $this->configKey)->delete();
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error when uninstall'];
        }
        (new InstallItems())->uninstallExtension();
        return $return;
    }
    public function enable()
    {
        $return  = ['error' => 0, 'msg' => ''];
        $process = (new Config)->where('key', $this->configKey)->update(['value' => self::ON]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error enable'];
        }
        return $return;
    }
    public function disable()
    {
        $return  = ['error' => 0, 'msg' => ''];
        $process = (new Config)->where('key', $this->configKey)->update(['value' => self::OFF]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error disable'];
        }
        return $return;
    }
    public function config()
    {$company_id = $this->getUserCompany()[0]->id;
        
    if(!$this->checkSuperUser()){
        $data = InstallItems::where("company_id", $company_id)->first();
       if($data==null){
           $data = array();
           $data['id'] = -1;
       }
      
        return view('admin.' . $this->configType . '.' . $this->configCode . '.' . $this->configScreen)->with(
            [
                'group' => $this->configCode,
                'key'   => $this->configKey,
                'title' => $this->title,
                'dataList'  => $data,
            ])->render();
    }
    
    return view('admin.' . $this->configType . '.' . $this->configCode . '.' . $this->configScreen)->with(
        [
            'group' => $this->configCode,
            'key'   => $this->configKey,
            'title' => $this->title,
            'dataList'  => InstallItems::get(),
        ])->render();
    }
    public function processConfig($data)
    {
        $return  = ['error' => 0, 'msg' => ''];
        if($data['pk'] != -1){
        $process = InstallItems::where('id', $data['pk'])->update([$data['name'] => $data['value']]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error update'];
        }
        return $return;
    }else{
        $shiping = new InstallItems();
        $shiping->save();
        $process = InstallItems::where('id', $shiping->id)->update([$data['name'] => $data['value']]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error update'];
        }
    }
    }
}
