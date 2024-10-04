@extends('layout.main')
@section('title', 'Blank Page')

@section('css-page')
<!---- isi css page  -->
@endsection

@section('breadcrumb')

@endsection

@section('content')
@if(count($flashmsg) > 0)
    @if($flashmsg['code'] == 200)
    <div class="alert alert-success" role="alert">
        {{ $flashmsg['msg'] }}
    </div>
    @else
    <div class="alert alert-danger" role="alert">
        {{ $flashmsg['msg'] }}
    </div>
    @endif
@endif

<div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>Ubah Password</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <form action="{{ url('/submitpassword') }}" method="POST">
                @csrf
                <div class="form-group row mb-4">
                    <label for="plama" class="col-sm-2 col-form-label">Password Lama</label>
                    <div class="col-sm-10">
                        <input type="password" name="passwordlama" class="form-control" id="plama" required>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label for="pbaru" class="col-sm-2 col-form-label">Password Baru</label>
                    <div class="col-sm-10">
                        <input type="password" name="passwordbaru" class="form-control" id="pbaru" onkeyup="cekpass()" required>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label for="pbaruR" class="col-sm-2 col-form-label">Masukan lagi Password Baru</label>
                    <div class="col-sm-10">
                        <input type="password" name="passwordbaruRe" class="form-control" id="pbaruR" onkeyup="cekpass()" required>
                    </div>
                </div>
                <span id="pesan" style="color:red"></span><br><br>
                <button type="submit" class="btn btn-primary" id="subbtn" disabled>Ubah Password</button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js-page')
<script>
    function cekpass(){
        if(document.getElementById('pbaru').value == "" || document.getElementById('pbaruR').value == ""){
            document.getElementById('pesan').innerHTML = "";
            document.getElementById('subbtn').disabled = true;
            return;
        }

        baru =  document.getElementById('pbaru').value;
        baruR =  document.getElementById('pbaruR').value;

        if(baru.length < 8){
            document.getElementById('pesan').innerHTML = "Password minimal 8 karakter";
            document.getElementById('subbtn').disabled = true;
        }else{
            if(baru != baruR){
                document.getElementById('pesan').innerHTML = "Password tidak sama";
                document.getElementById('subbtn').disabled = true;
            }else{
                document.getElementById('pesan').innerHTML = "";
                document.getElementById('subbtn').disabled = false;
            }
        }

        
    }
</script>
@endsection

