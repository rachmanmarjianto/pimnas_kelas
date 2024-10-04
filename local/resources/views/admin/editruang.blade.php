@extends('layout.main')
@section('title', 'Blank Page')

@section('css-page')
<!---- isi css page  -->
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Master</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin/masterruang') }}">Ruang</a></li>
    <li class="breadcrumb-item active" aria-current="page">edit</li>
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
            <form action="{{ url('/admin/updateruang') }}" method="POST">
                @csrf
                <div class="form-group row mb-4">
                    <label for="colFormLabel" class="col-sm-2 col-form-label">Nama Ruang</label>
                    <div class="col-sm-10">
                        <input type="text" name="namaruang" class="form-control" id="colFormLabel" value="{{ $ruang->nama_ruang }}" required>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label for="colFormLabel" class="col-sm-2 col-form-label">Gedung</label>
                    <div class="col-sm-10">
                        <select class="form-select" aria-label="Default select" name="gedung" required>
                            <option>Pilih Gedung</option>
                        @foreach($gedung as $g)
                            @if($g->idgedung == $ruang->idgedung)
                                <option value="{{ $g->idgedung }}" selected>{{ $g->nama_gedung }}</option>
                            @else
                                <option value="{{ $g->idgedung }}">{{ $g->nama_gedung }}</option>
                            @endif
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
                            @if($s->idskema == $ruang->idskema)
                                <option value="{{ $s->idskema }}" selected>{{ $s->nama_skema }}</option>
                            @else
                                <option value="{{ $s->idskema }}">{{ $s->nama_skema }}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>
                </div>
                <input type="hidden" name="idruang" value="{{ $ruang->idruang }}">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js-page')

@endsection

