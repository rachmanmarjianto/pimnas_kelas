@extends('layout.main')
@section('title', 'Edit User')

@section('css-page')
<!---- isi css page  -->
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Master</a></li>
<li class="breadcrumb-item"><a href="{{ url('/admin/masteruser') }}">User</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
{{-- @php print_r($user['ruang'][2]);die; @endphp --}}
<div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                    <h4>Tambah User Baru</h4>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-6 col-6" style="text-align: right">
                    <span style="color:red; margin-right:5px" id="rp-pesan"></span> <button class="btn btn-warning mb-2 me-4" style="margin-top:10px" onclick="resetpass()">Reset Password</button>
                    
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <form action="{{ url('/admin/submitedituser') }}" method="POST">
                @csrf
                <div class="form-group row mb-4">
                    <label for="colFormLabel" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="colFormLabel" value="{{ $user['user']['username'] }}" style="color:black" readonly>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label for="colFormLabel" class="col-sm-2 col-form-label">Role</label>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input class="form-check-input" name="role[]" type="checkbox" value="1" id="c_admin" @if(!empty($user['role'][1])) checked @endif>
                            <label class="form-check-label" for="c_admin">Admin</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="role[]" type="checkbox" value="2" id="c_LO" onchange="locheck(this)"  @if(!empty($user['role'][2])) checked @endif>
                            <label class="form-check-label" for="c_LO">LO Ruang</label>
                        </div>
                    </div>
                </div>
                <span id="rolelo" hidden="true">
                    
                    <div class="form-group row mb-4">
                        <label for="colFormLabel" class="col-sm-2 col-form-label">Skema</label>
                        <div class="col-sm-10">
                            <select class="form-select" aria-label="Default select" id="selskema" onchange="skema()">
                                <option value="">Pilih Skema</option>
                            @foreach($skema as $s)
                                
                                @if(!empty($user['ruang'][2]))
                                    @if($user['ruang'][2]['idskema'] == $s->idskema)
                                        <option value="{{ $s->idskema }}" selected>{{ $s->nama_skema }}</option>
                                    @else
                                        <option value="{{ $s->idskema }}">{{ $s->nama_skema }}</option>
                                    @endif
                                @else
                                    <option value="{{ $s->idskema }}">{{ $s->nama_skema }}</option>
                                @endif
                            @endforeach
                            </select>
                        </div>
                    </div>
                   
                    <div class="form-group row mb-4" id="loruang">
                        <label for="colFormLabel" class="col-sm-2 col-form-label" >Gedung</label>
                        <div class="col-sm-10">
                            <select class="form-select" aria-label="Default select" id="selgedung" onchange="gedung()">
                                <option value="">Pilih Gedung</option>
                                @if(!empty($user['ruang'][2]))
                                    @foreach($gedung as $g)
                                        @if($user['ruang'][2]['idgedung']  == $g['idgedung'])
                                            <option value="{{ $g['idgedung'] }}" selected>{{ $g['nama_gedung'] }}</option>
                                        @else
                                            <option value="{{ $g['idgedung'] }}">{{ $g['nama_gedung'] }}</option>
                                        @endif
                                    @endforeach
                                
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-4" id="loruang">
                        <label for="colFormLabel" class="col-sm-2 col-form-label" >Ruang LO</label>
                        <div class="col-sm-10">
                            <select class="form-select" aria-label="Default select" id="selruang" name="ruang">
                                <option value="">Pilih Ruang</option>
                                @if(!empty($user['ruang'][2]) && !empty($user['ruang'][2]['idgedung']))
                                    @foreach($ruang[$user['ruang'][2]['idgedung']] as $r)
                                        @if($user['ruang'][2]['idruang'] == $r['idruang'])
                                            <option value="{{ $r['idruang'] }}" selected>{{ $r['nama_ruang'] }}</option>
                                        @else
                                            <option value="{{ $r['idruang'] }}">{{ $r['nama_ruang'] }}</option>
                                        @endif
                                    @endforeach
                                
                                @endif
                            </select>
                        </div>
                    </div>
                    
                </span>
                <input type="hidden" name="iduser" value="{{ $user['user']['id'] }}">
                <input type="hidden" name="prevrole" value="{{ json_encode($user['role']) }}">
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js-page')
<script src="{{ asset('/public/js/jquery-3.6.0.min.js') }}"></script>
<script>
    var idgedung = 0;
    var idskema = 0;
    var dataruang = [];

    window.onload = function(){
        var e = document.getElementById('c_LO');
        locheck(e);
        dataruang = JSON.parse('<?php echo $retval; ?>');
        dataruang = dataruang['ruang'];
    }

    function skema(){
        var e = document.getElementById('selskema');
        idskema = e.value;
        pilihgedung();
    }

    function pilihgedung(){
        if(idskema != 0){
            $.ajax({
                url: "{{ url('/admin/getgedung') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    idskema: idskema
                },
                success: function(data){
                    // console.log(data);

                    dataruang = data['ruang'];
                    var e = document.getElementById('selgedung');
                    e.innerHTML = '';
                    var opt = document.createElement('option');
                    opt.value = '';
                    opt.innerHTML = 'Pilih Gedung';
                    e.appendChild(opt);
                    Object.keys(data['gedung']).forEach(function(k){
                        var opt = document.createElement('option');
                        opt.value = data['gedung'][k].idgedung;
                        opt.innerHTML = data['gedung'][k].nama_gedung;
                        e.appendChild(opt);
                    });
                }
                    
                
            });

            var e = document.getElementById('selruang');
            e.innerHTML = '';
            var opt = document.createElement('option');
            opt.value = '';
            opt.innerHTML = 'Pilih Ruang';
            e.appendChild(opt);
        }
    }

    function gedung(){
        var e = document.getElementById('selgedung');
        idgedung = e.value;
        if(idgedung != 0 || idgedung != ''){
            var e = document.getElementById('selruang');
            e.innerHTML = '';
            var opt = document.createElement('option');
            opt.value = '';
            opt.innerHTML = 'Pilih Ruang';
            e.appendChild(opt);
            dataruang[idgedung].forEach(function(r){
                var opt = document.createElement('option');
                opt.value = r.idruang;
                opt.innerHTML = r.nama_ruang;
                e.appendChild(opt);
            });
        }

        // console.log(dataruang[idgedung]);

        
        
    }

    

    function locheck(e){
        if(e.checked){
            document.getElementById('rolelo').hidden = false;
            document.getElementById('selruang').required = true;
        }else{
            document.getElementById('rolelo').hidden = true;
            document.getElementById('selruang').required = false;
        }
    }

    function resetpass(){
        $('#rp-pesan').html('Loading...');

        $.ajax({
            url: "{{ url('/admin/resetpass') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                iduser: "{{ $user['user']['id'] }}"
            },
            success: function(data){
                $('#rp-pesan').html(data['msg']);
            }
        });
    }
</script>
@endsection

