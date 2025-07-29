<div>
    <button wire:click="open" class="btn btn-primary mb-3">
        <i class="fa fa-plus"></i> Haber Ekle
    </button>

    @if($showModal)
    <div class="fixed z-50 inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-lg relative">
            <button wire:click="close" class="absolute right-4 top-4 text-gray-500 hover:text-red-500">
                &times;
            </button>
            <h2 class="text-2xl mb-4 font-bold">Haber Ekle</h2>

            <form wire:submit.prevent="save" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Başlık</label>
                    <input type="text" class="form-control" wire:model.defer="title">
                    @error('title') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label>Kategori</label>
                    <select class="form-control" wire:model.defer="category_id">
                        <option value="">Seçiniz</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label>Açıklama</label>
                    <input type="text" class="form-control" wire:model.defer="description">
                    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label>İçerik</label>
                    <textarea class="form-control" wire:model.defer="content"></textarea>
                    @error('content') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label>Resim</label>
                    <input type="file" wire:model="image" class="form-control">
                    @error('image') <div class="text-danger">{{ $message }}</div> @enderror
                    @if($image)
                        <img src="{{ $image->temporaryUrl() }}" class="mt-2" style="width:100px;">
                    @endif
                </div>
                <button class="btn btn-success" type="submit">Kaydet</button>
            </form>
        </div>
    </div>
    @endif
</div>
