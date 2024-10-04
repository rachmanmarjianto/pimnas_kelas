@extends('layout.main')
@section('title', 'Master Ruang')

@section('css-page')
<!---- isi css page  -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/public/template/src/plugins/src/table/datatable/datatables.css') }}">
    
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('/public/template/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/public/template/src/plugins/css/dark/table/datatable/dt-global_style.css') }}"> --}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Master</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ruang</li>
@endsection

@section('content')
<div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>Tambah Ruang Baru</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <form action="{{ url('/admin/submitruang') }}" method="POST">
                @csrf
                <div class="form-group row mb-4">
                    <label for="colFormLabel" class="col-sm-2 col-form-label">Nama Ruang</label>
                    <div class="col-sm-10">
                        <input type="text" name="namaruang" class="form-control" id="colFormLabel" required>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label for="colFormLabel" class="col-sm-2 col-form-label">Gedung</label>
                    <div class="col-sm-10">
                        <select class="form-select" aria-label="Default select" name="gedung" required>
                            <option selected>Pilih Gedung</option>
                        @foreach($gedung as $g)
                            <option value="{{ $g->idgedung }}">{{ $g->nama_gedung }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label for="colFormLabel" class="col-sm-2 col-form-label">Skema</label>
                    <div class="col-sm-10">
                        <select class="form-select" aria-label="Default select" name="skema" required>
                            <option selected>Pilih Skema</option>
                        @foreach($skema as $s)
                            <option value="{{ $s->idskema }}">{{ $s->nama_skema }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Tambahkan</button>
            </form>
        </div>
    </div>
</div>
<div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>List Ruang</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area br-8">
            <table id="zero-config" class="table dt-table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Nama Ruang</th>
                        <th>Gedung</th>
                        <th>kelas</th>
                        <th>Skema</th>
                        <th class="no-content">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ruang as $r)
                    <tr>
                        <td>{{ $r->nama_ruang }}</td>
                        <td>{{ $r->nama_gedung }}</td>
                        <td>{{ $r->kelas_pimnas }}</td>
                        <td>{{ $r->nama_skema }}</td>
                        <td>
                            <a href="{{ url('/admin/editruang/'.$r->idruang) }}" class="btn btn-primary">Edit</a>
                            <a href="{{ url('/admin/deleteruang/'.$r->idruang) }}" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
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
    </script>
@endsection

