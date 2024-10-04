<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\JwtService;

class Checkjwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // session()->forget('jwt');
        // dd($request->all());

        if(!empty(session('jwt')) && session('jwt') != null){
            // dd('masuk');
            // dd(session('jwt'));
            $jwt = json_decode(session('jwt'), true);
        }
        else if(!empty($request->input('jwt')) && $request->input('jwt') != null){
            // dd('sini bang');
            // dd($request->all());
            $jwt = json_decode(base64_decode($request->input('jwt')), true);
        }
        else{
            return redirect('/');
        }
        
        // dd($jwt);
        // dd($jwt['token']);
        $jwttoken = $jwt['token'];
        $jwtService = new JwtService();
        $data = $jwtService->decodeToken($jwttoken);
        // dd($data);

        // print_r($data); echo "<br>"; 

        if($data['role'] !== $role){
            return redirect('/unauthorized');
        }

        if($jwtService->verifyToken($jwt['token']) == false){
            return redirect('/');
        }

        if($data['exp'] < time()){
            return redirect('/')->with('msg', 'Token expired');
        }

        // print_r($data);
        // echo "<br><br>".time();die;

         
        $tokenextended = $jwtService->extendsToken($jwt['token']);

        $jwt_token = array(
            'token' => $tokenextended,
            'token_type' => 'Bearer',
            'expires_in' => 7200 //2 hours
        );

        session()->flash('jwt', json_encode($jwt_token));

        return $next($request);
    }
}
