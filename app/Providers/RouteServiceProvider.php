<?php

namespace App\Providers;

use App\Models\Config;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
      protected $Cartnamespace = 'App\Http\Controllers';
      protected $namespace ='App\Admin\Controllers';
      protected $secondNamespace ='Encore\Admin\Controllers';
  
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapExtensionsApiRoutes();
        $this->mapExtensionsRoutes();
        $this->mapWebRoutes();
        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware(['web', 'localization', 'currency'])
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
            ///
            Route::prefix('authapi')
            ->middleware('authapi')
            ->namespace($this->namespace)
            ->group(base_path('routes/authApi.php'));

           Route::prefix('auth')
            ->middleware('authx')
            ->namespace($this->secondNamespace)
            ->group(base_path('routes/api.php'));
      
        Route::prefix('cartapi')
            ->middleware('authapi')
            ->namespace($this->Cartnamespace)
            ->group(base_path('routes/api.php'));
    }
//sprint 3
    protected function mapExtensionsRoutes()
    {
        Route::middleware(['web', 'localization', 'currency'])
            ->group(function () {
                if (!is_file(base_path() . '/public/install.php')) {
                    try {
                        $arrExts = Config::where('value', 1)
                            ->whereIn('type', ['Modules', 'Extensions'])
                            ->get()
                            ->toArray();
                        foreach ($arrExts as $arrExt) {
                            $filename = base_path() . '/app/' . $arrExt['type'] . '/' . $arrExt['code'] . '/Route/' . $arrExt['key'] . '.php';
                            if (file_exists($filename)) {
                                require_once $filename;
                            }
                        }
                    } catch (\Exception $e) {

                    }
                }

            });
    }

    protected function mapExtensionsApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(function () {
                Route::group([
                    'namespace' => 'App\Modules\Api',
                ], function () {
                 //   Route::get('/product', 'Product@index');
                   // Route::get('/order', 'Order@index');
				    //Route::get('/activity', 'Activity@index');
					//Route::get('/shoplist', 'Companies@index'); 
                });

            });
    }
}
