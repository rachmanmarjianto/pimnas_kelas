@extends('layout.main')
@section('title', 'Monitoring Ruang')

@section('css-page')
<!---- isi css page  -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/public/template/src/plugins/src/table/datatable/datatables.css') }}">
    
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('/public/template/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/public/template/src/plugins/css/dark/table/datatable/dt-global_style.css') }}"> --}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/admin/monitorruang') }}">Monitoring Ruang</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Ruang</li>
@endsection

@section('content')
<div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>Ruang</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="form-group row mb-4">
                <label for="colFormLabel" class="col-sm-2 col-form-label">Nama Ruang</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="colFormLabel" value="{{ $ruang['nama_ruang'] }}" style="color:black" readonly>
                </div>
            </div>
            <div class="form-group row mb-4">
                <label for="colFormLabel" class="col-sm-2 col-form-label">Nama Gedung</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="colFormLabel" value="{{ $ruang['nama_gedung'] }}" style="color:black" readonly>
                </div>
            </div>
            <div class="form-group row mb-4">
                <label for="colFormLabel" class="col-sm-2 col-form-label">Nama Skema</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="colFormLabel" value="{{ $ruang['nama_skema'] }}" style="color:black" readonly>
                </div>
            </div>
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
                        <th width="20%">Nama Ketua</th>
                        <th width="15%">Perguruan Tinggi</th>
                        <th width="10%">Status Panggil</th>
                        <th width="15%">Waktu Panggil</th>
                        <th width="33%">Judul Usulan</th>
                        <th width="7%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kelompok as $k)
                        @php
                            if($k['status_panggil'] == 0){
                                $status = '<td>Belum</td>';
                                $button = '';
                            }
                            else{
                                $status = '<td style="color:green">Sudah</td>';
                                $button = '<button class="btn btn-primary" onclick="resetkel('.$k['idkelompok'].')">Reset</button>';
                            }
                        @endphp
                    <tr>                       
                        <td>{{ $k['nama_ketua'] }}</td>
                        <td>{{ $k['nama_perguruan_tinggi'] }}</td>
                        @php echo $status @endphp
                        <td>{{ $k['ts_terpanggil'] }}</td>
                        <td>{{ $k['judul_usulan'] }}</td>
                        <td><?= $button ?></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<form method="post" action="{{ url('/admin/resetkel') }}" id="submitform">
    @csrf
    <input type="hidden" name="idkel" id="idkel">
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

        function resetkel(idkel) {
            document.getElementById('idkel').value = idkel;
            let text = "Yakin reset?";
            if (confirm(text) == true) {
                document.getElementById('submitform').submit();
            } 
            
        }
    </script>
@endsection

