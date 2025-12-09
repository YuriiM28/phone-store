@extends('manager.layout')

@section('title', 'Добавить телефон')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-plus"></i> Добавить телефон</h1>
    <a href="{{ route('manager.phones.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Назад к списку
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('manager.phones.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <!-- Основная информация -->
                <div class="col-md-6">
                    <h5 class="mb-3">Основная информация</h5>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Название телефона *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="brand_id" class="form-label">Бренд *</label>
                            <select class="form-select @error('brand_id') is-invalid @enderror" 
                                    id="brand_id" name="brand_id" required>
                                <option value="">Выберите бренд</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Категория *</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Выберите категорию</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Цена (₽) *</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Количество на складе *</label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Характеристики и изображение -->
                <div class="col-md-6">
                    <h5 class="mb-3">Характеристики</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="screen_size" class="form-label">Размер экрана</label>
                            <input type="text" class="form-control" id="screen_size" name="screen_size" 
                                   value="{{ old('screen_size') }}" placeholder="6.1&quot;">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="ram" class="form-label">ОЗУ</label>
                            <input type="text" class="form-control" id="ram" name="ram" 
                                   value="{{ old('ram') }}" placeholder="8 ГБ">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="storage" class="form-label">Память</label>
                            <input type="text" class="form-control" id="storage" name="storage" 
                                   value="{{ old('storage') }}" placeholder="128 ГБ">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="camera" class="form-label">Камера</label>
                            <input type="text" class="form-control" id="camera" name="camera" 
                                   value="{{ old('camera') }}" placeholder="12 МП">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="battery" class="form-label">Батарея</label>
                            <input type="text" class="form-control" id="battery" name="battery" 
                                   value="{{ old('battery') }}" placeholder="4000 mAh">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="processor" class="form-label">Процессор</label>
                            <input type="text" class="form-control" id="processor" name="processor" 
                                   value="{{ old('processor') }}" placeholder="Snapdragon 888">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Изображение</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Поддерживаемые форматы: JPEG, PNG, JPG, GIF. Максимальный размер: 2MB</div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Расширенные характеристики -->
            <div class="mb-4">
                <h5 class="mb-3">Расширенные характеристики</h5>
                <div id="specifications-container">
                    <div class="specification-item mb-3">
                        <div class="row">
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="specifications[display][name]" 
                                       placeholder="Название характеристики" value="Дисплей">
                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="specifications[display][value]" 
                                       placeholder="Значение" value="6.1&quot; Super Retina XDR">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger w-100 remove-specification" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" id="add-specification">
                    <i class="fas fa-plus"></i> Добавить характеристику
                </button>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Сохранить телефон
                </button>
                <a href="{{ route('manager.phones.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Отмена
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let specCount = 1;
    
    // Добавление новой характеристики
    document.getElementById('add-specification').addEventListener('click', function() {
        specCount++;
        const container = document.getElementById('specifications-container');
        const newItem = document.createElement('div');
        newItem.className = 'specification-item mb-3';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-md-5">
                    <input type="text" class="form-control" name="specifications[spec${specCount}][name]" 
                           placeholder="Название характеристики" required>
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="specifications[spec${specCount}][value]" 
                           placeholder="Значение" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger w-100 remove-specification">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        
        // Добавляем обработчик удаления для новой кнопки
        newItem.querySelector('.remove-specification').addEventListener('click', function() {
            newItem.remove();
        });
    });
    
    // Обработчики удаления для существующих кнопок
    document.querySelectorAll('.remove-specification').forEach(button => {
        button.addEventListener('click', function() {
            if (!this.disabled) {
                this.closest('.specification-item').remove();
            }
        });
    });
});
</script>
@endpush