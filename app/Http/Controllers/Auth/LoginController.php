<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/admin/dashboard';

    /**
     * Get the post login redirect path based on user role.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $user = Auth::user();
        
        // Load user role with active status
        $user = User::with(['userRole' => function($query) {
            $query->where('status', 1);
        }, 'userRole.role'])
        ->find($user->iduser);
        
        // Get user's active role
        $userRole = $user->userRole->first();
        
        if ($userRole && $userRole->role) {
            $roleName = strtolower($userRole->role->nama_role ?? '');
            
            // Redirect based on role
            switch ($roleName) {
                case 'admin':
                case 'administrator':
                    return '/admin/dashboard';
                    
                case 'dokter':
                case 'doctor':
                    return '/dokter/dashboard';
                    
                case 'perawat':
                case 'nurse':
                    return '/perawat/dashboard';
                    
                case 'resepsionis':
                case 'receptionist':
                    return '/resepsionis/dashboard';
                    
                case 'apoteker':
                case 'pharmacist':
                    return '/apoteker/dashboard';
                    
                default:
                    return '/home';
            }
        }
        
        // Default redirect if no role found
        return '/home';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }


    public function index(){
        return view('login');
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::with(['userRole' => function($query){
            $query->where('status', 1);
        }, 'userRole.role'])
        ->where('email', $request->input('email'))
        ->first();

        if (!$user) {
            return redirect()->back()
            ->withErrors(['email' => 'Email tidak ditemukan'])
            ->withInput();
        }

        if(!Hash::check($request->password, $user->password)){
            return redirect()->back()
            ->withErrors(['password' => 'Password salah'])
            ->withInput();
        }

        $namaRole = Role::where('idrole', $user->userRole[0]->idrole ?? null)->first();

        Auth::login($user);

        $request->session()->put([
            'user_id' => $user->iduser,
            'user_name' => $user->nama,
            'user_email' => $user->email,
            'user_role' => $user->userRole[0]->idrole ?? 'user',
            'user_role_name' => $namaRole->nama_role ?? 'user',
            'user_status' => $user->userRole[0]->status ?? 'active'
        ]);
        
        return redirect()->intended('/admin/dashboard')->with('success', 'Login berhasil');

    }

    public function logout(Request $request){
        Auth::logout();
        
        $request->session->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout berhasil');
    }

}
