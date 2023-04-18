<?php

namespace Modules\Users\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Session,Auth;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function myprofile()
    { 
        return view('dashboard.myprofile');
    }

    public function changePassword() {
        return view('dashboard.change-password');
    }

    public function updatePassword(Request $request) {
        if(Auth::user()) {
            if(Hash::check($request->get('old_password'), Auth::user()->password)) {
                $this->resetPassword(auth()->user(), $request->npass);
                $msg = trans('flash.success.password_updated_successfully');
                $type = 'success';
                $reset = true; 
            } else {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'old_password' => [trans('flash.error.please_enter_old_password')]
                ]);
                throw $error;
            }
            return response()->json(['status_code' => 200, 'type' => $type, 'message' => $msg, 'reset' => $reset]);
        }
        return response()->json(['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')]);
    }

    protected function resetPassword($user, $password) {
        $user->forceFill([
            'password' => trim(Hash::make($password)),
            'remember_token' => Str::random(60),
        ])->save();
    }

    public function editProfile() {
        $countries = Country::pluck('countryName','countryName');
        return view('dashboard.edit-profile',compact('countries'));
    }

    public function updateProfile(Request $request) {
        $id = Auth::user()->id;
        $request->validate([
            'first_name' => ['required', 'string', 'max:150'],
            'last_name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string','max:15'],
            'nationality' => ['nullable','string', 'max:150'],
            'company' => ['nullable','string', 'max:150'],
            'dob' => ['required', 'string', 'max:100'],
            'email' => "required|unique:users,email,$id",
        ]);
        $filleable = $request->only('first_name','last_name','dob','email','nationality','phone','company');
        if (Auth::user()->update($filleable)) {
            return response()->json(['status_code' => 200, 'type' => 'success', 'message' => trans('flash.success.user_updated_successfully')]);
        } else {
            return response()->json(['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')]);
        }
        return back();
    }
}
