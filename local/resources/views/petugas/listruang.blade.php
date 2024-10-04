@extends('layout_petugas.main')
@section('title', 'List Ruang')

@section('css-page')
<!---- isi css page  -->
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('/kelas/listruang') }}">List Ruang</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card" style="cursor:pointer" onclick="bukawheel({{ $dataruang[0]->idruang }})">
            <div class="card-body">
                <h5 class="card-title">{{ $dataruang[0]->nama_ruang }}</h5>
                <p class="mb-0">
                    Gedung : {{ $dataruang[0]->nama_gedung }}
                </p>
                <p class="mb-0">
                    Skema : {{ $dataruang[0]->nama_skema }}
                </p>
                <p class="mb-0">
                    Kelas : {{ $dataruang[0]->kelas_pimnas }}
                </p>
            </div>
        </div>
    </div>
</div>

<form method="post" action="{{ url('/kelas/wheel') }}" id="submitform">
    @csrf
    <input type="hidden" name="idruang" id="idruang">
    <input type="hidden" name="jwt" value="{{ $flashMessage_json}}">
</form>

@endsection

@section('js-page')
<script>
    function bukawheel(idruang) {
        document.getElementById('idruang').value = idruang;
        document.getElementById('submitform').submit();
    }
</script>
@endsection

