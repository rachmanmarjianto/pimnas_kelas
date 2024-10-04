@extends('layout.main')
@section('title', 'Master User')

@section('css-page')
<!---- isi css page  -->
<link rel="stylesheet" type="text/css" href="{{ asset('/public/template/src/plugins/src/table/datatable/datatables.css') }}">
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Master</a></li>
    <li class="breadcrumb-item active" aria-current="page">User</li>
@endsection

@section('content')
<div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>Tambah User Baru</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <form action="{{ url('/admin/submituser') }}" method="POST">
                @csrf
                <div class="form-group row mb-4">
                    <label for="colFormLabel" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" name="username" class="form-control" id="colFormLabel" required>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label for="colFormLabel" class="col-sm-2 col-form-label">Role</label>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input class="form-check-input" name="role[]" type="checkbox" value="1" id="c_admin">
                            <label class="form-check-label" for="c_admin">Admin</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="role[]" type="checkbox" value="2" id="c_LO" onchange="locheck(this)">
                            <label class="form-check-label" for="c_LO">LO Ruang</label>
                        </div>
                    </div>
                </div>
                <span id="rolelo" style="display:none">
                    <div class="form-group row mb-4">
                        <label for="colFormLabel" class="col-sm-2 col-form-label">Skema</label>
                        <div class="col-sm-10">
                            <select class="form-select" aria-label="Default select" id="selskema" onchange="skema()">
                                <option value="0" selected>Pilih Skema</option>
                            @foreach($skema as $s)
                                <option value="{{ $s->idskema }}">{{ $s->nama_skema }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-4" id="loruang">
                        <label for="colFormLabel" class="col-sm-2 col-form-label" >Gudang</label>
                        <div class="col-sm-10">
                            <select class="form-select" aria-label="Default select" id="selgedung" onchange="gedung()">
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-4" id="loruang">
                        <label for="colFormLabel" class="col-sm-2 col-form-label" >Ruang LO</label>
                        <div class="col-sm-10">
                            <select class="form-select" aria-label="Default select" id="selruang" name="ruang" required>
                            
                            </select>
                        </div>
                    </div>
                    
                </span>
                <button type="submit" class="btn btn-primary">Submit</button>
                <span style="color: red;">{{ $flashmsg }}</span>
            </form>
        </div>
    </div>
</div>
<div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>List User</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area br-8">
            <table id="zero-config" class="table dt-table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Ruang</th>
                        <th class="no-content">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user as $u)
                    <tr>
                        <td>{{ $u['username'] }}</td>
                        
                        <td>
                            <ul>
                            @foreach($u['role'] as $r)
                                @if($r['status'] == 1)
                                    <li><span style="cursor:pointer" onclick="aktifkanrole({{ $r['iduser_role'] }}, {{ $u['id'] }})">{{ $r['role'] }}</span> - <span style="color:blue">active</span></li>
                                @else
                                    <li><span style="cursor:pointer" onclick="aktifkanrole({{ $r['iduser_role'] }}, {{ $u['id'] }})">{{ $r['role'] }}</span></li>
                                @endif
                            @endforeach
                            </ul>
                        </td>
                        <td>{{ $u['ruang'] }}</td>
                        <td>
                            <a href="{{ url('/admin/edituser/'.$u['id']) }}" class="btn btn-primary">Edit</a>
                            <a href="{{ url('/admin/deleteuser/'.$u['id']) }}" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<form method="post" id="formaktifkanrole" action="{{ url('/admin/aktifkanrole') }}">
    @csrf
    <input type="hidden" name="iduser_role" id="idrole_subaktifrole">
    <input type="hidden" name="iduser" id="iduser_subaktifrole">
</form>
@endsection

@section('js-page')
<script src="{{ asset('/public/js/jquery-3.6.0.min.js') }}"></script>
    
<script src="{{ asset('/public/template/src/plugins/src/table/datatable/datatables.js') }}"></script>

<script>

    $('#zero-config').DataTable({
        "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
    "<'table-responsive'tr>" +
    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
           "sLengthMenu": "Results :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [7, 10, 20, 50],
        "pageLength": 10 
    });

    var idgedung = 0;
    var idskema = 0;
    var dataruang = [];

    window.onload = function(){
        var e = document.getElementById('c_LO');
        locheck(e);
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

        // console.log(dataruang[idgedung]);

        
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

    

    function locheck(e){
        if(e.checked){
            document.getElementById('rolelo').style.display = 'block';
            skema();
            gedung();
        }else{
            document.getElementById('rolelo').style.display = 'none';
        }
    }

    function aktifkanrole(iduser_role, iduser){
        $('#idrole_subaktifrole').val(iduser_role);
        $('#iduser_subaktifrole').val(iduser);
        $('#formaktifkanrole').submit();
    }
</script>
@endsection

