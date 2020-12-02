<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Encore\Admin\Middleware\Session;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Support\Facades\Storage;
use Encore\Admin\Form\Field\Image;

class AuthController extends Controller
{
    /**
     * Show the login page.
     *
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     */
    public function getLogin()
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectPath());
        }

        return view('admin::login');
    }

    /**
     * Handle a login request.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postLogin(Request $request)
    {
        $company = $this->getCompanyByName($request->company);
    
        $credentials = $request->only([$this->username(), 'password']);
        if($company)
        {
        $credentials['company_id'] = $company->id;
        $remember = $request->get('remember', false);
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($credentials, [
            $this->username()   => 'required',
            'password'          => 'required',
            'company_id'        => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        if ($this->guard()->attempt($credentials, $remember)) {
            return $this->sendLoginResponse($request);
        }
       }
        return back()->withInput()->withErrors([
            $this->username() => $this->getFailedLoginMessage(),
        ]);
    }

    /**
     * User logout.
     *
     * @return Redirect
     */
    public function getLogout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(config('admin.route.prefix'));
    }

    /**
     * User setting page.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function getSetting(Content $content)
    {
        $form = $this->settingForm();
        $form->tools(
            function (Form\Tools $tools) {
                $tools->disableList();
            }
        );

        return $content
            ->header(trans('admin.user_setting'))
            ->body($form->edit(Admin::user()->id));
    }

    /**
     * Update user setting. 
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putSetting()
    {
        return $this->settingForm()->update(Admin::user()->id);
    }

    /**
     * Model-form for user setting.
     *
     * @return Form
     */
    protected function settingForm()
    {
        $class = config('admin.database.users_model');

        $form = new Form(new $class());

        $form->display('username', trans('admin.username'));
        $form->text('name', trans('admin.name'))->rules('required');
        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules('confirmed|required');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->setAction(admin_base_path('auth/setting'));

        $form->ignore(['password_confirmation']);

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });

        $form->saved(function () {
            admin_toastr(trans('admin.update_succeeded'));

            return redirect(admin_base_path('auth/setting'));
        });

        return  $form;
    }

    /**
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    protected function getFailedLoginMessage()
    {
        return Lang::has('auth.failed')
            ? trans('auth.failed')
            : 'These credentials do not match our records.';
    }

    /**
     * Get the post login redirect path.
     *
     * @return string
     */
    protected function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : config('admin.route.prefix');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        admin_toastr(trans('admin.login_successful'));

        $request->session()->regenerate();

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    protected function username()
    {
        return 'username';
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }
    //////////////
    public function postapiLogin(Request $request)
    {
        //$user = $this->guard()->user();
    //return $request->session()->token();
        $credentials = $request->only(['email', 'password']);
        
       // $remember = $request->get('remember', false);
        $remember = true;
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($credentials, [
            'email'             => 'required',
            'password'          => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
       
        if ($this->guard()->attempt($credentials, $remember)) {
            $user = \Illuminate\Support\Facades\Auth::guard('admin')->getUser();
            $user->api_token = $request->session()->token();
            $user->save();
            $sendUser = clone $user;
            $sendUser->password = '';
            $sendUser->company_id = null;
            return $this->sendResponse($sendUser, 200) ; 
        }
        return $this->sendError(null, 401) ;

    }
    public function logout(Request $request){
        if(!is_numeric($request->user_id)){
          return $this->sendError("error", 400);
        }
        $user = new Administrator();
         $data = $user->where("id",$request->user_id)->first();
         if($data->api_token == null){
            return $this->sendError("already logout", 400);
         }
         $data->api_token = null;
         $data->save();
        // "logout sucessfully"
        return $this->sendResponse("logout sucessfully", 200); 
    }
    public function postapiRegister(Request $request)
    {
        $credentials = $request->only(['name', 'password','lname','email']);
       
       // $remember = $request->get('remember', false);
        $remember = true;
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($credentials, [
            'name'              => 'required|max:220',
            'password'          => 'required|max:230',
            'lname'             => 'required|max:250',
            'email'             => 'required|unique:admin_users|max:250'
        ]);
        
        if ($validator->fails()) {
            return $this->sendError([], 401) ;
        }
            $user = new \Encore\Admin\Auth\Database\Administrator();
            $user->username = $credentials['name'].'_'.$credentials['lname'];
            $user->password = bcrypt($credentials['password']);
            $user->email    = $credentials['email'];
            $user->lname    = $credentials['lname'];
            $user->name     = $credentials['name'];
            $user->seller_type = 0;
            if(isset($userData["avatar"])){
                $image = new Image($userData["avatar"]);
                $image->uniqueName();
                $image->move("ImgProfile");
                $user->avatar = $image->prepare($userData['avatar']);
                $user->path = Storage::disk(config('admin.upload.disk'))->url('');
            }  
           if($user->save()){ 
            $sendUser = clone $user;
            $sendUser->password = '';
            $sendUser->company_id = null;
            return $this->sendResponse($sendUser, 200) ; 
           }
        return $this->sendResponse([], 401) ;

    }
}
