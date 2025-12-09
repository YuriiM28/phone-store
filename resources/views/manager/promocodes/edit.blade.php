@extends('manager.layout')

@section('title', 'Редактирование промокода')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-edit"></i> Редактирование промокода</h1>
    <a href="{{ route('manager.promocodes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Назад к списку
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('manager.promocodes.update', $promocode) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="code" class="form-label">Код промокода *</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $promocode->code) }}" 
                               placeholder="Например: SUMMER2024" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Тип скидки *</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Выберите тип</option>
                            <option value="percentage" {{ old('type', $promocode->type) == 'percentage' ? 'selected' : '' }}>Процентная скидка</option>
                            <option value="fixed" {{ old('type', $promocode->type) == 'fixed' ? 'selected' : '' }}>Фиксированная скидка</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="value" class="form-label">Значение скидки *</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                   id="value" name="value" value="{{ old('value', $promocode->value) }}" 
                                   min="0" step="0.01" required>
                            <span class="input-group-text" id="value-suffix">
                                {{ $promocode->type === 'percentage' ? '%' : '₽' }}
                            </span>
                        </div>
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="min_order_amount" class="form-label">Минимальная сумма заказа</label>
                        <input type="number" class="form-control @error('min_order_amount') is-invalid @enderror" 
                               id="min_order_amount" name="min_order_amount" 
                               value="{{ old('min_order_amount', $promocode->min_order_amount) }}" min="0" step="0.01">
                        @error('min_order_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="usage_limit" class="form-label">Лимит использований</label>
                        <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                               id="usage_limit" name="usage_limit" 
                               value="{{ old('usage_limit', $promocode->usage_limit) }}" min="1">
                        @error('usage_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Уже использовано: {{ $promocode->used_count }} раз</div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3 form-check form-switch mt-4">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', $promocode->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Активный промокод</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="valid_from" class="form-label">Действует с</label>
                        <input type="datetime-local" class="form-control @error('valid_from') is-invalid @enderror" 
                               id="valid_from" name="valid_from" 
                               value="{{ old('valid_from', $promocode->valid_from ? $promocode->valid_from->format('Y-m-d\TH:i') : '') }}">
                        @error('valid_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="valid_until" class="form-label">Действует до</label>
                        <input type="datetime-local" class="form-control @error('valid_until') is-invalid @enderror" 
                               id="valid_until" name="valid_until" 
                               value="{{ old('valid_until', $promocode->valid_until ? $promocode->valid_until->format('Y-m-d\TH:i') : '') }}">
                        @error('valid_until')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('manager.promocodes.index') }}" class="btn btn-secondary me-md-2">Отмена</a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Сохранить изменения
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const valueSuffix = document.getElementById('value-suffix');

    function updateValueDisplay() {
        if (typeSelect.value === 'percentage') {
            valueSuffix.textContent = '%';
        } else {
            valueSuffix.textContent = '₽';
        }
    }

    typeSelect.addEventListener('change', updateValueDisplay);
});
</script>
@endpush
@endsection