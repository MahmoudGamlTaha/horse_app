<?php

namespace Illuminate\Routing;

use BadMethodCallException;
use DB;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Support\Facades\Auth;
abstract class Controller
{
    /**
     * The middleware registered on the controller.
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * Register middleware on the controller.
     *
     * @param  array|string|\Closure  $middleware
     * @param  array   $options
     * @return \Illuminate\Routing\ControllerMiddlewareOptions
     */
    public function middleware($middleware, array $options = [])
    {
        foreach ((array) $middleware as $m) {
            $this->middleware[] = [
                'middleware' => $m,
                'options' => &$options,
            ];
        }

        return new ControllerMiddlewareOptions($options);
    }

    /**
     * Get the middleware assigned to the controller.
     *
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        return call_user_func_array([$this, $method], $parameters);
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
    function getUserCompany()
    {
        $user =  Auth::guard('admin')->user();
        return $user->company()->get();
    } 
    function checkSuperUser()
    {
        $user =  Auth::guard('admin')->user();
        if($user != null)
        {
            if($user->superuser == 1)
            {
                return true;
            }
        }
        return false;
    }
    function getCompanyByName($name)
    {
        $company = DB::table('companies')->where('name', $name)->first();
        return $company;
    }
    public function sendError($result, $message, $dataPointer = 'data')
    {
    	$response = [
            'success' => false,
            $dataPointer => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }
    public function sendResponse($result, $message, $dataPointer = 'data')
    {
    	$response = [
            'success' => true,
            $dataPointer => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }
}
