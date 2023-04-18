<?php

namespace Modules\Users\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Session,Auth;
use App\models\User;
use Modules\Space\Entities\Space;
use Illuminate\Routing\Controller;

class ProfileController extends Controller
{
    public function profile(Request $request,$username){
        $user = User::where('username', '=', $username)->first();
        if(empty($user)){
            abort(404);
        }
        $data['user'] = $user;
        $data['page_title'] = $user->getDisplayName();
        $data['spaces'] = Space::where('user_id',$user->id)->orderBy('id','desc')->paginate(6);
        return view('users::frontend.profile.index',$data);
    }
}
