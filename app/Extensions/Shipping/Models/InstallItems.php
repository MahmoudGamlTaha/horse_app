<?php
#App\Extension\Shipping\Models\ShippingStandard.php
namespace App\Extensions\Shipping\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstallItems extends Model
{
    public $timestamps = false;
    public $table      = 'install_items';
    public function uninstallExtension()
    {
        if (Schema::hasTable($this->table)) {
            Schema::drop($this->table);
        }

    }

    public function installExtension()
    {
        $return = ['error' => 0, 'msg' => 'Install extension success'];
        if (!Schema::hasTable($this->table)) {
            try {
                Schema::create($this->table, function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('fee');
                    $table->integer('condition_fee');
                    $table->integer('company_id');
                });
                $this->insert(['fee' => 20000, 'shipping_free' => 100000]);
            } catch (\Exception $e) {
                $return = ['error' => 1, 'msg' => $e->getMessage()];
            }
        } else {
            $return = ['error' => 1, 'msg' => 'Table ' . $this->table . ' exist!'];
        }
        return $return;
    }
}
