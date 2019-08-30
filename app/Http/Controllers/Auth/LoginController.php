<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request, $type=UserLogin::TYPE_ADMIN)
    {
        $request->validate([
            'usr' => 'required',
            'pwd' => 'required',
        ]);

        if (Auth::attempt([$this->username()=>$request->usr, 'password'=>$request->pwd, 'type'=> $type])) {
            if($type==UserLogin::TYPE_TEACHER) {
                $this->redirectTo = '/giao-vien/';
            }elseif ($type==UserLogin::TYPE_STUDENT) {
                $this->redirectTo = '/hoc-vien/';
            }
            return redirect($this->redirectTo);
        } else {
            return redirect()->back()->with('error_msg', 'Thông tin đăng nhập không chính xác.');
        }
    }

    public function studentLogin(Request $request) {
        return $this->login($request, UserLogin::TYPE_STUDENT);
    }

    public function teacherLogin(Request $request) {
        return $this->login($request, UserLogin::TYPE_TEACHER);
    }

    public function logout(Request $request)
    {
        $loginType = Auth::user()->type;
        if ($loginType==UserLogin::TYPE_ADMIN) {
            $redirect = 'admin/login';
        } elseif ($loginType==UserLogin::TYPE_TEACHER) {
            $redirect = 'giao-vien/dang-nhap';
        } elseif ($loginType==UserLogin::TYPE_STUDENT) {
            $redirect = 'hoc-vien/dang-nhap';
        }
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect($redirect);
    }

    public function username()
    {
        return 'loginID';
    }
}
