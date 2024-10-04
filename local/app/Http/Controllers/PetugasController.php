<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JwtService;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    public function index()
    {
        $menu = 'listruang';

        //========================================================================
        $flashMessage_json = session()->get('jwt');        
        $flashMessage = json_decode($flashMessage_json, true);
        $jwtService = new JwtService();
        $datauser = $jwtService->decodeToken($flashMessage['token']);
        $flashMessage_json = base64_encode($flashMessage_json);
        //========================================================================

        // dd($flashMessage_json);

        // print_r($datauser); echo "<br>";
        $dataruang = DB::table('ruang as r')
                    ->join('gedung as g', 'r.idgedung', '=', 'g.idgedung')
                    ->join('skema as s', 'r.idskema', '=', 's.idskema')
                    ->where('r.idruang', $datauser['idruang'])
                    ->select('r.idruang', 'r.nama_ruang', 'g.nama_gedung', 's.nama_skema', 'r.kelas_pimnas')
                    ->get();   
        
        return view('petugas.listruang', compact('flashMessage_json', 'menu', 'dataruang'));
    }

    public function index_post(Request $req){
        session()->flash('jwt', base64_decode($req->input('jwt')));
        // dd($req->all());
        return redirect('/kelas');
    }

    public function bukakelas(Request $req){
        // dd($req->all());
        //========================================================================
        $flashMessage_json = session()->get('jwt');        
        $flashMessage = json_decode($flashMessage_json, true);
        $jwtService = new JwtService();
        $datauser = $jwtService->decodeToken($flashMessage['token']);
        $flashMessage_json = base64_encode($flashMessage_json);
        //========================================================================

        // echo $flashMessage_json;die;

        if($req->post('idkelompok') !== null){
            // dd($req->all());

            $status = DB::table('kelompok')
                        ->where('idkelompok', $req->post('idkelompok'))
                        ->select('status_panggil')
                        ->first();

            if($status->status_panggil == '1'){
                date_default_timezone_set('Asia/Jakarta');
                $ts =  date('Y-m-d H:i:s');
                $update = DB::table('kelompok')
                            ->where('idkelompok', $req->post('idkelompok'))
                            ->update(['status_panggil' => '1', 'ts_terpanggil' => $ts]);
            }

        }

        $dataruang = DB::table('ruang as r')
                    ->join('kelompok as k', 'r.idruang', '=', 'k.idruang')
                    ->join('perguruan_tinggi as pt', 'k.idperguruan_tinggi', '=', 'pt.idperguruan_tinggi')
                    ->join('user_role_ruang as urr', 'r.idruang', '=', 'urr.idruang')
                    ->join('user_role as ur', 'urr.iduser_role', '=', 'ur.iduser_role')
                    ->where('r.idruang', $req->post('idruang'))
                    ->where('k.status_panggil', '0')
                    ->select('pt.nama_perguruan_tinggi', 
                            'k.judul_usulan', 'k.nama_ketua', 'k.idkelompok')
                    ->orderBy('k.idkelompok', 'asc')
                    ->get();

        $dataruang_json = json_encode($dataruang);
        $idruang = $req->post('idruang');

        return view('petugas.wheel', compact('flashMessage_json', 'dataruang_json', 'idruang'));
        // return view()->file(resource_path('views/petugas/wheel.blade.php'), ['flashMessage_json' => $flashMessage_json, 'dataruang_json' => $dataruang_json]);
    }

    public function kelas(Request $req){
        
    }

    public function history(Request $req){
        $menu = 'history';

        //========================================================================
        $flashMessage_json = session()->get('jwt');        
        $flashMessage = json_decode($flashMessage_json, true);
        $jwtService = new JwtService();
        $datauser = $jwtService->decodeToken($flashMessage['token']);
        $flashMessage_json = base64_encode($flashMessage_json);
        //========================================================================

        // dd($datauser);

        $histori = DB::table('ruang as r')
                    ->join('kelompok as k', 'r.idruang', '=', 'k.idruang')
                    ->join('perguruan_tinggi as pt', 'k.idperguruan_tinggi', '=', 'pt.idperguruan_tinggi')
                    ->join('user_role_ruang as urr', 'r.idruang', '=', 'urr.idruang')
                    ->join('user_role as ur', 'urr.iduser_role', '=', 'ur.iduser_role')
                    ->where('r.idruang', $datauser['idruang'])
                    ->where('k.status_panggil', '1')
                    ->select('pt.nama_perguruan_tinggi', 
                            'k.judul_usulan', 'k.nama_ketua', 'k.idkelompok', 'k.ts_terpanggil', 'r.nama_ruang', 'r.kelas_pimnas')
                    ->orderBy('k.ts_terpanggil', 'asc')
                    ->get();

        // dd($dataruang);
        return view('petugas.histori', compact('flashMessage_json', 'menu', 'histori'));

    }

    public function simpandataterpilih(Request $req){
        // print_r($req->all());
        date_default_timezone_set('Asia/Jakarta');
        $ts =  date('Y-m-d H:i:s');
        $update = DB::table('kelompok')
                    ->where('idkelompok', $req->post('idkelompok'))
                    ->update(['status_panggil' => '1', 'ts_terpanggil' => $ts]);

        if($update){
            return response()->json(['code'=>200, 'status' => 'success', 'message' => 'Data berhasil disimpan']);
        }
        else{
            return response()->json(['code'=>400, 'status' => 'failed', 'message' => 'Data gagal disimpan']);
        }

    }
}
