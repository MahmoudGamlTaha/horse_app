<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ConfigGlobal;
use App\Models\ShopCurrency;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form\Field\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserManagerController extends Controller
{
    use HasResourceActions;
    public function mobileEditProfile(Request $request){
        $user_id = $request->header("user-id");
        if(!is_numeric($user_id)){
            return $this->sendError("user failed");
        }
        $userData = $request->all();
        $userModel = config('admin.database.users_model');
        $model = new $userModel();
        $user = $model::findOrFail($user_id);
        if(isset($userData["avatar"])){
            $image = new Image($userData["avatar"]);
            $image->uniqueName();
            $image->move("ImgProfile");
            $user->avatar = $image->prepare($userData['avatar']);
            $user->path = Storage::disk(config('admin.upload.disk'))->url('');
        }
        if(isset($userData["name"])){
            $user->name = $userData["name"];
        }
        if(isset($userData["lname"])){
            $user->lname  = $userData["lname"];
        }
        if(isset($userData["email"])){
            $rules = ["email" => "required|unique:admin_users"];
            $message =["email" => "error mail exist"];
           $validate = Validator::make($userData, $rules ,$message);
           if($validate->fails()){
               return $this->sendError("mail exist", 400);
           }
            $user->email = $userData["email"];
        }
        if(isset($userData["mobile"])){
            if(is_numeric($userData["mobile"])){
                return $this->sendError("not valid number", 400);
            }
            $rules = ["mobile" => "required|unique:admin_users"];
            $message =["mobile" => "error mobile exist"];
           $validate = Validator::make($userData, $rules ,$message);
           if($validate->fails()){
               return $this->sendError("mail exist", 400);
           }
            $user->mobile = $userData["mobile"];
        }
        if(isset($userData["password"])){
            $user->password = bcrypt($userData["password"]);
        }
       $user->save();
       $user->password = "";
       $user->company_id = 0;
       $user->api_token = null;
       return $this->sendResponse($user, 200);
    }
   

}
