@extends('layouts.admin.app')

@section('title', 'Yeni Etiket Ekle')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3 mb-0 text-gray-800">Yeni Etiket Ekle</h1>
    </div>

    <div class="card shadow-sm max-w-md">
        <div class="card-body">
            <form action="{{ route('admin.tags.store') }}" method="POST">
                @csrf
                
                <div class="form-group mb-3">
                    <label for="name" class="font-weight-bold">Etiket Adı *</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="örn: ekonomi, teknoloji" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="color" class="font-weight-bold">Renk (HEX) *</label>
                    <div class="input-group">
                        <input type="color" name="color" id="color" class="form-control form-control-color w-25 mr-2" value="#3576f6" required>
                        <input type="text" id="color_text" class="form-control" value="#3576f6" placeholder="#3576f6" readonly>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary mr-2">İptal</a>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('color').addEventListener('input', function(e) {
        document.getElementById('color_text').value = e.target.value;
    });
</script>
@endsection
