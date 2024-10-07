@extends('layout.main')
@section('title', 'Monitor Ruang')

@section('css-page')
<!---- isi css page  -->
<link rel="stylesheet" type="text/css" href="{{ asset('/public/template/src/plugins/src/table/datatable/datatables.css') }}">
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page"><a href="#">Monitor Ruang</a></li>
@endsection

@section('content')
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
                        <th>Skema</th>
                        <th>Kelompok Maju</th>
                        <th>Jumlah Peserta</th>
                        <th class="no-content">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ruang as $r)
                    <tr>
                        <td>{{ $r->nama_ruang }}</td>
                        <td>{{ $r->nama_gedung }}</td>
                        <td>{{ $r->nama_skema }}</td>
                        <td>{{ $r->kelompok_maju }}</td>
                        <td>{{ $peserta_arr[$r->idruang] }}</td>
                        <td>
                            <a href="{{ url('/admin/dalamruang/'.$r->idruang) }}" class="btn btn-primary">Monitor</a>
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

