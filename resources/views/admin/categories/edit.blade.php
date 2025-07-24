@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kategori Düzenle</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>

    <!-- Content Row -->
    <div class="card shadow mb-4">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Kategori Adı</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Title (SEO) -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Başlık (SEO)</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $category->title) }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Main Category -->
                        <div class="mb-3">
                            <label for="main_category_id" class="form-label">Ana Kategori</label>
                            <select class="form-select @error('main_category_id') is-invalid @enderror" 
                                    id="main_category_id" name="main_category_id">
                                <option value="">Ana Kategori Seçin</option>
                                @foreach($mainCategories as $mainCategory)
                                    <option value="{{ $mainCategory->id }}" 
                                            {{ old('main_category_id', $category->main_category_id) == $mainCategory->id ? 'selected' : '' }}>
                                        {{ $mainCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('main_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="mb-3">
                            <label for="image_id" class="form-label">Görsel</label>
                            <select class="form-select @error('image_id') is-invalid @enderror" 
                                    id="image_id" name="image_id">
                                <option value="">Görsel Seçin</option>
                                @foreach($images as $image)
                                    <option value="{{ $image->id }}" 
                                            {{ old('image_id', $category->image_id) == $image->id ? 'selected' : '' }}
                                            data-preview="{{ asset('storage/profile-image/' . $image->path) }}">
                                        {{ $image->name ?? $image->path }}
                                    </option>
                                @endforeach
                            </select>
                            @error('image_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div id="image-preview" class="mt-2">
                                @if($category->image)
                                    <img src="{{ asset('storage/profile-image/' . $category->image->path) }}" 
                                         alt="Seçili görsel" 
                                         class="img-thumbnail">
                                @endif
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Kaydet
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageSelect = document.getElementById('image_id');
    const imagePreview = document.getElementById('image-preview');

    imageSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const previewUrl = selectedOption.getAttribute('data-preview');

        if (previewUrl) {
            imagePreview.innerHTML = `<img src="${previewUrl}" alt="Seçili görsel" class="img-thumbnail">`;
        } else {
            imagePreview.innerHTML = '';
        }
    });
});
</script>
@endpush

<style>
.form-select {
    background-color: #fff;
    border: 1px solid #d1d3e2;
    border-radius: 0.35rem;
    padding: 0.375rem 0.75rem;
}

.form-select:focus {
    border-color: #bac8f3;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

#image-preview img {
    max-width: 100%;
    height: auto;
}

.btn-secondary {
    color: #fff;
    background-color: #858796;
    border-color: #858796;
}

.btn-secondary:hover {
    color: #fff;
    background-color: #717384;
    border-color: #6b6d7d;
}
</style>
@endsection