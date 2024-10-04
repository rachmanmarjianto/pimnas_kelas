<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    public function index()
    {
        $menu = 'Dashboard';
        $submenu = '';

        // echo 'hallo';die;

        return view('admin.dashboard', compact('menu', 'submenu'));
    }

    public function masteruser()
    {
        $menu = 'Master';
        $submenu = 'User';

        $flashmsg = session('msg');
        
        $user_q = DB::table('users as u')
                    ->select('u.id', 'u.username', 'r.nama as role', 'ru.nama_ruang', 'g.nama_gedung', 's.nama_skema', 
                            'ur.status', 'ur.iduser_role')
                    ->leftJoin('user_role as ur', 'u.id', '=', 'ur.idusers')
                    ->leftJoin('role as r', 'ur.idrole', '=', 'r.idrole')
                    ->leftJoin('user_role_ruang as urr', 'ur.iduser_role', '=', 'urr.iduser_role')
                    ->leftJoin('ruang as ru', 'urr.idruang', '=', 'ru.idruang')
                    ->leftJoin('gedung as g', 'ru.idgedung', '=', 'g.idgedung')
                    ->leftJoin('skema as s', 'ru.idskema', '=', 's.idskema')
                    ->where('u.status', '1')
                    ->orderBy('u.id', 'asc')
                    ->orderBy('r.nama', 'asc')
                    ->get();

        // dd($user_q);

        $user = array();
        foreach($user_q as $u){
            $user[$u->id]['id'] = $u->id;
            $user[$u->id]['username'] = $u->username;
            $user[$u->id]['role'][] = array(
                                            'iduser_role' => $u->iduser_role,
                                            'role' => $u->role,
                                            'status' => $u->status
                                        );
            $user[$u->id]['ruang'] = $u->nama_ruang." - ".$u->nama_gedung." - ".$u->nama_skema;
            $user[$u->id]['status'] = $u->status;
        }

        // dd($user);

        $skema = DB::table('skema')
                    ->orderBy('nama_skema', 'asc')
                    ->get();

        return view('admin.masteruser', compact('menu', 'submenu', 'flashmsg', 'user',  'skema'));
    }

    public function masterruang(){
        $menu = 'Master';
        $submenu = 'Ruang';

        $gedung = DB::table('gedung')
                    ->orderBy('nama_gedung', 'asc')
                    ->get();
        $skema = DB::table('skema')
                    ->orderBy('nama_skema', 'asc')
                    ->get();

        $ruang = DB::table('ruang as r')
                    ->select('r.idruang', 'r.nama_ruang', 'g.nama_gedung', 's.nama_skema', 'r.kelas_pimnas')
                    ->join('gedung as g', 'r.idgedung', '=', 'g.idgedung')
                    ->join('skema as s', 'r.idskema', '=', 's.idskema')
                    ->orderBy('g.nama_gedung', 'asc')
                    ->orderBy('r.nama_ruang', 'asc')
                    ->select('r.*', 'g.nama_gedung', 's.nama_skema')
                    ->where('r.status', '1')
                    ->get();

        return view('admin.masterruang', compact('menu', 'submenu', 'gedung', 'skema', 'ruang'));
    }

    public function submitruang(Request $request){
        $menu = 'Master';
        $submenu = 'Ruang';

        $data = [
            'nama_ruang' => $request->input('namaruang'),
            'idgedung' => $request->input('gedung'),
            'idskema' => $request->input('skema'),
            'status' => '1',
        ];

        DB::table('ruang')->insert($data);

        return redirect(URL::previous());
    }

    public function deleteruang($id){
        $menu = 'Master';
        $submenu = 'Ruang';

        DB::table('ruang')->where('idruang', $id)->update(['status' => '0']);

        return redirect(URL::previous());
    }

    public function editruang($id){
        $menu = 'Master';
        $submenu = 'Ruang';

        $gedung = DB::table('gedung')
                    ->orderBy('nama_gedung', 'asc')
                    ->get();
        $skema = DB::table('skema')
                    ->orderBy('nama_skema', 'asc')
                    ->get();

        $ruang = DB::table('ruang')
                    ->where('idruang', $id)
                    ->first();

        return view('admin.editruang', compact('menu', 'submenu', 'gedung', 'skema', 'ruang'));
    }

    public function updateruang(Request $request){
        $menu = 'Master';
        $submenu = 'Ruang';

        $data = [
            'nama_ruang' => $request->input('namaruang'),
            'idgedung' => $request->input('gedung'),
            'idskema' => $request->input('skema'),
        ];

        DB::table('ruang')->where('idruang', $request->input('idruang'))->update($data);

        return redirect(url('/admin/masterruang'));
    }

    public function submituser(Request $request){
        $menu = 'Master';
        $submenu = 'User';

        // dd($request->all());
        $cek = DB::table('users')
                    ->where('username', $request->input('username'))
                    ->count();

        if($cek > 0){
            return redirect(URL::previous())->with('msg', 'Username already exist');
        }
        else{
            $data = [
                'username' => $request->input('username'),
                'password' => password_hash($request->input('username')."123", PASSWORD_BCRYPT),
                'status' => '1',
            ];

            DB::table('users')->insert($data);

            $idusers = DB::table('users')->where('username', $request->input('username'))->first()->id;

            $data = array();
            $status = '1';
            $lo = 0;
            foreach($request->input('role') as $role){
                $data[] = [
                    'idusers' => $idusers,
                    'idrole' => $role,
                    'status' => $status,
                ];

                if($status == '1'){
                    $status = '0';
                }

                if($role == '2'){
                    $lo = 1;
                }
            }

            DB::table('user_role')->insert($data);

            if($lo == 1){
                $iduser_role = DB::table('user_role as ur')
                                    ->join('role as r', 'ur.idrole', '=', 'r.idrole')
                                    ->where('ur.idusers', $idusers)
                                    ->where('r.nama', 'LOruang')
                                    ->first()->iduser_role;

                DB::table('user_role_ruang')->insert(array(
                    'iduser_role' => $iduser_role,
                    'idruang' => $request->input('ruang'),
                ));
            }
            

            return redirect(URL::previous());
        }

        // $data = [
        //     'username' => $request->input('username'),
        //     'password' => password_hash($request->input('password'), PASSWORD_BCRYPT),
        //     'status' => '1',
        // ];

        // DB::table('users')->insert($data);

        return redirect(URL::previous());
    }

    public function getruang(Request $request){
        $ruang = DB::table('ruang')
                    ->where('idgedung', $request->input('idgedung'))
                    ->where('idskema', $request->input('idskema'))
                    ->where('status', '1')
                    ->get();

        return response()->json($ruang);
    }

    public function getgedung(Request $request){
        $gedung_q = DB::table('gedung as g')
                    ->join('ruang as r', 'g.idgedung', '=', 'r.idgedung')
                    ->where('r.status', '1')
                    ->where('r.idskema', $request->input('idskema'))
                    ->get();

        $retval['gedung'] = array();
        $retval['ruang'] = array();
        foreach($gedung_q as $g){
            $retval['gedung'][$g->idgedung] = array(
                'idgedung' => $g->idgedung,
                'nama_gedung' => $g->nama_gedung,
            );
            

            $retval['ruang'][$g->idgedung][] = array(
                'idruang' => $g->idruang,
                'nama_ruang' => $g->nama_ruang,
            );
        }
        

        return response()->json($retval);
    }

    public function deleteuser($id){
        $menu = 'Master';
        $submenu = 'User';

        DB::table('users')->where('id', $id)->update(['status' => '0']);

        return redirect(URL::previous());
    }

    public function edituser($id){
        $menu = 'Master';
        $submenu = 'User';

        $user_q = DB::table('users as u')
                    ->select('u.id', 'u.username', 'r.nama as role', 'ur.iduser_role', 'r.idrole', 'ru.idruang', 'ru.idgedung', 'ru.idskema')
                    ->leftJoin('user_role as ur', function($join){
                        $join->on('u.id', '=', 'ur.idusers');
                    })
                    ->leftJoin('role as r', 'ur.idrole', '=', 'r.idrole')
                    ->leftJoin('user_role_ruang as urr', 'ur.iduser_role', '=', 'urr.iduser_role')
                    ->leftJoin('ruang as ru', 'urr.idruang', '=', 'ru.idruang')
                    ->where('u.status', '1')
                    ->where('u.id', $id)
                    ->get();

        // dd($user_q);

        $user = array(
            'user' => array(),
            'role' => array(),
            'ruang' => array(),
        );

        foreach($user_q as $u){
            $user['user']['id'] = $u->id;
            $user['user']['username'] = $u->username;
            $user['role'][$u->idrole] = array(
                                            "iduser_role" => $u->iduser_role,
                                            "role" => $u->role
                                        );
            $user['ruang'][$u->idrole]['idruang'] = $u->idruang;
            $user['ruang'][$u->idrole]['idgedung'] = $u->idgedung;
            $user['ruang'][$u->idrole]['idskema'] = $u->idskema;
        }

        // dd($user);

        $role = DB::table('role')->get();

        $skema = DB::table('skema')
                    ->orderBy('nama_skema', 'asc')
                    ->get();
        // dd($skema);

        $gedung = array();
        $ruang = array();

        if(!empty($user['ruang'][2]['idskema'])){
            $gedung_q = DB::table('gedung as g')
                        ->join('ruang as r', 'g.idgedung', '=', 'r.idgedung')
                        ->where('r.status', '1')
                        ->where('r.idskema', $user['ruang'][2]['idskema'])
                        ->get();

            
            foreach($gedung_q as $g){
                $gedung[$g->idgedung] = array(
                    'idgedung' => $g->idgedung,
                    'nama_gedung' => $g->nama_gedung,
                );
                

                $ruang[$g->idgedung][] = array(
                    'idruang' => $g->idruang,
                    'nama_ruang' => $g->nama_ruang,
                );
            }
        }

        // dd($gedung);
        
        $retval['gedung'] = $gedung;
        $retval['ruang'] = $ruang;

        $retval = json_encode($retval);
        // echo $retval;die;
        

        return view('admin.edituser', compact('menu', 'submenu', 'user', 'role', 'skema', 'gedung', 'ruang', 'retval'));
    }

    public function submitedituser(Request $req){
        // dd($req->all());
        $role_new = array();
        if($req->post('role') !== null){
            foreach($req->post('role') as $r){
                $role_new[] = $r;
            } 
        }     
        

        $role_prev = json_decode($req->post('prevrole'), true);

        if(isset($role_prev[1])){
            
            if(!in_array(1, $role_new)){
                DB::table('user_role')
                    ->where('iduser_role', $role_prev[1]['iduser_role'])
                    ->delete();
            }
        }
        else{
            if(in_array(1, $role_new)){
                DB::table('user_role')
                    ->where('idusers', $req->post('iduser'))
                    ->update(['status' => 0]);

                $data = array(
                    'idusers' => $req->post('iduser'),
                    'idrole' => 1,
                    'status' => 1,
                );

                DB::table('user_role')->insert($data);
            }
        }

        if(isset($role_prev[2])){
            if(in_array(2, $role_new)){
                DB::table('user_role_ruang')
                        ->where('iduser_role', $role_prev[2]['iduser_role'])
                        ->update(['idruang' => $req->post('ruang')]);
            }
            else{
                // dd($role_prev[2]);
                DB::table('user_role_ruang')
                            ->where('iduser_role', $role_prev[2]['iduser_role'])
                            ->delete();
                
                DB::table('user_role')
                    ->where('iduser_role', $role_prev[2]['iduser_role'])
                    ->delete();
            }
        }
        else{
            if(in_array(2, $role_new)){
                DB::table('user_role')
                    ->where('idusers', $req->post('iduser'))
                    ->update(['status' => 0]);

                DB::table('user_role')
                    ->insert(array(
                        'idusers' => $req->post('iduser'),
                        'idrole' => 2,
                        'status' => 1,
                    ));

                $iduser_role = DB::table('user_role')
                                    ->where('idusers', $req->post('iduser'))
                                    ->where('idrole', 2)
                                    ->first()->iduser_role;

                $data = array(
                    'iduser_role' => $iduser_role,
                    'idruang' => $req->post('ruang'),
                );

                DB::table('user_role_ruang')->insert($data);
            }
        }

        $user_aktif = DB::table('users as u')
                        ->select('u.id')
                        ->join('user_role as ur', function($join){
                            $join->on('u.id', '=', 'ur.idusers');
                            $join->where('ur.status', '=', '1');
                        })
                        ->join('role as r', 'ur.idrole', '=', 'r.idrole')
                        ->where('u.id', $req->post('iduser'))
                        ->count();
        
        if($user_aktif == 0){
            DB::table('user_role')
                ->where('idusers', $req->post('iduser'))
                ->update(['status' => 1]);
        }
                    

        return redirect(url('/admin/masteruser'));
        
        // if($role_prev['role'])


    }

    public function monitorruang(){
        $menu = 'Monitor';
        $submenu = '';

        $ruang = DB::table('ruang as r')
                    ->select('r.idruang', 'r.nama_ruang', 'g.nama_gedung', 's.nama_skema', DB::raw('count(k.idkelompok) as kelompok_maju'))
                    ->join('gedung as g', 'r.idgedung', '=', 'g.idgedung')
                    ->join('skema as s', 'r.idskema', '=', 's.idskema')
                    ->leftJoin('kelompok as k', function($join) {
                        $join->on('r.idruang', '=', 'k.idruang')
                             ->where('k.status_panggil', '=', '1');
                    })
                    ->where('r.status', '1')
                    ->groupBy('r.idruang', 'r.nama_ruang', 'g.nama_gedung', 's.nama_skema')
                    ->orderBy('g.nama_gedung', 'asc')
                    ->orderBy('r.nama_ruang', 'asc')
                    ->get();

        // dd($ruang);

        return view('admin.monitorruang', compact('menu', 'submenu', 'ruang'));
    }

    public function dalamruang($id){
        $menu = 'Monitor';
        $submenu = '';

        $ruang_q = DB::table('ruang as r')
                    ->select('r.idruang', 'r.nama_ruang', 'g.nama_gedung', 's.nama_skema', 'k.*', 'pt.nama_perguruan_tinggi')
                    ->join('gedung as g', 'r.idgedung', '=', 'g.idgedung')
                    ->join('skema as s', 'r.idskema', '=', 's.idskema')
                    ->leftJoin('kelompok as k', 'r.idruang', '=', 'k.idruang')
                    ->leftJoin('perguruan_tinggi as pt', 'k.idperguruan_tinggi', '=', 'pt.idperguruan_tinggi')
                    ->where('r.idruang', $id)
                    ->get();

        

        // dd($ruang_q);

        $ruang = array(
            'idruang' => $ruang_q[0]->idruang,
            'nama_ruang' => $ruang_q[0]->nama_ruang,
            'nama_gedung' => $ruang_q[0]->nama_gedung,
            'nama_skema' => $ruang_q[0]->nama_skema
        );

        $kelompok = array();
        foreach($ruang_q as $r){
            $kelompok[$r->idkelompok] = array(
                'idkelompok' => $r->idkelompok,
                'nama_ketua' => $r->nama_ketua,
                'nama_perguruan_tinggi' => $r->nama_perguruan_tinggi,
                'judul_usulan' => $r->judul_usulan,
                'status_panggil' => $r->status_panggil,
                'ts_terpanggil' => $r->ts_terpanggil
            );
        }


        return view('admin.dalamruang', compact('menu', 'submenu', 'ruang', 'kelompok'));
    }

    public function resetpass(Request $request){
        $menu = 'Master';
        $submenu = 'User';

        $username = DB::table('users')
                    ->where('id', $request->input('iduser'))
                    ->first()->username;

        $data = [
            'password' => password_hash($username."123", PASSWORD_BCRYPT),
        ];

        $ret = DB::table('users')->where('id', $request->input('iduser'))->update($data);

        if($ret){
            return response()->json(array('msg' => 'Berhasil, password: [username]123'));
        }
        else{
            return response()->json(array('msg' => 'Password reset failed'));
        }
    }

    public function aktifkanrole(Request $req){
        // print_r($req->all());
        DB::table('user_role')
            ->where('idusers', $req->post('iduser'))
            ->update(['status' => 0]);

        DB::table('user_role')
            ->where('iduser_role', $req->post('iduser_role'))
            ->update(['status' => 1]);

        return redirect(URL::previous());
    }

    public function resetkel(Request $req){
        // dd($req->all());
        DB::table('kelompok')
            ->where('idkelompok', $req->post('idkel'))
            ->update(['status_panggil' => 0, 'ts_terpanggil' => null]);

        return redirect(URL::previous());
    }


}
