<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Services\JwtService;

class LoginController extends Controller
{
    public function index()
    {
        $flashmsg = session('msg');

        return view('login', compact('flashmsg'));
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // $pass = password_hash($password, PASSWORD_BCRYPT);
        // echo $pass;die;

        $user = DB::table('users as u')
                    ->select('u.id', 'u.username', 'u.password', 'r.nama as role')
                    ->join('user_role as ur', function($join){
                        $join->on('u.id', '=', 'ur.idusers');
                        $join->where('ur.status', '=', '1');
                    })
                    ->join('role as r', 'ur.idrole', '=', 'r.idrole')
                    ->where('username', $username)
                    ->first();

        // echo $user->password;die;

        // dd($user);

        if(password_verify($password, $user->password)){
            session(['userdata' => $user]);
            Auth::loginUsingId(session('userdata')->id, true);

            if($user->role == 'admin'){
                return redirect('/admin');
            }
            else{
                // return redirect('/kelas');
                $ruang = DB::table('ruang as r')
                            ->join('user_role_ruang as urr', 'r.idruang', '=', 'urr.idruang')
                            ->join('user_role as ur', 'urr.iduser_role', '=', 'ur.iduser_role')
                            ->where('ur.idusers', session('userdata')->id)
                            ->select('r.idruang', 'ur.iduser_role')
                            ->get();

                if(count($ruang) == 0){
                    return redirect('/')->with('msg', 'Anda tidak memiliki akses ke ruang kelas manapun');
                }

                $payload = [
                    'id' => session('userdata')->id,
                    'username' => session('userdata')->username,
                    'role' => session('userdata')->role,
                    'iduser_role' => $ruang[0]->iduser_role,
                    'idruang' => $ruang[0]->idruang
                ];

                $jwtService = new JwtService();
                $token = $jwtService->createToken($payload);


                $jwt = array(
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => 7200 //2 hours
                );

                // dd($token);
                session()->flash('jwt', json_encode($jwt));
                return redirect('/kelas');
            }
        }            
        else{
            return redirect('/')->with('msg', 'Invalid username or password');
        }
            

        // dd($user);

        
    }

    public function logout()
    {
        Auth::logout();
        session()->forget('userdata');

        return redirect('/');
    }

    public function ubahpassword(){
        $menu = 'Dashboard';
        $submenu = '';

        if(!empty(session('retval')))
            $flashmsg = session('retval');
        else
            $flashmsg = array();

        return view('ubahpassword', compact('menu', 'submenu', 'flashmsg'));
    }

    public function submitpassword(Request $req){
        $passlama = $req->post('passwordlama');
        $passbaru = $req->post('passwordbaru');
        $passbaru2 = $req->post('passwordbaruRe');

        // dd(session('userdata')->password);

        // $user = DB::table('users')
        //             ->where('id', session('userdata')->id)
        //             ->first();

        if(password_verify($passlama, session('userdata')->password)){
            if($passbaru == $passbaru2){
                $pass = password_hash($passbaru, PASSWORD_BCRYPT);

                DB::table('users')
                    ->where('id', session('userdata')->id)
                    ->update([
                        'password' => $pass
                    ]);

                return redirect('/ubahpassword')->with('retval', [
                                                                    'code' => 200,
                                                                    'msg' =>'Password berhasil diubah'
                                                                ]);
            }
            else{
                return redirect('/ubahpassword')->with('retval', [
                                                                    'code' => 400,
                                                                    'msg' => 'Password baru tidak sama'
                                                                ]);
            }
        }
        else{
            return redirect('/ubahpassword')->with('retval', [
                                                                'code' => 401,
                                                                'msg' => 'Password lama salah'
                                                            ]);
        }
    }
}
